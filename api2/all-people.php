<?php
include_once "config.php";
include_once "common.php";

$conn = open_db(0);

$campaign_id = intval($_GET['campaign_id']);
$start = intval($_GET['start']);
$total = intval($_GET['total']);
$lang = mysql_escape_string($_REQUEST['lang']);
$geo = mysql_escape_string($_REQUEST['geo']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

if($total <= 0){
	$total = 100;
}


if(is_new_feeds($campaign_id,$conn)){
	$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
}else{
	$FEEDS = "smac_report.campaign_feeds}";
}



//total impressions
if($lang=='all'){
	$sql = "SELECT true_reach FROM smac_market.dashboard_summary WHERE campaign_id=".$campaign_id." AND geo='{$geo}' LIMIT 1";
}else{
	$sql = "SELECT true_reach FROM smac_market.dashboard_summary_lang WHERE campaign_id=".$campaign_id." AND geo='{$geo}' LIMIT 1";
}
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
	$where = " WHERE campaign_id=".$campaign_id." AND geo='{$geo}'";
}else{
	$where .= " AND campaign_id=".$campaign_id." AND geo='{$geo}'";
}

$qry = "SELECT 
				p.*,
				0 AS share
			FROM 
				smac_market.campaign_people_summary p
			$where
			$order
			LIMIT 
			".$start.",".$total;
$q = mysql_query($qry,$conn);
/*
echo $qry.'<hr />';
echo mysql_error();
exit;
*/
if(mysql_num_rows($q) > 0){
	$data = array();
	while($r=mysql_fetch_assoc($q)){
		if($r['impression']==null){
			$r['impression']=0;
		}
		if($r['rt_impression']==null){
			$r['rt_impression']=0;
		}
		
		$share = round(($r['impression']+$r['rt_impression'])/$total_impression*100,2);
		$author_id = $r['author_id'];
		
		$sql1 = "SELECT a.author_id,SUM(pii) as pii_total FROM {$FEEDS} a
				INNER JOIN smac_report.campaign_feed_sentiment b
					ON a.id = b.feed_id AND b.campaign_id = ".$campaign_id."
				INNER JOIN smac_report.country_twitter c
				ON a.feed_id = c.feed_id
				WHERE a.campaign_id=".$campaign_id." AND a.author_id='".mysql_escape_string($r['author_id'])."' 
				AND c.country_code='{$geo}'
				GROUP BY a.author_id LIMIT 1";
		$q2 = mysql_query($sql1,$conn);
		$pii = mysql_fetch_assoc($q2);
		
		mysql_free_result($q2);
		$data[] = array("id" => intval($r['id']),
								  "author_id" => $r['author_id'],
								  "author_avatar" => $r['author_avatar'],
								  "total_mentions" => intval($r['total_mentions']),
								  "impression" => $r['impression'],
								  "total_imp" => $r['impression']+$r['rt_impression'],
								  "rt_impression" => $r['rt_impression'],
								  "rt_mention" => intval($r['rt_mention']),
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