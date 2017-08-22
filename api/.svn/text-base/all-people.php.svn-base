<?php
include_once "config.php";
include_once "common.php";

$conn = open_db(0);

$campaign_id = intval($_GET['campaign_id']);
$start = intval($_GET['start']);
$total = intval($_GET['total']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

if($total <= 0){
	$total = 100;
}

//total impressions
$sql = "SELECT SUM(total_impression_twitter) AS true_reach
FROM smac_report.campaign_rule_volume_history 
WHERE campaign_id={$campaign_id};";
$q = mysql_query($sql,$conn);
$campaign=mysql_fetch_assoc($q);
mysql_free_result($q);
$total_impression = $campaign['true_reach'];
$campaign=null;

//--->

$order = mysql_escape_string($_GET['order']);
if($order == ''){
	$order = "";
}

$where = $_GET['where'];
if($where == ''){
	$where = " WHERE campaign_id=".$campaign_id."  ";
}else{
	$where .= " AND campaign_id=".$campaign_id."  ";
}

$qry = "SELECT 
				p.*,
				0 AS share
			FROM 
				smac_report.campaign_people_summary p
			$where
			$order
			LIMIT 
			".$start.",".$total;

$qry = "select *,0 as share 
from smac_report.twitter_top_authors
{$where}
{$order} 
LIMIT ".$start.",".$total;

$q = mysql_query($qry,$conn);
/*
echo $qry.'<hr />';
echo mysql_error();
exit;
*/
if(mysql_num_rows($q) > 0){
	$data = array();
	while($r=mysql_fetch_assoc($q)){
		if($r['total_impression']==null){
			$r['total_impression']=0;
		}
		if($r['total_rt_impression']==null){
			$r['total_rt_impression']=0;
		}
		
		$share = round(($r['total_impression'])/$total_impression*100,2);
		$author_id = $r['author_id'];
		
	
		
		$sql1 = "SELECT a.author_id,SUM(pii) AS pii_total 
				FROM smac_feeds.campaign_feeds_{$campaign_id} a
				INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
					ON a.id = b.feed_id 
				WHERE a.campaign_id={$campaign_id} AND a.author_id='".mysql_escape_string($r['author_id'])."' 
				GROUP BY a.author_id LIMIT 1;";
		
		$q2 = mysql_query($sql1,$conn);
		$pii = mysql_fetch_assoc($q2);
		
		mysql_free_result($q2);
		$data[] = array("id" => intval($r['id']),
								  "author_id" => $r['author_id'],
								  "author_avatar" => $r['author_avatar'],
								  "total_mentions" => intval($r['total_mentions']),
								  "impression" => $r['total_impression'],
								  "total_imp" => $r['total_impression'],
								  "rt_impression" => $r['total_rt_impression'],
								  "rt_mention" => intval($r['total_rt_mentions']),
								  "share" => $share,
								  "pii_total"=>$pii['pii_total'],
								  "pii" => round($pii['pii_total']/$r['total_mentions'],2),
								  "author_name" => $r['author_name'],
								  "overall_imp"=>$total_impression
						);
	
	}
}else{
	$data = array();
}
mysql_free_result($q);
close_db($conn);

echo json_encode($data);
exit;
?>