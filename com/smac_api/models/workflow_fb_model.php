<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
include_once "kol_model.php";
class workflow_fb_model extends kol_model{
	/*
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
		parent::setRequestHandler($req);
	}*/
	function folders($campaign_id){
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
			
			$total = $this->getFolderSize($campaign_id, $d['folder_id']);
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
					FROM smac_fb.campaign_fb a 
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment > 0 LIMIT 1";
		$positive = $this->fetch($sql);
		$sql = "SELECT COUNT(*) as total
					FROM smac_fb.campaign_fb a 
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment < 0 LIMIT 1";
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
	function getFolderSize($campaign_id,$folder_id){
		$sql = "SELECT COUNT(a.id) AS total 
					FROM smac_workflow.workflow_marked_fb a
					WHERE a.campaign_id={$campaign_id}
						AND a.folder_id={$folder_id}
						AND EXISTS (
					SELECT 1 FROM smac_fb.campaign_fb b
					WHERE b.id = a.campaign_fb_id 
						AND b.campaign_id={$campaign_id}
						AND b.from_object_id NOT IN 
						(SELECT author_id FROM smac_workflow.workflow_marked_people_fb w 
						WHERE w.campaign_id={$campaign_id} ) 
					);";
		$rs = $this->fetch($sql);
		
		return $rs['total'];
	}
	function keywords($campaign_id,$folder_id){
		$folder_id = intval($folder_id);
		$sql = "SELECT keyword,COUNT(keyword) AS total 
				FROM 
				smac_workflow.workflow_marked_fb
				WHERE campaign_id={$campaign_id}
				AND 
				folder_id={$folder_id}
				GROUP BY keyword LIMIT 10;";
		$keywords = $this->fetch($sql,1);
		return $keywords;
	}
	function feeds($campaign_id,$folder_id,$keyword,$start,$limit=10){
		$folder_id = intval($folder_id);
		$start = intval($start);
		$keyword = mysql_escape_string(cleanXSS($keyword));
		$rs = array();
		if($folder_id!=4){
			$sql = "SELECT a.id as feed_id,
						from_object_id,from_object_name,
						likes_count,message,description,created_time,
						created_time_ts,application_object_name
		        		FROM smac_fb.campaign_fb a
						INNER JOIN smac_workflow.workflow_marked_fb c
						ON a.id = c.campaign_fb_id
					WHERE a.campaign_id=".$campaign_id." 
					AND c.campaign_id=".$campaign_id." 
					AND c.folder_id=".$folder_id." 
					AND c.keyword='{$keyword}'
					
					ORDER BY c.keyword ASC
					LIMIT ".$start.",".$limit;
				$rs = $this->fetch($sql,1);
				
				if(sizeof($rs)==0){
					$rs = array();
				}
				$sql2 = "SELECT COUNT(id) AS total FROM smac_workflow.workflow_marked_fb wmt 
						 WHERE campaign_id={$campaign_id} 
						 AND folder_id={$folder_id} 
						 AND keyword='{$keyword}';";
						 
				$rows = $this->fetch($sql2);
				for($i=0;$i<sizeof($rs);$i++){
					$rs[$i]['message'].=" ".$rs[$i]['description'];
				}
		}
		return array("feeds"=>$rs,"total"=>$rows['total']);
	}
	function all_feeds($campaign_id,$folder_id,$start,$limit=10){
		$folder_id = intval($folder_id);
		$start = intval($start);
		$keyword = mysql_escape_string(cleanXSS($keyword));
		$rs = array();
		if($folder_id!=4){
			$sql = "
					SELECT a.id as feed_id,
						from_object_id,from_object_name,
						likes_count,message,created_time,
						created_time_ts,application_object_name,description,reply_status,reply_date
		        		FROM smac_fb.campaign_fb a
						INNER JOIN smac_workflow.workflow_marked_fb c
						ON a.id = c.campaign_fb_id
					WHERE a.campaign_id=".$campaign_id." 
					AND c.campaign_id=".$campaign_id." 
					AND c.folder_id=".$folder_id." 
					
					ORDER BY c.keyword ASC
					LIMIT ".$start.",".$limit;
				$rs = $this->fetch($sql,1);
				
				$sql2 = "SELECT COUNT(id) AS total FROM smac_workflow.workflow_marked_fb wmt 
						 WHERE campaign_id={$campaign_id} 
						 AND folder_id={$folder_id};";
						 
				$rows = $this->fetch($sql2);
				
				for($i=0;$i<sizeof($rs);$i++){
					$rs[$i]['message'].=" ".$rs[$i]['description'];
				}
		}
		return array("feeds"=>$rs,"total"=>$rows['total']);
	}
	function excluded_feeds($campaign_id,$filter_by,$start,$limit=10){
		$filter_by = mysql_escape_string(cleanXSS($filter_by));
		$start = intval($start);
		if(strlen($filter_by)>0){
			//$sql = "call smac_report.wf_excludes_filtered({$campaign_id},{$start},{$limit},'{$filter_by}');";
			$sql = "SELECT a.id AS feed_id,object_id AS author_id,from_object_name AS author_name,
					message,description,created_time_ts,is_active,keyword,likes_count
					FROM smac_fb.campaign_fb a
					INNER JOIN smac_workflow.workflow_marked_fb b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id} AND b.campaign_id={$campaign_id} 
					AND b.keyword = '{$filter_by}'
					AND b.folder_id=4
					LIMIT {$start},{$limit}";
					
			$sql2 = "SELECT COUNT(id) AS total 
				FROM smac_workflow.workflow_marked_fb
				 WHERE campaign_id={$campaign_id} 
				 AND folder_id=4
				 AND keyword='{$filter_by}';";
		}else{
			//$sql = "call smac_report.wf_excludes({$campaign_id},{$start},{$limit});";
			$sql2 = "SELECT COUNT(id) AS total 
				FROM smac_workflow.workflow_marked_fb
				 WHERE campaign_id={$campaign_id} 
				 AND folder_id=4;";
				 
			$sql = "SELECT a.id AS feed_id,object_id AS author_id,from_object_name AS author_name,
					message,description,created_time_ts,is_active,keyword,likes_count
					FROM smac_fb.campaign_fb a
					INNER JOIN smac_workflow.workflow_marked_fb b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id} AND b.campaign_id={$campaign_id} 
					AND b.folder_id=4
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
			$rs[$i]['content'] = $rs[$i]['message']." ".$rs[$i]['description'];
		}
		return array("feeds"=>$rs,"total"=>$rows['total']);
	}
	function add_folder($campaign_id,$folder_name){
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
	function remove_feeds($campaign_id,$folder_id,$feed_id){
		$q = $this->query("DELETE FROM smac_workflow.workflow_marked_fb
								WHERE campaign_id={$campaign_id} 
								AND folder_id={$folder_id} AND campaign_fb_id='{$feed_id}'");
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	/**
	 * move tweet to another folder.
	 */
	function move($campaign_id,$from_folder,$to_folder,$feed_id){
		
		$sql = "UPDATE smac_workflow.workflow_marked_fb
				SET folder_id = {$to_folder} 
				WHERE 
				campaign_id = {$campaign_id} 
				AND campaign_fb_id='{$feed_id}'
				AND folder_id = {$from_folder}
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
	function move_all($campaign_id,$from_folder,$to_folder,$keyword){
		$sql = "UPDATE smac_workflow.workflow_marked_fb
				SET folder_id = {$to_folder} 
				WHERE 
				campaign_id = {$campaign_id} 
				AND keyword='{$keyword}'
				AND folder_id = {$from_folder}
		";
		$q = $this->query($sql);
		if($q){
			return array('old_folder_id'=>$from_folder,'new_folder_id'=>$to_folder);
		}else{
			return array();
		}
	}
	
	function person($campaign_id,$person){
		
		$sql = "SELECT * FROM smac_fb.fb_user_profile WHERE id='{$person}' LIMIT 1";
		$summary = $this->fetch($sql);
		$summary['wordcloud'] = $this->fb_wordcloud($campaign_id,$person);
		return $summary;
	}
	function fb_wordcloud($campaign_id,$person){
		$sql = "SELECT a.keyword,COUNT(a.keyword) as occurance
			        FROM smac_fb.fb_wordlist_{$campaign_id} a
	                INNER JOIN smac_fb.campaign_fb b
	                ON a.fid = b.id
			        WHERE b.campaign_id={$campaign_id}
			        AND b.from_object_id={$person}
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
	function person_influence($campaign_id,$person){
		
		return array('influencer_of'=>array(),
					 'influenced_by'=>array());
	}
	function person_coordinate($campaign_id,$person){
		return  array("lat"=>0,"lon"=>0);
	}
	/**
	 * reply on facebook
	 */
	function send_reply($campaign_id,$account_id,$status,$person="",$folder_id=0,$feed_id){
		global $APP_PATH,$ENGINE_PATH;
		require_once $ENGINE_PATH."/Utility/facebook/facebook.php";
	
		$feed = $this->fetch("SELECT feeds_facebook_id,object_id FROM smac_fb.campaign_fb WHERE id = {$feed_id} LIMIT 1");
		
		global $FACEBOOK;
		
		$config = array('appId'=>$FACEBOOK['APP_ID'],'secret'=>$FACEBOOK['APP_SECRET'],'fileUpload'=>false);
		$this->helper = new facebook($config);
		$sql = "SELECT * FROM smac_web.smac_facebook WHERE account_id={$account_id} LIMIT 1";
	
		$rs = $this->fetch($sql);
		if(strlen($rs['access_token'])>0){
			
			$this->helper->setAccessToken($rs['access_token']);
			if(strlen($this->helper->getAccessToken())>0){
				try{
					$rs = $this->helper->api("/{$feed['object_id']}/comments",'post',array('message' => $status));
					if($rs){
						$status = mysql_escape_string($status);
						$sql = "UPDATE smac_workflow.workflow_marked_fb
							SET reply_date = NOW(), reply_date_ts = ".time().",reply_content='{$status}',reply_status=1
							WHERE folder_id={$folder_id} AND campaign_fb_id = {$feed_id}";
						
						$this->query($sql);
						return 1;
					}
				}catch(Exception $e){
				
				}	
				
			}
		}
		return 0;
	}
	function apply_exclude($campaign_id,$feed_id){
		$status = false;
		$sql = "INSERT IGNORE INTO `smac_workflow`.`campaign_fb_exclude` 
				(
				`campaign_fb_id`, 
				`campaign_id`, 
				`raw_id`, 
				`feeds_facebook_id`, 
				`dtinserted`, 
				`keyword_id`, 
				`object_id`, 
				`from_object_id`, 
				`from_object_name`, 
				`message`, 
				`story`, 
				`story_tags_id`, 
				`story_tags_name`, 
				`picture`, 
				`link`, 
				`name`, 
				`caption`, 
				`description`, 
				`icon`, 
				`type`, 
				`application_object_id`, 
				`application_object_name`, 
				`created_time`, 
				`created_time_ts`, 
				`updated_time`, 
				`updated_time_ts`, 
				`likes_count`, 
				`source_context`, 
				`source_service`, 
				`source_service_url`, 
				`geo`, 
				`is_active`
				)
			SELECT 	`id`, 
				`campaign_id`, 
				`raw_id`, 
				`feeds_facebook_id`, 
				`dtinserted`, 
				`keyword_id`, 
				`object_id`, 
				`from_object_id`, 
				`from_object_name`, 
				`message`, 
				`story`, 
				`story_tags_id`, 
				`story_tags_name`, 
				`picture`, 
				`link`, 
				`name`, 
				`caption`, 
				`description`, 
				`icon`, 
				`type`, 
				`application_object_id`, 
				`application_object_name`, 
				`created_time`, 
				`created_time_ts`, 
				`updated_time`, 
				`updated_time_ts`, 
				`likes_count`, 
				`source_context`, 
				`source_service`, 
				`source_service_url`, 
				`geo`, 
				`is_active`
				FROM 
				`smac_fb`.`campaign_fb`
				WHERE campaign_id={$campaign_id} AND id={$feed_id} LIMIT 1;
				";
		$q = $this->query($sql);
		if($q){
			$sql = "UPDATE smac_fb.campaign_fb SET is_active=0 
					WHERE campaign_id={$campaign_id} AND id='{$feed_id}'";
			if($this->query($sql)){
				$status=true;
			}
		}
		if($status){
			return 1;
		}else{
			return 0;
		}
	}
	function exclude_all($campaign_id,$keyword){
		$sql = "INSERT IGNORE INTO smac_workflow.fb_workflow_apply_exclude
				(campaign_id,keyword,submit_date,n_status)
				VALUES
				({$campaign_id},'{$keyword}',NOW(),0)
				";
		$q = $this->query($sql);
		if($q){
			$status = 1;
		}else{
			$status = 0;
		}
		return $status;
	}
	function undo_exclude($campaign_id,$feed_id){
		return 0;
	}
	
	function keyword_conversation($campaign_id,$keyword,$start,$total=10){
	
		$tweets = array();
		$rows = 0;
		if(strlen($keyword)){
			$sql = "SELECT campaign_id,keyword 
					FROM smac_workflow.fb_workflow_keyword_flag 
					WHERE campaign_id={$campaign_id} 
					AND keyword='{$keyword}' LIMIT 1";
			
			$cek = $this->fetch($sql);
			if($cek['campaign_id']==null){
				$keyword = cleanXSS(trim($keyword));
				$start = intval($start);
				
				$sql = "SELECT 
						cf.id,cf.feeds_facebook_id as feed_id,cf.keyword_id as rule_id,cf.from_object_id as author_id,
						cf.from_object_name as author_name,'' as author_avatar,created_time_ts,message,application_object_name as generator,
						likes_count,cf.description
						FROM 
						smac_fb.campaign_fb cf 
						INNER JOIN smac_fb.fb_wordlist_{$campaign_id} fw ON cf.id= fw.fid
						WHERE
						cf.campaign_id = {$campaign_id} AND fw.keyword = '{$keyword}'
						AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_fb mt 
										WHERE mt.campaign_id={$campaign_id}
										AND mt.campaign_fb_id=cf.feeds_facebook_id LIMIT 1)
						LIMIT {$start},{$total};";
						
				$feeds = $this->fetch($sql,1);
				for($n=0;$n<sizeof($feeds);$n++){
					$feeds[$n]['content'] = trim($feeds[$n]['message']." ".$feeds[$n]['description']);
				}
				$sql = "SELECT COUNT(id) as total FROM smac_workflow.workflow_marked_fb 
						WHERE campaign_id={$campaign_id} AND keyword='{$keyword}'";
						
				$wcount = $this->fetch($sql); 
				$sql = "SELECT COUNT(*) AS total FROM (
								SELECT feed_id FROM smac_fb.fb_wordlist_{$campaign_id} 
								WHERE keyword='{$keyword}' GROUP BY feed_id) a;";
				$r = $this->fetch($sql);

				$rows = intval($r['total']-$wcount['total']);
			}else{
				$feeds = array();
				$rows = 0;
			}
		}
		return array("feeds"=>$feeds,"total_rows"=>$rows);
	}
	
	function flag($campaign_id,$keyword,$feed_id,$folder_id){
		if(strlen($keyword)==0){
			$keyword = "N/A";
		}
		$sql="INSERT IGNORE INTO smac_workflow.workflow_marked_fb
			 (campaign_id,campaign_fb_id,keyword,folder_id,apply_date_ts,apply_date)
			 VALUES(".$campaign_id.",'".$feed_id."','".$keyword."',".$folder_id.",".time().",NOW())";	
		$q = mysql_query($sql);
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	function unflag($campaign_id,$feed_id,$folder_id){
		$sql="DELETE FROM  smac_workflow.workflow_marked_fb
			  WHERE campaign_id={$campaign_id} 
			  AND
			  campaign_fb_id={$feed_id} 
			  AND 
			  folder_id={$folder_id}";	
		$q = mysql_query($sql);
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	function flag_feeds($campaign_id,$keyword,$feed_id,$folder_id){
		if(strlen($keyword)==0){
			$keyword = "N/A";
		}
		
		$sql = "SELECT id FROM smac_fb.campaign_fb WHERE campaign_id={$campaign_id} AND feeds_facebook_id={$feed_id} LIMIT 1";
		$rs = $this->fetch($sql);
		
		return $this->flag($campaign_id,$keyword,$rs['id'],$folder_id);
	}
	function flag_keyword($campaign_id,$keyword,$folder_id){
		
		$sql = "INSERT IGNORE INTO smac_workflow.fb_workflow_keyword_flag
				(campaign_id,keyword,folder_id,submit_date,n_status)
				VALUES
				({$campaign_id},'{$keyword}',{$folder_id},NOW(),0)
				";
		$q = mysql_query($sql);
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	function folder_sentiment($campaign_id,$type=1,$start,$total=10){
		$type = intval($type);
		$start = intval($start);
		if($type==1){
			$sql = "SELECT a.id AS feed_id,
					feeds_facebook_id as fid,
					from_object_id,from_object_name,
					likes_count,message,created_time,
					created_time_ts,application_object_name,description,sentiment
					FROM smac_fb.campaign_fb a 
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment > 0 
					AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_fb c
									WHERE c.campaign_fb_id = a.id LIMIT 1)
					LIMIT {$start},{$total};
				";
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_fb.campaign_fb a 
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment > 0 
					AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_fb c
									WHERE c.campaign_fb_id = a.id LIMIT 1)
					LIMIT 1;
				";
		}else{
			$sql = "SELECT a.id AS feed_id,feeds_facebook_id as fid,
					from_object_id,from_object_name,
					likes_count,message,created_time,
					created_time_ts,application_object_name,description,sentiment
					FROM smac_fb.campaign_fb a 
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment < 0
					AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_fb c WHERE c.campaign_fb_id = a.id LIMIT 1) 
					LIMIT {$start},{$total};
				";
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_fb.campaign_fb a 
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id} AND b.sentiment < 0 
					AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_fb c WHERE c.campaign_fb_id = a.id LIMIT 1) 
					LIMIT 1;
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