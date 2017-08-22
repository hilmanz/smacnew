<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
class livetrack_model extends base_model{
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
	}
	function stats($campaign_id){
		
		$data = array("daily_volume"=>$this->daily_volume($campaign_id),
					  "issues"=>$this->issues($campaign_id),
					  "people"=>$this->people($campaign_id),
					  "country"=>$this->country($campaign_id)
					  );
		
		return $data;
	}
	function daily_volume($campaign_id,$limit=12){
		$sql = "SELECT * FROM (SELECT id,
				SUM(total_twitter) as twitter,
				SUM(total_facebook) as facebook,
				SUM(total_web) as web,
				dtpost as the_date,
				hour as the_hour 
				FROM smac_report.hourly_volume_post 
				WHERE campaign_id={$campaign_id}
				GROUP BY campaign_id,the_date,the_hour
				ORDER BY id DESC
				LIMIT {$limit}) a 
				ORDER BY the_date ASC,the_hour ASC;
		";
		$stat = $this->fetch($sql,1);
		return $stat;
	}
	function issues($campaign_id,$limit=5){
		$sql = "SELECT keyword, occurance 
				FROM smac_report.top_rule_wordcloud_summary 
				where campaign_id = {$campaign_id} AND keyword IS NOT NULL
				group by keyword
				order by occurance desc
				limit {$limit};";
		$issues = $this->fetch($sql,1);
		return $issues;
	}
	function people($campaign_id,$limit=5){
		$sql = "SELECT author_id,channel,
					SUM(occurance) AS total_mention,
					SUM(impression) AS total_impression
				FROM 
					smac_report.daily_campaign_authors 
				WHERE 
					campaign_id={$campaign_id}
				GROUP BY 
					author_id
				ORDER BY 
					total_impression DESC,
					total_mention DESC 
				LIMIT 
					{$limit};";
		$result = $this->fetch($sql,1);
		for($i=0;$i<sizeof($result);$i++){
			//foreach people, we need to retrieve their fullname and picture
			if($result[$i]['channel']==1){
				//twitter
				$profile = $this->fetch("SELECT author_name,author_avatar FROM smac_report.campaign_author_daily_stats 
										WHERE campaign_id={$campaign_id} AND author_id='{$result[$i]['author_id']}' LIMIT 1;");
				$result[$i]['author_name'] = $profile['author_name'];
				$result[$i]['author_avatar'] = $profile['author_avatar'];
			}else if($result[$i]['channel']==2){
				//fb
				$pic = "https://graph.facebook.com/{$result[$i]['author_id']}/picture";
				$profile = $this->fetch("SELECT author_name,'' AS author_avatar 
											FROM 
											smac_fb.daily_fb_people_stat 
											WHERE author_id='{$rs[$i]['author_id']}' 
											LIMIT 1;");
				$profile['author_avatar'] = $pic;
				$result[$i]['author_name'] = $profile['author_name'];
				$result[$i]['author_avatar'] = $profile['author_avatar'];
			}else{
				//web
				$result[$i]['author_name'] = $result[$i]['author_id'];
				$result[$i]['author_avatar'] = "";
			}
			//
		}
		//fb
		//SELECT * FROM smac_fb.daily_fb_people_stat WHERE author_id='100002962341562' LIMIT 1;
		//-->
		return $result;
	}
	function country($campaign_id,$limit=5){
		$sql = "SELECT a.country_id,b.country,SUM(total_mention) as occurance 
				FROM smac_report.daily_country_volume a
				INNER JOIN smac_data.geo_country b
				ON a.country_id = b.iso
				WHERE campaign_id={$campaign_id}
				GROUP BY country_id
				ORDER BY occurance DESC 
				LIMIT {$limit}";
		$country = $this->fetch($sql,1);
		return $country;
	}
	function getCountryName($geo){
		$sql = "SELECT country FROM smac_data.geo_country WHERE iso='{$geo}' LIMIT 1;";
		$r = $this->fetch($sql);
		return $r['country'];
	}
	
	function recent_posts($campaign_id,$start,$total=10){
		$start = intval($start);
		$total = intval($total);
		if($total>10){
			$total = 10;
		}
		$sql = "SELECT id,feed_id,dtpost,author,author_id,content,channel 
				FROM smac_report.latest_conversation 
				WHERE campaign_id={$campaign_id}
				ORDER BY id DESC LIMIT {$start},{$total}";
		$result = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(id) as total 
				FROM smac_report.latest_conversation 
				WHERE campaign_id={$campaign_id}
				";
		$rows = $this->fetch($sql);
		for($i=0;$i<sizeof($result);$i++){
			//foreach people, we need to retrieve their fullname and picture
			//check for flag
			$result[$i]['flag'] = $this->is_workflow_flag($campaign_id,$result[$i]['feed_id'],$result[$i]['channel']);
			//-->end of check
			if($result[$i]['channel']==1){
				//twitter
				$profile = $this->fetch("SELECT author_name,author_avatar FROM smac_report.campaign_author_daily_stats 
										WHERE campaign_id={$campaign_id} AND author_id='{$result[$i]['author_id']}' LIMIT 1;");
				$result[$i]['author_avatar'] = $profile['author_avatar'];
				$c = array("subdomain"=>$this->request->getParam('subdomain'),
					 'page' => 'workflow','act'=>'flag',
					 'keyword'=>'N/A',
					 'feed_id'=>trim($result[$i]['feed_id']),'opt'=>1,'ajax'=>1,'type'=>2);
				$reply_url = str_replace("req=","",$this->request->encrypt_params($c));
				$result[$i]['reply_url'] = $reply_url;
				
			}else if($result[$i]['channel']==2){
				//fb
				$pic = "https://graph.facebook.com/{$result[$i]['author_id']}/picture";
				$profile = $this->fetch("SELECT author_name,'' AS author_avatar 
											FROM 
											smac_fb.daily_fb_people_stat 
											WHERE author_id='{$rs[$i]['author_id']}' 
											LIMIT 1;");
				$profile['author_avatar'] = $pic;
				$result[$i]['author_avatar'] = $profile['author_avatar'];
				
			}else{
				//web
				$result[$i]['author_avatar'] = "";
			}
			//
		}
		return array('feeds'=>$result,'total_rows'=>$rows['total']);
	}
	function map_feeds($campaign_id,$feeds,$start=0,$limit=10){
		$feeds = mysql_escape_string(cleanXSS($feeds));
		$sql = "SELECT author_id,author_name,author_avatar,content,published_datetime 
				FROM smac_feeds.campaign_feeds_{$campaign_id} 
				WHERE id IN ({$feeds}) LIMIT {$start},{$limit}";
		$rs = $this->fetch($sql,1);
		return array("feeds"=>$rs,"total_rows"=>sizeof(explode(",",$feeds)));
	}
	function map_data($campaign_id,$since_id=0,$limit=1000){
		$feeds = mysql_escape_string(cleanXSS($feeds));
		$sql = "SELECT id,coordinate_x as lat,coordinate_y as lon 
				FROM smac_feeds.campaign_feeds_{$campaign_id}
				WHERE id>{$since_id} AND (coordinate_x <> 0 OR coordinate_y <> 0) 
				ORDER BY id ASC LIMIT {$limit}";
		$rs = @$this->fetch($sql,1);
		
		$sql = "SELECT total_feeds as total FROM smac_cardinal.campaign_feeds_location_count
				WHERE campaign_id = {$campaign_id};";
		$rows = $this->fetch($sql);
		return array("nodes"=>$rs,"total_rows"=>intval($rows['total']));
	}
}
?>