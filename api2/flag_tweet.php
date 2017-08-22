<?php
/**
 * a service to retrieve tweets which mentioned the queried keyword
 */
include_once "config.php";
include_once "common.php";
$start = intval($_REQUEST['start']);
$feed_id = clean($_REQUEST['feed_id']);
$keyword = clean($_REQUEST['keyword']);
$opt = intval($_REQUEST['opt']);
$folder_id = intval($_REQUEST['type']); //folder_id

//-->
$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);


if($campaign_id>0&&$feed_id > 0 && strlen($keyword)>0){
	
	$conn = open_db(0);
	
	
	if($opt==1){
		//delete the tweet
		$sql="DELETE FROM smac_report.workflow_marked_tweets WHERE campaign_id=".$campaign_id." AND feed_id='".$feed_id."' AND keyword='".$keyword."'";
		$q = mysql_query($sql,$conn);
		
		//flag the tweet
		$sql="INSERT IGNORE INTO smac_report.workflow_marked_tweets(campaign_id,feed_id,keyword,folder_id,posted_date_ts,posted_date)
			 VALUES(".$campaign_id.",'".$feed_id."','".$keyword."',".$folder_id.",".time().",NOW())";	
		$q = mysql_query($sql,$conn);
	}else{
		//unflag the tweet
		//flag the tweet
		//$sql="DELETE FROM smac_report.workflow_marked_tweets WHERE campaign_id=".$campaign_id." AND feed_id=".$feed_id."";
		//$q = mysql_query($sql,$conn);
	}
	close_db($conn);
	
	if($q){
		$status = 1;
	}else{
		$status = 0;
	}
	
	$arr = array("status"=>$status);
	$tweets = null;
	
}else{
	$arr = array("status"=>0);
	
}
header("Content-type: application/json");
print json_encode($arr);
?>