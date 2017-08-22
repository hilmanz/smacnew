<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);
$lang = mysql_escape_string($_REQUEST['lang']);
$type = intval($_REQUEST['type']);
if($lang==null){
	$lang="id";
}
$conn = open_db(0);

if($type==1){
	$sql = "SELECT a.wordlist
	FROM smac_report.campaign_feeds a 
	WHERE a.author_id =  '".$person."'
	UNION ALL
	SELECT b.wordlist FROM smac_report.campaign_replay_bulk b
	WHERE b.author_id='".$person."'
	LIMIT 1000";
}else{
	$sql = "SELECT a.wordlist
	FROM smac_report.campaign_feeds a
	WHERE  a.campaign_id = ".$campaign_id." 
	AND a.author_id =  '".$person."' LIMIT 1000";
}
$q = mysql_query($sql,$conn);
$words = array();
$_w = array();
$awords = array();
while($ff = mysql_fetch_assoc($q)){
	$words[] = $ff;
}
foreach($words as $word){
	$bulk = explode(",",strtolower($word['wordlist']));
	foreach($bulk as $b){
		if($_w[$b]==null){
			$_w[$b] = 0;
		}
		$_w[$b]++;
	}
}
arsort($_w);

$awords = array();


$n=0;
$arr_topwords = "";
$tt=0;
foreach($_w as $name=>$val){	
	$sql = "SELECT kata FROM smac_data.tb_stop WHERE kata IN ('".mysql_escape_string(trim($name))."') LIMIT 1;";
	$q = mysql_query($sql,$conn);
	$r = mysql_fetch_assoc($q);
	if($r['kata']==null&&strlen($name)>2){
		$s_keyword = trim(strtolower($name));
		if($tt==1){
			$arr_topwords.=",";
		}
		$arr_topwords.="'".mysql_escape_string($s_keyword)."'";
		
		//saat ini. keyword utama gak dicari dulu.. karena skrg kita sudah menggunakan RULE.
		/*
		$sql2 = "SELECT b.keyword_txt as keyword FROM smac_web.tbl_campaign_keyword a 
				INNER JOIN smac_web.tbl_keyword_power b
				ON a.keyword_id = b.keyword_id
				WHERE campaign_id=".$campaign_id." 
				AND b.keyword_txt='".mysql_escape_string($s_keyword)."' 
				LIMIT 1";
		$r2 = fetch($sql2,$conn);
		$is_main=0;
		if(strcmp($r2['keyword'],$s_keyword)==0){
			$is_main = 1;
		}
		*/
		$_w = array("keyword"=>$s_keyword,"total"=>$val,"sentiment"=>"0","is_main"=>$is_main);
		$awords[] = $_w;	
		$tt=1;
	}
	$n++;
	if($n>100){
		break;
	}
}
//calculate sentiment
$sql = "SELECT a.keyword,a.occurance as amount,a.id as keyword_id,b.weight as sentiment 
	FROM smac_report.campaign_words a 
	INNER JOIN smac_report.campaign_sentiment_setup b
	ON a.id = b.keyword_id
	WHERE a.campaign_id=".$campaign_id." AND a.keyword IN (".$arr_topwords.") LIMIT ".$n;


$sentiment = fetch_many($sql,$conn);

foreach($awords as $n=>$v){
	$awords[$n]['sentiment'] = 0;
	foreach($sentiment as $s=>$sv){
		
		if(strcmp($awords[$n]['keyword'],$sv['keyword'])==0){
			$awords[$n]['sentiment'] = $sv['sentiment'];
			break;
		}
	}
}
mysql_close($conn);
print json_encode($awords);
exit;