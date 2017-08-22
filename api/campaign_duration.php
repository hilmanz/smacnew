<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}


$conn = open_db(0);
$sql = "SELECT dtreport as published_date
FROM smac_report.campaign_rule_volume_history
WHERE campaign_id={$campaign_id} 
GROUP BY dtreport 
ORDER BY dtreport ASC";
 
$the_date = fetch_many($sql,$conn);
mysql_close($conn);
print json_encode($the_date);
exit;
?>