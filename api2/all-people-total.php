<?php
include_once "config.php";
include_once "common.php";

$conn = open_db(0);

$campaign_id = intval($_GET['campaign_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
$geo = mysql_escape_string($_REQUEST['geo']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

if($lang=='all'){
	$qry = "SELECT COUNT(*) as total FROM smac_market.campaign_people_summary WHERE campaign_id=".$campaign_id." AND geo='{$geo}' LIMIT 1";
}else{
	$qry = "SELECT COUNT(a.id) as total FROM smac_market.campaign_people_summary a
			INNER JOIN smac_market.campaign_people_lang b
			ON a.author_id = b.author_id
			WHERE a.campaign_id=".$campaign_id." AND a.geo='{$geo}' AND b.campaign_id=".$campaign_id." LIMIT 1";
}
$q = mysql_query($qry,$conn);

if(mysql_num_rows($q) > 0){
	while($r=mysql_fetch_assoc($q)){
		$data = array("total" => intval($r['total']));
	}
}else{
	$data = array("total" => 0);
}
mysql_free_result($q);
close_db($conn);

echo json_encode($data);
exit;
?>