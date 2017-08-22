<?php
/**
 * Create Campaign Service
 */

include_once "config.php";
include_once "common.php";
include_once "libs/Campaign.php";
include_once "../engines/Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName('api_campaign');
$logger->setDirectory('../logs/');
$logger->info("save_campaign");

$conn = open_db(0);
$campaign = new Campaign($logger);
header("Content-type: application/json");
$str = $campaign->create_campaign();
$logger->info("output :".$str);
close_db($conn);
if(strlen($str)==0){
	print json_encode(array("status"=>-1,"message"=>"no output returned"));
}else{
	print $str;
}
exit();
?>