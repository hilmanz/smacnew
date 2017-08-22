<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);
$geo = mysql_escape_string($_REQUEST['geo']);
$from = mysql_escape_string($_REQUEST['from']);
$to = mysql_escape_string($_REQUEST['to']);
$conn = open_db(0);

if($from_date!=null&&$to_date!=null){
	$sql = "SELECT dtreport,SUM(total_mention_positive) as positive,SUM(total_mention_negative) as negative 
			FROM smac_report.daily_country_campaign_sentiment 
			WHERE campaign_id={$campaign_id} AND country_code ='{$geo}' AND dtreport BETWEEN '{$from_date}' AND '{$to_date}'
			GROUP BY dtreport ORDER BY dtreport DESC LIMIT 60";
}else{
	$sql = "SELECT dtreport,SUM(total_mention_positive) as positive,SUM(total_mention_negative) as negative 
			FROM smac_report.daily_country_campaign_sentiment 
			WHERE campaign_id={$campaign_id} AND country_code ='{$geo}' 
			GROUP BY dtreport ORDER BY dtreport DESC LIMIT 60";
}
$rs = fetch_many($sql,$conn);

mysql_close($conn);
$positive = array();
$negative = array();

while(sizeof($rs)>0){
	$r = array_pop($rs);
	$positive[$r['dtreport']] = intval($r['positive']);
	$negative[$r['dtreport']] = intval($r['negative']);
	
}
print json_encode(array("positive"=>$positive,"negative"=>$negative));
exit;
?>