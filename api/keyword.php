<?php
include_once "config.php";
include_once "common.php";

$conn = open_db(0);

$campaign_id = intval($_GET['campaign_id']);
$start = intval($_GET['start']);
$total = intval($_GET['total']);

if($total <= 0){
	$total = 100;
}

$order = mysql_escape_string($_GET['order']);
if($order == ''){
	$order = "ORDER BY occurance DESC ";
}

$where = $_GET['where'] . " AND a.campaign_id=".$campaign_id."  ";
if($where == ''){
	$where = " WHERE a.campaign_id=".$campaign_id."  ";
}

//$qry = "SELECT * FROM smac.tbl_campaign_sentiment WHERE campaign_id='$campaign_id'";
/*$qry = "SELECT * FROM smac_report.campaign_sentiment WHERE campaign_id=".$campaign_id." AND keyword 
		IN (SELECT keyword FROM smac.tbl_campaign_sentiment WHERE campaign_id=".$campaign_id.") 
		ORDER BY occurance DESC LIMIT 100";

$qry = "SELECT * FROM smac.tbl_campaign_sentiment 
WHERE campaign_id=".$campaign_id." AND keyword IN 
(SELECT keyword FROM smac_report.campaign_sentiment WHERE campaign_id=".$campaign_id." AND keyword 
IN (SELECT keyword FROM smac.tbl_campaign_sentiment WHERE campaign_id=".$campaign_id.") ORDER BY occurance DESC);";*/

/*
$qry = "SELECT * FROM ".$SCHEMA_WEB.".tbl_campaign_sentiment a1 
INNER JOIN (SELECT campaign_id,keyword,SUM(occurance) as occurance,SUM(score) as score FROM (
SELECT 0 as id,campaign_id,keyword,occurance,0 as score FROM smac_report.campaign_sentiment WHERE campaign_id=".$campaign_id."
UNION ALL
SELECT id,campaign_id,keyword,0,score FROM ".$SCHEMA_WEB.".tbl_campaign_sentiment WHERE campaign_id=".$campaign_id.") a
GROUP BY keyword) aa
ON a1.keyword = aa.keyword
WHERE a1.campaign_id=".$campaign_id."
ORDER BY occurance DESC;";
*/

$qry = "SELECT 
			a.campaign_id,
			keyword_id as id,
			keyword,
			weight as score,
			occurance 
	FROM 
			smac_report.campaign_words a
			INNER JOIN smac_report.campaign_sentiment_setup b
			ON a.id = b.keyword_id
	$where
	$order
	LIMIT 
			".$start.",".$total;
$q = mysql_query($qry,$conn);

//echo $qry;
//echo mysql_error();
//exit;

header("Content-type: text/xml");
if(mysql_num_rows($q) > 0){
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?><rows>";
	while($r=mysql_fetch_assoc($q)){
		if($r['score'] < 0){
			$r['category'] = "Unfavourable";
		}else if($r['score']>0){
			$r['category'] = "Favourable";
		}else{
			$r['category'] = "Neutral";
		}
		echo '<keyword id="'.$r['id'].'" word="'.$r['keyword'].'" category="'.$r['category'].'" value="'.$r['score'].'" occurance="'.$r['occurance'].'"/>';
	}
	echo '</rows>';
}else{
	echo '<?xml version="1.0" encoding="utf-8"?><rows></rows>';
}
mysql_free_result($q);
close_db($conn);

//echo $xml;
exit;