<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$limit = intval($_REQUEST['limit']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
if($limit == 0){
	$limit = 1000;
}

$conn = open_db(0);

//get devices
$sql = "SELECT * FROM ".$SCHEMA.".device_lookup";
$q = mysql_query($sql,$conn);
$devices = array();
while($f=mysql_fetch_assoc($q)){
	$devices[] = $f;
}
$f=null;
mysql_free_result($f);

if($lang=='all'){
	$sql = "SELECT a.feed_id,from_object_id,from_object_name,likes_count,message,created_time,created_time_ts,application_object_name FROM smac_report.campaign_fb_history a
	INNER JOIN smac_data.feeds_facebook b
	ON a.feed_id = b.id
	WHERE a.campaign_id=".$campaign_id." AND b.message <> ''
	ORDER BY b.likes_count DESC LIMIT ".$limit;
}else{
		$sql = "SELECT a.feed_id,from_object_id,from_object_name,likes_count,message,created_time,created_time_ts,application_object_name FROM smac_report.campaign_fb_history a
	INNER JOIN smac_data.feeds_facebook b
	ON a.feed_id = b.id
	INNER JOIN smac_report.campaign_fb_lang c
	ON a.id = c.campaign_feed_id
	WHERE a.campaign_id=".$campaign_id." AND b.message <> ''
	AND c.lang = '".$lang."'
	ORDER BY b.likes_count DESC LIMIT ".$limit;
}
/*
$sql = "SELECT * 
FROM (
SELECT a.feed_id, a.author, a.content, a.avatar_pic, COUNT( a.feed_id ) AS total,b.score as sentiment
FROM smac_report.campaign_rt_content a
LET JOIN smac_report.campaign_rt_impact b
ON a.id = b.feed_id
WHERE a.campaign_id =".$campaign_id." 
AND b.campaign_id=".$campaign_id."
GROUP BY feed_id
)a
ORDER BY a.total DESC LIMIT 10";
*/
$q = mysql_query($sql,$conn);
//print mysql_error();
//$f = mysql_fetch_assoc($q);

//echo mysql_error();exit;

$num = mysql_num_rows($q);

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';

if($num > 0){
	while($r=mysql_fetch_array($q)){
		$xml.="<row>\n";
		$xml.="<id>".$r['feed_id']."</id>\n";
		$xml.="<published>".date("d/m/Y",$r['created_time_ts'])."</published>\n";
		$xml.="<name><![CDATA[".$r['from_object_name']."]]></name>\n";
		$xml.="<url>http://www.facebook.com/".$r['from_object_id']."</url>";
		$xml.="<txt><![CDATA[".htmlspecialchars($r['message'])."]]></txt>\n";
		$xml.="<device>".get_device($r['application_object_name'],$devices)."</device>\n";
		$xml.="<keyword></keyword>\n";
		$xml.="<lat></lat>\n";
		$xml.="<lon></lon>\n";
		$xml.="<profile_image_url>https://graph.facebook.com/".$r['from_object_id']."/picture</profile_image_url>\n";
		$xml.="<insert_date></insert_date>\n";
		$xml.="<reach>".number_format($r['likes_count'])."</reach>\n";
		$xml.="<sentiment>".intval($rs_i['likes_count'])."</sentiment>\n";
		$xml.="</row>";
	}
}
$xml .= '</rows>';

mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
echo $xml;
exit;