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
//total impressions
$sql = "select sum(total_impression_twitter+total_rt_impression_twitter) as total_imp
		from smac_report.campaign_rule_volume_history 
		where campaign_id = {$campaign_id}";
$campaign = fetch($sql,$conn);
$total_impression = $campaign['total_imp'];
$campaign=null;

//people summary
$sql = "SELECT author_id,author_name,author_avatar,
		total_mentions as mentions,
		total_impression as impression,
		total_rt_mentions as rt_total,
		total_rt_impression as rt_impression 
		FROM smac_report.twitter_top_authors 
		WHERE campaign_id={$campaign_id} 
		ORDER BY total_impression DESC LIMIT 5;";

$rs = fetch_many($sql,$conn);

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';

if(sizeof($rs)>0){
	foreach($rs as $r){
		if($r['rt_impression']==null){
			$r['rt_impression'] = 0;
		}
		$r['share'] = ceil((($r['impression']+$r['rt_impression'])/$total_impression)*100);
		$xml .= '<user image="'.$r['author_avatar'].'" name="'.$r['author_id'].'" followers="'.smac_number($r['impression']+$r['rt_impression']).'" rt="'.smac_number($r['rt_total']).'" share="'.$r['share'].'"/>';
	}
}
$xml .= '</rows>';

mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
echo $xml;
exit;