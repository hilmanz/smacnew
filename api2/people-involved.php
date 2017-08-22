<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

$conn = open_db(0);
if($lang=='all'){
	$sql = "SELECT people,influence,cities,cities_text FROM smac_report.dashboard_summary WHERE campaign_id='$campaign_id' && client_id='$client_id' LIMIT 1;";
}else{
	$sql = "SELECT people,influence,cities,cities_text FROM smac_report.dashboard_summary_lang WHERE campaign_id='$campaign_id' && client_id='$client_id' LIMIT 1;";
}
$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);
mysql_free_result($q);

//campaign_days
/*
$sql = "SELECT COUNT(*) as total FROM (SELECT * FROM (SELECT DATE(published_date) as _tgl FROM smac_report.campaign_history a
INNER JOIN smac_data.feeds b
ON a.feed_id = b.id
WHERE a.campaign_id=".$campaign_id.") aa
GROUP BY _tgl) bb";
*/
$sql = "SELECT COUNT(id) as total FROM smac_report.campaign_daily_stats WHERE campaign_id=".$campaign_id." AND lang='".$lang."'";
$q = mysql_query($sql,$conn);
$days = mysql_fetch_assoc($q);
mysql_free_result($q);

close_db($conn);

header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';
$xml .= 		'<people>'.number_format($f['people']).'</people>';
$xml .= 		'<influence>'.number_format($f['influence']).'</influence>';
$xml .=   	'<cities>'.number_format($f['cities']).'</cities>';
$xml .= 		'<text>'.$f['cities_text'].'</text>';
$xml .= 		'<total_days>'.$days['total'].'</total_days>';
$xml .= 	'</rows>';

echo $xml;
exit;