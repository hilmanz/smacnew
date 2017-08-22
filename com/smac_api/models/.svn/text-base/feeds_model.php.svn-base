<?php
class feeds_model extends base_model{
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
	}
	
	/**
	 * return feeds by specified date value
	 *@param $campaign_id
	 *@param $channel 1-> twitter, 2->facebook, 3->web
	 *@param $start the start offset of data
	 *@param $total default to 20
	 */
	function feed_by_date($campaign_id,$channel,$dt,$start,$total=20){
		$start = intval($start);
		$channel = intval($channel);
		$dt = mysql_escape_string(cleanXSS($dt));
		switch($channel){
			case 1:
				return $this->twitter_feeds($campaign_id,$dt,$start,$total);
			break;
			case 2:
				return $this->facebook_feeds($campaign_id,$dt,$start,$total);
			break;
			case 3:
				return $this->web_feeds($campaign_id,$dt,$start,$total);
			break;
			case 4:
				return $this->video_feeds($campaign_id,$dt,$start,$total);
			break;
			default:
				return array("tweets"=>array(),"total"=>0);
			break;
		}
		//return array("tweets"=>$tweets,"total"=>$rows['total']);
	}
	function site_feed_by_date($campaign_id,$group_type,$dt,$start,$total=20){
		$start = intval($start);
		$channel = intval($channel);
		$dt = mysql_escape_string(cleanXSS($dt));
		
		$rs = $this->site_feeds($campaign_id,$group_type,$dt,$start,$total);
		if(!is_array($rs)){
			return array("tweets"=>array(),"total"=>0);
		}
		return $rs;
		
	}
	private function twitter_feeds($campaign_id,$dt,$start,$limit=20){
		$start = intval($start);
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		//total impressions
		$sql = "SELECT SUM(total_impression_twitter+total_rt_impression_twitter) AS true_reach
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} AND dtreport = '{$dt}'";
		
		$campaign = $this->fetch($sql);
		$total_impression = $campaign['true_reach'];
		$campaign=null;
		unset($campaign);
		
		$sql = "SELECT feed_id,local_rt_count as rt_total,
				local_rt_impresion as rt_imp,
				author_id,
				author_name,
				author_avatar AS avatar_pic, 
				published_date,
				followers AS imp,generator,content,location
			FROM smac_feeds.campaign_feeds_{$campaign_id} 
			WHERE is_active = 1 AND published_date = '{$dt}'
			ORDER BY followers desc
			LIMIT {$start},{$limit};";
		
		$sql2 = "SELECT SUM(total_mention_twitter) AS total
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} AND dtreport='{$dt}';";
		
		$rs = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$posts = array();
		foreach($rs as $r){
			$share = round((($r['imp']+$r['rt_imp'])/$total_impression)*100,4);
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],1);
			//-->end of check
			$posts[] = array("id"=>$r['feed_id'],
						   "published"=>$r['published_date'],
						   "author_id"=>$r['author_id'],
						   "name"=>htmlspecialchars($r['author_name']),
						   "txt"=>htmlspecialchars($r['content']),
						   "device"=>$this->get_device($r['generator'],$devices),
						   "profile_image_url"=>$r['avatar_pic'],
						   "impression"=>$r['imp'],
						   "rt"=>$r['rt_total'],
						   "rt_imp"=>$r['rt_imp'],
						   "location"=>trim($r['locaiton']),
						   "share"=>$share,
						   "flag"=>$flag);		
		}
		return array("feeds"=>$posts,"total_rows"=>intval($rows['total']));
	}
	private function facebook_feeds($campaign_id,$dt,$start,$limit=20){
		$start = intval($start);
		
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		$created_time_ts = ($dt." 00:00:00");
		$ts_until = ($dt." 23:59:59");
		$sql = "SELECT feeds_facebook_id as feed_id,
						from_object_id,from_object_name,
						likes_count,message,created_time,
						created_time_ts,application_object_name,description
		        FROM smac_fb.campaign_fb
		        WHERE campaign_id = {$campaign_id} AND is_active = 1
		        AND created_time_ts BETWEEN UNIX_TIMESTAMP('{$created_time_ts}') 
				AND UNIX_TIMESTAMP('{$ts_until}')
		        ORDER BY likes_count DESC
		        limit {$start},{$limit};";
				
		$sql2 = "SELECT COUNT(*) AS total
		        FROM smac_fb.campaign_fb
		        WHERE campaign_id = {$campaign_id} AND is_active = 1
		        AND created_time_ts BETWEEN UNIX_TIMESTAMP('{$created_time_ts}') 
				AND UNIX_TIMESTAMP('{$ts_until}');
		        ";
				
		$rs = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		
		$num = sizeof($rs);
		$posts = array();
		for($i=0;$i<sizeof($rs);$i++){
			$r = $rs[$i];
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],2);
			//-->end of check
			$posts[] = array(
						'id'=>$r['feed_id'],
						'published'=>date("d/m/Y",$r['created_time_ts']),
						'name'=>$r['from_object_name'],
						'url'=>"http://www.facebook.com/".$r['from_object_id'],
						'txt'=>htmlspecialchars($r['message']." ".$r['description']),
						'device'=>$this->get_device($r['application_object_name'],$devices),
						'profile_image_url'=>"https://graph.facebook.com/".$r['from_object_id']."/picture",
						'like'=>number_format($r['likes_count']),
						'flag'=>$flag);
		}
		unset($rs);
		return array("feeds"=>$posts,"total_rows"=>intval($rows['total']));
	}
	private function site_feeds($campaign_id,$group_type,$dt,$start,$limit=20){
		$start = intval($start);
		$group_type = intval($group_type);
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		//timestamps
		$created_time_ts = ($dt." 00:00:00");
		$ts_until = ($dt." 23:59:59");
		//check campaign language setting. is it restrict to specific language ?
		$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		//if($campaign['lang']=="all"){
			$sql = "SELECT feed_id, link,author_name, author_uri, comments, title, summary, 
			published,published_ts,  
			DATE_FORMAT(FROM_UNIXTIME(published_ts),'%d/%m/%Y') AS published_date,
			source_service,generator
			FROM smac_report.campaign_web_feeds 
			WHERE 
			campaign_id={$campaign_id} 
			AND group_type_id = {$group_type} 
			AND published_ts BETWEEN UNIX_TIMESTAMP('{$created_time_ts}') 
			AND UNIX_TIMESTAMP('{$ts_until}')
			ORDER BY rank ASC LIMIT {$start},{$limit}";
			
			//print $sql;
			
			$sql2 = "SELECT COUNT(*) as total
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} 
			AND group_type_id = {$group_type} 
			 AND published_ts BETWEEN UNIX_TIMESTAMP('{$created_time_ts}') 
			AND UNIX_TIMESTAMP('{$ts_until}')
			LIMIT 1";
			
		
		
		$result = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$rs = array();
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				//check for flag
				$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],3);
				//-->end of check
				if($r['author_name']=="unknown"){
					$r['author_name'] = $r['author_uri'];
				}
				$rs[] = array('id'=>$r['feed_id'],
							  'url'=>$r['author_uri'],
							'link'=>$r['link'],
				'name'=>clean_ascii($r['author_name']),
				'title'=>clean_ascii($r['title']),
				//'published'=>date("d/m/Y",$r['published_ts']),
				'published'=>$r['published_date'],
				'txt'=>clean_ascii($r['summary']),
				'device'=>$this->get_device($r['generator'],$devices),
				'comments'=>number_format($r['comments']),
				'flag'=>$flag
				);
			}
		}
		$result = null;
		return array("feeds"=>$rs,"total_rows"=>intval($rows['total']));
	}
	private function web_feeds($campaign_id,$dt,$start,$limit=20){
		$start = intval($start);
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		//timestamps
		$created_time_ts = ($dt." 00:00:00");
		$ts_until = ($dt." 23:59:59");
		//check campaign language setting. is it restrict to specific language ?
		$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		//if($campaign['lang']=="all"){
			$sql = "SELECT feed_id, link,author_name, author_uri, comments, title, summary, 
			published,published_ts,  
			DATE_FORMAT(FROM_UNIXTIME(published_ts),'%d/%m/%Y') AS published_date,
			source_service,generator
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND published_ts BETWEEN UNIX_TIMESTAMP('{$created_time_ts}') 
			AND UNIX_TIMESTAMP('{$ts_until}')
			ORDER BY rank ASC LIMIT {$start},{$limit}";
			
			//print $sql;
			
			$sql2 = "SELECT COUNT(*) as total
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} 
			 AND published_ts BETWEEN UNIX_TIMESTAMP('{$created_time_ts}') 
			AND UNIX_TIMESTAMP('{$ts_until}')
			LIMIT 1";
			
		/*}else{
			$sql = "SELECT feed_id, link,author_name, author_uri, comments, title, summary, 
			published,published_ts, source_service,generator
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id}
			AND lang='{$campaign['lang']}' 
			AND published_ts BETWEEN '{$created_time_ts}' AND '{$ts_until}'
			ORDER BY rank ASC LIMIT {$start},{$limit}";
			
			$sql2 = "SELECT COUNT(*) as total
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} 
			AND lang='{$campaign['lang']}'
			 AND published_ts BETWEEN '{$created_time_ts}' AND '{$ts_until}'
			LIMIT 1";
			
		}*/
		
		$result = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$rs = array();
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				//check for flag
				$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],3);
				//-->end of check
				if($r['author_name']=="unknown"){
					$r['author_name'] = $r['author_uri'];
				}
				$rs[] = array('id'=>$r['feed_id'],
							  'url'=>$r['author_uri'],
							'link'=>$r['link'],
				'name'=>clean_ascii($r['author_name']),
				'title'=>clean_ascii($r['title']),
				//'published'=>date("d/m/Y",$r['published_ts']),
				'published'=>$r['published_date'],
				'txt'=>clean_ascii($r['summary']),
				'device'=>$this->get_device($r['generator'],$devices),
				'comments'=>number_format($r['comments']),
				'flag'=>$flag
				);
			}
		}
		$result = null;
		return array("feeds"=>$rs,"total_rows"=>intval($rows['total']));
	}
	private function video_feeds($campaign_id,$dt,$start,$limit=20){
		$start = intval($start);
	
		$sql = "SELECT author_id,feed_id,author_uri,video_url,title,description,preview_url,
				duration,likes_count,view_count,rate_avg,rate_count,country_code,published_date
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id} 
				AND published_date 
				BETWEEN '{$dt} 00:00:00' AND '{$dt} 23:59:59'
				LIMIT {$start},{$limit}";
		
		$sql2 = "SELECT COUNT(*) as total
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id} 
				AND published_date 
				BETWEEN '{$dt} 00:00:00' AND '{$dt} 23:59:59'
				LIMIT 1";
		
		
		$result = $this->fetch($sql,1);
		
		$rows = $this->fetch($sql2);
		$rs = array();
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				//check for flag
				$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],4);
				//-->end of check
				
				$rs[] = array('id'=>$r['feed_id'],
							  'url'=>$r['video_url'],
				'pic'=>$r['preview_url'],
				'name'=>clean_ascii($r['author_id']),
				'title'=>clean_ascii($r['title']),
				'published'=>$r['published_date'],
				'txt'=>clean_ascii($r['title']),
				'device'=>'',
				'comments'=>number_format($r['comments']),
				'flag'=>$flag,
				'channel'=>'video'
				);
			}
		}
		$result = null;
		return array("feeds"=>$rs,"total_rows"=>intval($rows['total']));
	}
	public function video_comments($campaign_id,$feed_id,$start,$limit=20){
		$start = intval($start);
		$feed_id = intval($feed_id);
		

		
		$sql = "SELECT id,campaign_youtube_id AS video_id,title,content,author_id,published_date 
				FROM smac_youtube.video_comments 
				WHERE campaign_id={$campaign_id} AND campaign_youtube_id = {$feed_id}
				LIMIT {$start},{$limit}";
				
				
				
		$sql2 = "SELECT COUNT(id) as total
				FROM smac_youtube.video_comments 
				WHERE campaign_id={$campaign_id} AND campaign_youtube_id = {$feed_id}
				LIMIT 1";
		
		
		$result = $this->fetch($sql,1);
		
		$rows = $this->fetch($sql2);
		$rs = array();
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				//check for flag
				$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],4);
				//-->end of check
				$rs[] = array('id'=>$r['id'],
							  'video_id'=>$r['video_id'],
				'name'=>clean_ascii($r['author_id']),
				'title'=>clean_ascii($r['title']),
				'published'=>$r['published_date'],
				'txt'=>clean_ascii($r['content'])
				);
			}
		}
		$result = null;
		return array("feeds"=>$rs,"total_rows"=>intval($rows['total']));
	}
	
}
?>