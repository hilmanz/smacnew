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
	$sql = "SELECT a.feed_id,from_object_id,from_object_name,likes_count,message,created_time,created_time_ts,application_object_name 
	FROM smac_report.campaign_fb_history a
	INNER JOIN smac_data.feeds_facebook b
	ON a.feed_id = b.id
	WHERE a.campaign_id=".$campaign_id." AND (b.message <> '' OR b.story <> '')
	ORDER BY b.likes_count DESC LIMIT ".$limit;
}else{
		$sql = "SELECT a.feed_id,from_object_id,from_object_name,likes_count,message,created_time,created_time_ts,application_object_name 
		FROM smac_report.campaign_fb_history a
	INNER JOIN smac_data.feeds_facebook b
	ON a.feed_id = b.id
	INNER JOIN smac_report.campaign_fb_lang c
	ON a.id = c.campaign_feed_id
	WHERE a.campaign_id=".$campaign_id." AND (b.message <> '' OR b.story <> '')
	AND c.lang = '".$lang."'
	ORDER BY b.likes_count DESC LIMIT ".$limit;
}

$q = mysql_query($sql,$conn);
//print mysql_error();
//$f = mysql_fetch_assoc($q);

//echo mysql_error();exit;

$num = mysql_num_rows($q);

if($num > 0){
	$rs = array();
	while($r=mysql_fetch_array($q)){
		$rs[] = array(
			'id'=>$r['feed_id'],
			'published'=>date("d/m/Y",$r['created_time_ts']),
			'name'=>$r['from_object_name'],
			'url'=>"http://www.facebook.com/".$r['from_object_id'],
			'txt'=>htmlspecialchars($r['message']),
			'device'=>get_device($r['application_object_name'],$devices),
			'profile_image_url'=>"https://graph.facebook.com/".$r['from_object_id']."/picture",
			'reach'=>number_format($r['likes_count']),
			'sentiment'=>intval($rs_i['likes_count'])
		);
	
	}
}
mysql_free_result($q);
close_db($conn);

header("Content-type: application/json"); 
echo json_encode($rs);
exit;