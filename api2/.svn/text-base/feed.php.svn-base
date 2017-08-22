<?php
/**
 * Twitter Search RSS feeder
 * run per minutes
 */
include_once "config.php";
include_once "common.php";
$last_id = intval($_REQUEST['last_id']);

//temporary solution, until gnip feed re-activated
if($last_id==0){
	//$last_id = 1;
}
//-->

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$entry = $arr['feed']['_c']['entry'];
$limit = intval($_REQUEST['limit']);
if($limit>1000){
	$limit = 1000;
}
if($limit==0){$limit=100;}
$conn = open_db(0);

$sql = "SELECT * FROM ".$SCHEMA_WEB.".tbl_campaign 
		WHERE client_id=".$client_id." 
		AND id=".$campaign_id." 
		LIMIT 1";

$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);
mysql_free_result($q);
/*
if($f['tracking_method']=="0"){
	$str = get_standard_feeds(intval($f['id']),$last_id,$limit);
}else{
	$str = get_power_feeds(intval($f['id']),$last_id,$limit);
}
*/
$str = get_realtime_feeds(intval($f['id']),$last_id,$limit);
close_db($conn);
header("Content-type: text/xml");
print $str;
?>
