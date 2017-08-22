<?php
/**
 * a service to retrieve tweets which mentioned the queried keyword
 */
include_once "config.php";
include_once "common.php";
$start = intval($_REQUEST['start']);
$person = clean($_REQUEST['person']);
$opt = intval($_REQUEST['opt']);
$type = intval($_REQUEST['type']);

//-->
$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);


if($campaign_id>0&&strlen($person) > 0){
	
	$conn = open_db(0);
	if($opt==1){
		//flag the tweet
		$sql="INSERT IGNORE INTO smac_report.workflow_marked_people(campaign_id,author_id,flag,posted_date_ts,posted_date)
			 VALUES(".$campaign_id.",'".$person."',".$type.",".time().",NOW())";	
		$q = mysql_query($sql,$conn);
		
	}else{
		//unflag the tweet
		//flag the tweet
		$sql="DELETE FROM smac_report.workflow_marked_people WHERE campaign_id=".$campaign_id." AND author_id='".$person."'";
		$q = mysql_query($sql,$conn);
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