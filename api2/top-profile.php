<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = $_REQUEST['person'];

$conn = open_db(0);
//Always do this
$sql = "SELECT 1 as res FROM smac_report.campaign_feeds_split_flag fg WHERE fg.n_status = 1 AND fg.campaign_id = ".$campaign_id;
$rs = mysql_query($sql,$conn);
if ($row = mysql_fetch_assoc($rs)) {
	//$flag = $row['res'];	
	$use_replicate_table = 1;
} else {
	//print "NOK use old table ".chr(13).chr(10);
}
mysql_free_result($rs);


//total impressions
$sql = "SELECT true_reach FROM smac_report.dashboard_summary WHERE campaign_id=".$campaign_id." LIMIT 1";
$q = mysql_query($sql,$conn);
$campaign=mysql_fetch_assoc($q);
mysql_free_result($q);
$total_impression = $campaign['true_reach'];
$campaign=null;


if($use_replicate_table){
		$sql = "SELECT author_id,followers 
	FROM smac_feeds.campaign_feeds_{$campaign_id} WHERE campaign_id=".$campaign_id." 
			AND author_id='".mysql_escape_string($person)."'";
}else{
	$sql = "SELECT author_id,followers 
	FROM smac_report.campaign_feeds WHERE campaign_id=".$campaign_id." 
			AND author_id='".mysql_escape_string($person)."'";
}
$q = mysql_query($sql,$conn);
$r=mysql_fetch_assoc($q);
mysql_free_result($q);

//$sql = "SELECT * FROM smac_report.campaign_people_summary WHERE campaign_id=".$campaign_id."  AND author_id='".mysql_escape_string($person)."'";
$sql = "SELECT a.*,b.rank FROM smac_report.campaign_people_summary a
LEFT JOIN smac_report.campaign_people_rank b
ON a.id = b.ref_id AND b.campaign_id = {$campaign_id}
WHERE a.campaign_id=".$campaign_id."
AND author_id='".mysql_escape_string($person)."' LIMIT 1";
$q = mysql_query($sql,$conn);
$r2=mysql_fetch_assoc($q);
mysql_free_result($q);

$r2['followers'] = $r['followers'];
$r2['total_impression'] = ($r2['impression']+$r2['rt_impression']);
$r2['share_percentage'] = round(($r2['total_impression'] / $total_impression) * 100,2);
//$r2['rank']	= round(($r2['total_impression']-$min_imp) / ($max_imp - $min_imp) * 100);

$r2['rt_percentage'] = round($r2['rt_mention'] / ($r2['rt_mention']+$r2['total_mentions']) * 100,2);
$r2['potential_impact'] = 0;

//we need to grab the info about user
$response = file_get_contents("https://api.twitter.com/1/users/show.json?screen_name=".$r2['author_id']);
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

//check historical coordinate first from our database.
if($use_replicate_table){
	$sql = "SELECT coordinate_x,coordinate_y FROM smac_feeds.campaign_feeds_{$campaign_id} 
			WHERE campaign_id={$campaign_id} 
			AND author_id='{$author_id}' LIMIT 1";
}else{
	$sql = "SELECT coordinate_x,coordinate_y FROM smac_report.campaign_feeds 
		WHERE campaign_id={$campaign_id} 
		AND author_id='{$author_id}' LIMIT 1";
}
$hist_profile = fetch($sql,$conn);
if(floatval($hist_profile['coordinate_x'])>0||floatval($hist_profile['coordinate_y'])>0){
	$uri = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$hist_profile['coordinate_x'].",".$hist_profile['coordinate_y']."&sensor=false";
	$glmap_response = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coordinate_x.",".$coordinate_y."&sensor=false");
	$map_obj = json_decode($glmap_response);
	
	if($map_obj->status=="OK"){
		$address = $map_obj->results[0]->formatted_address;
		$author_location = $address;
	}
}else if(floatval($coordinate_x)>0||floatval($coordinate_y)>0){
	$uri = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coordinate_x.",".$coordinate_y."&sensor=false";
	
	$glmap_response = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coordinate_x.",".$coordinate_y."&sensor=false");
	$map_obj = json_decode($glmap_response);
	
	if($map_obj->status=="OK"){
		$address = $map_obj->results[0]->formatted_address;
		$author_location = $address;
	}
}

//

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
				<mention>'.number_format($r2['total_mentions']).'</mention>
				<rt>'.number_format($r2['rt_mention']).'</rt>
				<impression>'.number_format($r2['total_impression']).'</impression>
				<lead_mentions>'.smac_number($r2['lead_mentions']).'</lead_mentions>
				<rt_impression>'.smac_number($r2['rt_impression']).'</rt_impression>
				<rt_percentage>'.($r2['rt_percentage']).'</rt_percentage>
				<rank>'.($r2['rank']).'</rank>
				<share_percentage>'.($r2['share_percentage']).'</share_percentage>
				<impressi>
					<positive>'.number_format($r2['total_impression']).'</positive>
					<negative>0</negative>
				</impressi>';
/*
$xml .= '<twits>';

while($r=mysql_fetch_array($q)){
	/*
	$xml .= '	<user>';
	$xml .= '		<name>'.htmlspecialchars($r[1]).'</name>';
	$xml .= '		<total>'.$r[2].'</total>';
	$xml .= '		<rt>0</rt>';
	$xml .= '		<img>'.$r[3].'</img>';
	$xml .= '	</user>';
	
	$xml .= '<twit>
						<text>'.htmlspecialchars($r[2]).'</text>
						<imp>'.smac_number($r[5]).'</imp>
						<status>'.$r[3].'</status>
					</twit>';
}			
$xml .= '</twits>';
*/
$xml .='</info>';

echo $xml;
exit;
?>