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
$conn = open_db(0);
if($from_date==null&&$to_date==NULL){
	$sql = "SELECT twitter,facebook,blog FROM smac_report.source_count 
			WHERE campaign_id={$campaign_id} LIMIT 1";
	$f = fetch($sql,$conn);
	
	
}else{

	$is_new_table = 0;
	$sql = "SELECT n_status FROM smac_report.campaign_feeds_split_flag WHERE campaign_id={$campaign_id} LIMIT 1";
	$row = fetch($sql,$conn);
	$is_new_table = intval($row['n_status']);

	//twitter
	if($lang=='all'){

		if ($is_new_table) {
			$sql = "SELECT COUNT(*) as total 
					FROM smac_feeds.campaign_feeds_{$campaign_id} 
					WHERE campaign_id=".$campaign_id." AND published_date >= '".$from_date."'
							AND published_date <= '".$to_date."' AND is_active = 1 LIMIT 1";
		} else {
			$sql = "SELECT COUNT(*) as total 
					FROM smac_report.campaign_feeds 
					WHERE campaign_id=".$campaign_id." AND published_date >= '".$from_date."'
							AND published_date <= '".$to_date."' LIMIT 1";			
		}

	}else{
		if ($is_new_table) {
			$sql = "SELECT COUNT(*) as total 
					FROM smac_feeds.campaign_feeds_{$campaign_id} a
					INNER JOIN smac_report.campaign_feed_lang b
					ON a.id = b.campaign_feed_id 
					WHERE a.campaign_id=".$campaign_id." AND a.published_date >= '".$from_date."'
					AND a.published_date <= '".$to_date."' AND is_active = 1 LIMIT 1";					
		} else {
			$sql = "SELECT COUNT(*) as total 
					FROM smac_report.campaign_feeds a
					INNER JOIN smac_report.campaign_feed_lang b
					ON a.id = b.campaign_feed_id 
					WHERE a.campaign_id=".$campaign_id." AND published_date >= '".$from_date."'
					AND published_date <= '".$to_date."' LIMIT 1";
		}

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
	$sql = "SELECT COUNT(id) as total FROM smac_report.campaign_web_feeds
			WHERE campaign_id=".$campaign_id."
			AND published_ts >= {$from_date_ts} AND published_ts <= {$to_date_ts}
			LIMIT 1";
	$web = fetch($sql,$conn);
	//-->

	//print $sql;

	$f['twitter'] = $twitter['total'];
	$f['facebook'] = $fb['total'];
	$f['blog'] = $web['total'];
}

close_db($conn);

header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';


//dummy buat demo bikin 100%
$xml .= '<rows>';
$xml .= '	<twitter>'.$f['twitter'].'</twitter>';
$xml .= '	<fb>'.$f['facebook'].'</fb>';
$xml .= '	<blog>'.$f['blog'].'</blog>';
$xml .= '</rows>';

echo $xml;
exit;