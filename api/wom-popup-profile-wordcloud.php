<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null){
	$lang="id";
}
$conn = open_db(0);

//check which feed_wordlist we gonna use ?
if(is_new_schema($campaign_id,$conn)==1){
	$FEED_WORDLIST = "smac_word.feed_wordlist_{$campaign_id}";
}else{
	$FEED_WORDLIST = "smac_report.feed_wordlist";
}
if(is_new_feeds($campaign_id,$conn)){
	$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
}else{
	$FEEDS = "smac_report.campaign_feeds";
}
$sql = "SELECT keyword,COUNT(keyword) as total 
FROM 
{$FEEDS} a
INNER JOIN {$FEED_WORDLIST} b
ON a.id = b.fid
WHERE a.campaign_id={$campaign_id} AND author_id='{$person}' 
GROUP BY keyword
ORDER BY total DESC
LIMIT 100";


$q = mysql_query($sql,$conn);
$words = array();
$_w = array();
$awords = array();
$words = fetch_many($sql,$conn);

$arr_topwords = "";
$n=0;
foreach($words as $kw){
	$w = mysql_escape_string($kw['keyword']);
	if(strlen($w)>=2){
		if($n>1){
			$arr_topwords .= ",";
		}
		$arr_topwords.="'{$w}'";
		$n++;
	}
}

//calculate sentiment
$sql = "SELECT a.keyword,a.occurance as amount,a.id as keyword_id,b.weight as sentiment 
	FROM smac_report.campaign_words a 
	INNER JOIN smac_report.campaign_sentiment_setup b
	ON a.id = b.keyword_id
	WHERE a.campaign_id=".$campaign_id." AND a.keyword IN (".$arr_topwords.") LIMIT 100";
//print $sql;

$sentiment = fetch_many($sql,$conn);

foreach($words as $n=>$v){
	$words[$n]['sentiment'] = 0;
	foreach($sentiment as $s=>$sv){
		if(strcmp($words[$n]['keyword'],$sv['keyword'])==0){
			$words[$n]['sentiment'] = $sv['sentiment'];
			break;
		}
	}
}
mysql_close($conn);
print json_encode($words);
exit;