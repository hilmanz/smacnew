<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
$conn = open_db(0);
//summary stats
$sql = "SELECT dtreport,
SUM(total_mention_twitter) as mentions
FROM smac_report.campaign_rule_volume_history 
WHERE campaign_id={$campaign_id}";
$f = fetch($sql,$conn);

//mention and impression difference.
$sql = "SELECT dtreport,
SUM(total_mention_twitter) as mentions
FROM smac_report.campaign_rule_volume_history 
WHERE campaign_id={$campaign_id} 
GROUP BY dtreport 
ORDER BY dtreport DESC 
LIMIT 2;";

$rs = fetch_many($sql,$conn);
close_db($conn);
if(sizeof($rs)==2){
	$h1_mention = $rs[0]['mentions'];
	$h0_mention = $rs[1]['mentions'];
	$mention_diff = $h1_mention - $h0_mention;
	$mention_change = round($mention_diff/$h0_mention,2);	
	
}else{
	$mention_diff = 0;
	$mention_change = 0;
}

header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml.='<data>';
$xml.= '<total>'.$f['mentions'].'</total>';
$xml.= '<mention_change>'.$mention_change.'</mention_change>';
$xml.="</data>";
echo $xml;
exit;
?>