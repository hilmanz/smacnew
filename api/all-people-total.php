<?php
include_once "config.php";
include_once "common.php";

$conn = open_db(0);

$campaign_id = intval($_GET['campaign_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}


$qry = "SELECT COUNT(*) as total FROM smac_report.twitter_top_authors WHERE campaign_id=".$campaign_id." LIMIT 1";

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