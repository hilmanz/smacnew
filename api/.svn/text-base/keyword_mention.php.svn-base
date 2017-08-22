<?php
/**
 * a service to retrieve tweets which mentioned the queried keyword
 */
include_once "config.php";
include_once "common.php";
$start = intval($_REQUEST['start']);
$keyword = clean($_REQUEST['keyword']);
$lang = clean($_REQUEST['lang']);
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
if($limit==0){
	$limit = 100;
}

if($campaign_id>0&&strlen($keyword)>0){
	
	$conn = open_db(0);
	//0. device lists
	//get devices
	$sql = "SELECT * FROM ".$SCHEMA.".device_lookup";
	$devices = fetch_many($sql,$conn);
	

	$use_replicate_table = 0;

	//Always do this
	$sql = "SELECT 1 as res FROM smac_report.campaign_feeds_split_flag fg WHERE fg.n_status = 1 AND fg.campaign_id = ".$campaign_id;
	$rs = mysql_query($sql,$conn);
	if ($row = mysql_fetch_assoc($rs)) {
		//$flag = $row['res'];	
		$use_replicate_table = 1;
	} else {
		//print "NOK use old table ".chr(13).chr(10);
	}
	mysql_free_result($rs);



	//1. hitung total impression dari topic ini.
	
	$sql = 
		"SELECT SUM(followers) as total
		 FROM smac_report.campaign_feeds
		 WHERE campaign_id=".$campaign_id."
		 LIMIT 1";

	if ($use_replicate_table) {
		$sql = 
			"SELECT SUM(followers) as total
			 FROM smac_report.campaign_feeds_".$campaign_id."
			 WHERE campaign_id=".$campaign_id."
			 LIMIT 1";		
	}


	$rows = fetch($sql,$conn);
	
	//2. ambil daftar orang, sekaligus hitung sharenya
	//a. ambil daftar feeds dulu.
	/*$sql = " SELECT COUNT(*) as total FROM smac_report.campaign_feeds a
		WHERE a.campaign_id={$campaign_id}
		AND NOT EXISTS(
			SELECT 1 FROM smac_report.workflow_marked_tweets b WHERE b.campaign_id={$campaign_id}
			AND a.feed_id = b.feed_id)
		AND EXISTS(
			SELECT 1 FROM smac_report.feed_wordlist c
			WHERE a.id = c.fid AND c.keyword='{$keyword}'
		)  LIMIT 1";
	
	$row = fetch($sql,$conn);
	$total_unmarked_tweets = $row['total'];*/
	
	$total_unmarked_tweets = 0;
	
	//check which feed_wordlist we gonna use ?
	
	if(is_new_schema($campaign_id,$conn)==1){
		$FEED_WORDLIST = "smac_word.feed_wordlist_{$campaign_id}";
	}else{
		$FEED_WORDLIST = "smac_report.feed_wordlist";
	}
	if($use_replicate_table){
		$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
	}else{
		$FEEDS = "smac_report.campaign_feeds}";
	}
	
	$sql = "SELECT a.id FROM {$FEEDS} a
		WHERE a.campaign_id={$campaign_id} 
		AND NOT EXISTS(
			SELECT 1 FROM smac_report.workflow_marked_tweets b WHERE b.campaign_id={$campaign_id}
			AND a.feed_id = b.feed_id)
		AND EXISTS(
			SELECT 1 FROM {$FEED_WORDLIST} c
			WHERE a.id = c.fid AND c.keyword='{$keyword}'
		)
		LIMIT ".$start.",".$limit;
	

	if($use_replicate_table){
		///Try to tune 
		/*
		$sql = 
			"SELECT c.fid as id
			FROM {$FEED_WORDLIST} c	
			WHERE c.campaign_id = {$campaign_id} AND c.keyword='{$keyword}'
				AND EXISTS (
					SELECT 1 FROM {$FEEDS} a 
					WHERE a.ref_campaign_feeds_id = c.fid
						AND a.is_active = 1
				)
				AND NOT EXISTS(
					SELECT 1 FROM smac_report.workflow_marked_tweets b 
					WHERE b.campaign_id={$campaign_id}
							AND c.feed_id = b.feed_id
				)
			LIMIT ".$start.",".$limit;
		*/
		$sql = 
			"SELECT c.fid as id
			FROM {$FEED_WORDLIST} c	
			WHERE c.campaign_id = {$campaign_id} AND c.keyword='{$keyword}'
				AND EXISTS (
					SELECT 1 FROM {$FEEDS} a 
					WHERE a.id = c.fid
						AND a.is_active = 1
				)
				AND NOT EXISTS(
					SELECT 1 FROM smac_report.workflow_marked_tweets b 
					WHERE b.campaign_id={$campaign_id}
							AND c.feed_id = b.feed_id
				)
			LIMIT ".$start.",".$limit;			

	}


	$feedlist = fetch_many($sql,$conn);
	$strIDs="";
	$n=0;
	foreach($feedlist as $f){

		if(strlen($f['id'])>0){
			if($n!=0){
				$strIDs.=",";
			}
			$strIDs.="'{$f['id']}'";
			$n++;
		}

	}


	if($n>0){
	//b. retrieve detail people
		$sql = 
			"SELECT a.feed_id,a.author_id,a.author_name,a.published_date,a.generator,a.content,a.followers as imp,
				author_avatar as avatar_pic,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
			FROM {$FEEDS} a
				LEFT JOIN smac_data.tbl_rt_content b
				ON a.feed_id = b.feed_id
			WHERE a.id IN ({$strIDs})
			GROUP BY a.feed_id";
		
		if($use_replicate_table){
			/*
			$sql = 
				"SELECT a.feed_id,a.author_id,a.author_name,a.published_date,a.generator,a.content,a.followers as imp,
					author_avatar as avatar_pic,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
				FROM {$FEEDS} a
					LEFT JOIN smac_data.tbl_rt_content b
					ON a.feed_id = b.feed_id
				WHERE a.ref_campaign_feeds_id IN ({$strIDs})
				GROUP BY a.feed_id";
			*/
			$sql = 
				"SELECT a.feed_id,a.author_id,a.author_name,a.published_date,a.generator,a.content,a.followers as imp,
					author_avatar as avatar_pic,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
				FROM {$FEEDS} a
					LEFT JOIN smac_data.tbl_rt_content b
					ON a.feed_id = b.feed_id
				WHERE a.id IN ({$strIDs})
				GROUP BY a.feed_id";
		}	
		

		/*$sql = "SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, published_date,followers as imp,generator,content
		FROM (SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
		FROM smac_report.campaign_feeds a
		LEFT JOIN smac_data.tbl_rt_content b
		ON a.feed_id = b.feed_id
		WHERE a.campaign_id=".$campaign_id." AND a.wordlist LIKE '%".$keyword."%'
		AND a.feed_id NOT IN (SELECT feed_id FROM smac_report.workflow_marked_tweets WHERE campaign_id=".$campaign_id.")
		GROUP BY a.feed_id
		ORDER BY a.followers DESC  
		LIMIT ".$start.",".$limit."
		) a";
		*/
		
		$tweets = fetch_many($sql,$conn);
		foreach($tweets as $n=>$v){
			$tweets[$n]['share'] = round($v['imp'] / $rows['total']*100,5);
			$tweets[$n]['device'] = get_device($v['generator'],$devices);
			
		}
	}
	close_db($conn);

	$arr = array("status"=>1,"data"=>$tweets,"total"=>sizeof($tweets),"total_unmarked"=>intval($total_unmarked_tweets));
	$tweets = null;
	
}else{
	$arr = array("status"=>0);
	
}
header("Content-type: application/json");
print json_encode($arr);
?>