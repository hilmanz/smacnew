<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);
$from_date = ($_REQUEST['from']);
$to_date = ($_REQUEST['to']);

$conn = open_db(0);
$sql = "SELECT dtreport as tgl,SUM(sum_pii)/SUM(total_mention_positive+total_mention_negative) as pii_score
		FROM smac_report.daily_campaign_sentiment 
		WHERE campaign_id={$campaign_id} AND dtreport >='".$from_date."' 
			AND dtreport <='".$to_date."'
		GROUP BY dtreport";
$stats = fetch_many($sql,$conn);
mysql_close($conn);
$conn = null;
print json_encode($stats);
$stats = null;
exit;