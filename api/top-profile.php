<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = $_REQUEST['person'];

$conn = open_db(0);
//check which feed_wordlist we gonna use ?
if(is_new_schema($campaign_id,$conn)==1){
	$FEED_WORDLIST = "smac_word.feed_wordlist_{$campaign_id}";
}else{
	$FEED_WORDLIST = "smac_report.feed_wordlist";
}
if(is_new_feeds($campaign_id,$conn)){
	$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
}else{
	$FEEDS = "smac_report.campaign_feeds";
}

//total impressions
$sql = "SELECT SUM(total_impression_twitter) AS true_reach
FROM smac_report.campaign_rule_volume_history 
WHERE campaign_id={$campaign_id};";
$q = mysql_query($sql,$conn);
$campaign=mysql_fetch_assoc($q);
mysql_free_result($q);
$total_impression = $campaign['true_reach'];
$campaign=null;
//-->


//getting user stats 
$sql = "SELECT author_id,author_name,author_avatar,SUM(imp) AS impression,SUM(rt_imp) AS rt_impression,
SUM(rt_mention) AS rt_total,SUM(total_mentions) AS mentions 
FROM smac_report.campaign_author_daily_stats 
WHERE campaign_id={$campaign_id} AND author_id='{$person}'
GROUP BY author_id
ORDER BY impression DESC
LIMIT 1;";

//using new summary table
$sql  ="SELECT * FROM smac_author.author_summary_{$campaign_id} WHERE author_id='{$person}'";
$r2 = fetch($sql,$conn);
//use these only for getting user's rank (temporary solution)
$sql = 
	"SELECT b.rank 
	 FROM smac_report.campaign_people_summary a
	 LEFT JOIN smac_report.campaign_people_rank b
	 ON a.id = b.ref_id AND b.campaign_id = {$campaign_id}
	 WHERE a.campaign_id=".$campaign_id."
	 AND author_id='".mysql_escape_string($person)."' LIMIT 1";

$rank = fetch($sql,$conn);

$r2['followers'] = round($r2['total_impression'] / $r2['total_mentions']);
$r2['total_impression'] = ($r2['total_impression']+$r2['total_rt_impression']);
$r2['share_percentage'] = round(($r2['total_impression'] / $total_impression) * 100,2);
//$r2['rank']	= round(($r2['total_impression']-$min_imp) / ($max_imp - $min_imp) * 100);

$r2['rt_percentage'] = round($r2['total_rt_mentions'] / ($r2['total_rt_mentions']+$r2['total_mentions']) * 100,2);
$r2['potential_impact'] = 0;
$r2['rank'] = $rank['rank'];


//we need to grab the info about user
$response = curl_get("https://api.twitter.com/1/users/show.json?screen_name=".$r2['author_id']);
$profile_obj = json_decode($response);
$author_timezone = $profile_obj->time_zone;
$author_location = $profile_obj->location;
$author_about = $profile_obj->description;
$arr_raw = explode(":",$author_location);
$arr_loc = @explode(",",$arr_raw[1]);
if(is_array($arr_loc)){
	$coordinate_x = @trim($arr_loc[0]);
	$coordinate_y = @trim($arr_loc[1]);
}
// get user coordinate from our feeds
//
$sql = "SELECT coordinate_x,coordinate_y 
		FROM {$FEEDS} 
		WHERE campaign_id={$campaign_id} 
		AND author_id='{$person}' LIMIT 1";
		
$hist_profile = fetch($sql,$conn);


if(floatval($hist_profile['coordinate_x'])>0||floatval($hist_profile['coordinate_y'])>0){

	//check from our database first
	
	//not found, so we use google.
	$uri = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$hist_profile['coordinate_x'].",".$hist_profile['coordinate_y']."&sensor=false";
	$glmap_response = file_get_contents($uri);
	$map_obj = json_decode($glmap_response);
	
	if($map_obj->status=="OK"){

		$address = $map_obj->results[0]->formatted_address;

		$author_location = $address;
	}else{
		//try our geo database
		$sql = "SELECT country,iso FROM smac_data.geo_country 
			WHERE {$hist_profile['coordinate_x']} BETWEEN y1 AND y2 AND {$hist_profile['coordinate_y']}
			BETWEEN x1 AND x2 LIMIT 100;";
		$geo = fetch_many($sql,$conn);
		if(sizeof($geo)==1){
			$author_location = $geo[0]['country'];
		}
	}

	
}else if(floatval($coordinate_x)>0||floatval($coordinate_y)>0){
	$uri = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coordinate_x.",".$coordinate_y."&sensor=false";
	
	$glmap_response = file_get_contents($uri);
	$map_obj = json_decode($glmap_response);
	
	if($map_obj->status=="OK"){
		$address = $map_obj->results[0]->formatted_address;
		$author_location = $address;
	}else{
		//try our geo database
		$sql = "SELECT country,iso FROM smac_data.geo_country 
			WHERE {$coordinate_x} BETWEEN y1 AND y2 AND {$coordinate_y}
			BETWEEN x1 AND x2 LIMIT 100;";
		$geo = fetch_many($sql,$conn);
		if(sizeof($geo)==1){
			$author_location = $geo[0]['country'];
		}
	}
}
mysql_close($conn);
//
//need to convert it into json soon. we hate xml really much.
header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';

$xml .= '<info>
				<image>'.$r2['author_avatar'].'</image>
				<name>'.$r2['author_name'].'</name>
				<username>'.$r2['author_id'].'</username>
				<location><![CDATA['.$author_location.']]></location>
				<timezone><![CDATA['.$author_timezone.']]></timezone>
				<about><![CDATA['.($author_about).']]></about>
				<follower>'.number_format($r2['followers']).'</follower>
				<mention>'.number_format($r2['total_mentions']).'</mention>
				<rt>'.number_format($r2['total_rt_mentions']).'</rt>
				<impression>'.number_format($r2['total_impression']).'</impression>
				<lead_mentions>'.smac_number($r2['lead_mentions']).'</lead_mentions>
				<rt_impression>'.smac_number($r2['total_rt_impression']).'</rt_impression>
				<rt_percentage>'.($r2['rt_percentage']).'</rt_percentage>
				<rank>'.($r2['rank']).'</rank>
				<share_percentage>'.($r2['share_percentage']).'</share_percentage>
				<impressi>
					<positive>'.number_format($r2['total_impression']).'</positive>
					<negative>0</negative>
				</impressi>';

$xml .='</info>';

echo $xml;
exit;
?>