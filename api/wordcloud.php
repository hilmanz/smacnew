<?php
/**
 * Dashboard Wordcloud - Data Global
 */
include_once "config.php";
include_once "common.php";
$last_id = intval($_REQUEST['last_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
$geo = mysql_escape_string($_REQUEST['geo']);
$from = mysql_escape_string($_REQUEST['from']);
$to = mysql_escape_string($_REQUEST['to']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}
//temporary solution, until gnip feed re-activated
if($last_id==0){
	//$last_id = 1;
}
//-->

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$entry = $arr['feed']['_c']['entry'];
$limit = intval($_REQUEST['limit']);
if($limit>1000){
	$limit = 1000;
}
$conn = open_db(0);

//if($from!=null&&$to!=null){
//	$additional="AND dtpost BETWEEN '{$from}' AND '{$to}'";
//}

/*
$sql = "SELECT a.keyword,SUM(a.occurance) AS amount FROM smac_report.daily_rule_wordcloud_summary a
WHERE a.campaign_id={$campaign_id}
AND NOT EXISTS (
SELECT 1 FROM smac_web.workflow_keyword_flag c 
WHERE c.campaign_id={$campaign_id} AND c.keyword = a.keyword
)
{$additional}
GROUP BY a.keyword
ORDER BY amount DESC LIMIT {$limit};";*/

/*
 * 
 */
if($from!=null&&$to!=null){
	$sql = "SELECT keyword, sum(occurance) as amount
			FROM smac_word.campaign_rule_keyword_history_{$campaign_id} a
			WHERE dtpost BETWEEN '{$from}' and '{$to}' 
				AND NOT EXISTS (
				SELECT 1 FROM smac_web.workflow_keyword_flag c 
				WHERE c.campaign_id={$campaign_id} AND c.keyword = a.keyword
			)
			GROUP BY keyword
			ORDER BY amount DESC
			LIMIT {$limit};
	";
}else{
	$sql = "SELECT keyword, sum(a.occurance) as amount
			FROM smac_report.top_rule_wordcloud_summary a
			WHERE campaign_id = {$campaign_id} 
			AND NOT EXISTS (
				SELECT 1 FROM smac_web.workflow_keyword_flag c 
				WHERE c.campaign_id= {$campaign_id}  AND c.keyword = a.keyword
			)
			GROUP BY a.keyword
			ORDER BY amount DESC
			LIMIT {$limit};
	";
}

mysql_set_charset("utf8",$conn);
$q = mysql_query($sql,$conn);
$num = mysql_num_rows($q);


$words = array();
if($num>0){
	$keywords = "";
	$nn=0;
	while($r=mysql_fetch_assoc($q)){
		$kw = mysql_escape_string($r['keyword']);
		
		$sql = "SELECT b.keyword_txt as keyword FROM smac_web.tbl_campaign_keyword a 
				INNER JOIN smac_web.tbl_keyword_power b
				ON a.keyword_id = b.keyword_id
				WHERE campaign_id=".$campaign_id." 
				AND b.keyword_txt='".$kw."' 
				LIMIT 1";

		mysql_set_charset("utf8",$conn);
		$q2 = mysql_query($sql,$conn);
		$r2=mysql_fetch_assoc($q2);
		if(strcmp($r2['keyword'],$r['keyword'])==0){
			$r['is_main'] = 1;
		}else{
			$r['is_main'] = 0;
		}
		$r['sentiment'] = 0;
		//$r['keyword'] = iconv('UTF-8', 'UTF-8//IGNORE', preg_replace('/[\xFFFD|\x3F]/','', $r['keyword']));
		$words[] = $r;
		
		if(strlen($kw)>0){
			
			if($nn>0){
				$keywords.=",";
			}
			$keywords.="'{$kw}'";
			$nn++;
		}
	}
}

if(strlen($keywords)>0){
	$sql = "SELECT keyword,weight as sentiment FROM smac_report.campaign_words a
			INNER JOIN smac_report.campaign_sentiment_setup b
			ON a.id = b.keyword_id
			WHERE a.campaign_id={$campaign_id} AND b.campaign_id={$campaign_id}
			AND keyword IN ({$keywords}) LIMIT {$nn}";
	
	mysql_set_charset("utf8",$conn);
	$sentiments = fetch_many($sql,$conn);
	foreach($sentiments as $s){
		foreach($words as $n=>$word){
			if(strcmp($word['keyword'],$s['keyword'])==0){
				$words[$n]['sentiment'] = $s['sentiment'];
				break;
			}
		}
	}
}
mysql_free_result($q);
close_db($conn);

header("Content-type: application/json; charset='UTF-8'");
print json_encode($words);
?>