<?php
global $APP_PATH;
include_once $APP_PATH."/smac/helper/FBHelper.php";
include_once "kol_model.php";
class workflow_model extends kol_model{
	/*
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
		parent::setRequestHandler($req);
	}*/
	function folders($campaign_id){
		$this->prepare_schema($campaign_id);
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
							   'total'=>intval($total));
		}
		
		//positive/negative counter
		$sql = "SELECT COUNT(*) as total
				FROM smac_feeds.campaign_feeds_{$campaign_id} a
				INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
				ON a.id = b.feed_id
				WHERE a.is_active = 1 AND b.sentiment > 0
				AND NOT EXISTS(
				SELECT 1 FROM smac_workflow.workflow_marked_tweets_{$campaign_id} c WHERE c.feed_id = a.feed_id LIMIT 1
				)
				LIMIT 1;";
		$positive = @$this->fetch($sql);
		$sql = "SELECT COUNT(*) as total
			FROM smac_feeds.campaign_feeds_{$campaign_id} a
			INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
			ON a.id = b.feed_id
			WHERE a.is_active = 1 AND b.sentiment < 0
			AND NOT EXISTS(
			SELECT 1 FROM smac_workflow.workflow_marked_tweets_{$campaign_id} c WHERE c.feed_id = a.feed_id LIMIT 1
			)
			LIMIT 1;";
		$negative = @$this->fetch($sql);
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
				FROM smac_workflow.workflow_marked_tweets_{$campaign_id} a
				INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b ON b.campaign_id={$campaign_id} AND b.feed_id = a.feed_id
				WHERE a.folder_id={$folder_id}
				AND b.author_id NOT IN (SELECT author_id FROM smac_workflow.workflow_marked_people w WHERE w.campaign_id={$campaign_id} )";
		$rs = @$this->fetch($sql);
		
		return $rs['total'];
	}
	function keywords($campaign_id,$folder_id){
		$folder_id = intval($folder_id);
		$sql = "SELECT keyword,COUNT(keyword) AS total 
				FROM 
				smac_workflow.workflow_marked_tweets_{$campaign_id}
				WHERE 
				folder_id={$folder_id}
				GROUP BY keyword LIMIT 10;";
				
		$keywords = $this->fetch($sql,1);
		return $keywords;
	}
	function tweets($campaign_id,$folder_id,$keyword,$start,$limit=10){
		$folder_id = intval($folder_id);
		$start = intval($start);
		$keyword = mysql_escape_string(cleanXSS($keyword));
		$rs = array();
		if($folder_id!=4){
			$sql = "SELECT keyword,feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, 
					published_date,followers as imp,generator,content,reply_date
				FROM (
					SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp,
							c.keyword,date_format(c.reply_date,'%d/%m/%Y %H:%i:%s') as reply_date,reply_content,reply_status
					FROM smac_feeds.campaign_feeds_".$campaign_id." a
						LEFT JOIN smac_rt.rt_content_{$campaign_id} b
						ON a.feed_id = b.feed_id
						INNER JOIN smac_workflow.workflow_marked_tweets_{$campaign_id} c
						ON a.feed_id = c.feed_id
					WHERE a.campaign_id=".$campaign_id." 
					AND c.folder_id=".$folder_id." 
					AND c.keyword='{$keyword}'
					GROUP BY a.feed_id
					ORDER BY c.keyword ASC
					LIMIT ".$start.",".$limit."
				) a";
				$rs = $this->fetch($sql,1);
				
				$sql2 = "SELECT COUNT(id) AS total FROM smac_workflow.workflow_marked_tweets_{$campaign_id} wmt 
						 WHERE folder_id={$folder_id} 
						 AND keyword='{$keyword}';";
						 
				$rows = $this->fetch($sql2);
		}
		return array("feeds"=>$rs,"total"=>$rows['total']);
	}
	function all_tweets($campaign_id,$folder_id,$start,$limit=10){
		$folder_id = intval($folder_id);
		$start = intval($start);
		$keyword = mysql_escape_string(cleanXSS($keyword));
		$rs = array();
		if($folder_id!=4){
			$sql = "SELECT keyword,feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, 
					published_date,followers as imp,generator,content,reply_date,reply_status
				FROM (
					SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp,
							c.keyword,date_format(c.reply_date,'%d/%m/%Y %H:%i:%s') as reply_date,reply_content,reply_status
					FROM smac_feeds.campaign_feeds_".$campaign_id." a
						LEFT JOIN smac_rt.rt_content_{$campaign_id} b
						ON a.feed_id = b.feed_id
						INNER JOIN smac_workflow.workflow_marked_tweets_{$campaign_id} c
						ON a.feed_id = c.feed_id
					WHERE a.campaign_id=".$campaign_id." 
					AND c.folder_id=".$folder_id." 
					GROUP BY a.feed_id
					ORDER BY c.keyword ASC
					LIMIT ".$start.",".$limit."
				) a";
				$rs = @$this->fetch($sql,1);
				
				$sql2 = "SELECT COUNT(id) AS total FROM smac_workflow.workflow_marked_tweets_{$campaign_id} wmt 
						 WHERE folder_id={$folder_id};";
						 
				$rows = @$this->fetch($sql2);
		}
		
		return array("feeds"=>$rs,"total"=>$rows['total']);
	}
	function excluded_tweets($campaign_id,$filter_by,$start,$limit=10){
		$filter_by = mysql_escape_string(cleanXSS($filter_by));
		$start = intval($start);
		if(strlen($filter_by)>0){
			$sql = "call smac_workflow.wf_excludes_filtered({$campaign_id},{$start},{$limit},'{$filter_by}');";
			$sql2 = "SELECT COUNT(id) AS total 
				FROM smac_workflow.workflow_marked_tweets_{$campaign_id}
				 WHERE folder_id=4
				 AND keyword='{$filter_by}';";
		}else{
			$sql = "call smac_workflow.wf_excludes({$campaign_id},{$start},{$limit});";
			
			$sql2 = "SELECT COUNT(id) AS total 
				FROM smac_workflow.workflow_marked_tweets_{$campaign_id}
				 WHERE folder_id=4;";
		}
		
		
		$rows = $this->fetch($sql2);
		$rs = $this->fetch($sql,1);

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
	function remove_folder($campaign_id,$folder_id){
		$campaign_id = intval($campaign_id);
		$folder_id = intval($folder_id);
		$sql = "DELETE FROM smac_workflow.workflow_folder
						WHERE campaign_id={$campaign_id} AND id = {$folder_id}";
					
		$q = $this->query($sql);
		if($q){
			$q2 = $this->query("DELETE FROM smac_workflow.workflow_marked_tweets_{$campaign_id} 
								WHERE campaign_id={$campaign_id} 
								AND folder_id={$folder_id}");
								
			$q2 = $this->query("DELETE FROM smac_workflow.workflow_marked_fb
								WHERE campaign_id={$campaign_id} 
								AND folder_id={$folder_id}");
								
			$q2 = $this->query("DELETE FROM smac_workflow.workflow_marked_gcs
								WHERE campaign_id={$campaign_id} 
								AND folder_id={$folder_id}");
			$status = 1;
			
		}else{
			$status = 0;
		}
		return array("folder_id"=>$folder_id,"status"=>$status);
	}
	/**
	 * unmark tweet from workflow.
	 */
	function remove_tweet($campaign_id,$folder_id,$feed_id){
		$q = $this->query("DELETE FROM smac_workflow.workflow_marked_tweets_{$campaign_id} 
								WHERE campaign_id={$campaign_id} 
								AND folder_id={$folder_id} AND feed_id='{$feed_id}'");
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
		
		$sql = "UPDATE smac_workflow.workflow_marked_tweets_{$campaign_id}
				SET folder_id = {$to_folder} 
				WHERE feed_id='{$feed_id}'
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
		$sql = "UPDATE smac_workflow.workflow_marked_tweets_{$campaign_id}
				SET folder_id = {$to_folder} 
				WHERE 
				keyword='{$keyword}'
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
		$summary = $this->twitter_profile($campaign_id,$person);
		$summary['influence'] = $this->person_influence($campaign_id, $person);
		$summary['coordinate'] = $this->person_coordinate($campaign_id, $person);
		return $summary;
	}
	function person_influence($campaign_id,$person){
		//influencer of
		$sql ="SELECT b.* FROM smac_feeds.people_network_{$campaign_id} a
				INNER JOIN smac_author.author_summary_{$campaign_id} b
				ON a.rt_author = b.author_id 
				WHERE a.author='{$person}' 
				ORDER BY b.total_impression DESC LIMIT 20;";
		$influencer_of = $this->fetch($sql,1);
		
		//influenced by
		$sql ="SELECT b.* FROM smac_feeds.people_network_{$campaign_id} a
				INNER JOIN smac_author.author_summary_{$campaign_id} b
				ON a.author = b.author_id 
				WHERE a.rt_author='{$person}' 
				ORDER BY b.total_impression DESC LIMIT 20;";
								
		$influenced_by = $this->fetch($sql,1);
		
		return array('influencer_of'=>$influencer_of,
					 'influenced_by'=>$influenced_by);
	}
	function person_coordinate($campaign_id,$person){
		$sql = "SELECT coordinate_x,coordinate_y FROM smac_feeds.campaign_feeds_{$campaign_id}
				WHERE author_id='{$person}' AND (coordinate_x <> 0 OR coordinate_y <> 0) LIMIT 1;";
		$rs = $this->fetch($sql);
		return  array("lat"=>floatval($rs['coordinate_x']),"lon"=>floatval($rs['coordinate_y']));
	}
	function send_tweet($campaign_id,$account_id,$status,$person="",$folder_id=0,$feed_id=0){
		global $TWITTER,$ENGINE_PATH,$CONFIG;
		include_once $ENGINE_PATH."Utility/twitteroauth/twitteroauth.php";
		$sql = "SELECT access_token FROM smac_web.smac_twitter WHERE account_id=".$account_id." LIMIT 1";
		$cek = $this->fetch($sql);
		$access_token = unserialize(urldecode64($cek['access_token']));
		$tOAuth = new TwitterOAuth($TWITTER['KEY'],$TWITTER['SECRET'], $access_token["oauth_token"], $access_token["oauth_token_secret"]);
		$uri='statuses/update';
		if(strlen($person)>0){
			if($CONFIG['DEVELOPMENT']){
				$status="@{$person} {$status}";
			}
		}
		$params = array('status'=>$status);
		$rs = $tOAuth->post($uri,$params);
		if($rs->error){
			$ok = 0;
		}else{
			$status = mysql_escape_string(cleanXSS($status));
			$ok=1;
		}
		$sql = "UPDATE smac_workflow.workflow_marked_tweets_{$campaign_id}
				SET reply_date = NOW(), reply_date_ts = ".time().",reply_content='{$status}',reply_status={$ok}
				WHERE folder_id={$folder_id} AND feed_id = {$feed_id}";
		$this->query($sql);
		return $ok;
	
	}
	function apply_exclude($campaign_id,$feed_id){
		$status = false;
		$sql = "INSERT IGNORE INTO smac_workflow.campaign_feeds_exclude
				(campaign_feeds_id,ref_id, campaign_id, raw_id, feed_id, published, published_date, published_datetime, hour_time, updated, title, summary, link, source_context, source_service, source_service_url, generator, generator_url, content, author_name, 
				author_link, 
				author_avatar, 
				author_id, 
				matching_rule, 
				location, 
				coordinate_x, 
				coordinate_y, 
				twitter_geo, 
				google_location, 
				n_status, 
				retrieve_date, 
				tag_id, 
				following, 
				followers, 
				lists, 
				total_mentions, 
				klout_score, 
				wordlist
				)
				SELECT id, ref_id, campaign_id, raw_id, feed_id, published, published_date, published_datetime, hour_time, updated, title, summary, link, source_context, source_service, source_service_url, generator, generator_url, content, author_name, 
					author_link, 
					author_avatar, 
					author_id, 
					matching_rule, 
					location, 
					coordinate_x, 
					coordinate_y, 
					twitter_geo, 
					google_location, 
					n_status, 
					retrieve_date, 
					tag_id, 
					following, 
					followers, 
					lists, 
					total_mentions, 
					klout_score, 
					wordlist 
				FROM smac_feeds.campaign_feeds_{$campaign_id} 
				WHERE campaign_id={$campaign_id} 
				AND feed_id='{$feed_id}';";
		$q = $this->query($sql);
		if($q){
			$sql = "UPDATE smac_feeds.campaign_feeds_{$campaign_id} SET is_active=0 
					WHERE feed_id='{$feed_id}'";
			if($this->query($sql)){
				$status=true;
				//do legacy stuffs
				/*
				global $APP_PATH;
				require_once $APP_PATH . APPLICATION . "/helper/BotHelper.php";
				$start_time = date("Y-m-d H:i:s",time()+(60*60));
				$bot = new BotHelper($this->Request);
				@$bot->refresh_report($campaign_id,$start_time);
				 * 
				 */
			}
		}
		if($status){
			return 1;
		}else{
			return 0;
		}
	}
	function exclude_all($campaign_id,$keyword){
		$sql = "INSERT IGNORE INTO smac_workflow.twitter_workflow_apply_exclude
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
		$sql = "INSERT IGNORE INTO smac_workflow.campaign_feeds_undo
				(campaign_feeds_id, ref_id, campaign_id, raw_id, feed_id, published, published_date, published_datetime, hour_time, updated, title, summary, link, source_context, source_service, source_service_url, generator, generator_url, content, author_name, 
				author_link, 
				author_avatar, 
				author_id, 
				matching_rule, 
				location, 
				coordinate_x, 
				coordinate_y, 
				twitter_geo, 
				google_location, 
				n_status, 
				retrieve_date, 
				tag_id, 
				following, 
				followers, 
				lists, 
				total_mentions, 
				klout_score, 
				wordlist
				)
				SELECT id, ref_id, campaign_id, raw_id, feed_id, published, published_date, published_datetime, hour_time, updated, title, summary, link, source_context, source_service, source_service_url, generator, generator_url, content, author_name, 
					author_link, 
					author_avatar, 
					author_id, 
					matching_rule, 
					location, 
					coordinate_x, 
					coordinate_y, 
					twitter_geo, 
					google_location, 
					n_status, 
					retrieve_date, 
					tag_id, 
					following, 
					followers, 
					lists, 
					total_mentions, 
					klout_score, 
					wordlist 
				FROM smac_feeds.campaign_feeds_{$campaign_id} 
				WHERE campaign_id={$campaign_id} 
				AND feed_id='{$feed_id}';";
		$q = $this->query($sql);
		if($q){
			$sql = "UPDATE smac_feeds.campaign_feeds_{$campaign_id} SET is_active=1 
					WHERE feed_id='{$feed_id}'";
			if($this->query($sql)){
				$status=true;
				//do legacy stuffs
				require_once $APP_PATH . APPLICATION . "/helper/BotHelper.php";
				$start_time = date("Y-m-d H:i:s",time()+(60*60));
				$bot = new BotHelper($this->Request);
				$bot->refresh_report($campaign_id,$start_time);
			}
		}
		if($status){
			return 1;
		}else{
			return 0;
		}
	}
	
	function keyword_conversation($campaign_id,$keyword,$start,$total=10){
		$this->prepare_schema($campaign_id);
		$tweets = array();
		$rows = 0;
		if(strlen($keyword)){
			$sql = "SELECT campaign_id,keyword 
					FROM smac_workflow.twitter_workflow_keyword_flag 
					WHERE campaign_id={$campaign_id} 
					AND keyword='{$keyword}' LIMIT 1";
			
			$cek = $this->fetch($sql);
			if($cek['campaign_id']==null){
				$keyword = cleanXSS(trim($keyword));
				$start = intval($start);
				//total impressions
				$sql = "SELECT SUM(total_impression_twitter+total_rt_impression_twitter) AS true_reach
				FROM smac_report.campaign_rule_volume_history 
				WHERE campaign_id={$campaign_id}";
				$campaign = $this->fetch($sql);
				$sql = "SELECT 
						cf.feed_id,cf.tag_id AS rule_id,cf.author_id,cf.author_name,cf.author_avatar,
						cf.published_datetime,cf.content,cf.generator,cf.followers AS impression,rt_count AS rt,local_rt_impresion as rt_imp
						FROM 
						smac_feeds.campaign_feeds_{$campaign_id} cf 
						INNER JOIN smac_word.feed_wordlist_{$campaign_id} fw ON cf.id= fw.fid
						WHERE
						cf.campaign_id = {$campaign_id} AND fw.keyword = '{$keyword}'
						AND NOT EXISTS (SELECT 1 FROM smac_workflow.workflow_marked_tweets_{$campaign_id} mt 
										WHERE mt.feed_id=cf.feed_id LIMIT 1)
						LIMIT {$start},{$total};";
						
				$tweets = $this->fetch($sql,1);
				
				$sql = "SELECT COUNT(id) as total FROM smac_workflow.workflow_marked_tweets_{$campaign_id} WHERE keyword='{$keyword}'";
				$wcount = $this->fetch($sql); 
				$sql = "SELECT feed_count as total FROM smac_cardinal.word_feed_{$campaign_id} WHERE keyword = '{$keyword}'";
				$r = $this->fetch($sql);
				if(sizeof($tweets)==0){
					$tweets = array();
				}
				for($n=0;$n<sizeof($tweets);$n++){
					$v = $tweets[$n];
					$tweets[$n]['share'] = round($v['impression'] / $campaign['true_reach'] * 100,5);
				}
				
				$rows = intval($r['total']-$wcount['total']);
			}else{
				$tweets = array();
				$rows = 0;
			}
		}
		return array("tweets"=>$tweets,"total_rows"=>$rows);
	}
	function prepare_schema($campaign_id){
		$sql = "CREATE TABLE IF NOT EXISTS smac_workflow.workflow_marked_tweets_{$campaign_id}
				LIKE smac_workflow.workflow_marked_tweets_model";
		$q = mysql_query($sql);
		return $q;
	}
	function flag($campaign_id,$keyword,$feed_id,$folder_id){
		$this->prepare_schema($campaign_id);
		if(strlen($keyword)==0){
			$keyword = "N/A";
		}
		$sql="INSERT IGNORE INTO smac_workflow.workflow_marked_tweets_{$campaign_id}
			 (feed_id,keyword,folder_id,apply_date_ts,apply_date)
			 VALUES('".$feed_id."','".$keyword."',".$folder_id.",".time().",NOW())";	
			
		$q = mysql_query($sql);
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	function unflag($campaign_id,$feed_id,$folder_id){
		$this->prepare_schema($campaign_id);
		$sql="DELETE FROM smac_workflow.workflow_marked_tweets_{$campaign_id}
			  WHERE feed_id='{$feed_id}' AND folder_id={$folder_id}";	
		$q = mysql_query($sql);
		if($q){
			return 1;
		}else{
			return 0;
		}
	}
	function flag_keyword($campaign_id,$keyword,$folder_id){
		$this->prepare_schema($campaign_id);
		$sql = "INSERT IGNORE INTO smac_workflow.twitter_workflow_keyword_flag
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
		
		$this->prepare_schema($campaign_id);
		if($type==1){
			$sql = "SELECT a.feed_id,a.published_date,content,generator,
				author_name,author_avatar AS avatar_pic,author_id,generator,followers AS imp,
				a.rt_count,a.local_rt_impresion AS rt_imp,b.sentiment 
				FROM smac_feeds.campaign_feeds_{$campaign_id} a
				INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
				ON a.id = b.feed_id
				WHERE a.is_active = 1 AND b.sentiment > 0
				AND NOT EXISTS(
				SELECT 1 FROM smac_workflow.workflow_marked_tweets_{$campaign_id} c WHERE c.feed_id = a.feed_id LIMIT 1
				)
				LIMIT {$start},{$total};
				";
			$sql2 = "SELECT COUNT(*) as total
				FROM smac_feeds.campaign_feeds_{$campaign_id} a
				INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
				ON a.id = b.feed_id
				WHERE a.is_active = 1 AND b.sentiment > 0
				AND NOT EXISTS(
				SELECT 1 FROM smac_workflow.workflow_marked_tweets_{$campaign_id} c WHERE c.feed_id = a.feed_id LIMIT 1
				)
				LIMIT 1;
				";
		}else{
			$sql = "SELECT a.feed_id,a.published_date,content,generator,
					author_name,author_avatar AS avatar_pic,author_id,generator,followers AS imp,
					a.rt_count,a.local_rt_impresion AS rt_imp,b.sentiment 
					FROM smac_feeds.campaign_feeds_{$campaign_id} a
					INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
					ON a.id = b.feed_id
					WHERE a.is_active = 1 AND b.sentiment < 0
					AND NOT EXISTS(
					SELECT 1 FROM smac_workflow.workflow_marked_tweets_{$campaign_id} c WHERE c.feed_id = a.feed_id LIMIT 1
					)
					LIMIT {$start},{$total};
					";
			$sql2 = "SELECT COUNT(*) as total
					FROM smac_feeds.campaign_feeds_{$campaign_id} a
					INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
					ON a.id = b.feed_id
					WHERE a.is_active = 1 AND b.sentiment < 0
					AND NOT EXISTS(
					SELECT 1 FROM smac_workflow.workflow_marked_tweets_{$campaign_id} c WHERE c.feed_id = a.feed_id LIMIT 1
					)
					LIMIT 1;
					";
		}
		$feeds = @$this->fetch($sql,1);
		$rows = @$this->fetch($sql2);
	
		return array("feeds"=>$feeds,"total_rows"=>$rows['total']);
		
	}
}
?>