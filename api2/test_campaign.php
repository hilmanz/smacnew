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

$str = $campaign->upload_replay_keyword(2,array("makan nasi lang:id","makan ayam lang:id"),1,$conn);

$logger->info("output :".$str);
close_db($conn);
if(strlen($str)==0){
	print json_encode(array("status"=>-1,"message"=>"no output returned"));
}else{
	print $str;
}
exit();
?>