<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);

$conn = open_db(0);

//1. retrieve all the sentiments score
$sql = "SELECT keyword,occurance as total,type as score FROM smac_report.campaign_sentiment WHERE campaign_id=".$campaign_id." ORDER BY occurance  DESC LIMIT 100";
$q = mysql_query($sql,$conn);
$sentiments = array();
while($f=mysql_fetch_assoc($q)){
	$sentiments[] = $f;
}
$f = null;
mysql_free_result($q);

mysql_close($conn);
print json_encode($sentiments);
exit;
?>