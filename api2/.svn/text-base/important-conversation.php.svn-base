<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$limit = intval($_REQUEST['limit']);
$lang = mysql_escape_string($_REQUEST['lang']);
$geo = mysql_escape_string($_REQUEST['geo']);
$FEEDS = "smac_report.campaign_feeds"; 

if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

if($limit == 0){
	$limit = 1000;
}

$conn = open_db(0);

//Always do this
$sql = "SELECT 1 as res FROM smac_report.campaign_feeds_split_flag fg 
		WHERE fg.n_status = 1 AND fg.campaign_id = ".$campaign_id;
$row = fetch($sql,$conn);
if ($row) {
	//$flag = $row['res'];	
	$use_replicate_table = 1;
	$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
} else {
	//print "NOK use old table ".chr(13).chr(10);
}


//get devices
$sql = "SELECT * FROM ".$SCHEMA.".device_lookup";
$q = mysql_query($sql,$conn);
$devices = array();
while($f=mysql_fetch_assoc($q)){
	$devices[] = $f;
}
$f=null;
mysql_free_result($f);

//total impressions
if($lang=='all'){
	$sql = "SELECT true_reach FROM smac_market.dashboard_summary WHERE campaign_id=".$campaign_id." AND geo='{$geo}' LIMIT 1";
}else{
	$sql = "SELECT true_reach FROM smac_market.dashboard_summary_lang WHERE campaign_id=".$campaign_id." AND geo='{$geo}' LIMIT 1";
}
$q = mysql_query($sql,$conn);
$campaign=mysql_fetch_assoc($q);
mysql_free_result($q);
$total_impression = $campaign['true_reach'];
$campaign=null;


if($lang=='all'){
	if(strtolower($geo)=="id"||strtolower($geo)=="my"){
		$sql = "SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, published_date,followers as imp,generator,content
		FROM (SELECT a.*,0 as rt_total,0 as rt_imp 
		FROM {$FEEDS} a
		INNER JOIN smac_report.country_twitter b
		ON a.feed_id = b.feed_id
		WHERE a.campaign_id=".$campaign_id." AND b.country_code='{$geo}'
		) a
		ORDER BY imp DESC LIMIT ".$limit;
	}else{
		$sql = "SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, published_date,followers as imp,generator,content
		FROM (SELECT a.*,0 as rt_total,0 as rt_imp 
		FROM {$FEEDS} a
		INNER JOIN smac_report.country_twitter b
		ON a.feed_id = b.feed_id
		WHERE a.campaign_id=".$campaign_id." AND b.country_code='{$geo}'
		) a
		ORDER BY imp DESC LIMIT ".$limit;
		
		//nanti ganti pake ini :
		/* 
		$sql = "SELECT a.feed_id,a.author_name,a.author_avatar as avatar_pic,a.published_date,a.followers as imp,a.generator,a.content,0 as rt_total,0 as rt_imp 
		FROM smac_feeds.campaign_feeds_174 a
		INNER JOIN smac_report.country_twitter b
		ON a.feed_id = b.feed_id
		WHERE a.campaign_id=174 AND b.country_code='us' ORDER BY followers DESC 
		LIMIT 10;";*/
		//
	}
	
}else{
	$sql = "SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, published_date,followers as imp,generator,content
	FROM (SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
	FROM {$FEEDS} a
	LEFT JOIN smac_data.tbl_rt_content b
	ON a.feed_id = b.feed_id
	INNER JOIN smac_report.campaign_feed_lang c
	ON a.id = c.campaign_feed_id
	INNER JOIN smac_report.country_twitter d
	ON a.feed_id = d.feed_id
	WHERE a.campaign_id=".$campaign_id." AND d.country_code='{$geo}'
	GROUP BY b.feed_id) a
	ORDER BY imp DESC LIMIT ".$limit;
}


$q = mysql_query($sql,$conn);
$num = mysql_num_rows($q);

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';

if($num > 0){
	$rs = array();
	while($fetch=mysql_fetch_array($q)){
		$rs[] = $fetch;
	}
	foreach($rs as $r){
			$share = round((($r['imp']+$r['rt_imp'])/$total_impression)*100,4);
			$xml.="<row>\n";
			$xml.="<id>".$r['feed_id']."</id>\n";
			$xml.="<published>".$r['published_date']."</published>\n";
			$xml.="<author_id>".$r['author_id']."</author_id>\n";
			$xml.="<name>".htmlspecialchars($r['author_name'])."</name>\n";
			$xml.="<txt>".htmlspecialchars($r['content'])."</txt>\n";
			$xml.="<device>".get_device($r['generator'],$devices)."</device>\n";
			$xml.="<keyword></keyword>\n";
			$xml.="<lat></lat>\n";
			$xml.="<lon></lon>\n";
			$sql = "SELECT COUNT(feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
					FROM smac_data.tbl_rt_content WHERE feed_id='".$r['feed_id']."';";
			$q = mysql_query($sql,$conn);
			$rt=mysql_fetch_assoc($q);
			mysql_free_result($q);
			
			$r['rt_imp'] = $rt['rt_imp'];
			$r['rt_total'] = $rt['rt_total'];
			$xml.="<profile_image_url>".$r['avatar_pic']."</profile_image_url>\n";
			$xml.="<insert_date></insert_date>\n";
			$xml.="<imp>".smac_number($r['imp']+$r['rt_imp'])."</imp>\n";
			
			
			
			$xml.="<rt>".smac_number($r['rt_total'])."</rt>\n";
			$xml.="<rt_imp>".smac_number($r['rt_imp'])."</rt_imp>\n";
			$xml.="<sentiment>0</sentiment>\n";
			$xml.="<share>".$share."</share>\n";
			$xml.="</row>";
			mysql_free_result($qq);
	}
}
$xml .= '</rows>';

mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
echo $xml;
exit;
