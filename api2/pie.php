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
$conn = open_db(0);

$str = "<?xml version=\"1.0\"?>\n";
$str.="<rows>\n";
$str.="<row>";
$str.="<positive>80</positive>\n";
$str.="<negative>20</negative>\n";
$str.="<twitter>80</twitter>\n";
$str.="<blogs>20</blogs>\n";
$str.="<facebook>5</facebook>\n";
$str.="</row>\n";
$str.="</rows>\n";

close_db($conn);
header("Content-type: text/xml");
print $str;
?>
