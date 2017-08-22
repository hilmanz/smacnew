<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$status = intval($_REQUEST['status']);

$conn = open_db(0);
$sql = "UPDATE ".$SCHEMA_WEB.".tbl_campaign SET n_status='$status' WHERE client_id='$client_id' AND id='$campaign_id';";
$q = mysql_query($sql,$conn);
$data = array();
if($q){
	//update status keyword berdasarkan status campaign.
	if($status==1){
		//reactivate keyword
		$sql = "UPDATE ".$SCHEMA_WEB.".tbl_campaign_keyword SET n_status=1 WHERE campaign_id=".$campaign_id." AND n_status=0";
		mysql_query($sql,$conn);
		//-->
		//reactivate keyword in collector rules
		$sql = "SELECT a.keyword_id FROM smac_web.tbl_keyword_power a
				INNER JOIN smac_web.tbl_campaign_keyword b
				ON a.keyword_id=b.keyword_id
				WHERE b.campaign_id=".$campaign_id." 
				AND b.n_status=1 AND a.n_status >  1";
		$a_kwds = fetch_many($sql,$conn);
		
		$s_keys = "";
		$n=0;
		foreach($a_kwds as $keyword){
			if($n==1){
				$s_keys.=",";
			}
			$s_keys.=mysql_escape_string($keyword['keyword_id']);
			$n=1;
		}
		if(strlen($s_keys)>0){
			$sql = "UPDATE smac_web.tbl_keyword_power SET n_status=0 WHERE keyword_id IN (".$s_keys.")";
			mysql_query($sql,$conn);
		}
		//-->
	}else{
		//disactivate keyword
		$sql = "UPDATE ".$SCHEMA_WEB.".tbl_campaign_keyword SET n_status=0 WHERE campaign_id=".$campaign_id." AND n_status=1";
		mysql_query($sql,$conn);
		//-->
	}
	$data['success'] = 1;
}else{
	$data['success'] = 0;
}
mysql_free_result($q);
mysql_close($conn);

print json_encode($data);
exit;
?>