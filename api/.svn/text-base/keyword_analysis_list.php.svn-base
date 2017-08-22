<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$filter = intval($_REQUEST['filter']);

$conn = open_db(0);
if($filter==0){
	//retrieve all available keywords
	
	
	$sql = "SELECT a.keyword_id,a.label,a.n_status,b.keyword_txt as keyword 
			FROM smac_web.tbl_campaign_keyword a
			INNER JOIN smac_web.tbl_keyword_power b
				ON a.keyword_id = b.keyword_id
			WHERE a.campaign_id={$campaign_id} AND 
			EXISTS (
				SELECT c.keyword_id FROM smac_report.campaign_rule_volume_history c
				WHERE c.campaign_id={$campaign_id} AND c.keyword_id = a.keyword_id 
			);";
	$q = mysql_query($sql,$conn);
	$keywords = array();
	while($f=mysql_fetch_assoc($q)){	
		$keywords[] = $f;
	}
	mysql_free_result($q);
	$f = null;
	unset($f);
	
}else{
	if($filter==2){
		$top=50;
	}else{
		$top=10;
	}
	$sql = "SELECT * FROM (SELECT keyword,SUM(mention) as total 
			FROM smac_report.campaign_top50_daily 
			WHERE campaign_id=".$campaign_id." GROUP BY keyword) a 
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