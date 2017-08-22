<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$filter = intval($_REQUEST['filter']);
$geo = mysql_escape_string($_REQUEST['geo']);

$conn = open_db(0);
if($filter==0){
	//retrieve all available keywords
	
	$sql = "SELECT a.keyword_id,SUM(a.total_mention) as total,b.label,
			b.n_status,c.keyword_txt as keyword
			FROM smac_report.daily_country_volume a
			INNER JOIN smac_web.tbl_campaign_keyword b
			ON a.keyword_id = b.keyword_id AND b.campaign_id={$campaign_id}
			INNER JOIN smac_web.tbl_keyword_power c
			ON b.keyword_id = c.keyword_id
			WHERE a.campaign_id={$campaign_id} AND a.country_id='{$geo}' 
			GROUP by a.keyword_id ORDER BY total DESC LIMIT 10;";
	
	
	$keywords = fetch_many($sql,$conn);
	$f = null;
	mysql_free_result($q);
}else{
	if($filter==2){
		$top=50;
	}else{
		$top=10;
	}
	$sql = "SELECT * FROM (SELECT keyword,SUM(mention) as total 
			FROM smac_market.campaign_top50_daily 
			WHERE campaign_id=".$campaign_id." AND geo='{$geo}' GROUP BY keyword) a 
			ORDER BY total DESC LIMIT ".$top;
	
	$keywords = fetch_many($sql,$conn);
	foreach($keywords as $n=>$v){
		$keywords[$n]['keyword_id']=0;
		$keywords[$n]['n_status']=0;
	}
}
mysql_close($conn);

print json_encode($keywords);
exit;
?>