<?php
/**
 * Topic Summary Service
 */
include_once "config.php";
include_once "common.php";
include_once "libs/TopicSummary.php";
$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
$action = mysql_escape_string($_REQUEST['action']);
$start_date = mysql_escape_string($_REQUEST['start_date']);
$end_date = mysql_escape_string($_REQUEST['end_date']);
$keywords = ($_REQUEST['keywords']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
$conn = open_db(0);
$report = new TopicSummary($conn);
$report->set_campaign_id($campaign_id);
$report->set_client_id($client_id);
$report->set_language($lang);
$report->set_keywords($keywords);
if(method_exists($report,$action)){
	$arr = @$report->$action($start_date,$end_date);
}else{
	$arr = array("status"=>"0","message"=>"Invalid Action");
}
close_db($conn);
print json_encode($arr);
exit;
?>