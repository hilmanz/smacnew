<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);
$from_date = ($_REQUEST['from']);
$to_date = ($_REQUEST['to']);

$conn = open_db(0);
$sql  = "SELECT keyword_id,
		SUM(total_mention_twitter) as twitter, 
		SUM(total_mention_facebook) as facebook, 
		SUM(total_mention_web) as web
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} AND dtreport >='".$from_date."' 
		AND dtreport <='".$to_date."'
		GROUP BY keyword_id";
$keywords = fetch_many($sql,$conn);
foreach($keywords as $n=>$v){
	$keyword_id = $v['keyword_id'];
	$sql = "SELECT a.label,b.keyword_txt as keyword FROM smac_web.tbl_campaign_keyword a
	INNER JOIN smac_web.tbl_keyword_power b
	ON a.keyword_id = b.keyword_id
	WHERE a.campaign_id={$campaign_id} AND a.keyword_id={$keyword_id} LIMIT 1";
	
	$r = fetch($sql,$conn);
	$keywords[$n]['label'] = $r['label'];
	$keywords[$n]['keyword'] = $r['keyword'];
	$keywords[$n]['total_mention'] = intval($keywords[$n]['twitter'])+intval($keywords[$n]['facebook'])+intval($keywords[$n]['web']);
}
mysql_close($conn);
print json_encode($keywords);
exit;
?>