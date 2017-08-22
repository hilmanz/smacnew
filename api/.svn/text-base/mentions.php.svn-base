<?php

include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);

$conn = open_db(0);
$sql = "SELECT mentions FROM smac_report.dashboard_summary WHERE campaign_id='$campaign_id' && client_id='$client_id' LIMIT 1;";
$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);
mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';

$xml .= '<mentions>'.$f['mentions'].'</mentions>';

echo $xml;
exit;