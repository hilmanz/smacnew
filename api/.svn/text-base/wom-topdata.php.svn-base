<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$exclude = intval($_REQUEST['exclude']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

$conn = open_db(0);


//total impressions
$sql = "SELECT SUM(total_impression_twitter) AS true_reach
FROM smac_report.campaign_rule_volume_history 
WHERE campaign_id={$campaign_id};";
$q = mysql_query($sql,$conn);
$campaign=mysql_fetch_assoc($q);
mysql_free_result($q);
$total_impression = $campaign['true_reach'];
$campaign=null;

//--> top KOL
if($exclude==1){
		$sql = "SELECT * FROM smac_report.twitter_top_authors
		WHERE campaign_id = {$campaign_id}
		AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
		ORDER BY total_impression desc
		LIMIT 10;";
}else if($exclude==2){
	$sql = "SELECT * FROM smac_report.twitter_top_authors
		WHERE campaign_id = {$campaign_id}
		AND author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
		ORDER BY total_impression desc
		LIMIT 10;";
}else{
	$sql = "SELECT * FROM smac_report.twitter_top_authors
		WHERE campaign_id = {$campaign_id}
		ORDER BY total_impression desc
		LIMIT 10;";
}


$q = mysql_query($sql,$conn);
//$f = mysql_fetch_assoc($q);

$num = mysql_num_rows($q);

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<top>';

if($num > 0){
	while($r=mysql_fetch_array($q)){
		$r['rt_percentage'] = round($r['total_rt_mentions']/($r['total_mentions'] + $r['rt_mention']) * 100,2);
		$r['share'] = round(($r['total_impression'] / $total_impression) * 100,2);
		$xml .= '	<user>';
		$xml .= '		<person>'.htmlspecialchars($r['author_id']).'</person>';
		$xml .= '		<name>'.htmlspecialchars($r['author_name']).'</name>';
		$xml .= '		<total>'.round($r['amplification_score']).'</total>';
		$xml .= '		<rt_percentage>'.round($r['rt_percentage'],2).'</rt_percentage>';
		$xml .= '		<impression>'.($r['total_impression']).'</impression>';
		$xml .= '		<rt_impression>'.($r['total_rt_impression']).'</rt_impression>';
		$xml .= '		<total_impression>'.($r['total_impression']).'</total_impression>';
		$xml .= '		<share>'.($r['share']).'</share>';
		$xml .= '		<rt>0</rt>';
		$xml .= '		<img>'.$r['author_avatar'].'</img>';
		$xml .= '	</user>';
	}
}
$xml .= '</top>';

mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
echo $xml;
exit;