<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);

$conn = open_db(0);


$sql = 
	"SELECT tag_id as keyword_id,SUM(total) as total_mention 
	FROM smac_report.campaign_keyword_daily
	WHERE campaign_id=".$campaign_id."
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


if(is_new_feeds($campaign_id,$conn)){
	$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
}else{
	$FEEDS = "smac_report.campaign_feeds";
}



$sql = 
	"SELECT campaign_id,a.tag_id,published_date as tgl,matching_rule as keyword,SUM(pii) as pii 
	FROM {$FEEDS} a
		INNER JOIN smac_report.campaign_feed_sentiment b
			ON a.id = b.feed_id AND b.campaign_id = ".$campaign_id."
	WHERE a.campaign_id=".$campaign_id." AND a.tag_id IN (".$keyword_ids.")
	GROUP BY a.tag_id,a.published_date";

$q = mysql_query($sql,$conn);
$data = array();
while($f=mysql_fetch_assoc($q)){
	$data[] = $f;
}
$f = null;
mysql_free_result($q);
mysql_close($conn);

$keywords = array();

foreach($data as $d){
	$keywords[str_replace(" ","_",$d['keyword'])] = array();
}

foreach($data as $d){
	foreach($keywords as $k=>$n){
		if($keywords[$k][$d['tgl']]==NULL){
			$keywords[$k][$d['tgl']] = 0;
		}
		
	}
	$keywords[str_replace(" ","_",$d['keyword'])][$d['tgl']] = intval($d['pii']);
}
$data = null;
foreach($keywords as $k=>$n){
		//krsort($keywords[$k]);
		ksort($keywords[$k]);
		if(sizeof($keywords[$k])>14){
			$keywords[$k] = array_slice($keywords[$k],sizeof($keywords[$k])-14);
		}
		
}
print json_encode(array($keywords));
$keywords = null;
exit;