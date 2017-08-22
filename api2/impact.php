<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$from_date = ($_REQUEST['from']);
$to_date = ($_REQUEST['to']);
$lang = mysql_escape_string($_REQUEST['lang']);
$geo = mysql_escape_string($_REQUEST['geo']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
$conn = open_db(0);
//total days
$sql ="SELECT COUNT(*) AS total FROM (SELECT DISTINCT dtreport
FROM smac_report.campaign_rule_volume_history 
WHERE campaign_id={$campaign_id}) a;";
$days = fetch($sql,$conn);


//mentions and impressions
$sql = "SELECT COUNT(dtreport) as total_days,SUM(total_mention) as mentions, 
SUM(total_author) as people,
SUM(total_impression) as impressions,
SUM(total_rt) as total_rt
FROM smac_report.daily_country_volume 
WHERE campaign_id={$campaign_id} 
AND country_id='{$geo}'
AND channel = 1"; 

$f = fetch($sql,$conn);

//mention and impression differences
$sql = "SELECT dtreport,SUM(total_mention) as mentions,
SUM(total_impression) as impression
FROM smac_report.daily_country_volume 
WHERE campaign_id={$campaign_id} 
AND country_id='{$geo}'
AND channel = 1
GROUP BY dtreport
ORDER BY dtreport DESC
LIMIT 2;";
$rs = fetch_many($sql,$conn);


//PII

$sql = "SELECT dtreport,pii,sentiment_total as total
FROM (SELECT dtreport,SUM(sum_pii) as pii,SUM(total_mention_positive+total_mention_negative) as sentiment_total 
FROM 
smac_report.daily_country_campaign_sentiment 
WHERE campaign_id={$campaign_id}
AND country_code='{$geo}'
GROUP BY dtreport) a
WHERE a.pii <> 0
ORDER BY dtreport DESC 
LIMIT 2;";
$pii = fetch_many($sql,$conn);

close_db($conn);


//wrapping up the performances
if(sizeof($rs)==2){
	$h1_mention = $rs[0]['mentions'];
	$h0_mention = $rs[1]['mentions'];
	$mention_diff = $h1_mention - $h0_mention;
	$mention_change = round($mention_diff/$h0_mention,2);	
	
	$h1_imp = $rs[0]['impression'];
	$h0_imp = $rs[1]['impression'];
	$imp_diff = $h1_imp - $h0_imp;
	$imp_change = round($imp_diff/$h0_imp,2);
	
}else{
	$mention_diff = 0;
	$mention_change = 0;
	$imp_diff = 0;
	$imp_change = 0;
}
if(sizeof($pii)>1){
	$pii1 =  round($pii[0]['pii']/$pii[0]['total'],2);
	$pii2 =  round($pii[1]['pii']/$pii[1]['total'],2);
	$pii_diff = $pii1 - $pii2;
	$pii_change = round($pii_diff/$pii2,2);
	$pii_score = $pii1;
	
}else if(sizeof($pii)==1){
	$pii_score = round($pii[0]['pii']/$pii[0]['total'],2);
	$pii_diff = 0;
	$pii_change = 0;
}else{
	$pii_score = 0;
}
//-->
header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= "<rows>";
//$xml .= '<impact>'.(round($f['sentiment_positive']/($f['sentiment_positive']+$f['sentiment_negative']),2)*100).'</impact>';
$xml .= '<impact>'.$pii_score.'</impact>';
$xml .= "<mentions>".number_format($f['mentions'])."</mentions>";
$xml .= "<impressi>".smac_number($f['impressions'])."+</impressi>";
$xml .= "<people>".number_format($f['people'])."</people>";
$xml .= "<imp_change>".$imp_change."</imp_change>";
$xml .= "<mention_change>".$mention_change."</mention_change>";
$xml .= "<pii_change>".$pii_change."</pii_change>";
$xml .= "<imp_diff>".$imp_diff."</imp_diff>";
$xml .= "<mention_diff>".$mention_diff."</mention_diff>";
$xml .= "<pii_diff>".$pii_diff."</pii_diff>";

$xml .= "<total_days>".number_format($days['total'])."</total_days>";
$xml .= "</rows>";

echo $xml;
exit;
?>