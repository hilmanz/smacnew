<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);

$conn = open_db(0);
$sql = "SELECT SUM(t_usage) as total_usage,SUM(t_limit) as total_limit FROM 
(SELECT (traffic+traffic_fb+traffic_web) as t_usage,0 as t_limit FROM smac_web.campaign_traffic
WHERE campaign_id={$campaign_id}
UNION ALL 
SELECT 0 as t_usage,total_limit as t_limit FROM smac_report.topic_usage WHERE campaign_id={$campaign_id}) c;";

$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);
mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';
$xml .= '<row name="usage" total="'.number_format($f['total_usage']).'" />';
$xml .= '<row name="limit" total="'.number_format($f['total_limit']).'" />';
$xml .= '<row name="percentage" total="'.round($f['total_usage']/$f['total_limit']*100,2).'" />';
$xml .= '</rows>';

echo $xml;
exit;