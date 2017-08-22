<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
$geo = mysql_escape_string($_REQUEST['geo']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
$conn = open_db(0);
if($lang=='all'){
$sql = "SELECT mentions FROM smac_market.dashboard_summary WHERE campaign_id='$campaign_id' && client_id='$client_id' AND geo='{$geo}'
LIMIT 1;";
}else{
$sql = "SELECT mentions FROM smac_market.dashboard_summary_lang WHERE campaign_id='$campaign_id' && client_id='$client_id' AND geo='{$geo}' LIMIT 1;";	
}
$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);

mysql_free_result($q);

$sql = "SELECT published_date,mentions,impressions,rt_impression 
FROM smac_market.campaign_daily_stats 
WHERE campaign_id=".$campaign_id." AND lang='".$lang."' AND geo='{$geo}'
ORDER BY id DESC LIMIT 2";
$q = mysql_query($sql,$conn);
$rows = array();
while($ff = mysql_fetch_assoc($q)){
	$rows[] = $ff;
}
if(sizeof($rows)==2){
	$rows[0]['imp'] = $rows[0]['impressions']+$rows[0]['rt_impression'];
	$rows[1]['imp'] = $rows[1]['impressions']+$rows[1]['rt_impression'];
	$sql = "SELECT SUM(mentions) as mentions,SUM(impressions) as imp,SUM(rt_impression) as rt_imp
			FROM smac_market.campaign_daily_stats 
			WHERE campaign_id={$campaign_id} AND published_date < '{$rows[0]['published_date']}' AND lang='all' AND geo='{$geo}' LIMIT 1;";
	$perf = fetch($sql,$conn);
	
	//$mention_change = round(($rows[0]['mentions']-$rows[1]['mentions'])/$rows[1]['mentions']*100,2);
	$mention_change = round(($rows[0]['mentions'])/($perf['mentions'])*100,1);
}
close_db($conn);

header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml.='<data>';
$xml.= '<total>'.$f['mentions'].'</total>';
$xml.= '<mention_change>'.$mention_change.'</mention_change>';
$xml.="</data>";
echo $xml;
exit;
?>