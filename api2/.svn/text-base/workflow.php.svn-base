<?php
/**
 * a service to retrieve marked data in workflow
 */
include_once "config.php";
include_once "common.php";
$start = intval($_REQUEST['start']);

$folder_id = intval($_REQUEST['type']);
//-->

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$limit = intval($_REQUEST['limit']);
$filter_by = clean($_REQUEST['filter']);

if(strlen($filter_by)>0){
	$add = "AND c.keyword='".$filter_by."'";
	$add1 = "AND a.keyword='".$filter_by."'";
}
if($limit>1000){
	$limit = 1000;
}
if($limit==0){
	$limit = 100;
}
if($campaign_id>0){
	
	$conn = open_db(0);


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


	//0. device lists
	//get devices
	$sql = "SELECT * FROM ".$SCHEMA.".device_lookup";
	$devices = fetch_many($sql,$conn);
	//1. hitung total impression dari topic ini.
	
	/**
	 * THIS CALL IS COST A LOT
	 *
	 */
	$sql = 
		"SELECT SUM(followers) as total
		FROM smac_report.campaign_feeds
		WHERE campaign_id=".$campaign_id." 
		LIMIT 1";	
	if ($use_replicate_table) {
		$sql = 
			"SELECT SUM(followers) as total
			FROM smac_feeds.campaign_feeds_".$campaign_id."
			WHERE campaign_id=".$campaign_id." 
			LIMIT 1";	
	} 
	$cmp = fetch($sql,$conn);
	
	//total tweets
	
	if($folder_id==2){
		$sql = 
			"SELECT COUNT(*) as total
			FROM (
				SELECT a.feed_id,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp,
					c.keyword,date_format(d.reply_date,'%d/%m/%Y %H:%i:%s') as reply_date
				FROM smac_report.campaign_feeds a
				LEFT JOIN smac_data.tbl_rt_content b
				ON a.feed_id = b.feed_id
				INNER JOIN smac_report.workflow_marked_tweets c
				ON a.feed_id = c.feed_id
				LEFT JOIN smac_report.workflow_replied d
				ON a.feed_id = d.feed_id
				WHERE a.campaign_id=".$campaign_id." AND c.campaign_id=".$campaign_id." AND c.folder_id=".$folder_id."
				AND author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people WHERE campaign_id=".$campaign_id.") ".$add."
				GROUP BY a.feed_id
			) a LIMIT 1";
		if ($use_replicate_table) {
			$sql = 
				"SELECT COUNT(*) as total
				 FROM (
					SELECT a.feed_id,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp,
						c.keyword,date_format(d.reply_date,'%d/%m/%Y %H:%i:%s') as reply_date
					FROM smac_feeds.campaign_feeds_".$campaign_id." a
						LEFT JOIN smac_data.tbl_rt_content b
							ON a.feed_id = b.feed_id
						INNER JOIN smac_report.workflow_marked_tweets c
							ON a.feed_id = c.feed_id
						LEFT JOIN smac_report.workflow_replied d
							ON a.feed_id = d.feed_id
					WHERE a.campaign_id=".$campaign_id." AND c.campaign_id=".$campaign_id." AND c.folder_id=".$folder_id."
						AND author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people WHERE campaign_id=".$campaign_id.") ".$add."
					GROUP BY a.feed_id
				) a LIMIT 1";
		}

	}else{
		/*
		$sql = "SELECT COUNT(a.id) as total FROM smac_report.workflow_marked_tweets a
			INNER JOIN smac_report.campaign_feeds b
			ON a.feed_id = b.feed_id
			WHERE a.campaign_id=".$campaign_id." 
			AND a.folder_id=".$folder_id." 
			AND b.author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people 
			WHERE campaign_id=".$campaign_id.") ".$add1." 
			LIMIT 1";
		*/
		$sql = /*"SELECT COUNT(a.id) as total FROM smac_report.workflow_marked_tweets a
					INNER JOIN smac_report.campaign_feeds b
					ON b.campaign_id=".$campaign_id."  AND b.feed_id = a.feed_id
				WHERE a.campaign_id=".$campaign_id." 
					AND a.folder_id=".$folder_id." 
					AND b.author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people WHERE campaign_id=".$campaign_id.") ".$add1." 
				LIMIT 1";*/
				"SELECT COUNT(a.id) as total 
				 FROM smac_report.workflow_marked_tweets a
				 WHERE a.campaign_id=".$campaign_id." 
						AND a.folder_id=".$folder_id." 
						AND EXISTS (
							SELECT 1 FROM smac_report.campaign_feeds b
							WHERE b.feed_id = a.feed_id 
								AND b.campaign_id=".$campaign_id."  
								AND b.author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people w WHERE w.campaign_id=".$campaign_id." ) 
						)";


		if ($use_replicate_table) {
			$sql = 	"SELECT COUNT(a.id) as total 
					 FROM smac_report.workflow_marked_tweets a
					 WHERE a.campaign_id=".$campaign_id." 
							AND a.folder_id=".$folder_id." 
							AND EXISTS (
								SELECT 1 FROM smac_feeds.campaign_feeds_".$campaign_id." b
								WHERE b.feed_id = a.feed_id 
									AND b.campaign_id=".$campaign_id."  
									AND b.author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people w WHERE w.campaign_id=".$campaign_id." ) 
							)";			
		}

		//print $sql."<br/>";
	}

	$rows = fetch($sql,$conn);
	if($folder_id==4){
		$sql = "SELECT COUNT(a.id) as total 
				FROM smac_report.workflow_marked_tweets a
					INNER JOIN smac_report.campaign_feeds_exc b
				ON a.feed_id = b.feed_id
				WHERE a.campaign_id=".$campaign_id." 
					AND a.folder_id=".$folder_id." 
					AND b.author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people 
				WHERE campaign_id=".$campaign_id.") ".$add1." 
				LIMIT 1";

		$rows2 = fetch($sql,$conn);
		$rows['total'] += $rows2['total'];
		$rows2 = null;
	}
	
	if($folder_id!=4){
		$sql = 
			"SELECT keyword,feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, published_date,followers as imp,generator,content,reply_date
			FROM (
				SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp,c.keyword,date_format(d.reply_date,'%d/%m/%Y %H:%i:%s') as reply_date
				FROM smac_report.campaign_feeds a
				LEFT JOIN smac_data.tbl_rt_content b
				ON a.feed_id = b.feed_id
				INNER JOIN smac_report.workflow_marked_tweets c
				ON a.feed_id = c.feed_id
				LEFT JOIN smac_report.workflow_replied d
				ON a.feed_id = d.feed_id
				WHERE a.campaign_id=".$campaign_id." AND c.campaign_id=".$campaign_id." AND c.folder_id=".$folder_id."
				AND author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people WHERE campaign_id=".$campaign_id.") ".$add."
				GROUP BY a.feed_id
				ORDER BY keyword ASC
				LIMIT ".$start.",".$limit."
			) a";


		if ($use_replicate_table) {
			$sql = 
				"SELECT keyword,feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, published_date,followers as imp,generator,content,reply_date
				FROM (
					SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp,
							c.keyword,date_format(d.reply_date,'%d/%m/%Y %H:%i:%s') as reply_date
					FROM smac_feeds.campaign_feeds_".$campaign_id." a
						LEFT JOIN smac_data.tbl_rt_content b
						ON a.feed_id = b.feed_id
						INNER JOIN smac_report.workflow_marked_tweets c
						ON a.feed_id = c.feed_id
						LEFT JOIN smac_report.workflow_replied d
						ON a.feed_id = d.feed_id
					WHERE a.campaign_id=".$campaign_id." AND c.campaign_id=".$campaign_id." AND c.folder_id=".$folder_id."
					AND author_id NOT IN (SELECT author_id FROM smac_report.workflow_marked_people WHERE campaign_id=".$campaign_id.") ".$add."
					GROUP BY a.feed_id
					ORDER BY keyword ASC
					LIMIT ".$start.",".$limit."
				) a";

		}

	}else{
		//we're using stored procedure for these.
		if(strlen($filter_by)>0){
			$sql = "call smac_report.wf_excludes_filtered({$campaign_id},{$start},{$limit},'{$filter_by}');";
		}else{
			$sql = "call smac_report.wf_excludes({$campaign_id},{$start},{$limit});";
		}
		
	}
	$tweets = fetch_many($sql,$conn);
	
	foreach($tweets as $n=>$v){
		$tweets[$n]['share'] = round($v['imp'] / $cmp['total']*100,5);
		$tweets[$n]['device'] = get_device($v['generator'],$devices);
	}	
	close_db($conn);
	//if folder_id==4 (exclusion) we retrieve the deleted tweets as well
	
	if($folder_id==4){
		//find unfinished keyword exclusion jobs
		//right now, we assume that no more 100 jobs in progress.
		$conn = open_db(0);
		$sql = "SELECT keyword FROM smac_web.workflow_apply_exclude WHERE campaign_id={$campaign_id} AND n_status < 2 LIMIT 100";
		$jobs = fetch_many($sql,$conn);
		close_db($conn);
	}
	
	$arr = array("status"=>1,"data"=>$tweets,"total"=>$rows['total'],"jobs"=>$jobs);
	$data = null;
	
}else{
	$arr = array("status"=>0);
	
}
header("Content-type: application/json");
print json_encode($arr);
?>