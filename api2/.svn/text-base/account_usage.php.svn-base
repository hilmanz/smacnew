<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);

$conn = open_db(0);
$sql = "SELECT account_usage,account_limit FROM smac_report.account_usage WHERE account_id='".intval($client_id)."' LIMIT 1;";
$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);
mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';
$xml .= '<row name="usage" total="'.number_format($f['account_usage']).'" />';
$xml .= '<row name="limit" total="'.number_format($f['account_limit']).'" />';
$xml .= '<row name="percentage" total="'.round($f['account_usage']/$f['account_limit']*100,2).'" />';
$xml .= '</rows>';

echo $xml;
exit;