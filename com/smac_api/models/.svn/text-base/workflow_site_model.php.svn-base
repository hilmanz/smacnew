<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
include_once "kol_model.php";
class workflow_site_model extends kol_model{
	/*
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
		parent::setRequestHandler($req);
	}*/
	function folders($campaign_id,$type){
		$type = intval($type);
		$sql = "SELECT id AS folder_id,folder_name,campaign_id as topic_id
				FROM smac_workflow.workflow_folder 
				WHERE campaign_id=0 OR campaign_id = {$campaign_id}
				ORDER BY topic_id ASC,id ASC LIMIT 100;";
		$rs = $this->fetch($sql,1);
		$folders = array();
		while(sizeof($rs)>0){
			$d = array_shift($rs);		   
			if($d['folder_id']==4){
				 $d['custom'] = 0;
				 $d['exclude'] = 1;
				 $d['reply'] = 0;
				 $d['auto'] = 0;
			}else if($d['campaign_id']==0&&($d['folder_name']=="Positive"||$d['folder_name']=="Negative")){
				$d['custom'] = 0;
				$d['exclude'] = 0;
				$d['reply'] = 0;
				$d['auto'] = 1;
			}else if($d['folder_id']>4&&$d['topic_id']>0){
				$d['custom'] = 1;
				$d['exclude'] = 0;
				$d['reply'] = 0;
				$d['auto'] = 0;
			}else{
				$d['custom'] = 0;
				$d['exclude'] = 0;
				$d['reply'] = 0;
				$d['auto'] = 0;
			}
			if($d['folder_id']==2){
				$d['reply']=1;
			}
			
			$total = $this->getFolderSize($campaign_id, $d['folder_id'],$type);
			$folders[] = array('folder_id'=> $d['folder_id'],
							   'folder_name'=> $d['folder_name'],
							   'custom'=>$d['custom'],
							   'exclude'=>$d['exclude'],
							   'reply'=>$d['reply'],
							   'auto'=>$d['auto'],
							   'total'=>$total);
		}
		//positive/negative counter
		$sql = "SELECT COUNT(*) as total
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id
					WHERE a.campaign_id={$campaign_id} 
					AND a.group_type_id={$type}
					AND b.sentiment > 0 LIMIT 1";
		$positive = $this->fetch($sql);
		$sql = "SELECT COUNT(*) as total
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id
					WHERE a.campaign_id={$campaign_id} 
					AND a.group_type_id={$type}
					AND b.sentiment < 0 LIMIT 1";
		$negative = $this->fetch($sql);
		if(sizeof($folders)>0){
			foreach($folders as $n=>$v){
				if($v['auto']>0&&strtolower($v['folder_name'])=="positive"){
					$folders[$n]['total'] = intval($positive['total']);
				}else if($v['auto']>0&&strtolower($v['folder_name'])=="negative"){
					$folders[$n]['total'] = intval($negative['total']);
					$folders[$n]['auto']=2;
				}else{}
			}
		}
		
		
		$data = array("folders"=>$folders);
		return $data;
	}
	function getFolderSize($campaign_id,$folder_id,$type){
		$type = intval($type);
		$sql = "SELECT COUNT(a.id) AS total 
					FROM smac_workflow.workflow_marked_gcs a
					WHERE a.campaign_id={$campaign_id}
						AND a.folder_id={$folder_id}
						AND a.group_type_id = $type
						AND EXISTS (
					SELECT 1 FROM smac_report.campaign_web_feeds b
					WHERE b.id = a.campaign_web_feeds_id 
						AND b.campaign_id={$campaign_id}
						AND b.group_type_id = {$type}
						AND b.author_name NOT IN 
						(SELECT author_id FROM smac_workflow.workflow_marked_website w 
						WHERE w.campaign_id={$campaign_id} ) 
					);";
					
		$rs = $this->fetch($sql);
	
		return $rs['total'];
	}
	function keywords($campaign_id,$type,$folder_id){
		$type = intval($type);
		$folder_id = intval($folder_id);
		$sql = "SELECT keyword,COUNT(keyword) AS total 
				FROM 
				smac_workflow.workflow_marked_gcs
				WHERE campaign_id={$campaign_id}
				AND 
				folder_id={$folder_id}
				AND group_type_id = {$type}
				GROUP BY keyword LIMIT 10;";
		$keywords = $this->fetch($sql,1);
		return $keywords;
	}
	function feeds($campaign_id,$type,$folder_id,$keyword,$start,$limit=10){
		$type = intval($type);
		$folder_id = intval($folder_id);
		$start = intval($start);
		$keyword = mysql_escape_string(cleanXSS($keyword));
		$rs = array();
		if($folder_id!=4){
		
			$sql = "SELECT a.id as feed_id, author_name,link,author_uri, comments,title,summary as content,published_ts, source_service,
					generator,rank,lang,geo 
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_workflow.workflow_marked_gcs c ON a.id = c.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id} AND c.campaign_id={$campaign_id} 
					AND c.folder_id={$folder_id} AND c.keyword='{$keyword}' AND c.group_type_id={$type}
					GROUP BY a.feed_id ORDER BY c.keyword ASC LIMIT ".$start.",".$limit;
				
				$rs = $this->fetch($sql,1);
				
				$sql2 = "SELECT COUNT(id) AS total FROM smac_workflow.workflow_marked_gcs wmt 
						 WHERE campaign_id={$campaign_id} 
						 AND folder_id={$folder_id}
						 AND group_type_id = {$type} 
						 AND keyword='{$keyword}';";
						 
				$rows = $this->fetch($sql2);
		}
		return array("feeds"=>$rs,"total"=>$rows['total']);
	}
	function all_feeds($campaign_id,$type,$folder_id,$start,$limit=10){
		$type = intval($type);
		$folder_id = intval($folder_id);
		$start = intval($start);
		$keyword = mysql_escape_string(cleanXSS($keyword));
		$rs = array();
		if($folder_id!=4){
		
			$sql = "SELECT a.id as feed_id, author_name,link,author_uri, comments,title,summary as content,published_ts, source_service,
					generator,rank,lang,geo 
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_workflow.workflow_marked_gcs c ON a.id = c.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id} AND c.campaign_id={$campaign_id} 
					AND c.folder_id={$folder_id}
					AND a.group_type_id = {$type}
					AND c.group_type_id = {$type}
					GROUP BY a.feed_id ORDER BY c.keyword ASC LIMIT ".$start.",".$limit;
				
				$rs = $this->fetch($sql,1);
				
				$sql2 = "SELECT COUNT(id) AS total FROM smac_workflow.workflow_marked_gcs wmt 
						 WHERE campaign_id={$campaign_id} 
						 AND folder_id={$folder_id} 
						 AND group_type_id = {$type}
						 ";
						 
				$rows = $this->fetch($sql2);
		}
		return array("feeds"=>$rs,"total"=>$rows['total']);
	}
	function excluded_feeds($campaign_id,$type,$filter_by,$start,$limit=10){
		$type = intval($type);
		$filter_by = mysql_escape_string(cleanXSS($filter_by));
		$start = intval($start);
		if(strlen($filter_by)>0){
			//$sql = "call smac_report.wf_excludes_filtered({$campaign_id},{$start},{$limit},'{$filter_by}');";
			$sql = "SELECT a.id as feed_id, author_name,link,author_uri, comments,title,summary as content,published_ts, source_service,
					generator,rank,lang,geo,a.is_active
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_workflow.workflow_marked_gcs c ON a.id = c.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id} AND c.campaign_id={$campaign_id} 
					AND c.folder_id=4 AND c.keyword='{$filter_by}'
					AND a.group_type_id = {$type}
					AND c.group_type_id = {$type}
					LIMIT {$start},{$limit}";
					
			$sql2 = "SELECT COUNT(id) AS total 
				FROM smac_workflow.workflow_marked_gcs
				 WHERE campaign_id={$campaign_id} 
				 AND folder_id=4 AND group_type_id={$type}
				 AND keyword='{$filter_by}';";
		}else{
			//$sql = "call smac_report.wf_excludes({$campaign_id},{$start},{$limit});";
			$sql2 = "SELECT COUNT(id) AS total 
				FROM smac_workflow.workflow_marked_gcs
				 WHERE campaign_id={$campaign_id} AND group_type_id = {$type}
				 AND folder_id=4;";
				 
			$sql = "SELECT a.id as feed_id, author_name,link,author_uri, comments,title,summary as content,published_ts, source_service,
					generator,rank,lang,geo,a.is_active
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_workflow.workflow_marked_gcs c ON a.id = c.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id} AND c.campaign_id={$campaign_id} 
					AND a.group_type_id = {$type}
					AND c.group_type_id = {$type}
					AND c.folder_id=4
					LIMIT {$start},{$limit}";
		}
		
		$rows = $this->fetch($sql2);
		$rs = $this->fetch($sql,1);
		for($i=0;$i<sizeof($rs);$i++){
			if($rs[$i]['is_active']==1){
				$rs[$i]['is_deleted'] = 0;
			}else{
				$rs[$i]['is_deleted'] = 1;
			}
		}

		
		return array("feeds"=>$rs,"total"=>$rows['total']);
	}
	function add_folder($campaign_id,$type,$folder_name){
		$type = intval($type);
		$folder_name = mysql_escape_string(cleanXSS($folder_name));
		$campaign_id = intval($campaign_id);
		$sql = "INSERT INTO smac_workflow.workflow_folder(campaign_id,folder_name)
						VALUES(".$campaign_id.",'".$folder_name."')";
		$q = $this->query($sql);
		$id = 0;
		if($q){
			$status = 1;
			$id = mysql_insert_id();
		}else{
			$status = 0;
		}
		return array("folder_id"=>$id,"folder_name"=>$folder_name,"status"=>$status);
	}
	
	/**
	 * unmark tweet from workflow.
	 */
	function remove_feeds($campaign_id,$type,$folder_id,$feed_id){
		$type = intval($type);
		$q = $this->query("DELETE FROM smac_workflow.workflow_marked_gcs
								WHERE campaign_id={$campaign_id} 
								AND folder_id={$folder_id} 
								AND group_type_id={$type}
								AND campaign_web_feeds_id='{$feed_id}'");
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	/**
	 * move tweet to another folder.
	 */
	function move($campaign_id,$type,$from_folder,$to_folder,$feed_id){
		
		$sql = "UPDATE smac_workflow.workflow_marked_gcs
				SET folder_id = {$to_folder} 
				WHERE 
				campaign_id = {$campaign_id} 
				AND campaign_web_feeds_id='{$feed_id}'
				AND folder_id = {$from_folder}
				AND group_type_id={$type}
		";
		$q = $this->query($sql);
		if($q){
			return array('old_folder_id'=>$from_folder,'new_folder_id'=>$to_folder);
		}else{
			return array();
		}
	}
	/**
	 * move all tweets to another folder based on its keyword
	 */
	function move_all($campaign_id,$type,$from_folder,$to_folder,$keyword){
		$type = intval($type);
		$sql = "UPDATE smac_workflow.workflow_marked_gcs
				SET folder_id = {$to_folder} 
				WHERE 
				campaign_id = {$campaign_id} 
				AND keyword='{$keyword}'
				AND group_type_id={$type}
				AND folder_id = {$from_folder}
		";
		$q = $this->query($sql);
		if($q){
			return array('old_folder_id'=>$from_folder,'new_folder_id'=>$to_folder);
		}else{
			return array();
		}
	}
	
	function website_detail($campaign_id,$type,$sitename){
		$type = intval($type);
		$summary = array('sitename'=>$sitename,"wordcloud"=>$this->web_wordcloud($campaign_id,$type,$sitename));
		
		return $summary;
	}
	function web_wordcloud($campaign_id,$type,$sitename){
		$type = intval($type);
		$sql="SELECT keyword,SUM(total) as occurance
			        FROM smac_gcs.gcs_wordlist_{$campaign_id}
			        WHERE EXISTS (
			                        SELECT 1 FROM smac_report.campaign_web_feeds g
			                        WHERE g.id = smac_gcs.gcs_wordlist_{$campaign_id}.campaign_web_feeds_id
			                                AND g.campaign_id = {$campaign_id} and g.author_name='{$sitename}'
			                                AND g.group_type_id = {$type}
			                )
			        GROUP BY keyword
			        ORDER BY occurance DESC LIMIT 100";
		$rs = $this->fetch($sql,1);
		for($i=0;$i<sizeof($rs);$i++){
			$sql = "SELECT weight
					FROM smac_sentiment.sentiment_setup_{$campaign_id}
					WHERE keyword='{$rs[$i]['keyword']}' LIMIT 1";
			$sentiment = $this->fetch($sql);
			$rs[$i]['sentiment'] = intval($sentiment['weight']);
		}
		return $rs;
	}
	
	function apply_exclude($campaign_id,$type,$feed_id){
		$type = intval($type);
		$sql = "INSERT IGNORE INTO `smac_workflow`.`campaign_web_feeds_exclude` 
				(
				`campaign_web_feeds_id`, 
				`campaign_id`, 
				`feed_id`, 
				`link`, 
				`author_name`, 
				`author_uri`, 
				`comments`, 
				`title`, 
				`summary`, 
				`published`, 
				`published_ts`, 
				`source_service`, 
				`generator`, 
				`source_id`, 
				`rank`, 
				`manual`, 
				`lang`, 
				`geo`, 
				`keyword_id`, 
				`is_active`,
				group_type_id
				)
				SELECT 	`id`, 
				`campaign_id`, 
				`feed_id`, 
				`link`, 
				`author_name`, 
				`author_uri`, 
				`comments`, 
				`title`, 
				`summary`, 
				`published`, 
				`published_ts`, 
				`source_service`, 
				`generator`, 
				`source_id`, 
				`rank`, 
				`manual`, 
				`lang`, 
				`geo`, 
				`keyword_id`, 
				`is_active`,
				group_type_id
				 
				FROM 
				`smac_report`.`campaign_web_feeds` 
				WHERE campaign_id={$campaign_id} AND id={$feed_id} AND group_type_id = {$type}
				LIMIT 1
				";
		$q = $this->query($sql);
		$status = false;
		if($q){
			$sql = "UPDATE smac_report.campaign_web_feeds SET is_active=0 
					WHERE campaign_id={$campaign_id} AND id='{$feed_id}' AND group_type_id = {$type}";
			if($this->query($sql)){
				$status=true;
			}
		}
		if($status){
			return 1;
		}else{
			return 0;
		}
		return 0;
	}
	function exclude_all($campaign_id,$type,$keyword){
		$type = intval($type);
		$sql = "INSERT INTO smac_workflow.gcs_workflow_apply_exclude
				(campaign_id,keyword,submit_date,n_status)
				VALUES
				({$campaign_id},'{$keyword}',NOW(),0)
				ON DUPLICATE KEY UPDATE
				n_status=VALUES(n_status)
				";
		
		$q = $this->query($sql);
		if($q){
			$status = 1;
		}else{
			$status = 0;
		}
		return $status;
	}
	function undo_exclude($campaign_id,$type,$feed_id){
		return 0;
	}
	
	function keyword_conversation($campaign_id,$type,$keyword,$start,$total=10){
		$type = intval($type);
		$tweets = array();
		$rows = 0;
		if(strlen($keyword)){
			$sql = "SELECT campaign_id,keyword 
					FROM smac_workflow.gcs_workflow_keyword_flag 
					WHERE campaign_id={$campaign_id} 
					AND keyword='{$keyword}' LIMIT 1";
			
			$cek = $this->fetch($sql);
			if($cek['campaign_id']==null){
				$keyword = cleanXSS(trim($keyword));
				$start = intval($start);
				
				$sql = "SELECT feed_id,
						author_name,link,author_uri,
						comments,title,summary,published_ts,
						source_service,generator,rank,lang,geo
						FROM 
						smac_report.campaign_web_feeds cf 
						INNER JOIN smac_gcs.gcs_wordlist_{$campaign_id} fw ON cf.id= fw.campaign_web_feeds_id
						WHERE
						cf.campaign_id = {$campaign_id} AND fw.keyword = '{$keyword}'
						AND cf.group_type_id={$type}
						AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_gcs mt 
										WHERE mt.campaign_id={$campaign_id}
										AND mt.campaign_web_feeds_id=cf.id AND mt.group_type_id={$type} LIMIT 1)
						LIMIT {$start},{$total};";
						
				$feeds = $this->fetch($sql,1);
				
				/*
				$sql = "SELECT COUNT(id) as total FROM smac_workflow.workflow_marked_gcs 
						WHERE campaign_id={$campaign_id} AND keyword='{$keyword}' AND group_type_id={$type}";
			
				$wcount = $this->fetch($sql); 
				
				$sql = "SELECT COUNT(*) AS total FROM (
								SELECT campaign_web_feeds_id FROM smac_gcs.gcs_wordlist_{$campaign_id} 
								WHERE keyword='{$keyword}' AND group_type_id={$type} 
								GROUP BY campaign_web_feeds_id) a;";
				*/
				$sql = "SELECT COUNT(cf.id) as total
						FROM 
						smac_report.campaign_web_feeds cf 
						INNER JOIN smac_gcs.gcs_wordlist_{$campaign_id} fw ON cf.id= fw.campaign_web_feeds_id
						WHERE
						cf.campaign_id = {$campaign_id} AND fw.keyword = '{$keyword}'
						AND cf.group_type_id={$type}
						AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_gcs mt 
										WHERE mt.campaign_id={$campaign_id}
										AND mt.campaign_web_feeds_id=cf.id AND mt.group_type_id={$type} LIMIT 1)
						LIMIT {$start},{$total};";
				$r = $this->fetch($sql);
				
				//$rows = intval($r['total']-$wcount['total']);
				$rows = $r['total'];
			}else{
				$feeds = array();
				$rows = 0;
			}
		}
		return array("feeds"=>$feeds,"total_rows"=>$rows);
	}
	
	function flag_feeds($campaign_id,$type,$keyword,$feed_id,$folder_id){
		$type = intval($type);
		if(strlen($keyword)==0){
			$keyword = "N/A";
		}
		$sql = "SELECT id FROM smac_report.campaign_web_feeds 
				WHERE campaign_id={$campaign_id} AND feed_id = {$feed_id} AND group_type_id = {$type} LIMIT 1";
		$rs = $this->fetch($sql);
		return $this->flag($campaign_id,$type,$keyword,$rs['id'],$folder_id);
	}
	function flag($campaign_id,$type,$keyword,$feed_id,$folder_id){
		$type = intval($type);
		if(strlen($keyword)==0){
			$keyword = "N/A";
		}
		$sql="INSERT IGNORE INTO smac_workflow.workflow_marked_gcs
			 (campaign_id,campaign_web_feeds_id,keyword,folder_id,apply_date_ts,apply_date,group_type_id)
			 VALUES(".$campaign_id.",'".$feed_id."','".$keyword."',".$folder_id.",".time().",NOW(),{$type})";	
		$q = $this->query($sql);
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	function unflag($campaign_id,$type,$feed_id,$folder_id){
		$type = intval($type);
		$sql="DELETE FROM  smac_workflow.workflow_marked_gcs
			  WHERE campaign_id={$campaign_id} 
			  AND
			  campaign_web_feeds_id={$feed_id} 
			  AND 
			  folder_id={$folder_id}
			  AND group_type_id={$type}";
			 
		$q = $this->query($sql);
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	function flag_keyword($campaign_id,$type,$keyword,$folder_id){
		$type = intval($type);
		$sql = "INSERT IGNORE INTO smac_workflow.gcs_workflow_keyword_flag
				(campaign_id,keyword,folder_id,submit_date,n_status)
				VALUES
				({$campaign_id},'{$keyword}',{$folder_id},NOW(),0)
				";
		$q = $this->query($sql);
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	/**
	 *
	 * @param $campaign_id 
	 * @param $type 1->positive, else-> negative
	 * @param $group_type web channel group_type.
	 */
	function folder_sentiment($campaign_id,$type=1,$group_type=1,$start,$total=10){
		$group_type = intval($group_type);
		$type = intval($type);
		$start = intval($start);
		if($type==1){
			$sql = "SELECT a.id AS feed_id,a.feed_id as fid,author_name,link,author_uri, comments,title,summary AS content,published_ts, source_service,
					generator,rank,lang,geo,sentiment
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment > 0 AND a.group_type_id = {$group_type}
					AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_gcs c
									WHERE c.campaign_web_feeds_id = a.id AND c.group_type_id={$group_type} LIMIT 1)
					LIMIT {$start},{$total}
				";
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment > 0 
					AND a.group_type_id = {$group_type}
					AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_gcs c
									WHERE c.campaign_web_feeds_id = a.id AND c.group_type_id = {$group_type} 
									LIMIT 1)
					LIMIT 1
				";
		}else{
			$sql = "SELECT a.id AS feed_id,a.feed_id as fid, author_name,link,author_uri, comments,title,summary AS content,published_ts, source_service,
					generator,rank,lang,geo,sentiment
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment < 0 
					AND a.group_type_id = {$group_type}
					AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_gcs c
									WHERE c.campaign_web_feeds_id = a.id AND c.group_type_id={$group_type} LIMIT 1)
					LIMIT {$start},{$total}
				";
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment < 0 
					AND a.group_type_id = {$group_type}
					AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_gcs c
									WHERE c.campaign_web_feeds_id = a.id 
									c.group_type_id = {$group_type}
									LIMIT 1)
					LIMIT 1
				";
		}
		$feeds = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		if(sizeof($feeds)==0){
			$feeds = array();
		}
		return array("feeds"=>$feeds,"total_rows"=>$rows['total']);
		
	}
}
?>