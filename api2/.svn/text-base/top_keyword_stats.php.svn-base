<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);
$from_date = ($_REQUEST['from']);
$to_date = ($_REQUEST['to']);
$top = intval(mysql_escape_string($_REQUEST['top'])); //top 10 isi 10, top 50 isi 50
$geo = mysql_escape_string($_REQUEST['geo']);
if($top>50){$top=50;}
$conn = open_db(0);


$sql = "SELECT * FROM (SELECT keyword,SUM(mention) as mention,SUM(rt_mention) as rt_mention,SUM(impression) as imp,
		SUM(rt_impression) as rt_imp 
		FROM smac_market.campaign_top50_daily 
		WHERE campaign_id=".$campaign_id." 
		AND geo='{$geo}'
		AND published_date >='".$from_date."' 
		AND published_date <='".$to_date."' 
		GROUP BY keyword) a 
		ORDER BY mention DESC LIMIT ".$top;

$keywords = fetch_many($sql,$conn);
foreach($keywords as $n=>$v){
	$keywords[$n]['total_impression'] = $keywords[$n]['imp'] + $keywords[$n]['rt_imp'];
}

mysql_close($conn);
print json_encode($keywords);
$keywords = null;
exit;
?>