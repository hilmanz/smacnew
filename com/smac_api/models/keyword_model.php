<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
class keyword_model extends base_model{
	var $twitter_kols;
	var $fb_kols;
	var $web_kols;
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
	}
	function summary_by_rule($campaign_id,$from,$to){
		$this->open(0);
		$data = array("daily_volume"=>$this->daily_volume($campaign_id, $from, $to),
					  "post"=>$this->top_post($campaign_id, $from, $to),
					  "impression"=>$this->top_impression($campaign_id, $from, $to),
					  "pii"=>$this->daily_pii($campaign_id, $from, $to),
					  "quadrant"=>$this->quadrant($campaign_id, $from, $to),
					  "interactivity"=>$this->interactivity($campaign_id, $from, $to),
					  "rt"=>$this->top_rt($campaign_id, $from, $to),
					  "sentiment"=>$this->daily_sentiment($campaign_id, $from, $to)
					  );
		$this->close();
		return $data;
	}
	/**
	 * @notes
	 * we keep pii and sentiment as same as summary by rule.  
	 */
	function summary_by_top_keywords($campaign_id,$type,$from,$to){
		$this->open(0);
		$type = intval($type);
		switch($type){
			case 2:
				$top = 50;
			break;
			default:
				$top = 10;
			break;
		}
		$sql = "SELECT * FROM (SELECT keyword,SUM(mention) as mention,SUM(rt_mention) as rt_mention,SUM(impression) as imp,
				SUM(rt_impression) as rt_imp 
				FROM smac_report.campaign_top50_daily 
				WHERE campaign_id=".$campaign_id." 
				AND published_date >='".$from."' 
				AND published_date <='".$to."' 
				GROUP BY keyword) a 
				ORDER BY mention DESC LIMIT ".$top;
		
		$results = $this->fetch($sql,1);
		
		if(sizeof($results)==0){
			return array();
		}
		$post = array();
		$impression = array();
		$rt = array();
		
		
		foreach($results as $n=>$v){
			$post[] = array("label"=>$v['keyword'],"keyword"=>$v['keyword'],"total_mention"=>$v['mention']);
			$impression[] = array("label"=>$v['keyword'],"keyword"=>$v['keyword'],"impression"=>$v['imp']+$v['rt_imp']);
			$rt[] = array("label"=>$v['keyword'],"keyword"=>$v['keyword'],"retweets"=>$v['rt_mention']);
		}
		$keywords = null;
		unset($keywords);
		$data = array("post"=>$post,
					  "impression"=>$impression,
					  "pii"=>$this->daily_pii($campaign_id,$from, $to),
					  "rt"=>$rt,
					   "quadrant"=>$this->quadrant($campaign_id, $from, $to),
					  "sentiment"=>$this->daily_sentiment($campaign_id,$from, $to)
					  );
		$this->close();
		return $data;
	}
	function quadrant($campaign_id,$from,$to){
		$datefilter = "";
		
		if($from!=null&&$to!=null){
			$datefilter = "AND dcs.dtreport BETWEEN '{$from}' AND '{$to}'";
		}
		$sql = "SELECT campaign_name FROM smac_web.tbl_campaign WHERE id={$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		$sql = "SELECT round(sum_pii/(total_mention_positive+total_mention_negative),2) as pii
				FROM smac_report.daily_campaign_sentiment as dcs
				WHERE campaign_id = {$campaign_id}
					    AND (total_mention_positive+total_mention_negative) > 0 
						AND channel = 1
				{$datefilter}
				group by dtreport
				ORDER BY dtreport DESC LIMIT 1";
		$_pii = $this->fetch($sql);		
	
		//temporary solution, until we got new query from db team.
		$sql = "SELECT 
				tc.id AS campaign_id
				,SUM(dcv.total_impression_twitter) AS impression
				,SUM(total_rt_impression_twitter) AS rt_impression
			FROM 	
				smac_web.tbl_campaign tc
				INNER JOIN smac_report.daily_campaign_sentiment dcs ON dcs.campaign_id = tc.id
				INNER JOIN smac_report.campaign_rule_volume_history dcv ON tc.id = dcv.campaign_id
				AND dcs.dtreport = dcv.dtreport
			WHERE
				tc.id={$campaign_id}
				AND dcs.channel = 1
				{$datefilter}
			GROUP BY tc.id
			;";
		$quadrant = $this->fetch($sql);
		
		
		$viral_effect = @round($quadrant['rt_impression']/($quadrant['impression']+$quadrant['rt_impression'])*100,2);
		return array("id"=>$campaign_id,
					"name"=>$campaign['campaign_name'],
					"sentiment"=>floatval($_pii['pii']),
					"viral"=>floatval($viral_effect),
					"volume"=>intval($quadrant['impression']));
	}
	function interactivity($campaign_id,$from,$to){
		$datefilter = "";
		
		if($from!=null&&$to!=null){
			$datefilter = "AND dcs.dtreport BETWEEN '{$from}' AND '{$to}'";
		}
		$sql = "SELECT campaign_name FROM smac_web.tbl_campaign WHERE id={$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		$sql = "SELECT round(sum_pii/(total_mention_positive+total_mention_negative),2) as pii
				FROM smac_report.daily_campaign_sentiment as dcs
				WHERE campaign_id = {$campaign_id}
					    AND (total_mention_positive+total_mention_negative) > 0 
						AND channel = 1
				{$datefilter}
				group by dtreport
				ORDER BY dtreport DESC LIMIT 1";
		$_pii = $this->fetch($sql);		
	
		//temporary solution, until we got new query from db team.
		$sql = "SELECT 
				tc.id AS campaign_id
				,SUM(dcv.total_mention_twitter) AS mention
				,SUM(dcv.total_rt_twitter) AS rt
			FROM 	
				smac_web.tbl_campaign tc
				INNER JOIN smac_report.daily_campaign_sentiment dcs ON dcs.campaign_id = tc.id
				INNER JOIN smac_report.campaign_rule_volume_history dcv ON tc.id = dcv.campaign_id
				AND dcs.dtreport = dcv.dtreport
			WHERE
				tc.id={$campaign_id}
				AND dcs.channel = 1
				{$datefilter}
			GROUP BY tc.id
			;";
		$quadrant = $this->fetch($sql);
		
		
		$interaction_rate = @round($quadrant['rt']/($quadrant['mention'])*100,2);
		return array("id"=>$campaign_id,
					"name"=>$campaign['campaign_name'],
					"sentiment"=>floatval($_pii['pii']),
					"rate"=>floatval($interaction_rate),
					"volume"=>intval($quadrant['mention'])
					);
	}
	function top_post($campaign_id,$from,$to,$default_range=7){
		
		$sql  = "SELECT keyword_id,
		SUM(total_mention_twitter) as twitter, 
		SUM(total_mention_facebook) as facebook, 
		SUM(total_mention_web) as web
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} AND dtreport >='".$from."' 
		AND dtreport <='".$to."'
		GROUP BY keyword_id";
		$keywords = $this->fetch($sql,1);
		if(sizeof($keywords)==0){return array();}
		foreach($keywords as $n=>$v){
			$keyword_id = $v['keyword_id'];
			$sql = "SELECT a.label,b.keyword_txt as keyword 
					FROM smac_web.tbl_campaign_keyword a
					INNER JOIN smac_web.tbl_keyword_power b
					ON a.keyword_id = b.keyword_id
					WHERE a.campaign_id={$campaign_id} 
					AND a.keyword_id={$keyword_id} 
					LIMIT 1";
			
			$r = $this->fetch($sql);
			$keywords[$n]['label'] = $r['label'];
			if($r['label']==NULL){
				$keywords[$n]['label'] = reformat_rule($r['keyword']);
			}
			$keywords[$n]['keyword'] = $r['keyword'];
			$keywords[$n]['total_mention'] = intval($keywords[$n]['twitter'])+intval($keywords[$n]['facebook'])+intval($keywords[$n]['web']);
		}
		return $keywords;
	}
	function top_impression($campaign_id,$from,$to,$default_range=7){
		$sql = "SELECT a.keyword_id,SUM(total_impression_twitter) as impression,
		c.keyword_txt as keyword,b.label
		FROM smac_report.campaign_rule_volume_history a
		INNER JOIN smac_web.tbl_campaign_keyword b
		ON a.keyword_id = b.keyword_id AND b.campaign_id={$campaign_id}
		INNER JOIN smac_web.tbl_keyword_power c
		ON a.keyword_id = c.keyword_id
		WHERE a.campaign_id = {$campaign_id}
		AND dtreport BETWEEN '{$from}' AND '{$to}'
		GROUP BY a.keyword_id
		ORDER BY impression DESC LIMIT 10;";
		$keywords = $this->fetch($sql,1);
		if(sizeof($keywords)==0){return array();}
		foreach($keywords as $n=>$v){
			if($v['label']==NULL){
				$keywords[$n]['label'] = reformat_rule($v['keyword']);
			}
		}
		return $keywords;
	}
	function daily_pii($campaign_id,$from,$to,$default_range=7){
		$sql = "SELECT dtreport as tgl,SUM(sum_pii)/SUM(total_mention_positive+total_mention_negative) as pii_score
		FROM smac_report.daily_campaign_sentiment 
		WHERE campaign_id={$campaign_id} AND dtreport >='".$from."' 
			AND dtreport <='".$to."'
		GROUP BY dtreport";
		$stats =  $this->fetch($sql,1);
		return $stats;
		
	}
	function daily_sentiment($campaign_id,$from,$to,$default_range=7){
		if($from!=null&&$to!=null){
			$sql = "SELECT dtreport,SUM(total_mention_positive) as positive,SUM(total_mention_negative) as negative 
					FROM smac_report.daily_campaign_sentiment 
					WHERE campaign_id={$campaign_id} AND channel=1 AND dtreport BETWEEN '{$from}' AND '{$to}'
					GROUP BY dtreport ORDER BY dtreport DESC LIMIT 60";
		}else{
			$sql = "SELECT dtreport,SUM(total_mention_positive) as positive,SUM(total_mention_negative) as negative 
					FROM smac_report.daily_campaign_sentiment 
					WHERE campaign_id={$campaign_id} AND channel=1 
					GROUP BY dtreport ORDER BY dtreport DESC LIMIT 60";
		}
		$rs = $this->fetch($sql,1);
		$positive = array();
		$negative = array();
		
		while(sizeof($rs)>0){
			$r = array_pop($rs);
			$positive[$r['dtreport']] = intval($r['positive']);
			$negative[$r['dtreport']] = intval($r['negative']);
		}
		return array("positive"=>$positive,"negative"=>$negative);
	}
	function top_rt($campaign_id,$from,$to,$default_range=7){
		$sql = "SELECT a.keyword_id,SUM(total_rt_twitter) as retweets,
		c.keyword_txt as keyword,b.label
		FROM smac_report.campaign_rule_volume_history a
		INNER JOIN smac_web.tbl_campaign_keyword b
		ON a.keyword_id = b.keyword_id AND b.campaign_id={$campaign_id}
		INNER JOIN smac_web.tbl_keyword_power c
		ON a.keyword_id = c.keyword_id
		WHERE a.campaign_id = {$campaign_id}
		AND dtreport BETWEEN '{$from}' AND '{$to}'
		GROUP BY a.keyword_id
		ORDER BY retweets DESC LIMIT 10;";

		$keywords = $this->fetch($sql,1);
		if(sizeof($keywords)==0){return array();}
		foreach($keywords as $n=>$v){
			if($v['label']==NULL){
				$keywords[$n]['label'] = reformat_rule($v['keyword']);
			}
		}
		return $keywords;
	}
	function rule_daily($campaign_id,$rule_id,$from,$to,$default_range=7){
		$filter_date_from = $from;
		$filter_to_date = $to;
		$rule_id = intval($rule_id);
		if(intval($default_range)==1){
			//retrieve only from the last 7 days
			$sql = "SELECT dtreport FROM smac_report.campaign_rule_volume_history 
					WHERE campaign_id={$campaign_id} GROUP BY dtreport ORDER BY dtreport DESC LIMIT 7";
			$r_date = $this->fetch($sql,1);
			$filter_date_from = $r_date[sizeof($r_date)-1]['dtreport'];
			$filter_to_date = $r_date[0]['dtreport'];
		}
		
		$sql = "SELECT dtreport as published_date,'{$v['keyword']}' as keyword,
				SUM(a.total_mention_twitter+a.total_mention_facebook+a.total_mention_web) as total_mention 
				FROM smac_report.campaign_rule_volume_history a 
				WHERE a.campaign_id={$campaign_id} 
				AND keyword_id = {$rule_id} 
				AND dtreport BETWEEN '{$filter_date_from}' AND '{$filter_to_date}'
				GROUP BY dtreport;";
		
		$data = $this->fetch($sql,1);
		foreach($data as $dn=>$dv){
			@$all_dates[$dv['published_date']] = 1;
			$data[$dn]['ts'] = strtotime($dv['published_date']);
		}
		return $data;
	}
	function daily_volume($campaign_id,$from,$to,$default_range=7){
		$filter_date_from = $from;
		$filter_to_date = $to;
		
		if(intval($default_range)==1){
			//retrieve only from the last 7 days
				$sql = "SELECT dtreport FROM smac_report.campaign_rule_volume_history 
						WHERE campaign_id={$campaign_id} GROUP BY dtreport ORDER BY dtreport DESC LIMIT 7";
				$r_date = $this->fetch($sql,1);
				$filter_date_from = $r_date[sizeof($r_date)-1]['dtreport'];
				$filter_to_date = $r_date[0]['dtreport'];
		}
		
		$sql = "SELECT a.keyword_id,b.keyword_txt as keyword,
				SUM(a.total_mention_twitter+a.total_mention_facebook+a.total_mention_web) as total_mention
				FROM smac_report.campaign_rule_volume_history a
				INNER JOIN smac_web.tbl_keyword_power b
				ON a.keyword_id = b.keyword_id
				WHERE a.campaign_id={$campaign_id} 
				AND dtreport BETWEEN '{$filter_date_from}' AND '{$filter_to_date}'
				GROUP BY a.keyword_id ORDER BY total_mention DESC LIMIT 10;";
				
		$keywords = $this->fetch($sql,1);
		$all_dates = array();
		if(sizeof($keywords)==0){return array();}
		foreach($keywords as $n=>$v){
			$kw = mysql_escape_string(trim($v['keyword_id']));
			
			$sql = "SELECT dtreport as published_date,'{$v['keyword']}' as keyword,
					SUM(a.total_mention_twitter+a.total_mention_facebook+a.total_mention_web) as total_mention 
					FROM smac_report.campaign_rule_volume_history a 
					WHERE a.campaign_id={$campaign_id} 
					AND keyword_id = {$kw} 
					AND dtreport BETWEEN '{$filter_date_from}' AND '{$filter_to_date}'
					GROUP BY dtreport;";
			
			$data = $this->fetch($sql,1);
			foreach($data as $dn=>$dv){
				@$all_dates[$dv['published_date']] = 1;
				$data[$dn]['ts'] = strtotime($dv['published_date']);
			}
			$sql = "SELECT label FROM smac_web.tbl_campaign_keyword WHERE campaign_id={$campaign_id} AND keyword_id={$kw} LIMIT 1";
			$row = $this->fetch($sql);
			if(strlen($row['label'])>0){
				$keywords[$n]['keyword'] = trim(($row['label']));
			}else{
				$keywords[$n]['keyword'] = trim(translate_rule($keywords[$n]['keyword']));
			}
			$keywords[$n]['data'] = $data;
		}
		
		foreach($keywords as $m=>$d){
			$keyword = $d;
			foreach($all_dates as $tgl=>$flag){
				$is_exist = false;
				foreach($keyword['data'] as $n=>$v){
					if($v['published_date']==$tgl){
						$is_exist = true;
					}
				}
				if(!$is_exist){
					$keywords[$m]['data'][]=array('published_date'=>$tgl,'total_mention'=>0,'keyword'=>'','ts'=>strtotime($tgl));
				}
			}
			//print "yey";
			$keywords[$m]['data'] = $this->subval_sort($keywords[$m]['data'],'ts');
		}
		return $keywords;
	}
	function get_rule_breakdown($campaign_id,$from,$to){
		$sql = "SELECT a.keyword_id,keyword_txt,a.label
				FROM smac_web.tbl_campaign_keyword a
				INNER JOIN smac_web.tbl_keyword_power b
				ON a.keyword_id = b.keyword_id
				WHERE a.campaign_id={$campaign_id} LIMIT 1000";
		$keywords = $this->fetch($sql,1);
		$response = array();
		foreach($keywords as $k){
			$sql = "SELECT SUM(total_mention_twitter) as mention_twitter,
					SUM(total_mention_facebook) as mention_fb,SUM(total_mention_web) as mention_web, 
					SUM(total_people_twitter) as people_twitter,
					SUM(total_rt_twitter) as rt,SUM(total_impression_twitter) as twitter_imp,
					SUM(total_sentiment_positive) as sentiment_positive,
					SUM(total_sentiment_negative) as sentiment_negative,
					SUM(total_countries) as countries
					FROM smac_report.campaign_rule_volume_history 
					WHERE campaign_id={$campaign_id} AND 
					keyword_id={$k['keyword_id']} 
					AND dtreport BETWEEN '{$from}' AND '{$to}'
					LIMIT 1;";
			$r = $this->fetch($sql);
			
			
			$sql = "SELECT SUM(total_mention_positive) AS positive,
					SUM(total_mention_negative) AS negative 
					FROM smac_report.daily_rule_campaign_sentiment 
					WHERE campaign_id={$campaign_id} AND keyword_id={$k['keyword_id']};";
			$sentiment = $this->fetch($sql);
			
			
			
			$sql = "SELECT w.keyword, s.weight AS sentiment, SUM(w.occurance) AS total 
				FROM smac_report.top_rule_wordcloud_summary w LEFT JOIN 
				smac_sentiment.sentiment_setup_{$campaign_id} s ON w.keyword = s.keyword
				WHERE w.campaign_id= {$campaign_id} AND w.keyword_id={$k['keyword_id']}
				GROUP BY w.keyword,  s.weight
				ORDER BY total DESC
				LIMIT 100;";
				
			$wordcloud = $this->fetch($sql,1);
			
			$data = array(
							"total_mentions"=>$r['mention_twitter']+$r['mention_fb']+$r['mention_web'],
							"twitter"=>$r['mention_twitter'],
							"facebook"=>$r['mention_fb'],
							"web"=>$r['mention_web'],
							"people"=>$r['people_twitter'],
							"rt"=>$r['rt'],
							"impression"=>$r['twitter_imp'],
							"sentiment_positive"=>$sentiment['positive'],
							"sentiment_negative"=>$sentiment['negative'],
							"countries"=>$r['countries']);
			
			if(strlen($k['label'])>0){
				$rule_name = $k['label'];
			}else{
				$rule_name = $k['keyword_txt'];
			}
			$response[] = array("keyword"=>$rule_name,
									"keyword_id"=>$k['keyword_id'],
									"data"=>$data,
									"wordcloud"=>$wordcloud);
			$data = null;
			$wordcloud = null;
		}
		return $response;
	}
	function conversation($campaign_id,$start,$total=5){
		$start = intval($start);
		$this->open(0);
		$sql = "SELECT * FROM (
					SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers AS imp 
					FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} a
					INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
					ON a.feed_id = b.id						 
					WHERE  
					(a.sentiment <> 0)
				) c
				ORDER BY c.imp DESC LIMIT ".$start.",".$total;

		$sql2 = "
					SELECT COUNT(*) as total
					FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} a
					INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
					ON a.feed_id = b.id						 
					WHERE  
					(a.sentiment <> 0)
					LIMIT 1";
		
		$tweets = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$this->close();
		return array("tweets"=>$tweets,"total"=>$rows['total']);
	}
	function conversation_by_date($campaign_id,$rule_id,$dt,$start,$total=5){
		$start = intval($start);
		$this->open(0);
		$sql = "
					SELECT published_date,author_id,author_name,author_avatar,content,followers AS imp 
					FROM smac_feeds.campaign_feeds_{$campaign_id} 
								 
					WHERE  
					published_date = '{$dt}' AND tag_id={$rule_id}
					LIMIT ".$start.",".$total;

		$sql2 = "
					SELECT COUNT(*) as total
					FROM smac_feeds.campaign_feeds_{$campaign_id}
					WHERE  
					published_date = '{$dt}' AND tag_id={$rule_id}
					LIMIT 1";
		
		$tweets = $this->fetch($sql,1);
		if(sizeof($tweets)==0) $tweets = array();
		$rows = $this->fetch($sql2);
		$this->close();
		return array("posts"=>$tweets,"total"=>$rows['total']);
	}
	function fb_conversation_by_date($campaign_id,$rule_id,$dt,$start,$total=5){
		$start = intval($start);
		$dts = strtotime($dt." 00:00:00");
		$dts2 = strtotime($dt." 23:59:59");
		$this->open(0);
		$sql = "
					SELECT DATE(FROM_UNIXTIME(created_time_ts)) AS published_date,
					from_object_id AS author_id,from_object_name AS author_name,
					likes_count,caption,message,description
					FROM smac_fb.campaign_fb 
					WHERE  
					campaign_id={$campaign_id}
					AND
					created_time_ts BETWEEN {$dts} AND {$dts2} AND keyword_id={$rule_id}
					LIMIT ".$start.",".$total;

		$sql2 = "SELECT COUNT(*) as total
				FROM smac_fb.campaign_fb 
				WHERE campaign_id={$campaign_id}
				AND
				created_time_ts BETWEEN {$dts} AND {$dts2} 
				AND keyword_id={$rule_id}
				LIMIT 1";
		
		$posts = $this->fetch($sql,1);
		if(is_array($posts)){
			foreach($posts as $n=>$v){
				$posts[$n]['content'] = $v['caption']." ".$v['description']." ".$v['message'];
			}
		}
		if(sizeof($tweets)==0) $tweets = array();
		$rows = $this->fetch($sql2);
		
		$this->close();
		return array("posts"=>$posts,"total"=>$rows['total']);
	}
	function web_conversation_by_date($campaign_id,$group_type_id,$rule_id,$dt,$start,$total=5){
		$start = intval($start);
		$dts = strtotime($dt." 00:00:00");
		$dts2 = strtotime($dt." 23:59:59");
		$this->open(0);
		$sql = "
					SELECT DATE(FROM_UNIXTIME(published_ts)) AS published_date,
					author_name,author_uri,link,
					comments,summary as content
					FROM smac_report.campaign_web_feeds
					WHERE  
					campaign_id={$campaign_id}
					AND group_type_id={$group_type_id}
					AND
					published_ts BETWEEN {$dts} AND {$dts2} AND keyword_id={$rule_id}
					LIMIT ".$start.",".$total;
		
		$sql2 = "SELECT COUNT(*) as total
				FROM smac_report.campaign_web_feeds
				WHERE campaign_id={$campaign_id}
				AND group_type_id={$group_type_id}
				AND
				published_ts BETWEEN {$dts} AND {$dts2}
				AND keyword_id={$rule_id}
				LIMIT 1";
		
		$tweets = $this->fetch($sql,1);
		if(sizeof($tweets)==0) $tweets = array();
		$rows = $this->fetch($sql2);
		
		$this->close();
		return array("posts"=>$tweets,"total"=>$rows['total']);
	}
	function top_keyword_by_rule($campaign_id,$rule_id){
		$campaign_id = intval($campaign_id);
		$rule_id = intval($rule_id);
		$sql = "SELECT keyword, occurance
				FROM smac_report.top_rule_wordcloud_summary
				WHERE campaign_id = {$campaign_id} AND keyword_id = {$rule_id} 
				AND geo = 'all'
				ORDER BY occurance DESC
				LIMIT 20;";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	
	/**
	 * retrieve top keyword's twitter conversations
	 */
	function top_keyword_conversation($campaign_id,$keyword,$start,$total=10){
		$tweets = array();
		$rows = 0;
		if(strlen($keyword)){	
			$keyword = cleanXSS(trim($keyword));
			$start = intval($start);
			$sql = "SELECT 
					cf.id,cf.feed_id,cf.tag_id AS rule_id,cf.author_id,cf.author_name,cf.author_avatar,
					cf.published_datetime,cf.content,cf.generator,cf.followers AS impression,rt_count AS rt
					FROM 
					smac_feeds.campaign_feeds_{$campaign_id} cf 
					INNER JOIN smac_word.feed_wordlist_{$campaign_id} fw ON cf.id= fw.fid
					WHERE
					cf.campaign_id = {$campaign_id} AND fw.keyword = '{$keyword}'
					LIMIT {$start},{$total};";
			$tweets = $this->fetch($sql,1);
			
			$sql = "SELECT feed_count as total FROM smac_cardinal.word_feed_{$campaign_id} WHERE keyword = '{$keyword}'";
			$r = $this->fetch($sql);
			foreach($tweets as $n=>$v){
				//check for flag
				$flag = $this->is_workflow_flag($campaign_id,$v['feed_id'],1);
				//-->end of check
				//get feed sentiment
				$sql = "SELECT sentiment FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} WHERE feed_id={$v['id']} LIMIT 1";
				$sentiment = $this->fetch($sql);
				$tweets[$n]['sentiment'] = intval($sentiment['sentiment']);
				//-->
				$tweets[$n]['reply_url'] = "";
				$tweets[$n]['flag'] = $flag;
			}
			$rows = intval($r['total']);
		}
		return array("tweets"=>$tweets,"total_rows"=>$rows);
	}
	function keyword_sentiment($campaign_id){
		
			$aColumns = array( 'a.keyword', 'a.weight','occurance', 'a.weight');
			
			$qry = "SELECT COUNT(*) as total FROM smac_sentiment.sentiment_setup_{$campaign_id} 
					LIMIT 1";
			$row = $this->fetch($qry);
			
			$total = $row['total'];
			//LIMIT
			$start = 0;
			$limit = 10;
			if ( $this->request->getParam('iDisplayStart')!=null && $this->request->getParam('iDisplayLength') != '-1' )
			{
				$start = intval( $_GET['iDisplayStart'] );
				$limit = intval( $_GET['iDisplayLength'] );
			}
			
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( $this->request->getParam('iSortCol_0')!=null )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $this->request->getParam('iSortingCols')) ; $i++ )
				{
					if ( $this->request->getParam('bSortable_'.intval($this->request->getParam('iSortCol_'.$i))) == "true" )
					{
						//echo "Masuk<br />";
						$sOrder .= $aColumns[ intval( $this->request->getParam('iSortCol_'.$i) ) ]." ". $this->request->getParam('sSortDir_'.$i) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
			
			$sWhere = "";
			if ( $this->request->getParam('sSearch') != "" )
			{
				$search = mysql_escape_string(cleanXSS($this->request->getParam('sSearch')));
				$sWhere = "WHERE (";
				$sWhere .= "a.keyword LIKE '%". $search ."%'";
				$sWhere .= ')';
			}
			if($sOrder == ''){
				$sOrder = "ORDER BY occurance DESC ";
			}
		
			
			
			$sql = "SELECT {$campaign_id} as campaign_id,a.id,a.keyword,a.weight as score,
					COUNT(b.keyword) AS occurance 
					FROM smac_sentiment.sentiment_setup_{$campaign_id} a
					INNER JOIN smac_word.feed_wordlist_{$campaign_id} b
					ON a.keyword = b.keyword
					{$sWhere}
					GROUP BY b.keyword
					{$sOrder}
					LIMIT {$start},$limit";
				
			$rs = $this->fetch($sql,1);
			$data = array(
					"sEcho" => intval($this->request->getParam('sEcho')),
					"iTotalRecords" => $total,
					"iTotalDisplayRecords" => $total,
					"aaData" => array()
				);
			for($i=0;$i<sizeof($rs);$i++){
				if($rs[$i]['score'] < 0){
					$rs[$i]['category'] = "Unfavourable";
				}else if($r['score']>0){
					$rs[$i]['category'] = "Favourable";
				}else{
					$rs[$i]['category'] = "Neutral";
				}
				$r = $rs[$i];
				$idx = $r['id'];
				
				$data['aaData'][] = array($r['keyword'],
															'<div id="c-'.$idx.'">'.$r['category'].'</div>',
															$r['occurance'],
															'<div id="w-'.$idx.'">'.$r['score'].'</div>',
															'<input id="btn-'.$idx.'" type="button" value="edit" onclick="javascript:sentiment.edit('.$idx.');" /><div id="edit-'.$idx.'" style="display:none;"><input type="text" id="val-'.$idx.'" name="put-'.$idx.'" size="3" value="'.$r['score'].'" /><input type="button" value="save" onclick="javascript:sentimentSave('.$idx.');"/></div>');
			}
			return $data;
	}
	function sentiment_graph($campaign_id,$from_date,$to_date){
		if($from_date!=null&&$to_date!=null){
			$sql = "SELECT dtreport,SUM(total_mention_positive) as positive,SUM(total_mention_negative) as negative 
					FROM smac_report.daily_campaign_sentiment 
					WHERE campaign_id={$campaign_id} AND dtreport BETWEEN '{$from_date}' AND '{$to_date}'
					GROUP BY dtreport ORDER BY dtreport DESC LIMIT 60";
		}else{
			$sql = "SELECT dtreport,SUM(total_mention_positive) as positive,SUM(total_mention_negative) as negative 
					FROM smac_report.daily_campaign_sentiment 
					WHERE campaign_id={$campaign_id}
					GROUP BY dtreport ORDER BY dtreport DESC LIMIT 60";
		}
		$rs = $this->fetch($sql,1);
		
		$positive = array();
		$negative = array();
		
		while(sizeof($rs)>0){
			$r = array_pop($rs);
			$positive[$r['dtreport']] = intval($r['positive']);
			$negative[$r['dtreport']] = intval($r['negative']);
			
		}
		return array("positive"=>$positive,"negative"=>$negative);	
	}
	/**
	 * like conversation, except we filter the result by sentiment type
	 * 1 -> positive
	 * 2 -> negative
	 * 0 -> positive & negative
	 */
	function sentiment_tweet($campaign_id,$type=0,$dt,$start,$total=20){
		$start = intval($start);
		$type = intval($type);
		$this->open(0);
		if($type==1){
			$sql = "SELECT * FROM (
						SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers AS imp 
						FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} a
						INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
						ON a.feed_id = b.id						 
						WHERE  
						(a.sentiment > 0) AND b.published_date = '{$dt}'
					) c
					ORDER BY c.imp DESC LIMIT ".$start.",".$total;
		
			$sql2 = "
						SELECT COUNT(*) as total
						FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} a
						INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
						ON a.feed_id = b.id						 
						WHERE  
						(a.sentiment > 0) AND b.published_date = '{$dt}'
						LIMIT 1";
		}else if($type==2){
			
				$sql = "SELECT * FROM (
							SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers AS imp 
							FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} a
							INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
							ON a.feed_id = b.id						 
							WHERE  
							(a.sentiment < 0) AND b.published_date = '{$dt}'
						) c
						ORDER BY c.imp DESC LIMIT ".$start.",".$total;
		
				$sql2 = "
							SELECT COUNT(*) as total
							FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} a
							INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
							ON a.feed_id = b.id						 
							WHERE  
							(a.sentiment < 0) AND b.published_date = '{$dt}'
							LIMIT 1";
			
		}else{
			$sql = "SELECT * FROM (
						SELECT published_date,author_id,author_name,author_avatar,content,sentiment,followers AS imp 
						FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} a
						INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
						ON a.feed_id = b.id						 
						WHERE  
						(a.sentiment > 0 OR a.sentiment < 0) AND b.published_date = '{$dt}'
					) c
					ORDER BY c.imp DESC LIMIT ".$start.",".$total;
	
			$sql2 = "
						SELECT COUNT(*) as total
						FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id} a
						INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
						ON a.feed_id = b.id						 
						WHERE  
						(a.sentiment > 0 OR a.sentiment < 0) AND b.published_date = '{$dt}'
						LIMIT 1";
		}
		$tweets = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$this->close();
		if(sizeof($tweets)==0){
			$tweets = array();
		}
		return array("posts"=>$tweets,"total"=>$rows['total']);
	}
	function fb_sentiment_post($campaign_id,$type=0,$dt,$start,$total=20){
		$start = intval($start);
		$type = intval($type);
		$this->open(0);
		if($type==1){
			$sql = "SELECT DATE(FROM_UNIXTIME(a.created_time_ts)) AS published_date,
					a.from_object_id AS author_id,a.from_object_name AS author_name,
					a.message,a.caption,a.description,a.likes_count,b.sentiment,b.pii
					FROM smac_fb.campaign_fb a
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id}
					AND b.sentiment > 0
					AND a.created_time_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')
					ORDER BY a.id ASC
					LIMIT {$start},{$total};";
					
			$sql2 = "SELECT COUNT(a.id) as total
					FROM smac_fb.campaign_fb a
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id}
					AND b.sentiment > 0
					AND a.created_time_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')";
					
		}else if($type==2){
			
				$sql = "SELECT DATE(FROM_UNIXTIME(a.created_time_ts)) AS published_date,
					a.from_object_id AS author_id,a.from_object_name AS author_name,
					a.message,a.caption,a.description,a.likes_count,b.sentiment,b.pii
					FROM smac_fb.campaign_fb a
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id}
					AND b.sentiment < 0
					AND a.created_time_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')
					ORDER BY a.id ASC
					LIMIT {$start},{$total};";
					
			$sql2 = "SELECT COUNT(a.id) as total
					FROM smac_fb.campaign_fb a
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id}
					AND b.sentiment < 0
					AND a.created_time_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')";
			
		}else{
			$sql = "SELECT DATE(FROM_UNIXTIME(a.created_time_ts)) AS published_date,
					a.from_object_id AS author_id,a.from_object_name AS author_name,
					a.message,a.caption,a.description,a.likes_count,b.sentiment,b.pii
					FROM smac_fb.campaign_fb a
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id}
					AND a.created_time_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')
					ORDER BY a.id ASC
					LIMIT {$start},{$total};";
					
			$sql2 = "SELECT COUNT(a.id) as total
					FROM smac_fb.campaign_fb a
					INNER JOIN smac_fb.campaign_fb_sentiment b
					ON a.id = b.campaign_fb_id
					WHERE a.campaign_id={$campaign_id}
					AND a.created_time_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')";
		}
		$posts = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$this->close();
		if(sizeof($posts)==0){
			$posts = array();
		}else{
			foreach($posts as $n=>$v){
				$posts[$n]['content'] = $v['caption']." ".$v['description']." ".$v['message'];
			}
		}
		return array("posts"=>$posts,"total"=>$rows['total']);
	}
	function web_sentiment_post($campaign_id,$group_type_id=0,$type=0,$dt,$start,$total=20){
		$start = intval($start);
		$type = intval($type);
		$this->open(0);
		if($type==1){
			
			$sql = "SELECT a.author_name,a.author_uri,a.title,a.summary AS content,
					FROM_UNIXTIME(a.published_ts) AS published_date,b.sentiment,b.pii
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id}
					AND a.group_type_id={$group_type_id} 
					AND b.sentiment > 0
					AND a.published_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')
					ORDER BY a.id ASC
					LIMIT {$start},{$total};
					";
			$sql2 = "SELECT COUNT(a.id) as total
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id}
					AND a.group_type_id={$group_type_id} 
					AND b.sentiment > 0
					AND a.published_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')";
					
		}else if($type==2){
			
				$sql = "SELECT a.author_name,a.author_uri,a.title,a.summary AS content,
					FROM_UNIXTIME(a.published_ts) AS published_date,b.sentiment,b.pii
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id}
					AND a.group_type_id={$group_type_id} 
					AND b.sentiment < 0
					AND a.published_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')
					ORDER BY a.id ASC
					LIMIT {$start},{$total};
					";
			$sql2 = "SELECT COUNT(a.id) as total
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id}
					AND a.group_type_id={$group_type_id} 
					AND b.sentiment < 0
					AND a.published_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')";
			
		}else{
			$sql = "SELECT a.author_name,a.author_uri,a.title,a.summary AS content,
					FROM_UNIXTIME(a.published_ts) AS published_date,b.sentiment,b.pii
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id}
					AND a.group_type_id={$group_type_id} 
					AND a.published_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')
					ORDER BY a.id ASC
					LIMIT {$start},{$total};
					";
			$sql2 = "SELECT COUNT(a.id) as total
					FROM smac_report.campaign_web_feeds a 
					INNER JOIN smac_gcs.campaign_gcs_sentiment b
					ON a.id = b.campaign_web_feeds_id 
					WHERE a.campaign_id={$campaign_id}
					AND a.group_type_id={$group_type_id} 
					AND a.published_ts 
					BETWEEN 
					UNIX_TIMESTAMP('{$dt} 00:00:00') 
					AND 
					UNIX_TIMESTAMP('{$dt} 23:59:59')";
		}
		$posts = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$this->close();
		if(sizeof($posts)==0){
			$posts = array();
		}
		return array("posts"=>$posts,"total"=>$rows['total']);
	}
	function update_keyword_sentiment($campaign_id,$id,$value){
		if(is_numeric($id)&&is_numeric($value)){
			$qry = "UPDATE smac_sentiment.sentiment_setup_{$campaign_id}
					SET weight='".$value."' WHERE id=".mysql_escape_string($id).";";
			if($this->query($qry)){
				//insert entry in job_recalculate_sentiment
				$sql = "INSERT INTO `smac_report`.`job_recalculate_sentiment_status` 
						(
						`campaign_id`, 
						`dtscheduled`, 
						`dtlastchange`, 
						`n_status`
						)
						VALUES
						(
						{$campaign_id}, 
						NOW(), 
						'', 
						1
						)
						ON DUPLICATE KEY UPDATE
						n_status=1";
				$this->query($sql);
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	function response_list($campaign_id,$channel=1,$start,$total=20){
		$start = intval($start);
		$channel = intval($channel);
		$this->open(0);
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		//total impressions
		$sql = "SELECT SUM(total_impression_twitter+total_rt_impression_twitter) AS true_reach
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id}";
		
		
		$campaign = $this->fetch($sql);
		$total_impression = $campaign['true_reach'];
		$campaign=null;
		unset($campaign);
		
		$sql = "SELECT b.id as id,a.rt_feed_id AS feed_id,a.rt_author,a.rt_author_followers AS imp,
				b.published_date,b.author_id,b.author_name,b.author_avatar as avatar_pic,
				b.content,b.generator
				FROM smac_rt.rt_content_{$campaign_id} a
				INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
				ON a.rt_feed_id = b.feed_id
				LIMIT {$start},{$total};";
		$rs = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(a.id) as total
				FROM smac_rt.rt_content_{$campaign_id} a
				INNER JOIN smac_feeds.campaign_feeds_{$campaign_id} b
				ON a.rt_feed_id = b.feed_id
				LIMIT 1";
		$rows = $this->fetch($sql);
		
		
		if(sizeof($rs)==0){
			$rs = array();
		}
		$posts = array();
		foreach($rs as $r){
			$r['content'] = htmlspecialchars($r['content']);
			$sql = "SELECT a.keyword,b.weight FROM smac_word.feed_wordlist_{$campaign_id} a
						INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} b
						ON a.keyword = b.keyword 
						WHERE a.feed_id = '{$r['feed_id']}' 
						AND b.weight <> 0;";
			
			$ksentiment = $this->fetch($sql,1);
			$r['content'] = $this->highlight_content($r['content'], $ksentiment);
			
			$feed_sentiment = $this->fetch(" SELECT sentiment,rt_sentiment 
												FROM smac_sentiment.campaign_feed_sentiment_{$campaign_id}
												WHERE feed_id={$r['id']} LIMIT 1");
						
												
			if(intval($feed_sentiment['rt_sentiment'])!=0){
				$final_sentiment = intval($feed_sentiment['rt_sentiment']);
			}else{
				$final_sentiment = intval($feed_sentiment['sentiment']);
			}
			
			$share = round((($r['imp']+$r['rt_imp'])/$total_impression)*100,4);
			
			
			$posts[] = array("id"=>$r['feed_id'],
						   "published"=>$r['published_date'],
						   "author_id"=>$r['author_id'],
						   "name"=>htmlspecialchars($r['author_name']),
						   "txt"=>$r['content'],
						   "device"=>$this->get_device($r['generator'],$devices),
						   "profile_image_url"=>$r['avatar_pic'],
						   "impression"=>$r['imp'],
						   "share"=>$share,
						   "sentiment"=>$final_sentiment);		
		}
		$this->close();
		
		return array("posts"=>$posts,"total"=>$rows['total']);
	}
	/**
	 * highlight each keyword with its respective sentiment color.
	 * @param $txt the text to be highlighted
	 * @param $ksentiment  the keyword-sentiment list
	 * @return string highlighted text
	 */
	function highlight_content($txt,$ksentiment){
		//
		if(is_array($ksentiment)){
			$txt = strtolower($txt);
			foreach($ksentiment as $ks){
				if($ks['weight']>0){
					$txt = eregi_replace($ks['keyword'],"<span class='green2'>{$ks['keyword']}</span>",$txt);
				}else if($ks['weight']<0){
					$txt = eregi_replace($ks['keyword'],"<span class='red'>{$ks['keyword']}</span>",$txt);
				}else{
					//do nothing if neutral
				}
			}
		}
		return $txt;
	}
	
	/**
	 * a helper function to help sorting an array based on its key's value
	 * @param $a
	 * @param $subkey
	 */
	function subval_sort($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
	}
}
?>