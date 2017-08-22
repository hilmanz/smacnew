<?php
class TopicSummary{
	var $conn;
	var $campaign_id;
	var $client_id;
	var $lang;
	var $campaign;
	var $keywords = "";
	function __construct($conn){
		$this->conn = $conn;
	}
	function set_campaign_id($campaign_id){
		$this->campaign_id = $campaign_id;
		
		$sql = "SELECT twitter_account,campaign_start,campaign_end 
				FROM smac_web.tbl_campaign 
				WHERE id={$campaign_id} LIMIT 1";
		$this->campaign = fetch($sql,$this->conn);
	}
	function set_keywords($keywords){
		$this->keywords = $keywords;
	}
	function set_client_id($client_id){
		$this->client_id = $client_id;
	}
	function set_language($lang){
		$this->lang = $lang;
	}
	function topic_summary($tgl_start=null,$tgl_end=null){
		
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		
		
		$campaign = $this->campaign;
		
		if($lang=='all'){
			
			if($tgl_start==null&&$tgl_end==null){
				$tgl_start = $campaign['campaign_start'];
				$tgl_end = $campaign['campaign_end'];
			}
			$sql = "SELECT campaign_id,published_date,
					SUM(mentions) as mentions,SUM(people_mentioned),SUM(impressions) as imp,SUM(rt_impression) as rt_imp,
					SUM(people),SUM(rt_people)
					FROM smac_report.campaign_daily_stats 
					WHERE campaign_id={$campaign_id} AND lang='all'
					AND published_date >='{$tgl_start}' AND published_date<='{$tgl_end}';";
			
			$sql2 = "SELECT SUM(b.pii) as total FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.campaign_feed_sentiment b
				ON a.id = b.feed_id
				WHERE a.campaign_id={$campaign_id} 
				AND published_date >='{$tgl_start}' 
				AND published_date<='{$tgl_end}';";
		}else{
			/*
			$sql = "SELECT potential_impact_score,mentions,sentiment_positive,sentiment_negative,true_reach 
			FROM smac_report.dashboard_summary_lang 
			WHERE campaign_id='".$campaign_id."' AND client_id='$client_id' LIMIT 1;";
			*/	
		}
		
		$data = fetch($sql,$this->conn);
		$pii = fetch($sql2,$this->conn);
		
		$data['true_reach'] = $data['imp']+$data['rt_imp'];
		
		$data['pii_score'] = round(($pii['total']/$data['mentions']),2);
		$data['potential_impact_score'] = $pii['total'];
		//$data['pii_score'] = round(($data['potential_impact_score']/$data['mentions']),2);
		return array("action"=>"topic_summary","status"=>1,"message"=>"OK","data"=>$data);
	}
	function top_keywords($tgl_start=null,$tgl_end=null){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		$campaign = $this->campaign;
		if($tgl_start==null&&$tgl_end==null){
				$tgl_start = $campaign['campaign_start'];
				$tgl_end = $campaign['campaign_end'];
		}
		/*
		$sql = "SELECT keyword,occurance,total_people FROM smac_report.campaign_top_keywords 
				WHERE campaign_id=".$campaign_id." ORDER BY occurance DESC";
		$data = fetch_many($sql,$this->conn);
		*/
		$sql = "SELECT keyword,occurance FROM 
				(SELECT campaign_id,keyword,SUM(occurance) as occurance 
					FROM smac_report.campaign_keyword_history
					WHERE campaign_id={$campaign_id} 
					AND published_date >= '{$tgl_start}' AND published_date <= '{$tgl_end}' 
					AND
					keyword NOT IN (SELECT kata FROM smac_data.tb_stop)
										GROUP BY keyword) a
									ORDER BY occurance DESC
									LIMIT 50";
		$data =fetch_many($sql,$this->conn);
		$keywords='';
		foreach($data as $n=>$v){
			if($n>0){
				$keywords.=",";
			}
			$keywords.="'{$v['keyword']}'";
		}
		$rs = array("action"=>"topic_summary","status"=>1,"message"=>"OK","data"=>$data);
		$data = null;
		return $rs;
	}
	function getTotalPeoplePerKeyword($tgl_start=null,$tgl_end=null){
		$keyword = (stripslashes($this->keywords));
		$campaign_id = $this->campaign_id;
		if(strlen($keyword)>0){
			$campaign = $this->campaign;
			if($tgl_start==null&&$tgl_end==null){
					$tgl_start = $campaign['campaign_start'];
					$tgl_end = $campaign['campaign_end'];
			}
			$sql = "SELECT keyword,COUNT(author_id) as total_people FROM (SELECT keyword,author_id
						FROM smac_report.campaign_feeds a
						INNER JOIN smac_report.feed_wordlist b
						ON a.id=b.fid
						WHERE a.campaign_id={$campaign_id} AND a.published_date >='{$tgl_start}'
	                                        AND a.published_date <= '{$tgl_end}'
						AND b.keyword IN ({$keyword})
						GROUP BY keyword,author_id) a
						GROUP BY keyword;";
			$rs = fetch_many($sql,$this->conn);
			$response = array("action"=>"topic_summary","status"=>1,"message"=>"OK","data"=>$rs);
		}else{
			$response = array("action"=>"topic_summary","status"=>0,"message"=>"EMPTY");
		}
		return $response;
	}
	function top_keyword_conversation($tgl_start=null,$tgl_end=null){
		//
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		
		$campaign = $this->campaign;
		if($tgl_start==null&&$tgl_end==null){
			$tgl_start = $campaign['campaign_start'];
			$tgl_end = $campaign['campaign_end'];
		}
		
		
		//$sql = "SELECT true_reach FROM smac_report.dashboard_summary WHERE campaign_id=".$campaign_id." LIMIT 1";
		/*$sql = "SELECT SUM(impressions) as imp,SUM(rt_impression) as rt_imp
					FROM smac_report.campaign_daily_stats 
					WHERE campaign_id={$campaign_id} AND lang='all'
					AND published_date >='{$tgl_start}' AND published_date<='{$tgl_end}';";
		
		$campaign = fetch($sql,$this->conn);
		$campaign['true_reach'] = $campaign['imp'] + $campaign['rt_impression'];
		*/
		//5 top keywordsnya : 
		
		$sql = "SELECT n_status FROM smac_report.tbl_feed_wordlist_flag WHERE campaign_id={$campaign_id} LIMIT 1";
		$row = $this->fetch($sql);
		$n_status = intval($row['n_status']);
		$row = null;
		
		if($n_status==1){
			$databank = "smac_word.campaign_words_databank_".$campaign_id;
		}else{
			$databank = "smac_report.campaign_words_databank";
		}
		
		$sql = "SELECT keyword
				FROM smac_report.campaign_keyword_history WHERE campaign_id={$campaign_id}
				AND published_date >= '{$tgl_start}' AND published_date <= '{$tgl_end}'
				GROUP BY keyword
				ORDER BY occurance DESC
				LIMIT 5;";
		
		$keywords = fetch_many($sql,$this->conn);
		
		foreach($keywords as $k){
			$keyword = mysql_escape_string($k['keyword']);
			$data[$keyword] = array();
			$sql = "SELECT author_id,author_avatar,author_name,'{$keyword}' as keyword,content,followers as impression
					FROM smac_report.campaign_feeds a
					INNER JOIN {$databank} b
					ON a.id = b.fid
					WHERE a.campaign_id={$campaign_id} 
					AND a.published_date >= '{$tgl_start}' 
					AND a.published_date <= '{$tgl_end}'
					AND b.keyword='{$keyword}'
					GROUP BY a.feed_id
					ORDER BY followers DESC
					LIMIT 10;";
			$data[$keyword] = fetch_many($sql,$this->conn);
		}
		/*$sql = "SELECT author_id,author_name,author_avatar,content,keyword,impression
				FROM smac_report.campaign_topword_conversation 
				WHERE campaign_id=".$campaign_id." ORDER BY keyword";
		
		$rs = fetch_many($sql,$this->conn);
		
		
		foreach($rs as $r){
			if(@$data[$r['keyword']]==null){
				@$data[$r['keyword']] = array();
			}
			 array_push($data[$r['keyword']],$r);
		}
		*/
		return array("action"=>"top_keyword_conversation","status"=>1,"message"=>"OK","data"=>$data,"true_reach"=>$campaign['true_reach']);
	}
	function top_influencers($tgl_start=null,$tgl_end=null){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		$campaign = $this->campaign;
		if($tgl_start==null&&$tgl_end==null){
			$tgl_start = $campaign['campaign_start'];
			$tgl_end = $campaign['campaign_end'];
		}
		
		$sql = "SELECT SUM(impressions) as imp,SUM(rt_impression) as rt_imp
					FROM smac_report.campaign_daily_stats 
					WHERE campaign_id={$campaign_id} AND lang='all'
					AND published_date >='{$tgl_start}' AND published_date<='{$tgl_end}';";
		
		$campaign = fetch($sql,$this->conn);
		$campaign['true_reach'] = $campaign['imp'] + $campaign['rt_impression'];
		
		
		$dumps = array();
		$sql = "SELECT a.author_id,b.author_name as author,a.author_avatar as pic,a.sentiment,a.pii_score,
				SUM(total_mentions) as total_mentions,SUM(imp) as impression,SUM(rt_imp) as rt_impression,
				SUM(rt_mention) as rt_mention 
				FROM 
                smac_report.campaign_people_daily_stats b
                INNER JOIN 
                smac_report.campaign_ambas a
				ON a.author_id = b.author_id
				WHERE a.campaign_id = {$campaign_id}
				AND b.campaign_id={$campaign_id}
				AND b.published_date >='{$tgl_start}' 
  				AND b.published_date <= '{$tgl_end}'
				GROUP BY b.author_id
				ORDER BY sentiment DESC LIMIT 25";
		
		$ambas = fetch_many($sql,$this->conn);
		foreach($ambas as $n=>$v){
			$ambas[$n]['share'] = round((($ambas[$n]['impression']+$ambas[$n]['rt_impression'])/$campaign['true_reach'])*100,6);
		}
		
		$sql = "SELECT a.author_id,b.author_name as author,a.author_avatar as pic,a.sentiment,a.pii_score,
				SUM(total_mentions) as total_mentions,SUM(imp) as impression,SUM(rt_imp) as rt_impression,
				SUM(rt_mention) as rt_mention 
				FROM 
                smac_report.campaign_people_daily_stats b
                INNER JOIN 
                smac_report.campaign_trolls a
				ON a.author_id = b.author_id
				WHERE a.campaign_id = {$campaign_id}
				AND b.campaign_id={$campaign_id}
				AND b.published_date >='{$tgl_start}' 
  				AND b.published_date <= '{$tgl_end}'
				GROUP BY b.author_id
				ORDER BY sentiment DESC LIMIT 25";
		
		$troll = fetch_many($sql,$this->conn);
		
		foreach($troll as $n=>$v){
			$troll[$n]['share'] = round((($troll[$n]['impression']+$troll[$n]['rt_impression'])/$campaign['true_reach'])*100,6);
		}
		
		$data = array("ambassador"=>$ambas,"troll"=>$troll);
		return array("action"=>"top_influencers","status"=>1,"message"=>"OK","data"=>$data);
	}
	function top_100_posts(){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		$sql = "SELECT true_reach FROM smac_report.dashboard_summary WHERE campaign_id=".$campaign_id." LIMIT 1";
		$campaign = fetch($sql,$this->conn);
		
		$sql = "SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, published_date,followers as imp,generator,content
				FROM (SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
				FROM smac_report.campaign_feeds a
				INNER JOIN smac_data.tbl_rt_content b
				ON a.feed_id = b.feed_id
				WHERE a.campaign_id=".$campaign_id."
				GROUP BY b.feed_id) a
				ORDER BY rt_total DESC LIMIT 100";
		
		$rs = fetch_many($sql,$this->conn);
		return array("action"=>"top_100_posts","status"=>1,"message"=>"OK","data"=>$rs,"true_reach"=>$campaign['true_reach']);
	}
	function key_issues($tgl_start=null,$tgl_end=null){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		
		$campaign = $this->campaign;
		if($tgl_start==null&&$tgl_end==null){
			$tgl_start = $campaign['campaign_start'];
			$tgl_end = $campaign['campaign_end'];
		}
		
		$sql = "SELECT a.keyword,a.occurance,c.weight as sentiment 
				FROM smac_report.campaign_keyword_history a
				INNER JOIN smac_report.campaign_words b
				ON a.keyword = b.keyword
				INNER JOIN smac_report.campaign_sentiment_setup c
				ON c.keyword_id = b.id
				WHERE a.campaign_id={$campaign_id}
				AND a.campaign_id = b.campaign_id
				AND published_date >= '{$tgl_start}'
				AND published_date <= '{$tgl_end}'
				AND c.weight <> 0
				GROUP BY keyword
				ORDER BY occurance DESC
				LIMIT 10;";
		
		$data = fetch_many($sql,$this->conn);
		
		return array("action"=>"key_issues","status"=>1,"message"=>"OK","data"=>$data);
	}
	function brand_tweets($campaign_id,$account,$tgl_start,$tgl_end){
		$sql = "SELECT campaign_id,feed_id,author_id,followers as impression,0 as rt,0 as rt_imp,content,published_date 
				FROM smac_report.campaign_feeds 
				WHERE campaign_id=".$campaign_id." 
				AND published_date >= '{$tgl_start}' 
				AND published_date <= '{$tgl_end}'
				AND author_id='".mysql_escape_string($account)."'
				ORDER BY impression DESC
				LIMIT 10";
		$rows1 = fetch_many($sql,$this->conn);
		$str = "";
		$n=0;
		foreach($rows1 as $row){
			if($n==1){
					$str.=",";
			}
			$str.=$row['feed_id'];
			$n=1;
		}
		$sql = "SELECT id,feed_id,SUM(rt_author_followers) as rt_imp,COUNT(feed_id) as rt
						FROM smac_data.tbl_rt_content 
						WHERE feed_id IN (".$str.")
						GROUP BY feed_id 
						LIMIT 10";
		$rows2 = fetch_many($sql,$this->conn);
		foreach($rows1 as $n=>$v){
			foreach($rows2 as $row2){
				if($row2['feed_id']==$rows1[$n]['feed_id']){
					$rows1[$n]['rt'] = $row2['rt'];
					$rows1[$n]['rt_imp'] = $row2['rt_imp'];
				}
			}
		}
		$rows2 = null;
		return $rows1;
	}
	function branded_account_report($tgl_start=null,$tgl_end=null){
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		
		$campaign = $this->campaign;
		if($tgl_start==null&&$tgl_end==null){
			$tgl_start = $campaign['campaign_start'];
			$tgl_end = $campaign['campaign_end'];
		}
		
		$accounts = explode(",",$campaign['twitter_account']);
		
		
		if(sizeof($accounts)>0){
		
			foreach($accounts as $account){
				
				if(strlen($account)>0){
					$account = str_replace("@","",trim($account));
					
					$authors[] = array("author_id"=>$account);
					
				}
				
			}
		}
		
		/*
		 $sql = "SELECT author_id FROM smac_report.campaign_brand_feeds
				WHERE campaign_id=".$campaign_id." GROUP BY author_id";
		*/
		//$authors = fetch_many($sql,$this->conn);
		foreach($authors as $n=>$v){
			$author_id = $v['author_id'];
			
			$sql = "SELECT SUM(mentions) as total_mentions,SUM(impressions) as imp,SUM(rt_impression) as rt_imp
					FROM smac_report.campaign_daily_stats 
					WHERE campaign_id={$campaign_id} AND lang='all'
					AND published_date >='{$tgl_start}' AND published_date<='{$tgl_end}';";
		
			$campaign = fetch($sql,$this->conn);
			$total_impression = $campaign['imp'] + $campaign['rt_impression'];
			$total_mentions = $campaign['total_mentions'];
			$campaign = null;
			$rs = null;
			
			$sql = "SELECT author_id,FLOOR(SUM(followers)/COUNT(author_id)) as followers 
						FROM smac_report.campaign_feeds WHERE campaign_id=".$campaign_id." 
								AND author_id='".mysql_escape_string($author_id)."' GROUP BY author_id LIMIT 1";
			
			$rs = fetch($sql,$this->conn);
			$followers = $rs['followers'];
			$rs = null;
			//retrieve stats
			
			
			/*$sql = "SELECT total_mentions,impression,rt_impression,
					rt_mention,rank
					FROM smac_report.campaign_people_summary a
					INNER JOIN smac_report.campaign_people_rank b
					ON a.id = b.ref_id
					WHERE a.campaign_id=".$campaign_id." 
					AND a.author_id='".mysql_escape_string($author_id)."' 
					LIMIT 1";
			
			$rs = fetch($sql,$this->conn);
			$rs['total_impression'] = ($rs['impression']+$rs['rt_impression']);
			$rs['share_percentage'] = round(($rs['total_impression'] / $total_impression) * 100,2);
			$rs['overall_impression'] = $total_impression;
			$rs['rt_percentage'] = round($rs['rt_mention'] / ($rs['rt_mention']+$rs['total_mentions']) * 100,2);
			$rs['followers'] = $followers;
			*/
			
			$rs = null;
			
			$rs = $this->brand_tweets($campaign_id,$account,$tgl_start,$tgl_end);
			
			$stats = array();
			$stats['total_impression'] = 0;
			$stats['total_mentions'] = 0;
			$stats['share_percentage'] = 0;
			$stats['overall_impression'] = 0;
			$stats['rt_percentage'] = 0;
			$stats['rt_mention'] = 0;
			$stats['impression'] = 0;
			$stats['rt_impression'] = 0;
			$stats['followers'] = $followers;
			foreach($rs as $nn=>$v){
				
				$stats['total_impression'] += ($v['impression']+$v['rt_imp']);
				$rs[$nn]['imp'] = ($v['impression']+$v['rt_imp']);
				$stats['overall_impression'] = $stats['total_impression'];
				$stats['impression']  +=$v['imp'];
				$stats['rt_impression']  +=$v['rt_imp'];
				$stats['rt_mention'] += $v['rt'];
				$rs[$nn]['share'] = round((($v['impression']+$v['rt_imp'])/$total_impression) * 100,5);
			}
			$stats['total_mentions'] = $total_mentions;
			$stats['rt_percentage'] = round($stats['rt_mention'] / $total_mentions*100,5);
			$stats['share_percentage'] = round($stats['total_impression']/$total_impression*100,5);
			$authors[$n]['tweets'] = $rs;
			$authors[$n]['stats'] = $stats;
			
			$rs = null;
			
			
		}	
		return array("action"=>"branded_account_report","status"=>1,"message"=>"OK","data"=>$authors);
	}
	function top_50_links($tgl_start=null,$tgl_end=null){
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		
		$campaign = $this->campaign;
		if($tgl_start==null&&$tgl_end==null){
			$tgl_start = $campaign['campaign_start'];
			$tgl_end = $campaign['campaign_end'];
		}
		
		$sql = "SELECT campaign_id,a.feed_id,url,followers as impression FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.feed_links b
				ON a.feed_id = b.feed_id
				WHERE a.campaign_id={$campaign_id} 
				AND published_date >='{$tgl_start}' AND published_date <='{$tgl_end}'
				ORDER BY impression DESC LIMIT 50;";
		$rs = fetch_many($sql,$this->conn);
		$response = array("action"=>"top_50_links","status"=>1,"message"=>"OK","data"=>$rs);
		$rs = null;
		return $response;
	}
}
?>