<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$day = mysql_escape_string($_REQUEST['day']);
$start = intval(mysql_escape_string($_REQUEST['start']));
$perpage = intval(mysql_escape_string($_REQUEST['perpage']));
if($perpage==0){
	$perpage = 5;
}
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
$type = intval(mysql_escape_string($_REQUEST['type'])); //positive isi 1, negative isi 2

$conn = open_db(0);
if(is_new_feeds($campaign_id,$conn)){
	$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
}else{
	$FEEDS = "smac_report.campaign_feeds}";
}

if($type==1){
$sql = "SELECT * FROM (
					SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers as imp 
					FROM 
					smac_report.campaign_feed_sentiment a
					INNER JOIN {$FEEDS} b
						ON a.feed_id = b.id AND a.campaign_id = ".$campaign_id."
					WHERE 
					b.campaign_id=".$campaign_id." 
					AND a.sentiment > 0 
					AND b.published_date = '".$day."'
					) c
					ORDER BY c.imp DESC LIMIT ".$start.",".$perpage;

$sql2 = "SELECT COUNT(*) as total FROM (
				SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers as imp FROM smac_report.campaign_feed_sentiment a
				INNER JOIN {$FEEDS} b
						ON a.feed_id = b.id AND a.campaign_id = ".$campaign_id."
				WHERE 
				b.campaign_id=".$campaign_id." 
				AND a.sentiment > 0 
				AND b.published_date = '".$day."'
			) c 
			LIMIT 1";

}else if($type==3){ //tampilkan 2-2 nya dan diurutkan dari tanggal terakhir
	$sql = "SELECT * FROM (
						SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers as imp 
						FROM smac_report.campaign_feed_sentiment a
						INNER JOIN {$FEEDS} b
							ON a.feed_id = b.id AND a.campaign_id = ".$campaign_id."						 
						WHERE b.campaign_id=".$campaign_id." AND 
							(a.sentiment > 0 OR a.sentiment < 0)
					) c
					ORDER BY c.imp DESC LIMIT ".$start.",".$perpage;

	$sql2 = "SELECT COUNT(*) as total FROM (
					SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers as imp FROM smac_report.campaign_feed_sentiment a
					INNER JOIN {$FEEDS} b
						ON a.feed_id = b.id AND a.campaign_id = ".$campaign_id."
					WHERE b.campaign_id=".$campaign_id." 
						AND (a.sentiment > 0 OR a.sentiment < 0)
				) c 
				LIMIT 1";
	
}else{
$sql = "SELECT * FROM (
					SELECT published_date,author_id,author_name,author_avatar,
					content,sentiment,followers as imp 
					FROM smac_report.campaign_feed_sentiment a
					INNER JOIN {$FEEDS} b
						ON a.feed_id = b.id AND a.campaign_id = ".$campaign_id."
					WHERE b.campaign_id=".$campaign_id." 
						AND a.sentiment < 0 AND b.published_date = '".$day."'
			) c
		ORDER BY c.imp DESC
		LIMIT ".$start.",".$perpage;

$sql2 = "SELECT COUNT(*) as total FROM (
		SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers as imp 
		FROM smac_report.campaign_feed_sentiment a
			INNER JOIN {$FEEDS} b
						ON a.feed_id = b.id AND a.campaign_id = ".$campaign_id."
		WHERE b.campaign_id=".$campaign_id." 
		AND a.sentiment < 0 
		AND b.published_date = '".$day."'
		) c LIMIT 1";
}
$tweets = fetch_many($sql,$conn);
$rows = fetch($sql2,$conn);
mysql_close($conn);
print json_encode(array("data"=>$tweets,"total"=>$rows['total']));
$tweets = null;
exit;
?>