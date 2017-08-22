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
	$sql = "SELECT campaign_id, SUM( people ) AS people, SUM( rt_people ) AS rt_people
			FROM smac_report.campaign_daily_stats
			WHERE campaign_id =".$campaign_id."
			AND lang = 'all'
			AND published_date >= '".$from_date."'
			AND published_date <= '".$to_date."'
			GROUP BY campaign_id LIMIT 1";
}else{
	$sql = "SELECT campaign_id, SUM( mentions ) AS mentions, SUM( people_mentioned ) AS people_mentioned, SUM( impressions ) AS impressions, SUM( rt_impression ) AS rt_impression, SUM( people ) AS people, SUM( rt_people ) AS rt_people
			FROM smac_report.campaign_daily_stats
			WHERE campaign_id =".$campaign_id."
			AND lang = '".$lang."'
			AND published_date >= '".$from_date."'
			AND published_date <= '".$to_date."'
			GROUP BY campaign_id LIMIT 1";
}
$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);
mysql_free_result($q);

//campaign_days

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