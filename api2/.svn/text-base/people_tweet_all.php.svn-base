<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);

$start = intval(mysql_escape_string($_REQUEST['start']));
$perpage = intval(mysql_escape_string($_REQUEST['perpage']));
$person = clean($_REQUEST['person']);

if($perpage==0){
	$perpage = 5;
}
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
$type = intval(mysql_escape_string($_REQUEST['type'])); //positive isi 1, negative isi 2
if(strlen($person)>0){
	$conn = open_db(0);
	

	if(is_new_feeds($campaign_id,$conn)){
		$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
	}else{
		$FEEDS = "smac_report.campaign_feeds";
	}
		
	
	/*$sql = "SELECT published_date,author_id,author_name,author_avatar,content,followers as imp,
			b.sentiment,
			coordinate_x,
			coordinate_y,
			location
			FROM smac_report.campaign_feeds a
			LEFT JOIN smac_report.campaign_feed_sentiment b
			ON a.id = b.feed_id
			WHERE a.author_id='".$person."' ORDER BY a.id DESC 
			LIMIT ".$start.",".$perpage;
	*/
	$sql = "SELECT * FROM (SELECT feed_id,author_id,author_name,author_avatar,published_datetime as tanggal,content as body,location,
							coordinate_x,coordinate_y  
			FROM {$FEEDS} a 
			WHERE a.author_id='".$person."' 
			UNION ALL
			SELECT feed_id,author_id,author_name,author_avatar,published_date as tanggal,body,location,
					coordinate_x,coordinate_y  
			FROM smac_report.campaign_replay_bulk b
			WHERE b.author_id = '".$person."'
			) c
			ORDER BY c.tanggal DESC
			LIMIT ".$start.",".$perpage;
	
	$tweets = fetch_many($sql,$conn);
	

	$sql = "SELECT COUNT(id) as total
			FROM {$FEEDS}
			WHERE author_id='".$person."' LIMIT 1";
	$rows = fetch($sql,$conn);
	
	mysql_close($conn);
	
	header("Content-type: application/json");
	print json_encode(array("status"=>1,"data"=>$tweets,"total"=>$rows['total']));
	$tweets = null;
	
}else{
	header("Content-type: application/json");
	print json_encode(array("status"=>0));
}
?>