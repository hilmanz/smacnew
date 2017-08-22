<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$lang = mysql_escape_string($_REQUEST['lang']);

$from_date = (mysql_escape_string($_REQUEST['from']));
$to_date = (mysql_escape_string($_REQUEST['to']));
$from_date_ts = strtotime(mysql_escape_string($_REQUEST['from']));
$to_date_ts = strtotime(mysql_escape_string($_REQUEST['to']));
$geo = mysql_escape_string($_REQUEST['geo']);
$conn = open_db(0);
if($from_date==null&&$to_date==NULL){
	//twitter
	if($lang=='all'){
		$sql = "SELECT COUNT(*) as total 
				FROM smac_report.campaign_feeds a 
				INNER JOIN smac_report.country_twitter b
				ON a.feed_id = b.feed_id
				WHERE campaign_id=".$campaign_id." AND b.country_code='{$geo}'
				LIMIT 1";
	}else{
		$sql = "SELECT COUNT(*) as total FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.campaign_feed_lang b
				ON a.id = b.campaign_feed_id 
				WHERE a.campaign_id=".$campaign_id." LIMIT 1";
	}
	$twitter = fetch($sql,$conn);
	//facebook
	if($lang=='all'){
	$sql = "SELECT COUNT(a.feed_id) as total FROM smac_report.campaign_fb_history a
		INNER JOIN smac_data.feeds_facebook b
		ON a.feed_id = b.id
		WHERE a.campaign_id=".$campaign_id." AND b.message <> '' 
		
		LIMIT 1";
	}else{
		$sql = "SELECT COUNT(a.feed_id) as total FROM smac_report.campaign_fb_history a
		INNER JOIN smac_data.feeds_facebook b
		ON a.feed_id = b.id
		INNER JOIN smac_report.campaign_fb_lang c
		ON a.id = c.campaign_feed_id
		WHERE a.campaign_id=".$campaign_id." AND b.message <> ''
	
		AND c.lang = '".$lang."'
		LIMIT 1";
	}
	$fb = fetch($sql,$conn);
	//web
	//code here
	$sql = "SELECT COUNT(id) as total FROM smac_report.campaign_web_feeds a
			INNER JOIN smac_report.campaign_web_country b
			ON a.id = b.fid
			WHERE a.campaign_id=".$campaign_id."
			AND b.geo='{$geo}'
			LIMIT 1";
	$web = fetch($sql,$conn);
	
	//-->
	$f['twitter'] = $twitter['total'];
	$f['facebook'] = 0; //fb blm bisa
	$f['blog'] = $web['total'];
	
}else{
	//twitter
	if($lang=='all'){
		$sql = "SELECT COUNT(*) as total FROM smac_report.campaign_feeds a WHERE campaign_id=".$campaign_id." AND published_date >= '".$from_date."'
			AND published_date <= '".$to_date."' LIMIT 1";
	}else{
		$sql = "SELECT COUNT(*) as total FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.campaign_feed_lang b
				ON a.id = b.campaign_feed_id 
				WHERE a.campaign_id=".$campaign_id." AND published_date >= '".$from_date."'
				AND published_date <= '".$to_date."' LIMIT 1";
	}
	$twitter = fetch($sql,$conn);
	//facebook
	if($lang=='all'){
	$sql = "SELECT COUNT(a.feed_id) as total FROM smac_report.campaign_fb_history a
		INNER JOIN smac_data.feeds_facebook b
		ON a.feed_id = b.id
		WHERE a.campaign_id=".$campaign_id." AND b.message <> '' 
		AND b.created_time_ts>=".$from_date_ts." AND b.created_time_ts<=".$to_date_ts."
		LIMIT 1";
	}else{
		$sql = "SELECT COUNT(a.feed_id) as total FROM smac_report.campaign_fb_history a
		INNER JOIN smac_data.feeds_facebook b
		ON a.feed_id = b.id
		INNER JOIN smac_report.campaign_fb_lang c
		ON a.id = c.campaign_feed_id
		WHERE a.campaign_id=".$campaign_id." AND b.message <> ''
		AND b.created_time_ts>=".$from_date_ts." AND b.created_time_ts<=".$to_date_ts."
		AND c.lang = '".$lang."'
		LIMIT 1";
	}
	$fb = fetch($sql,$conn);
	//web
	//code here
	$sql = "SELECT COUNT(a.id) as total FROM smac_report.campaign_web_feeds
			WHERE campaign_id=".$campaign_id."
			AND published_ts >= {$from_date_ts} AND published_ts <= {$to_date_ts}
			LIMIT 1";
	$web = fetch($sql,$conn);
	//-->
	$f['twitter'] = $twitter['total'];
	$f['facebook'] = $fb['total'];
	$f['blog'] = $web['total'];
}

close_db($conn);

header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';



$xml .= '<rows>';
$xml .= '	<twitter>'.$f['twitter'].'</twitter>';
$xml .= '	<fb>'.$f['facebook'].'</fb>';
$xml .= '	<blog>'.$f['blog'].'</blog>';
$xml .= '</rows>';

echo $xml;
exit;