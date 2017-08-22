<?php
/**
 * Livetrack Live-Wordcloud
 */
include_once "config.php";
include_once "common.php";
$last_id = intval($_REQUEST['last_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
$reset = intval(mysql_escape_string($_REQUEST['reset']));

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

if($reset==1){
	$sql = "DELETE FROM smac_report.tmp_livetrack_words WHERE campaign_id=".$campaign_id."";
	$q = mysql_query($sql,$conn);
}

$sql = "SELECT keyword,SUM(occurance) as amount 
FROM smac_report.tmp_livetrack_words WHERE campaign_id=".$campaign_id." GROUP BY keyword
ORDER BY amount DESC LIMIT ".$limit."";

$q = mysql_query($sql,$conn);
$num = mysql_num_rows($q);

//echo 'sql: '.$sql.'<br />';
//echo $num;
//exit;
$words = array();
if($num>0){
	while($r=mysql_fetch_assoc($q)){
		//$words[ $r['keyword'] ] = $r['amount'];
		$sql = "SELECT b.keyword_txt as keyword FROM smac_web.tbl_campaign_keyword a 
				INNER JOIN smac_web.tbl_keyword_power b
				ON a.keyword_id = b.keyword_id
				WHERE campaign_id=".$campaign_id." 
				AND b.keyword_txt='".mysql_escape_string($r['keyword'])."' 
				LIMIT 1";
		$q2 = mysql_query($sql,$conn);
		$r2=mysql_fetch_assoc($q2);
		if(strcmp($r2['keyword'],$r['keyword'])==0){
			$r['is_main'] = 1;
		}else{
			$r['is_main'] = 0;
		}
		$words[] = $r;
	}
}

mysql_free_result($q);
close_db($conn);

header("Content-type: application/json");
print json_encode($words);
?>
