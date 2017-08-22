<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);
$geo = mysql_escape_string($_REQUEST['geo']);
$from_date = ($_REQUEST['from']);
$to_date = ($_REQUEST['to']);
$conn = open_db(0);

/*
$sql = "SELECT tag_id as keyword_id,SUM(total) as total_mention 
FROM smac_market.campaign_keyword_daily
WHERE campaign_id=".$campaign_id." AND geo='{$geo}'
GROUP BY tag_id
ORDER BY total_mention DESC LIMIT 10";
$q = mysql_query($sql,$conn);
$keyword_ids = "";
$n=0;
while($f=mysql_fetch_assoc($q)){
	if($n==1){
		$keyword_ids.=",";
	}
	$keyword_ids.=$f['keyword_id'];
	$n=1;
}
$f = null;
mysql_free_result($q);

$sql = "SELECT tag_id as keyword_id,published_date as tgl,matching_rule as keyword,SUM(total) as total_mention 
		FROM smac_market.campaign_keyword_daily 
		WHERE campaign_id=".$campaign_id." AND geo='{$geo}' AND n_type=0 AND tag_id IN (".$keyword_ids.")
		AND published_date >='".$from_date."' 
		AND published_date <='".$to_date."'
		GROUP BY tag_id 
		ORDER BY total_mention DESC";
$keywords = fetch_many($sql,$conn);
foreach($keywords as $n=>$v){
	$keyword_id = $v['keyword_id'];
	$sql = "SELECT label FROM smac_web.tbl_campaign_keyword WHERE campaign_id={$campaign_id} AND keyword_id={$keyword_id} LIMIT 1";
	
	$r = fetch($sql,$conn);
	$keywords[$n]['label'] = $r['label'];
}
mysql_close($conn);


print json_encode($keywords);
exit;
*/


$sql = "SELECT keyword_id,SUM(total_mention) as mentions
FROM smac_report.daily_country_volume 
WHERE campaign_id={$campaign_id} AND country_id='{$geo}' 
AND dtreport >='{$from_date}' 
AND dtreport <='{$to_date}'
GROUP BY keyword_id;";

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
	$keywords[$n]['total_mention'] = intval($keywords[$n]['mentions']);
}
mysql_close($conn);
print json_encode($keywords);
exit;
?>