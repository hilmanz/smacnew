<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);
$geo = mysql_escape_string($_REQUEST['geo']);
$from_date = ($_REQUEST['from']);
$to_date = ($_REQUEST['to']);
$conn = open_db(0);
$sql = "SELECT a.keyword_id,SUM(total_rt) AS retweets,
		c.keyword_txt AS keyword,b.label
		FROM smac_report.daily_country_volume a
		INNER JOIN smac_web.tbl_campaign_keyword b
		ON a.keyword_id = b.keyword_id AND b.campaign_id={$campaign_id}
		INNER JOIN smac_web.tbl_keyword_power c
		ON a.keyword_id = c.keyword_id
		WHERE a.campaign_id = {$campaign_id}
		AND country_id='{$geo}'
		AND dtreport BETWEEN '{$from_date}' AND '{$to_date}'
		GROUP BY a.keyword_id
		ORDER BY retweets DESC LIMIT 10;";

$keywords = fetch_many($sql,$conn);

mysql_close($conn);


print json_encode($keywords);
exit;