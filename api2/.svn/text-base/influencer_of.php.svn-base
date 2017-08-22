<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);

$start = intval(mysql_escape_string($_REQUEST['start']));
$perpage = intval(mysql_escape_string($_REQUEST['perpage']));
$person = clean($_REQUEST['person']);

if($perpage==0){
	$perpage = 10;
}
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
$type = intval(mysql_escape_string($_REQUEST['type'])); //positive isi 1, negative isi 2
if(strlen($person)>0){
	$conn = open_db(0);
	if($type==1){
		$sql="SELECT * FROM (SELECT * FROM  (SELECT author_id,author_avatar,COUNT(author_id) as occurance
				FROM smac_report.campaign_replay_bulk 
				WHERE campaign_id=".$campaign_id." 
				AND rtof_author_id='".$person."'
				GROUP BY author_id) a
				UNION ALL
				SELECT rt_author_id as author_id,rt_author_pic as author_avatar, occurance
				FROM (SELECT rt_author as rt_author_id,rt_author_pic,COUNT(rt_author) as occurance 
				FROM smac_data.tbl_rt_content WHERE author='".$person."'
				GROUP BY rt_author) b
				) c
				ORDER BY occurance DESC
				LIMIT ".$start.",".$perpage;
	}else{
		$sql = "SELECT rt_author_id as author_id,rt_author_pic as author_avatar, occurance
				FROM (SELECT rt_author as rt_author_id,rt_author_pic,COUNT(rt_author) as occurance 
				FROM smac_report.campaign_feeds a 
				INNER JOIN smac_data.tbl_rt_content b 
				ON a.feed_id = b.feed_id
				WHERE a.campaign_id=".$campaign_id." AND b.author='".$person."' 
				GROUP BY rt_author) a
				ORDER BY occurance DESC
				LIMIT ".$start.",".$perpage;
	}
	$people = fetch_many($sql,$conn);
	
	/*
	$sql = "SELECT COUNT(id) as total
			FROM smac_report.campaign_feeds
			WHERE author_id='".$person."' LIMIT 1";
	$rows = fetch($sql,$conn);
	*/
	mysql_close($conn);
	
	header("Content-type: application/json");
	print json_encode(array("status"=>1,"data"=>$people,"total"=>0));
	$tweets = null;
	
}else{
	header("Content-type: application/json");
	print json_encode(array("status"=>0));
}
?>