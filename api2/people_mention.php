<?php
/**
 * a service to retrieve people who mentioned the queried keyword
 */
include_once "config.php";
include_once "common.php";
$start = intval($_REQUEST['start']);
$keyword = clean($_REQUEST['keyword']);
$lang = clean($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
//temporary solution, until gnip feed re-activated
if($last_id==0){
	//$last_id = 1;
}
//-->
$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$entry = $arr['feed']['_c']['entry'];
$limit = intval($_REQUEST['limit']);
if($limit>1000){
	$limit = 1000;
}
if($limit==0){
	$limit = 100;
}

if($campaign_id>0){
	
	$conn = open_db(0);

	//1. hitung total impression dari topic ini.
	
	$sql = "SELECT SUM(followers) as total
	FROM smac_report.campaign_feeds
	WHERE campaign_id=".$campaign_id." 
	AND feed_id NOT IN (SELECT feed_id FROM smac_report.workflow_marked_tweets WHERE campaign_id=".$campaign_id.")
	LIMIT 1";
	$rows = fetch($sql,$conn);
	
	//2. ambil daftar orang, sekaligus hitung sharenya
	$sql = "SELECT feed_id,author_id,published_date,klout_score,SUM(followers) as total_imp
	FROM smac_report.campaign_feeds a
	WHERE campaign_id=".$campaign_id." AND wordlist LIKE '%".$keyword."%'
	AND feed_id NOT IN (SELECT feed_id FROM smac_report.workflow_marked_tweets WHERE campaign_id=".$campaign_id.")
	GROUP BY author_id
	ORDER BY total_imp DESC
	LIMIT ".$start.",".$limit;
	$people = fetch_many($sql,$conn);
	foreach($people as $n=>$v){
		$people[$n]['share'] = round($v['total_imp'] / $rows['total']*100,5);
	}
	close_db($conn);

	
	$arr = array("status"=>1,"data"=>$people);
	
	
}else{
	$arr = array("status"=>0);
	
}
header("Content-type: application/json");
print json_encode($arr);
?>