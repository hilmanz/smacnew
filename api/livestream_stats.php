<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);

$data = array("people"=>0,"mentions"=>0,"reach"=>0);
$conn = open_db(0);
$sql = "SELECT COUNT(author_id) as people FROM (SELECT author_id
FROM smac_report.campaign_feeds WHERE campaign_id=".$campaign_id." GROUP BY author_id ) a LIMIT 1;";
$q = mysql_query($sql,$conn);
$rs = mysql_fetch_assoc($q);
mysql_free_result($q);
$data['people'] = smac_number($rs['people']);
$rs = null;

$sql = "SELECT COUNT(id) as mentions FROM smac_report.campaign_feeds WHERE campaign_id=".$campaign_id." LIMIT 1;";
$q = mysql_query($sql,$conn);
$rs = mysql_fetch_assoc($q);
mysql_free_result($q);
$data['mentions'] = smac_number($rs['mentions']);
$rs = null;

$sql = "SELECT SUM(followers) as reach FROM smac_report.campaign_feeds WHERE campaign_id=".$campaign_id." LIMIT 1;";
$q = mysql_query($sql,$conn);
$rs = mysql_fetch_assoc($q);
mysql_free_result($q);
$data['reach'] = smac_number($rs['reach']);
$rs = null;
close_db($conn);

header("Content-type: application/json");
print json_encode(array("status"=>1,"data"=>$data));
exit;
?>