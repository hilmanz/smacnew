<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
class dashboard_model extends base_model{
	protected $from;
	protected $to;
	protected $request;	
	
	/**
	 * filter data by date range (if available) 
	 */
	function setDateRange($from=null,$to=null){
		$this->from = $from;
		$this->to = $to;
	}
	function setRequestHandler($req){
		$this->request = $req;
	}
	function post_count($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		$sql = "SELECT
		SUM(total_mention_twitter) as twitter, 
		SUM(total_mention_facebook) as fb, 
		SUM(total_mention_web) as web,
		sum(total_youtube_video) as video
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} {$filter}";
		$rs = $this->fetch($sql);
		
		$sql = "SELECT group_type_id,COUNT(*) AS total_posts
				FROM smac_report.campaign_web_feeds 
				WHERE campaign_id={$campaign_id}
				GROUP BY group_type_id;";
		$gcs = $this->fetch($sql,1);
		
		$web = array("blog"=>0,"forum"=>0,"news"=>0,"common"=>0,"ecommerce"=>0);
		if(is_array($gcs)){
			foreach($gcs as $n){
				switch($n['group_type_id']){
					case 1:
						$group_name = "blog";
					break;
					case 2:
						$group_name = "forum";
					break;
					case 3:
						$group_name = "news";
					break;
					case 5:
						$group_name = "ecommerce";
					break;
					default:
						$group_name = "common";
					break;
				}
				$web[$group_name] = $n['total_posts'];
			}
		}
		$rs['twitter'] = intval($rs['twitter']);
		$rs['fb'] = intval($rs['fb']);
		$rs['web'] = intval($rs['web']);
		$rs['video'] = intval($rs['video']);
		$rs['blog'] = intval($web['blog']);
		$rs['forum'] = intval($web['forum']);
		$rs['news'] = intval($web['news']);
		$rs['common'] = intval($web['common']);
		$rs['ecommerce'] = intval($web['ecommerce']);
		
		return $rs;
	}
	/**
	 * @todo share of voice,sentiment all channels, daily volume by positive/negative sentiments.
	 */
	function summary($campaign_id){
		
		$rs = array("summary"=>$this->summary_stat($campaign_id),
					"daily_volume_by_positive_sentiment"=>$this->summary_daily_volume_by_positive_sentiment($campaign_id),
						"daily_volume_by_negative_sentiment"=>$this->summary_daily_volume_by_negative_sentiment($campaign_id),
						"daily_volume_by_impression"=>$this->summary_daily_volume_by_impression($campaign_id),
						"daily_volume_by_mention"=>$this->summary_daily_volume_by_mention($campaign_id),
						"share_of_voice"=>$this->summary_share_of_voice($campaign_id),
						"sentiment_all_channels"=>$this->summary_sentiment_all_channels($campaign_id),
						"top_kol"=>$this->summary_top_kol($campaign_id),
						"top_keywords"=>$this->summary_top_keywords($campaign_id)
			  		);
		return $rs;
	}
	/**
	 * @todo sentiment
	 */
	function twitter($campaign_id){
		$rs = array("summary"=>$this->twitter_summary_stat($campaign_id),
					"sentiment"=>$this->twitter_sentiment($campaign_id),
						"top_kol"=>$this->twitter_top_kols($campaign_id),
						"daily_volume_by_impression"=>$this->twitter_daily_volume_by_impression($campaign_id),
						 "daily_volume_by_mention"=>$this->twitter_daily_volume_by_mention($campaign_id),
						 "daily_volume_by_sentiment"=>$this->twitter_daily_volume_by_sentiment($campaign_id),
						"top_keywords"=>$this->summary_top_keywords($campaign_id),
						"quadrant"=>$this->twitter_quadrant($campaign_id),
						"interaction"=>$this->twitter_interaction_quadrant($campaign_id));
		return $rs;
	}
	function twitter_quadrant($campaign_id){
		$datefilter = "";
		
		if($this->from!=null&&$this->to!=null){
			$datefilter = "AND dcs.dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
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
		
		//$viral_effect = @round($data['rt_impression']/($data['impression']+$data['rt_impression'])*100,2);
		$viral_effect = @round($quadrant['rt_impression']/($quadrant['impression']+$quadrant['rt_impression'])*100,2);
		return array("id"=>$campaign_id,
					"name"=>$campaign['campaign_name'],
					"sentiment"=>floatval($_pii['pii']),
					"viral"=>floatval($viral_effect),
					"volume"=>intval($quadrant['impression']));
	}
	function twitter_interaction_quadrant($campaign_id){
		$datefilter = "";
		
		if($this->from!=null&&$this->to!=null){
			$datefilter = "AND dcs.dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
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
					"volume"=>intval($quadrant['mention']));
	}
	function twitter_sentiment($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$datefilter = "AND dcs.dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		$sql = "SELECT 
					SUM(dcs.total_mention_positive) AS positive
					,SUM(dcs.total_mention_negative) AS negative
				FROM 
					smac_web.tbl_campaign tc
					INNER JOIN smac_report.daily_campaign_sentiment dcs ON dcs.campaign_id = tc.id
				WHERE dcs.channel = 1
				AND tc.id={$campaign_id} {$datefilter}
				;";
		$sentiment = $this->fetch($sql);
		
		$sql = "SELECT SUM(total_mention_twitter) AS total 
				FROM smac_report.campaign_rule_volume_history 
				WHERE campaign_id={$campaign_id};";
		$mentions = $this->fetch($sql);
		
		
		return array(array("rule"=>"positive","value"=>$sentiment['positive']),
					array("rule"=>"negative","value"=>$sentiment['negative']),
					array("rule"=>"neutral",
							"value"=>$mentions['total']-($sentiment['positive']-$sentiment['negative']))
					);
		
	}
	/**
	 * @todo sentiment, daily_volume_by_impression,daily_volume_by_mention
	 */
	function fb($campaign_id,$from=null,$to=null){
		
		$rs = array("summary"=>$this->fb_summary($campaign_id,$from,$to),
						"sentiment"=>$this->fb_sentiment($campaign_id, $from, $to),
						"top_kol"=>$this->fb_kol($campaign_id,$from,$to),
						"daily_volume_by_impression"=>array(),
						"daily_volume_by_mention"=>$this->fb_daily_volume_by_mention($campaign_id, $from, $to),
						"daily_volume_by_sentiments"=>$this->fb_daily_volume_by_sentiment($campaign_id, $from, $to),
						"top_keywords"=>$this->fb_top_keywords($campaign_id,$from,$to)
					   							
			);
		return $rs;
	}
	function fb_daily_volume_by_sentiment($campaign_id,$from,$to){
		if($from!=null&&$to!=null){
			$filter = "AND dcs.dtreport BETWEEN '{$from}' AND '{$to}'";
		}
		$sql = "SELECT 
				dcs.dtreport AS report_date
				,dcs.total_mention_positive AS positive
				,dcs.total_mention_negative AS negative
				FROM 
					smac_web.tbl_campaign tc
					INNER JOIN smac_report.daily_campaign_sentiment dcs ON dcs.campaign_id = tc.id
				WHERE dcs.channel = 2
				AND tc.id={$campaign_id}
				{$filter}
				;";
				
		$results = $this->fetch($sql,1);
		$response = array();
		$positive = array();
		$negative = array();
		
		$n_size = sizeof($results);
		if($n_size>0){
			for($i=0;$i<$n_size;$i++){
				$rs = $results[$i];
				//foreach($results as $rs){
				$positive[] = array("{$rs['report_date']}"=>$rs['positive']);
				$negative[] = array("{$rs['report_date']}"=>$rs['negative']);
			}
		}
		$rs = array("positive"=>$positive,
				   	"negative"=>$negative
					);
		return $rs;
	}
	function fb_daily_volume_by_mention($campaign_id,$from,$to){
		if($from!=null&&$to!=null){
			$filter = "AND dtreport BETWEEN '{$from}' AND '{$to}'";
			$limit = "LIMIT 365";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT dtreport as post_date,
			SUM(total_mention_facebook) as mentions
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id}
			{$filter}
			GROUP BY dtreport
			ORDER BY dtreport DESC {$limit}";
			
			$results = $this->fetch($sql,1);
			foreach($results as $n=>$rs){
				$results[$n]['ts'] = strtotime($rs['post_date']);
			}
			$results = subval_sort($results, 'ts'); 
			
			$response = array();
			
			$n_size = sizeof($results);
			$response = array();
			if($n_size>0){
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					//foreach($results as $rs){
					$ts = $rs['post_date'];
					$response[$rs['post_date']] = intval($rs['mentions']);
				}
			}
		return $response;	
	}
	function fb_sentiment($campaign_id,$from,$to){
		if($from!=null&&$to!=null){
			$filter = "AND dcs.dtreport BETWEEN '{$from}' AND '{$to}'";
		}
		$sql = "SELECT 
					dcs.dtreport AS report_date
					,dcs.total_mention_positive AS positive
					,dcs.total_mention_negative AS negative
				FROM 
					smac_web.tbl_campaign tc
					INNER JOIN smac_report.daily_campaign_sentiment dcs ON dcs.campaign_id = tc.id
				WHERE dcs.channel = 2
				AND tc.id={$campaign_id} 
				{$filter}
				LIMIT 30";
		$rows = $this->fetch($sql,1);
		$positive = array();
		$negative = array();
		for($i=0;$i<sizeof($rows);$i++){
			$row = $rows[$i];
			$positive[] = array($row['report_date']=>$row['positive']);
			$negative[] = array($row['report_date']=>$row['negative']);
		}
		return array("positive"=>$positive,"negative"=>$negative);
		
	}
	function fb_summary($campaign_id,$from,$to){
		
		if($from==null||$to==NULL){
			$sql = "SELECT COUNT(DISTINCT author_id) AS total_people,SUM(mentions) AS total_mentions,SUM(likes) AS total_likes
					FROM smac_fb.daily_fb_people_stat
					WHERE campaign_id={$campaign_id} LIMIT 1;";
		}else{
			$sql = "SELECT COUNT(DISTINCT author_id) AS total_people,SUM(mentions) AS total_mentions,SUM(likes) AS total_likes
					FROM smac_fb.daily_fb_people_stat
					WHERE campaign_id={$campaign_id}
					AND published_date >='{$from}'
					AND published_date <='{$to}'
					LIMIT 1;";
		}
		
		$summary = $this->fetch($sql);
		
		//temporarily query the stats from smac_report.fb_people_stats
		$performance = $this->getPerformanceIndicator($campaign_id,$from,$to);
		$summary['performance'] = $performance;
		
		return array("people"=>array("value"=>$summary['total_people'],
											 "percent"=>"0",
											 "diff"=>"0"),
								"total_posts"=>array("value"=>$summary['total_mentions'],
											 "percent"=>$summary['performance']['mention_change'],
											 "diff"=>$summary['performance']['mention_diff']),
								"total_likes"=>array("value"=>$summary['total_likes'],
											 "percent"=>$summary['performance']['like_change'],
											 "diff"=>$summary['performance']['like_diff'])
							  );
	}
	function getPerformanceIndicator($campaign_id,$from,$to){
		
		

		if($from!=null&&$to!=null){
			$sql = "SELECT * FROM (
					SELECT published_date,SUM(mentions) as mentions 
					FROM smac_fb.daily_fb_people_stat 
					WHERE campaign_id={$campaign_id} AND published_date BETWEEN {$from} AND {$to}  
					GROUP BY published_date) a
					WHERE mentions <> 0 ORDER BY published_date DESC LIMIT 2;";
			$rs = $this->fetch($sql,1);
			
			
			$sql = "SELECT * FROM (
					SELECT published_date,SUM(likes) as total_likes 
					FROM smac_fb.daily_fb_people_stat 
					WHERE campaign_id={$campaign_id} AND published_date BETWEEN {$from} AND {$to} 
					GROUP BY published_date) a
					WHERE total_likes <> 0 ORDER BY published_date DESC LIMIT 2;";
			
			$rs2 = $this->fetch($sql,1);
		}else{
			$sql = "SELECT * FROM (
					SELECT published_date,SUM(mentions) as mentions 
					FROM smac_fb.daily_fb_people_stat 
					WHERE campaign_id={$campaign_id} AND published_date
					GROUP BY published_date) a
					WHERE mentions <> 0 ORDER BY published_date DESC LIMIT 2;";
			$rs = $this->fetch($sql,1);
			
			
			$sql = "SELECT * FROM (
					SELECT published_date,SUM(likes) as total_likes 
					FROM smac_fb.daily_fb_people_stat 
					WHERE campaign_id={$campaign_id} AND published_date 
					GROUP BY published_date) a
					WHERE total_likes <> 0 ORDER BY published_date DESC LIMIT 2;";
			
			$rs2 = $this->fetch($sql,1);
		}
		//-->
		
		$mention_change = 0;
		$like_change = 0;
		$like_diff = 0;
		$mention_diff = 0;
		
		
		if(sizeof($rs)==2){
			$mention_diff = $rs[0]['mentions'] - $rs[1]['mentions'];
			$mention_change = round((($rs[0]['mentions'] - $rs[1]['mentions'])/$rs[1]['mentions'])*100,2);
		}else{
			$mention_diff = intval($rs[0]['mentions']);
		}
		if(sizeof($rs2)==2){
			$like_change = round((($rs2[0]['total_likes'] - $rs2[1]['total_likes'])/$rs2[1]['total_likes'])*100,2);
			$like_diff = ($rs2[0]['total_likes'] - $rs2[1]['total_likes']);
		}else{
			$like_diff = intval($rs2[0]['total_likes']);
		}
		$result['mention_change'] = $mention_change;
		$result['like_change'] = $like_change;
		$result['mention_diff']= $mention_diff;
		$result['like_diff'] = $like_diff;

		return $result;
	}
	function fb_kol($campaign_id,$from,$to){
		
		if($from==null||$to==NULL){
			$sql = "SELECT author_id,author_name,SUM(likes) as total,SUM(mentions) as mentions
			        FROM smac_fb.daily_fb_people_stat
			        WHERE campaign_id={$campaign_id}
			        GROUP BY author_id,author_name
			        ORDER BY total DESC
			        LIMIT 5";
			
		}else{
			$sql = "SELECT author_id,author_name,SUM(likes) as total,SUM(mentions) as mentions
			        FROM smac_fb.daily_fb_people_stat
			        WHERE campaign_id={$campaign_id}
			        AND published_date >='{$from}'
					AND published_date <='{$to}'
			        GROUP BY author_id,author_name
			        ORDER BY total DESC
			        LIMIT 5";
		}
		
		
		$kol = $this->fetch($sql,1);
		
		
		return $kol;
		
	}
	function fb_top_keywords($campaign_id,$from,$to){
		$from_date_ts = strtotime(mysql_escape_string($from));
		$to_date_ts = strtotime(mysql_escape_string($to));
		if($from==null||$to==NULL){
			$sql = "SELECT a.keyword,COUNT(a.keyword) as occurance
			        FROM smac_fb.fb_wordlist_{$campaign_id} a
	                INNER JOIN smac_fb.campaign_fb b
	                ON a.fid = b.id
			        WHERE b.campaign_id={$campaign_id} AND b.is_active=1
			        GROUP BY keyword
			        ORDER BY occurance DESC LIMIT 100";
		}else{
			$sql = "SELECT a.keyword,COUNT(a.keyword) as occurance
			        FROM smac_fb.fb_wordlist_{$campaign_id} a
	                INNER JOIN smac_fb.campaign_fb b
	                ON a.fid = b.id
	                AND b.created_time_ts BETWEEN {$from_date_ts} and {$to_date_ts}
			        WHERE b.campaign_id={$campaign_id} AND b.is_active=1
			        GROUP BY keyword
			        ORDER BY occurance DESC LIMIT 100";
		}
		
		$keywords = @$this->fetch($sql,1);
		$rs = array();
		$max_val = 0;
		$nn=0;
		if(sizeof($keywords)>0){
			foreach($keywords as $keyword){
				$max_val = max($max_val,intval($keyword['occurance']));
			}
			
			$sWords = "";
			foreach($keywords as $n=>$keyword){
				if(strlen($keyword['keyword'])>0){
					if($n>0){
						$sWords.=",";
					}
					$sWords.="'{$keyword['keyword']}'";
					$nn++;
				}
				$rs[] = array("keyword"=>$keyword['keyword'],"value"=>($keyword['occurance']/$max_val)*10);
			}
		}
		
		$sql = "SELECT keyword,weight as sentiment FROM smac_sentiment.sentiment_setup_{$campaign_id}
				WHERE keyword IN ({$sWords}) LIMIT {$nn}";	
		$sentiments = @$this->fetch($sql,1);
		if(sizeof($sentiments)>0){
			foreach($sentiments as $s){
				foreach($rs as $n=>$word){
					if(strcmp($word['keyword'],$s['keyword'])==0){
						$rs[$n]['sentiment'] = $s['sentiment'];
						break;
					}
				}
			}
		}
		unset($sentiments);
		unset($keyword);
		
		shuffle($rs);
		return $rs;
	}
	/**
	 * Dashboard Overall Summary.
	 * the data are the total of twitter+fb+web.
	 * @param $campaign_id topic's id
	 */
	function summary_stat($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		//total days
		$sql ="SELECT COUNT(*) AS total FROM (SELECT DISTINCT dtreport
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} {$filter}) a;";
		$days = $this->fetch($sql);
		
		//summary stats
		$sql = "SELECT dtreport,
		SUM(total_mention_twitter+total_mention_facebook+total_mention_web) as mentions, 
		SUM(total_people_twitter+total_author_web+total_people_facebook) as people,
		SUM(total_impression_twitter) as impression,
		SUM(total_countries) as countries,
		SUM(total_rt_twitter) as rt_mention
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} {$filter}";
		$f = $this->fetch($sql);
		
		//pii
		$sql = "SELECT dtreport,pii,sentiment_total as total
		FROM (SELECT dtreport,SUM(sum_pii) as pii,SUM(total_mention_positive+total_mention_negative) as sentiment_total 
		FROM 
		smac_report.daily_campaign_sentiment 
		WHERE campaign_id={$campaign_id}
		GROUP BY dtreport) a
		WHERE a.pii <> 0 {$filter}
		ORDER BY dtreport DESC 
		LIMIT 2;";
		$pii = $this->fetch($sql,1);
		
		//mention and impression difference.
		$sql = "SELECT dtreport,
		SUM(total_mention_twitter) as mentions, 
		SUM(total_impression_twitter) as impression,
		SUM(total_people_twitter+total_author_web+total_people_facebook) as people
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} {$filter}
		GROUP BY dtreport 
		ORDER BY dtreport DESC 
		LIMIT 2;";
		//print $sql;
		$rs = $this->fetch($sql,1);
		
		
		
		if(sizeof($rs)==2){
			$h1_people = $rs[0]['people'];
			$h0_people = $rs[1]['people'];
			$h1_mention = $rs[0]['mentions'];
			$h0_mention = $rs[1]['mentions'];
			$people_diff = $h1_mention - $h0_mention;
			$people_change = 0;
			$mention_diff = $h1_mention - $h0_mention;
			$mention_change = 0;
			if($h0_people!=0){
				$people_change = round($people_diff/$h0_people,2);	
			}
			if($h0_mention!=0){
				$mention_change = round($mention_diff/$h0_mention,2);	
			}
			
			$h1_imp = $rs[0]['impression'];
			$h0_imp = $rs[1]['impression'];
			$imp_diff = $h1_imp - $h0_imp;
			$imp_change = 0;
			if($h0_imp>0){
				$imp_change = round($imp_diff/$h0_imp,2);
			}
		}else{
			$mention_diff = 0;
			$mention_change = 0;
			$imp_diff = 0;
			$imp_change = 0;
		}
		if(sizeof($pii)>1){
			$pii1 =  round($pii[0]['pii']/$pii[0]['total'],2);
			$pii2 =  round($pii[1]['pii']/$pii[1]['total'],2);
			$pii_diff = round($pii1 - $pii2,2);
			$pii_change = round($pii_diff/$pii2,2);
			$pii_score = $pii1;
			
		}else if(sizeof($pii)==1){
			$pii_score = round($pii[0]['pii']/$pii[0]['total'],2);
			$pii_diff = 0;
			$pii_change = 0;
		}else{
			$pii_score = 0;
		}
		
		//temporary solutions to integrate people fb counts, 
		//until the stats become available in campaign_rule_volume_history
		if($from==null||$to==NULL){
			$sql = "SELECT COUNT(DISTINCT author_id) AS total_people
					FROM smac_fb.daily_fb_people_stat
					WHERE campaign_id={$campaign_id} LIMIT 1;";
		}else{
			$sql = "SELECT COUNT(DISTINCT author_id) AS total_people
					FROM smac_fb.daily_fb_people_stat
					WHERE campaign_id={$campaign_id}
					AND published_date >='{$this->from}'
					AND published_date <='{$this->to}'
					LIMIT 1;";
		}
		$fb = $this->fetch($sql);
		//-->
		$rs = array("PII"=>array("value"=>$pii_score,"percent"=>$pii_change,"diff"=>$pii_diff),
					"potential_impression"=>array("value"=>$f['impression'],"percent"=>$imp_change,"diff"=>$imp_diff),
					"people"=>array("value"=>$f['people']+intval($fb['total_people']),"percent"=>$people_change,"diff"=>$people_diff),
					"mentions"=>array("value"=>$f['mentions'],"percent"=>$mention_change,"diff"=>$mention_diff));
		return $rs;
		
	}
	function summary_daily_volume_by_positive_sentiment($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$limit = "";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT 
					dtreport, 
					sum(total_mention_positive) as total
				FROM 
					smac_report.daily_campaign_sentiment 
				WHERE 
					campaign_id={$campaign_id} 
					AND channel = 1
					{$filter}
				GROUP BY dtreport
				ORDER BY dtreport DESC 
				{$limit}";
		$twitter = $this->fetch($sql,1);
		
		
		$sql = "SELECT 
					dtreport, 
					sum(total_mention_positive) as total
				FROM 
					smac_report.daily_campaign_sentiment 
				WHERE 
					campaign_id={$campaign_id} 
					AND channel = 2
					{$filter}
				GROUP BY dtreport
				ORDER BY dtreport DESC 
				{$limit}";
		$fb = $this->fetch($sql,1);
		
		
		$sql = "SELECT 
					dtreport, 
					sum(total_mention_positive) as total
				FROM 
					smac_report.daily_campaign_sentiment 
				WHERE 
					campaign_id={$campaign_id} 
					AND channel = 3
					{$filter}
				GROUP BY dtreport
				ORDER BY dtreport DESC 
				{$limit}
				";
				
		$web = $this->fetch($sql,1);
		
		//equalized the result
		for($i=0;$i<sizeof($twitter);$i++){
			$twitter[$i]['ts'] = strtotime($twitter[$i]['dtreport']);
		}
		for($i=0;$i<sizeof($fb);$i++){
			$fb[$i]['ts'] = strtotime($fb[$i]['dtreport']);
		}
		for($i=0;$i<sizeof($web);$i++){
			$web[$i]['ts'] = strtotime($web[$i]['dtreport']);
		}
		$ts = array();
		for($i=0;$i<sizeof($twitter);$i++){
			$ts[$twitter[$i]['ts']] = 1;
		}
		for($i=0;$i<sizeof($fb);$i++){
			$ts[$fb[$i]['ts']] = 1;
		}
		for($i=0;$i<sizeof($web);$i++){
			$ts[$web[$i]['ts']] = 1;
		}
		if(sizeof($ts)>0){
			foreach($ts as $t=>$v){
				$found = false;
				for($i=0;$i<sizeof($twitter);$i++){
					if($twitter[$i]['ts']==$t){
						$found=true;
						break;
					}
				}
				if(!$found){
					$twitter[] = array("ts"=>$t,"dtreport"=>date("Y-m-d",$t),"total"=>0);
				}
				
				$found = false;
				for($i=0;$i<sizeof($fb);$i++){
					if($fb[$i]['ts']==$t){
						$found=true;
						break;
					}
				}
				if(!$found){
					$fb[] = array("ts"=>$t,"dtreport"=>date("Y-m-d",$t),"total"=>0);
				}
				
				$found = false;
				for($i=0;$i<sizeof($web);$i++){
					if($web[$i]['ts']==$t){
						$found=true;
						break;
					}
				}
				if(!$found){
					$web[] = array("ts"=>$t,"dtreport"=>date("Y-m-d",$t),"total"=>0);
				}
			}	
		}
		$twitter = @subval_sort($twitter,'ts');
		$fb = @subval_sort($fb,'ts');
		$web = @subval_sort($web,'ts');
		
		$_tw = array();
		$_fb = array();
		$_web = array();
		
		foreach($twitter as $v){
			$_tw[] = array($v['dtreport']=>$v['total']);
		}
		foreach($fb as $v){
			$_fb[] = array($v['dtreport']=>$v['total']);
		}
		foreach($web as $v){
			$_web[] = array($v['dtreport']=>$v['total']);
		}
		unset($twitter);
		unset($fb);
		unset($web);
		$rs = array(
				"twitter"=>$_tw,
				"fb"=>$_fb,
				"web"=>$_web
			);
		return $rs;
	}
	function summary_daily_volume_by_negative_sentiment($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$limit = "";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT 
					dtreport, 
					sum(total_mention_negative) as total
				FROM 
					smac_report.daily_campaign_sentiment 
				WHERE 
					campaign_id={$campaign_id} AND channel = 1
					{$filter}
				GROUP BY dtreport
				ORDER BY dtreport DESC 
				{$limit}";
		$twitter = $this->fetch($sql,1);
		$sql = "SELECT 
					dtreport, 
					sum(total_mention_negative) as total
				FROM 
					smac_report.daily_campaign_sentiment 
				WHERE 
					campaign_id={$campaign_id} AND channel = 2
					{$filter}
				GROUP BY dtreport
				ORDER BY dtreport DESC 
				{$limit}";
		$fb = $this->fetch($sql,1);
		$sql = "SELECT 
					dtreport, 
					sum(total_mention_negative) as total
				FROM 
					smac_report.daily_campaign_sentiment 
				WHERE 
					campaign_id={$campaign_id} AND channel = 3
					{$filter}
				GROUP BY dtreport
				ORDER BY dtreport DESC 
				{$limit}";
		$web = $this->fetch($sql,1);
		//equalized the result
		for($i=0;$i<sizeof($twitter);$i++){
			$twitter[$i]['ts'] = strtotime($twitter[$i]['dtreport']);
		}
		for($i=0;$i<sizeof($fb);$i++){
			$fb[$i]['ts'] = strtotime($fb[$i]['dtreport']);
		}
		for($i=0;$i<sizeof($web);$i++){
			$web[$i]['ts'] = strtotime($web[$i]['dtreport']);
		}
		$ts = array();
		for($i=0;$i<sizeof($twitter);$i++){
			$ts[$twitter[$i]['ts']] = 1;
		}
		for($i=0;$i<sizeof($fb);$i++){
			$ts[$fb[$i]['ts']] = 1;
		}
		for($i=0;$i<sizeof($web);$i++){
			$ts[$web[$i]['ts']] = 1;
		}
		if(sizeof($ts)>0){
			foreach($ts as $t=>$v){
				$found = false;
				for($i=0;$i<sizeof($twitter);$i++){
					if($twitter[$i]['ts']==$t){
						$found=true;
						break;
					}
				}
				if(!$found){
					$twitter[] = array("ts"=>$t,"dtreport"=>date("Y-m-d",$t),"total"=>0);
				}
				
				$found = false;
				for($i=0;$i<sizeof($fb);$i++){
					if($fb[$i]['ts']==$t){
						$found=true;
						break;
					}
				}
				if(!$found){
					$fb[] = array("ts"=>$t,"dtreport"=>date("Y-m-d",$t),"total"=>0);
				}
				
				$found = false;
				for($i=0;$i<sizeof($web);$i++){
					if($web[$i]['ts']==$t){
						$found=true;
						break;
					}
				}
				if(!$found){
					$web[] = array("ts"=>$t,"dtreport"=>date("Y-m-d",$t),"total"=>0);
				}
			}	
		}
		$twitter = @subval_sort($twitter,'ts');
		$fb = @subval_sort($fb,'ts');
		$web = @subval_sort($web,'ts');
		
		$_tw = array();
		$_fb = array();
		$_web = array();
		
		foreach($twitter as $v){
			$_tw[] = array($v['dtreport']=>$v['total']);
		}
		foreach($fb as $v){
			$_fb[] = array($v['dtreport']=>$v['total']);
		}
		foreach($web as $v){
			$_web[] = array($v['dtreport']=>$v['total']);
		}
		unset($twitter);
		unset($fb);
		unset($web);
		$rs = array(
				"twitter"=>$_tw,
				"fb"=>$_fb,
				"web"=>$_web
			);
		return $rs;
	}
	function summary_daily_volume_by_impression($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$limit = "";
		}else{
			$limit = " LIMIT 30";
		}
		$sql = "SELECT dtreport as post_date,
			SUM(total_impression_twitter) as twitter_impression
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id} 
			{$filter}
			GROUP BY dtreport
			ORDER BY dtreport DESC
			{$limit}";
			$results = $this->fetch($sql,1);
			for($i=0;$i<sizeof($results);$i++){
				$results[$i]['ts'] = strtotime($results[$i]['post_date']);
			}
			$results = subval_sort($results,'ts');
			$response = array();
			$twitter = array();
			$fb = array();
			$web = array();
			$n_size = sizeof($results);
			if($n_size>0){
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					//foreach($results as $rs){
					$twitter[] = array("{$rs['post_date']}"=>$rs['twitter_impression']);
					$fb[] = array("{$rs['post_date']}"=>0);
					$web[] = array("{$rs['post_date']}"=>0);
					$youtube[] = array("{$rs['post_date']}"=>0);
				}
			}
			$rs = array("twitter"=>$twitter,
					   	"fb"=>$fb,
						"web"=>$web,
						"youtube"=>$youtube
						);
		return $rs;
		
	}
	
	function summary_daily_volume_by_mention($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$limit = "";
		}else{
			$limit = " LIMIT 30";
		}
		$sql = "SELECT dtreport as post_date,
			SUM(total_mention_twitter) as twitter_mentions,
			SUM(total_mention_facebook) as fb_mentions,
			SUM(total_mention_web) as web_mentions,
			SUM(total_youtube_video) as youtube_mentions
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id} 
			{$filter}
			GROUP BY dtreport
			ORDER BY dtreport DESC
			{$limit}";
			$results = $this->fetch($sql,1);
			for($i=0;$i<sizeof($results);$i++){
				$results[$i]['ts'] = strtotime($results[$i]['post_date']);
			}
			$results = subval_sort($results,'ts');
			$response = array();
			$twitter = array();
			$fb = array();
			$web = array();
			$youtube = array();
			$n_size = sizeof($results);
			if($n_size>0){
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					//foreach($results as $rs){
					$twitter[] = array("{$rs['post_date']}"=>$rs['twitter_mentions']);
					$fb[] = array("{$rs['post_date']}"=>$rs['fb_mentions']);
					$web[] = array("{$rs['post_date']}"=>$rs['web_mentions']);
					$youtube[] = array("{$rs['post_date']}"=>$rs['youtube_mentions']);
				}
			}
			
			//web channels stats
			$blog = $this->site_daily_volume_by_mention($campaign_id, 1, $this->from, $this->to);
			$forum = $this->site_daily_volume_by_mention($campaign_id, 2, $this->from, $this->to);
			$news = $this->site_daily_volume_by_mention($campaign_id, 3, $this->from, $this->to);
			$ecommerce = $this->site_daily_volume_by_mention($campaign_id, 5, $this->from, $this->to);
			$corporate = $this->site_daily_volume_by_mention($campaign_id, 0, $this->from, $this->to);
			foreach($web as $w=>$v){
				$key = array_keys($v);
				if(!array_key_exists($key[0],$blog)){
					$blog[$key[0]]=0;
				}
				if(!array_key_exists($key[0],$forum)){
					$forum[$key[0]]=0;
				}
				if(!array_key_exists($key[0],$news)){
					$news[$key[0]]=0;
				}
				if(!array_key_exists($key[0],$ecommerce)){
					$ecommerce[$key[0]]=0;
				}
				if(!array_key_exists($key[0],$corporate)){
					$corporate[$key[0]]=0;
				}
				
				unset($key);
			}
			
			$blog = arr_assoc_sort_date($blog,'Y-m-d');
			$forum = arr_assoc_sort_date($forum,'Y-m-d');
			$news = arr_assoc_sort_date($news,'Y-m-d');
			$ecommerce = arr_assoc_sort_date($ecommerce,'Y-m-d');
			$corporate = arr_assoc_sort_date($corporate,'Y-m-d');
			
			$rs = array("twitter"=>$twitter,
					   	"fb"=>$fb,
						"web"=>$web,
						"youtube"=>$youtube,
						"blog"=>array(),
						"forum"=>array(),
						"ecommerce"=>array(),
						"corporate"=>array()
						);
			if(is_array($blog)){
				foreach($blog as $n=>$v){
					$rs['blog'][] = array($n=>$v);
				}
			}
			unset($blog);
			if(is_array($forum)){
				foreach($forum as $n=>$v){
					$rs['forum'][] = array($n=>$v);
				}
			}
			unset($forum);
			if(is_array($news)){
				foreach($news as $n=>$v){
					$rs['news'][] = array($n=>$v);
				}
			}
			if(is_array($ecommerce)){
				foreach($ecommerce as $n=>$v){
					$rs['ecommerce'][] = array($n=>$v);
				}
			}
			unset($ecommerce);
			if(is_array($corporate)){
				foreach($corporate as $n=>$v){
					$rs['corporate'][] = array($n=>$v);
				}
			}
			unset($corporate);
			
			
			unset($twitter);
			unset($fb);
			unset($web);
			unset($youtube);

		return $rs;
	}
	function summary_share_of_voice($campaign_id){
		
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
		}	
		$sql = "SELECT keyword_id,
				SUM(total_mention_twitter+total_mention_facebook+total_mention_web) AS mentions 
				FROM smac_report.campaign_rule_volume_history
				WHERE campaign_id={$campaign_id} 
				{$filter}
				GROUP BY keyword_id;";
		$stats = $this->fetch($sql,1);
		$rs = array();
		foreach($stats as $stat){
			$sql = "SELECT label FROM smac_web.tbl_campaign_keyword 
					WHERE campaign_id={$campaign_id} AND keyword_id = {$stat['keyword_id']} LIMIT 1";
			$keyword = $this->fetch($sql);
			if(strlen($keyword['label'])>0){
				$rs[] = array("rule"=>$keyword['label'],"value"=>$stat['mentions']);
			}
		}
		return $rs;
	}
	function summary_sentiment_all_channels($campaign_id){
		
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			
		}
		
		$sql = "SELECT g.positive, g.negative, h.total
				FROM (
					SELECT 1 as glue, SUM(total_mention_positive) AS positive,SUM(total_mention_negative) AS negative
					FROM smac_report.daily_campaign_sentiment
					WHERE campaign_id = {$campaign_id} {$filter}
				) g INNER JOIN 
					(SELECT 1 as glue, SUM(total_mention_twitter+total_mention_web+total_mention_facebook) as total
					 FROM smac_report.campaign_rule_volume_history 
					 WHERE campaign_id = {$campaign_id} {$filter}
				) h
				ON g.glue = h.glue";
		
		
		$rs = $this->fetch($sql);
		//neutral = total - (positive+negative)
		return array(array("rule"=>"positive","value"=>$rs['positive']),
				array("rule"=>"negative","value"=>$rs['negative']),
				array("rule"=>"neutral","value"=>$rs['total'] - ($rs['positive']+$rs['negative']))
				);
	}
	function summary_top_kol($campaign_id){
		
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$filter2 = "AND dtpost BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		$sql = "SELECT SUM(total_impression_twitter) AS true_reach
				FROM smac_report.campaign_rule_volume_history 
				WHERE campaign_id={$campaign_id} {$filter}";
		$campaign = $this->fetch($sql);
		$total_impression = intval($campaign['true_reach']);
		$campaign=null;
		unset($campaign);
		
		
		$sql = "SELECT author_id,author_name,author_avatar,SUM(imp) AS impression,
				SUM(rt_imp) AS rt_impression,
				SUM(rt_mention) AS rt_total,SUM(total_mentions) AS mentions 
				FROM smac_report.campaign_author_daily_stats 
				WHERE campaign_id={$campaign_id}
				{$filter2}
				GROUP BY author_id
				ORDER BY impression DESC
				LIMIT 5;";
		
		$stats = $this->fetch($sql,1);
		$rs = array();
		if(is_array($stats)){
			foreach($stats as $stat){
				
				$author_name = $stat['author_id'];
				if(strlen($stat['author_name'])>0){
					$author_name = $stat['author_name'];
				}
				if($stat['impression']>0){
					$rs[] = array("author_id"=>$stat['author_id'],
							  "author_avatar"=>$stat['author_avatar'],
							  "author_name"=>$author_name,
							  "impression"=>$stat['impression'],
							  "rt"=>$stat['rt_total'],
							  "share"=>$stat['impression']/$total_impression);
				}
			}
		}
		
		return $rs;
		
	}
	function summary_top_keywords($campaign_id,$limit=100){
		$limit = intval($limit);
		if($this->from!=null&&$this->to!=null){
			$sql = "SELECT w.keyword,SUM(w.occurance) AS occurance,s.weight AS sentiment
					FROM smac_word.campaign_rule_keyword_history_{$campaign_id} w
					LEFT JOIN 
					smac_sentiment.sentiment_setup_{$campaign_id} s ON w.keyword = s.keyword
					WHERE w.dtpost BETWEEN '{$this->from}' AND '{$this->to}' 
					GROUP BY w.keyword
					ORDER BY occurance DESC
					LIMIT {$limit};";
		}else{	
			$sql = "SELECT w.keyword, s.weight AS sentiment, SUM(w.occurance) AS occurance 
					FROM smac_report.top_rule_wordcloud_summary w LEFT JOIN 
					smac_sentiment.sentiment_setup_{$campaign_id} s ON w.keyword = s.keyword
					WHERE w.campaign_id= {$campaign_id}
					GROUP BY w.keyword,  s.weight
					ORDER BY occurance DESC
					LIMIT {$limit};";
		}
		$keywords = $this->fetch($sql,1);
		$rs = array();
		$max_val = 0;
		$nn=0;
		$n_kw = sizeof($keywords);
		for($i=0;$i<$n_kw;$i++){
			$keyword = $keywords[$i];
			$max_val = max($max_val,intval($keyword['occurance']));
		}
		$sWords = "";
		if($n_kw>0){
			foreach($keywords as $n=>$keyword){
				$arr = array("subdomain"=>$this->request->getParam('subdomain'),
							'page' => 'workflow',
							'act'=>'getTweets',
							'keyword'=>$keyword['keyword'],
							'ajax'=>1);
				$workflow_url = str_replace("req=","",$this->request->encrypt_params($arr));
				$rs[] = array("keyword"=>$keyword['keyword'],
							"value"=>($keyword['occurance']/$max_val)*10,
							"sentiment"=>intval($keyword['sentiment']),
							"link"=>$workflow_url);
			}
		}
		unset($keyword);
		shuffle($rs);
		return $rs;
	}
	
	/**
	 * Twitter Summary Statistics
	 * @param $campaign_id topic's id
	 */
	function twitter_summary_stat($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		//total days
		$sql ="SELECT COUNT(*) AS total FROM (SELECT DISTINCT dtreport
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} 
		{$filter}) a";
		$days = $this->fetch($sql);
		
		//summary stats
		$sql = "SELECT dtreport,
		SUM(total_mention_twitter) as mentions, 
		SUM(total_people_twitter) as people,
		SUM(total_impression_twitter) as impression,
		SUM(total_countries) as countries,
		SUM(total_rt_twitter) as rt_mention
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id}
		{$filter}";
		$f = $this->fetch($sql);
		
		//pii
		$sql = "SELECT dtreport,pii,sentiment_total as total
		FROM (SELECT dtreport,SUM(sum_pii) as pii,SUM(total_mention_positive+total_mention_negative) as sentiment_total 
		FROM 
		smac_report.daily_campaign_sentiment 
		WHERE campaign_id={$campaign_id}
		GROUP BY dtreport) a
		WHERE a.pii <> 0
		ORDER BY dtreport DESC 
		LIMIT 2;";
		$pii = $this->fetch($sql,1);
		
		//mention and impression difference.
		$sql = "SELECT dtreport,
		SUM(total_mention_twitter) as mentions, 
		SUM(total_impression_twitter) as impression,
		SUM(total_rt_twitter) as rt_mention
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} 
		GROUP BY dtreport 
		ORDER BY dtreport DESC 
		LIMIT 2;";
		//print $sql;
		$rs = $this->fetch($sql,1);
		
		
		
		if(sizeof($rs)==2){
			$h1_mention = $rs[0]['mentions'];
			$h0_mention = $rs[1]['mentions'];
			$mention_diff = $h1_mention - $h0_mention;
			$mention_change = 0;
			if($h0_mention!=0){
				$mention_change = round($mention_diff/$h0_mention,2);	
			}
			
			$h1_imp = $rs[0]['impression'];
			$h0_imp = $rs[1]['impression'];
			$imp_diff = $h1_imp - $h0_imp;
			$imp_change = 0;
			if($h0_imp>0){
				$imp_change = round($imp_diff/$h0_imp,2);
			}
			
			$h1_rt = $rs[0]['rt_mention'];
			$h0_rt = $rs[1]['rt_mention'];
			$rt_diff = $h1_imp - $h0_imp;
			$rt_change = 0;
			if($h0_rt>0){
				$rt_change = round($rt_diff/$h0_rt,2);
			}
		}else{
			$mention_diff = 0;
			$mention_change = 0;
			$imp_diff = 0;
			$imp_change = 0;
			$rt_diff = 0;
			$rt_change = 0;
		}
		if(sizeof($pii)>1){
			$pii1 =  round($pii[0]['pii']/$pii[0]['total'],2);
			$pii2 =  round($pii[1]['pii']/$pii[1]['total'],2);
			$pii_diff = round($pii1 - $pii2,2);
			$pii_change = round($pii_diff/$pii2,2);
			$pii_score = $pii1;
			
		}else if(sizeof($pii)==1){
			$pii_score = round($pii[0]['pii']/$pii[0]['total'],2);
			$pii_diff = 0;
			$pii_change = 0;
		}else{
			$pii_score = 0;
		}
		$rs = array("rt"=>array("value"=>$f['rt_mention'],"percent"=>$rt_change,"diff"=>$rt_diff),
					"potential_impression"=>array("value"=>$f['impression'],"percent"=>$imp_change,"diff"=>$imp_diff),
					"people"=>array("value"=>$f['people'],"percent"=>0,"diff"=>0),
					"total_posts"=>array("value"=>$f['mentions'],"percent"=>$mention_change,"diff"=>$mention_diff),
					"original_posts"=>array("value"=>$f['mentions']-$f['rt_mention'],"percent"=>$mention_change,"diff"=>$mention_diff));
		return $rs;
	}
	function twitter_top_kols($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$filter2 = "AND dtpost BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		$sql = "SELECT SUM(total_impression_twitter) AS true_reach
				FROM smac_report.campaign_rule_volume_history 
				WHERE campaign_id={$campaign_id} {$filter}";
				
		$campaign = $this->fetch($sql);
		$total_impression = intval($campaign['true_reach']);
		$campaign=null;
		unset($campaign);
		
		
		$rs = array();
		
			
		
		$sql = "SELECT author_id,author_name,author_avatar,SUM(imp) AS impression,
				SUM(rt_imp) AS rt_impression,
				SUM(rt_mention) AS rt_total,SUM(total_mentions) AS mentions 
				FROM smac_report.campaign_author_daily_stats 
				WHERE campaign_id={$campaign_id} 
				{$filter2}
				GROUP BY author_id
				ORDER BY impression DESC
				LIMIT 5;";
			
		$stats = $this->fetch($sql,1);
		if($total_impression>0){
			$share = floatval($stat['impression']/$total_impression);
		}else{
			$share = 0;
		}
		if(sizeof($stats)){
			foreach($stats as $stat){
				$rs[] = array("author_id"=>$stat['author_id'],
							  "author_avatar"=>$stat['author_avatar'],
							  "author_name"=>$stat['author_name'],
							  "impression"=>$stat['impression'],
							  "rt"=>$stat['rt_total'],
							  "share"=>$share);
			
			}
		}
		return $rs;
			
	}
	function twitter_daily_volume_by_impression($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$limit = "";
		}else{
			$limit = " LIMIT 30";
		}
		$sql = "SELECT dtreport as post_date,
			SUM(total_impression_twitter) as twitter_impression
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id} 
			{$filter}
			GROUP BY dtreport
			ORDER BY dtreport DESC 
			{$limit}";
			$results = $this->fetch($sql,1);
			$response = array();
			$n_size = sizeof($results);
			$response = array();
			if($n_size>0){
				foreach($results as $n=>$rs){
					$results[$n]['ts'] = strtotime($rs['post_date']);
				}
				$results = subval_sort($results, 'ts'); 
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					//foreach($results as $rs){
					//$ts = $rs['post_date'];
					$response[$rs['post_date']] = intval($rs['twitter_impression']);
				}
			}
		return $response;
		
																
	}
	function twitter_daily_volume_by_mention($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$limit = "";
		}else{
			$limit = " LIMIT 30";
		}
			$sql = "SELECT dtreport as post_date,
			SUM(total_mention_twitter) as twitter_mentions
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id}
			{$filter}
			GROUP BY dtreport
			ORDER BY dtreport DESC {$limit}";
			$results = $this->fetch($sql,1);
			$response = array();
			
			$n_size = sizeof($results);
			$response = array();
			if($n_size>0){
				foreach($results as $n=>$rs){
					$results[$n]['ts'] = strtotime($rs['post_date']);
				}
				$results = subval_sort($results, 'ts'); 
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					//foreach($results as $rs){
					//$ts = $rs['post_date'];
					$response[$rs['post_date']] = intval($rs['twitter_mentions']);
				}
			}
		return $response;	
	}
	function twitter_daily_volume_by_sentiment($campaign_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$limit = "";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT 
					dtreport,UNIX_TIMESTAMP(dtreport) as ts,
					sum(total_mention_positive) as positive,
					sum(total_mention_negative) as negative
				FROM 
					smac_report.daily_campaign_sentiment 
				WHERE 
					campaign_id={$campaign_id} 
					AND channel = 1
					{$filter}
				GROUP BY dtreport
				ORDER BY dtreport DESC 
				{$limit}";
		$rs = $this->fetch($sql,1);
		$rs = subval_sort($rs, 'ts');
		for($i=0;$i<sizeof($rs);$i++){
			$rs[$i]['positive'] = intval($rs[$i]['positive']);
			$rs[$i]['negative'] = intval($rs[$i]['negative']);
		}
		return $rs;
	}
	function web($campaign_id,$from=null,$to=null){
		$helper = new FBHelper(null);
		$rs = array("summary"=>$this->web_summary($campaign_id, $from, $to),
							"sentiment"=>$this->web_sentiment($campaign_id, $from, $to),
							"top_websites"=>$this->web_top_sites($campaign_id, $from, $to),
							"daily_volume_by_impression"=>array(),
							"daily_volume_by_mention"=>$this->web_daily_volume_by_mention($campaign_id, $from, $to),
							"top_keywords"=>$this->web_top_keywords($campaign_id, $from, $to)
						   							
				);
		return $rs;
	}
	function web_daily_volume_by_mention($campaign_id,$from,$to){
		if($from!=null&&$to!=null){
			$filter = "AND dtreport BETWEEN '{$from}' AND '{$to}'";
			$limit = "LIMIT 365";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT dtreport as post_date,
			SUM(total_mention_web) as mentions
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id} 
			{$filter}
			GROUP BY dtreport
			ORDER BY dtreport DESC 
			{$limit}";
			$results = $this->fetch($sql,1);
			
			foreach($results as $n=>$rs){
				$results[$n]['ts'] = strtotime($rs['post_date']);
			}
			$results = subval_sort($results, 'ts'); 
			
			
			$response = array();
			
			$n_size = sizeof($results);
			$response = array();
			if($n_size>0){
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					$response[$rs['post_date']] = intval($rs['mentions']);
				}
			}
		return $response;	
	}
	function web_summary($campaign_id,$from,$to){
		if($from==NULL||$to==NULL){
			$sql = "SELECT
					SUM(total_mention_web) as total_posts, 
					SUM(total_author_web) as total_websites
					FROM smac_report.campaign_rule_volume_history 
					WHERE campaign_id={$campaign_id};";
		}else{
			$sql = "SELECT
					SUM(total_mention_web) as total_posts, 
					SUM(total_author_web) as total_websites
					FROM smac_report.campaign_rule_volume_history 
					WHERE campaign_id={$campaign_id}
					AND dtreport BETWEEN '{$from}' AND '{$to}' ";
		}
		$summary = $this->fetch($sql);
		$sql = "SELECT dtreport,
		SUM(total_mention_web) as total_posts, 
		SUM(total_author_web) as total_websites
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id} 
		GROUP BY dtreport 
		ORDER BY dtreport DESC 
		LIMIT 2;";
		//print $sql;
		$rs = $this->fetch($sql,1);
		if(sizeof($rs)==2){
			$h1_post = $rs[0]['total_posts'];
			$h0_post = $rs[1]['total_posts'];
			$post_diff = $h1_post - $h0_post;
			$post_change = 0;
			if($h0_post!=0){
				$post_change = round($post_diff/$h0_post*100,2);	
			}
			$h1_sites = $rs[0]['total_websites'];
			$h0_sites = $rs[1]['total_websites'];
			$site_diff = $h1_sites - $h0_sites;
			$site_change = 0;
			if($h0_sites!=0){
				$h0_sites = round($site_diff/$h0_sites*100,2);	
			}
		}else{
			$post_diff = 0;
			$post_change = 0;
			$site_diff = 0;
			$site_change = 0;
		}
		return array(
					"websites"=>array("value"=>$summary['total_websites'],
								 "percent"=>$site_change,
								 "diff"=>$site_diff),
					"total_posts"=>array("value"=>$summary['total_posts'],
								 "percent"=>$post_change,
								 "diff"=>$post_diff)
				  );
	}
	function web_sentiment($campaign_id,$from,$to){
		return array(array("rule"=>"positive","value"=>45),
					array("rule"=>"negative","value"=>15),
					array("rule"=>"neutral","value"=>32),
					);
	}
	function web_top_sites($campaign_id,$from,$to){
		if($from==null||$to==NULL){
			$sql = "SELECT author_name,author_uri,COUNT(author_name) as total FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} GROUP BY author_name 
					ORDER BY total DESC
					LIMIT 5";
		}else{
			$start_ts = strtotime($from);
			$end_ts = strtotime($to);
			$sql = "SELECT author_name,author_uri,COUNT(author_name) as total FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} 
					AND published_ts >= {$start_ts} AND published_ts<= {$end_ts}
					GROUP BY author_name 
					ORDER BY total DESC
					LIMIT 5";
		}
		$kol = $this->fetch($sql,1);
		return $kol;
	}
	function web_top_keywords($campaign_id,$from,$to){
		if($from==null||$to==NULL){
		
			$sql = "SELECT keyword,SUM(total) as occurance
			        FROM smac_gcs.gcs_wordlist_{$campaign_id}
			        WHERE EXISTS (
			                        SELECT 1 FROM smac_report.campaign_web_feeds g
			                        WHERE g.id = smac_gcs.gcs_wordlist_{$campaign_id}.campaign_web_feeds_id
			                                AND g.campaign_id = {$campaign_id}
			                )
			        GROUP BY keyword
			        ORDER BY occurance DESC LIMIT 100";
		} else {

			$from_date = strtotime(mysql_escape_string($from));
			$to_date = strtotime(mysql_escape_string($to));
						
			$sql = "SELECT keyword,SUM(total) as occurance
			        FROM smac_gcs.gcs_wordlist_{$campaign_id}
			        WHERE EXISTS (
			                        SELECT 1 FROM smac_report.campaign_web_feeds g
			                        WHERE g.id = smac_gcs.gcs_wordlist_{$campaign_id}.campaign_web_feeds_id
			                                AND g.campaign_id = {$campaign_id}
			                                AND g.published_ts BETWEEN {$from_date} and {$to_date}
			                )
			        GROUP BY keyword
			        ORDER BY occurance DESC LIMIT 100";		
		}
		$keywords = $this->fetch($sql,1);
		$rs = array();
		$max_val = 0;
		$nn=0;
		for($t=0;$t<sizeof($keywords);$t++){
			$keyword = $keywords[$t];
			$max_val = max($max_val,intval($keyword['occurance']));
		}
		
		$sWords = "";
		for($n=0;$n<sizeof($keywords);$n++){
			$keyword = $keywords[$n];
			if(strlen($keyword['keyword'])>0){
				if($n>0){
					$sWords.=",";
				}
				$sWords.="'{$keyword['keyword']}'";
				$nn++;
			}
			$rs[] = array("keyword"=>$keyword['keyword'],"value"=>($keyword['occurance']/$max_val)*10);
		}
		
		if($nn>0){
			 $sql = "SELECT keyword,weight as sentiment FROM smac_sentiment.sentiment_setup_{$campaign_id}
			 		 WHERE keyword IN ({$sWords}) LIMIT {$nn}";
		
			$sentiments = @$this->fetch($sql,1);
		}
		
		if(is_array($sentiments)){
			foreach($sentiments as $s){
				foreach($rs as $n=>$word){
					if(strcmp($word['keyword'],$s['keyword'])==0){
						$rs[$n]['sentiment'] = $s['sentiment'];
						break;
					}
				}
			}
		}
		unset($sentiments);
		unset($keyword);
		
		shuffle($rs);
		return $rs;
		
	}
	/**
	 * current top conversations for all channels.
	 * if the post has been flagged to workflow, we provide the flag field with a value of 1, 
	 * otherwise 0.
	 */
	function summary_top_posts($campaign_id,$from=null,$to=null,$since_id=0,$limit=10){
		if($from!=null&&$to!=null){
			$filter = "AND dtpost BETWEEN '{$from}' AND '{$to}'";
		}
		$sql = "SELECT id,feed_id,dtpost,author,author_id,content,channel 
				FROM smac_report.latest_conversation 
				WHERE campaign_id={$campaign_id} AND id > {$since_id}
				{$filter}
				ORDER BY id DESC LIMIT {$limit}";
		$rs = $this->fetch($sql,1);
		$posts = array();
		//$twitter_ids = array();
		//$fb_ids = array();
		//$web_ids = array();
		foreach($rs as $r){
			$location = "";
			$r['content'] = htmlspecialchars($r['content']);
			if($r['channel']==1){
				$sql = "SELECT author_avatar,location
						FROM smac_feeds.campaign_feeds_{$campaign_id} WHERE author_id = '{$r['author_id']}' LIMIT 1";
				$author = $this->fetch($sql);
				$pic = $author['author_avatar'];
				$location = $author['location'];
				
				$sql = "SELECT a.keyword,b.weight FROM smac_word.feed_wordlist_{$campaign_id} a
						INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} b
						ON a.keyword = b.keyword 
						WHERE a.feed_id = '{$r['feed_id']}' 
						AND b.weight <> 0;";
				$ksentiment = $this->fetch($sql,1);
				
			}else if($r['channel']==2){
				$pic = "https://graph.facebook.com/{$r['author_id']}/picture";
				
				$sql = "SELECT b.keyword,b.weight FROM smac_fb.fb_wordlist_{$campaign_id} a 
						INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} b
						ON a.keyword = b.keyword 						
						WHERE a.feed_id={$r['feed_id']} AND weight <> 0;";
				$ksentiment = $this->fetch($sql,1);
				
			}else{
				$web_ids = $r['feed_id'];
				$sql = "SELECT c.keyword,c.weight FROM smac_gcs.gcs_wordlist_{$campaign_id} a
						INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} c
						ON a.keyword = c.keyword
						WHERE EXISTS (SELECT 1 FROM smac_report.campaign_web_feeds b 
								WHERE b.feed_id = {$r['feed_id']} 
								AND a.campaign_web_feeds_id = b.id 
								LIMIT 1)
						AND c.weight <> 0;";
				$ksentiment = $this->fetch($sql,1);
			}
			$r['content'] = $this->highlight_content($r['content'], $ksentiment);
			
			
			$final_sentiment = 0;
			if($r['channel']==1){
				$feed_sentiment = $this->fetch("SELECT b.sentiment,b.rt_sentiment 
											FROM smac_feeds.campaign_feeds_{$campaign_id} a 
											 INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b 
											 ON a.id = b.feed_id
											 WHERE a.feed_id= '{$r['feed_id']}'
											 LIMIT 1");
			
				
				if(intval($feed_sentiment['rt_sentiment'])!=0){
					$final_sentiment = intval($feed_sentiment['rt_sentiment']);
				}else{
					$final_sentiment = intval($feed_sentiment['sentiment']);
				}
			}else if($r['channel']==2){
				$feed_sentiment = $this->fetch("SELECT a.id,sentiment 
							FROM smac_fb.campaign_fb a 
							LEFT JOIN smac_fb.campaign_fb_sentiment b 
							ON a.id = b.campaign_fb_id 
							WHERE a.campaign_id={$campaign_id} 
							AND a.feeds_facebook_id='{$r['feed_id']}'
							LIMIT 1;");
					
				if(intval($feed_sentiment['sentiment'])!=0){
					$final_sentiment = intval($feed_sentiment['sentiment']);
				}
				
			}else if($r['channel']==3){
				$feed_sentiment = $this->fetch("SELECT sentiment 
												FROM smac_report.campaign_web_feeds a 
												LEFT JOIN smac_gcs.campaign_gcs_sentiment b 
												ON a.id = b.campaign_web_feeds_id
												WHERE a.campaign_id={$campaign_id} 
												AND a.feed_id='{$r['feed_id']}'
												LIMIT 1;");
					
				if(intval($feed_sentiment['sentiment'])!=0){
					$final_sentiment = intval($feed_sentiment['sentiment']);
				}
				unset($feed_sentiment);
				
				//get the web_feeds_id
				$web_feeds = $this->fetch("SELECT id FROM smac_report.campaign_web_feeds 
											WHERE 
											campaign_id={$campaign_id} 
											AND feed_id = {$r['feed_id']};");
			}else{
				if(is_array($ksentiment)){
					foreach($ksentiment as $k){
						$final_sentiment +=$k['weight']; 
					}
				}
			}
			//link for reply
			$c = array("subdomain"=>$this->request->getParam('subdomain'),
					 'page' => 'workflow','act'=>'flag',
					 'keyword'=>'N/A',
					 'feed_id'=>trim($r['feed_id']),'opt'=>1,'ajax'=>1,'type'=>2);
			$reply_url = str_replace("req=","",$this->request->encrypt_params($c));
			//-->
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],$r['channel']);
			//-->end of check
			$posts[] = array("id"=>$r['feed_id'],
						   "published"=>$r['dtpost'],
						   "author_id"=>$r['author_id'],
						   "name"=>htmlspecialchars($r['author']),
						   "txt"=>$r['content'],
						   "channel"=>$r['channel'],
						   "sentiment"=>$final_sentiment,
						   "profile_image_url"=>$pic,
						   "location"=>trim($location),
						   "reply_url"=>'',
						   "flag"=>$flag,
						   'screenshot'=>$this->_getWebPic($web_feeds['id'])
						   );
			
		}
		return $posts;
	}
	
	function twitter_top_posts($campaign_id,$from=null,$to=null,$limit=10){
		if($from!=null&&$to!=null){
			$filter = "AND dtreport BETWEEN '{$from}' AND '{$to}'";
			$filter2 = "AND published_date BETWEEN '{$from}' AND '{$to}'";
		}
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		//total impressions
		$sql = "SELECT SUM(total_impression_twitter+total_rt_impression_twitter) AS true_reach
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id}
		{$filter}";
		$campaign = $this->fetch($sql);
		$total_impression = $campaign['true_reach'];
		$campaign=null;
		unset($campaign);
		$sql = "SELECT id,feed_id,local_rt_count as rt_total,
					local_rt_impresion as rt_imp,
					author_id,
					author_name,
					author_avatar AS avatar_pic, 
					published_date,
					followers AS imp,generator,content,location
				FROM smac_feeds.campaign_feeds_{$campaign_id} 
				WHERE is_active = 1
				{$filter2}
				ORDER BY followers desc
				LIMIT {$limit};";
		
		$rs = $this->fetch($sql,1);
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
			//link for reply
			$c = array("subdomain"=>$this->request->getParam('subdomain'),
					 'page' => 'workflow','act'=>'flag',
					 'keyword'=>'N/A',
					 'feed_id'=>trim($r['feed_id']),'opt'=>1,'ajax'=>1,'type'=>2);
			$reply_url = str_replace("req=","",$this->request->encrypt_params($c));
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],1);
			//-->end of check
			$posts[] = array("id"=>$r['feed_id'],
						   "published"=>$r['published_date'],
						   "author_id"=>$r['author_id'],
						   "name"=>htmlspecialchars($r['author_name']),
						   "txt"=>$r['content'],
						   "device"=>$this->get_device($r['generator'],$devices),
						   "profile_image_url"=>$r['avatar_pic'],
						   "impression"=>$r['imp'],
						   "rt"=>$r['rt_total'],
						   "rt_imp"=>$r['rt_imp'],
						   "share"=>$share,
						   "sentiment"=>$final_sentiment,
						   "location"=>trim($r['location']),
						   "reply_url"=>'',
						   'flag'=>$flag);		
		}
		return $posts;
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
	function fb_top_posts($campaign_id,$from,$to,$limit=10){
		if($from!=null&&$to!=null){
			$from_ts = strtotime($from);
			$to_ts = strtotime($to);
			$filter = "AND created_time_ts BETWEEN {$from_ts} AND {$to_ts}";
			
		}
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		$sql = "SELECT id,feeds_facebook_id as feed_id,
						from_object_id,from_object_name,caption,story,
						likes_count,message,description,created_time,
						created_time_ts,application_object_name
		        FROM smac_fb.campaign_fb
		        WHERE campaign_id = {$campaign_id} AND is_active = 1
		        {$filter}
		        ORDER BY likes_count DESC
		        limit {$limit};";
		
		$rs = $this->fetch($sql,1);
		
		$num = sizeof($rs);
		$posts = array();
		for($i=0;$i<sizeof($rs);$i++){
			$r = $rs[$i];
			$r['content'] = "";
			if(strlen($r['caption'])>0){
				$r['content'].=$r['caption']." ";
			}
			
			if(strlen($r['message'])>0){
				$r['content'].= "{$r['message']} ";
			}
			if(strlen($r['description'])>0){
				$r['content'].= "{$r['description']} ";
			}
			if(strlen($r['story'])>0){
				$r['content'].= "{$r['story']} ";
			}
			$r['content'] = htmlspecialchars($r['content']);
			$sql = "SELECT b.keyword,b.weight FROM smac_fb.fb_wordlist_{$campaign_id} a 
					INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} b
					ON a.keyword = b.keyword 						
					WHERE a.feed_id={$r['feed_id']} AND weight <> 0;";
					
			$ksentiment = $this->fetch($sql,1);
			$r['content'] = $this->highlight_content($r['content'], $ksentiment);
			
			$final_sentiment = 0;
			if(is_array($ksentiment)){
				foreach($ksentiment as $k){
					$final_sentiment +=$k['weight']; 
				}
			}
			$feed_sentiment = $this->fetch("SELECT sentiment 
											FROM smac_fb.campaign_fb_sentiment
											WHERE campaign_fb_id = {$r['id']} LIMIT 1");
					
			if(intval($feed_sentiment['sentiment'])!=0){
				$final_sentiment = intval($feed_sentiment['sentiment']);
			}
			
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],2);
			//-->end of check
			$posts[] = array(
						'id'=>$r['feed_id'],
						'published'=>date("d/m/Y",$r['created_time_ts']),
						'name'=>$r['from_object_name'],
						'url'=>"http://www.facebook.com/".$r['from_object_id'],
						'txt'=>$r['content'],
						'device'=>$this->get_device($r['application_object_name'],$devices),
						'profile_image_url'=>"https://graph.facebook.com/".$r['from_object_id']."/picture",
						'like'=>number_format($r['likes_count']),
						'sentiment'=>$final_sentiment,
						'flag'=>$flag);
		}
		unset($rs);
		return $posts;
	}
	/**
	 * website top posts
	 */
	function web_top_posts($campaign_id,$from,$to,$limit=10){
		if($from!=null&&$to!=null){
			$from_ts = strtotime($from);
			$to_ts = strtotime($to);
			$filter = "AND published_ts BETWEEN {$from_ts} AND {$to_ts}";
			
		}
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		//check campaign language setting. is it restrict to specific language ?
		$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		if($campaign['lang']=="all"){
			$sql = "SELECT id,feed_id, link,author_name, author_uri, comments, title, summary, 
			published, source_service,generator
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id}
			{$filter}
			ORDER BY rank ASC LIMIT ".$limit;
		}else{
						
			$sql = "SELECT id,feed_id, link,author_name, author_uri, comments, title, summary, 
			published, source_service,generator
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND lang='{$campaign['lang']}'
			{$filter}
			ORDER BY rank ASC LIMIT ".$limit;
		}
		
		$result = $this->fetch($sql,1);
		$rs = array();
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				$r['summary'] = clean_ascii($r['summary']);
				$sql = "SELECT c.keyword,c.weight FROM smac_gcs.gcs_wordlist_{$campaign_id} a
						INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} c
						ON a.keyword = c.keyword
						WHERE a.campaign_web_feeds_id = {$r['id']}
						AND c.weight <> 0;";
				$ksentiment = $this->fetch($sql,1);
				$r['summary'] = $this->highlight_content($r['summary'], $ksentiment);
				
				$final_sentiment = 0;
				if(is_array($ksentiment)){
					foreach($ksentiment as $k){
						$final_sentiment +=$k['weight']; 
					}
				}
				$feed_sentiment = $this->fetch("SELECT sentiment 
											FROM smac_gcs.campaign_gcs_sentiment
											WHERE campaign_web_feeds_id = {$r['id']} LIMIT 1");
				
				if(intval($feed_sentiment['sentiment'])!=0){
					$final_sentiment = intval($feed_sentiment['sentiment']);
				}
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
				'txt'=>$r['summary'],
				'sentiment'=>$final_sentiment,
				'device'=>$this->get_device($r['generator'],$devices),
				'comments'=>number_format($r['comments']),
				'flag'=>$flag
				);
			}
		}
		$result = null;
		return $rs;
	}
	
	function twitter_posts($campaign_id,$start=0,$limit=10){
		$start = intval($start);
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		//total impressions
		$sql = "SELECT SUM(total_impression_twitter+total_rt_impression_twitter) AS true_reach
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id};";
		$campaign = $this->fetch($sql);
		$total_impression = $campaign['true_reach'];
		$campaign=null;
		unset($campaign);
		
		$sql = "SELECT id,feed_id,local_rt_count as rt_total,
				local_rt_impresion as rt_imp,
				author_id,
				author_name,
				author_avatar AS avatar_pic, 
				published_date,
				followers AS imp,generator,content,location
			FROM smac_feeds.campaign_feeds_{$campaign_id} 
			WHERE is_active = 1
			ORDER BY followers desc
			LIMIT {$start},{$limit};";
		
		$sql2 = "SELECT SUM(total_mention_twitter) AS total
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id};";
		
		$rs = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
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
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],1);
			//-->end of check
			$posts[] = array("id"=>$r['feed_id'],
						   "published"=>date("d/m/Y",strtotime($r['published_date'])),
						   "author_id"=>$r['author_id'],
						   "name"=>htmlspecialchars($r['author_name']),
						   "txt"=>($r['content']),
						   "device"=>$this->get_device($r['generator'],$devices),
						   "profile_image_url"=>$r['avatar_pic'],
						   "impression"=>$r['imp'],
						   "rt"=>$r['rt_total'],
						   "rt_imp"=>$r['rt_imp'],
						   "location"=>trim($r['locaiton']),
						   "share"=>$share,
						   "sentiment"=>$final_sentiment,
						   "flag"=>$flag);		
		}
		return array("feeds"=>$posts,"total_rows"=>$rows['total']);
	}
	function fb_posts($campaign_id,$start=0,$limit=10){
		$start = intval($start);
		
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		$sql = "SELECT id,feeds_facebook_id as feed_id,
						from_object_id,from_object_name,
						likes_count,message,created_time,
						created_time_ts,application_object_name,description
		        FROM smac_fb.campaign_fb
		        WHERE campaign_id = {$campaign_id} AND is_active = 1
		        ORDER BY likes_count DESC
		        limit {$start},{$limit};";
				
		$sql2 = "SELECT COUNT(*) AS total
		        FROM smac_fb.campaign_fb
		        WHERE campaign_id = {$campaign_id} AND is_active = 1;";
				
		$rs = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		
		$num = sizeof($rs);
		$posts = array();
		for($i=0;$i<sizeof($rs);$i++){
			$r = $rs[$i];
			$r['content'] = "";
			if(strlen($r['caption'])>0){
				$r['content'].=$r['caption']." ";
			}
			
			if(strlen($r['message'])>0){
				$r['content'].= "{$r['message']} ";
			}
			if(strlen($r['description'])>0){
				$r['content'].= "{$r['description']} ";
			}
			if(strlen($r['story'])>0){
				$r['content'].= "{$r['story']} ";
			}
			$r['content'] = htmlspecialchars($r['content']);
			$sql = "SELECT b.keyword,b.weight FROM smac_fb.fb_wordlist_{$campaign_id} a 
					INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} b
					ON a.keyword = b.keyword 						
					WHERE a.feed_id={$r['feed_id']} AND weight <> 0;";
					
			$ksentiment = $this->fetch($sql,1);
			$r['content'] = $this->highlight_content($r['content'], $ksentiment);
			
			$final_sentiment = 0;
			if(is_array($ksentiment)){
				foreach($ksentiment as $k){
					$final_sentiment +=$k['weight']; 
				}
			}
			$feed_sentiment = $this->fetch("SELECT sentiment 
											FROM smac_fb.campaign_fb_sentiment
											WHERE campaign_fb_id = {$r['id']} LIMIT 1");
					
			if(intval($feed_sentiment['sentiment'])!=0){
				$final_sentiment = intval($feed_sentiment['sentiment']);
			}
			
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],2);
			//-->end of check
			$posts[] = array(
						'id'=>$r['feed_id'],
						'published'=>date("d/m/Y",$r['created_time_ts']),
						'name'=>$r['from_object_name'],
						'url'=>"http://www.facebook.com/".$r['from_object_id'],
						'txt'=>$r['content'],
						'device'=>$this->get_device($r['application_object_name'],$devices),
						'profile_image_url'=>"https://graph.facebook.com/".$r['from_object_id']."/picture",
						'like'=>number_format($r['likes_count']),
						'sentiment'=>$final_sentiment,
						'flag'=>$flag);
		}
		unset($rs);
		return array("feeds"=>$posts,"total_rows"=>$rows['total']);
	}
	function web_posts($campaign_id,$start=0,$limit=10){
		$start = intval($start);
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		//check campaign language setting. is it restrict to specific language ?
		$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		if($campaign['lang']=="all"){
			$sql = "SELECT feed_id, link,author_name, author_uri, comments, title, summary, 
			published, source_service,generator,published_ts
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id}
			ORDER BY rank ASC LIMIT {$start},{$limit}";
			
			$sql2 = "SELECT COUNT(*) as total
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} LIMIT 1";
			
		}else{
			$sql = "SELECT feed_id, link,author_name, author_uri, comments, title, summary, 
			published, source_service,generator,published_ts
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND lang='{$campaign['lang']}'
			ORDER BY rank ASC LIMIT {$start},{$limit}";
			
			$sql2 = "SELECT COUNT(*) as total
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND lang='{$campaign['lang']}' LIMIT 1";
		}
		
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
				'txt'=>clean_ascii($r['summary']),
				'published'=>date("d/m/Y",$r['published_ts']),
				'device'=>$this->get_device($r['generator'],$devices),
				'comments'=>number_format($r['comments']),
				'flag'=>$flag
				);
			}
		}
		$result = null;
		return array("feeds"=>$rs,"total_rows"=>$rows['total']);
	}
	function twitter_channel_stats($campaign_id,$twitter_id){
		$campaign_id = intval($campaign_id);
		$twitter_id = cleanXSS($twitter_id);
		$twitter_id = mysql_escape_string($twitter_id);
		
		$rs = array("daily_volume"=>$this->twitter_channel_daily($campaign_id,$twitter_id),
					"summary"=>$this->twitter_channel_summary($campaign_id,$twitter_id)
		
		);
		return $rs;
	}
	function twitter_channel_summary($campaign_id,$twitter_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtpost BETWEEN '{$this->from}' AND '{$this->to}'";
			$filter2 = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		$sql = "SELECT author_id,author_name,author_avatar,
				SUM(total_mentions) AS mention,
				SUM(imp) AS impression,
				SUM(rt_mention) AS rt,0 AS followers,0 AS unfollows
				FROM smac_report.campaign_author_daily_stats
				WHERE campaign_id={$campaign_id} AND author_id='{$twitter_id}'
				{$filter}
				GROUP BY author_id;";
		$rs = $this->fetch($sql);
		
		$sql = "SELECT dtreport, current_followers, new_followers, unfollowers 
				FROM smac_report.daily_twitter_follower
				WHERE campaign_id = {$campaign_id} AND author_id = '{$twitter_id}'
				{$filter2}
				ORDER BY id DESC LIMIT 1;";
		$follow = $this->fetch($sql);
		
		$result = array(
			"author_id"=>$rs['author_id'],
			"author_name"=>$rs['author_name'],
			"author_avatar"=>$rs['author_avatar'],
			"mention"=>$rs['mention'],
			"rt"=>$rs['rt'],
			"followers"=>$follow['current_followers'],
			"new_followers"=>$follow['new_followers'],
			"unfollows"=>$follow['unfollowers']
		);
		return $result;
	}
	function twitter_channel_daily($campaign_id,$twitter_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND dtpost BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		$sql = "SELECT
					dtpost
					,SUM(total_mentions) AS mention
					,SUM(imp) AS impression
					,SUM(rt_mention) AS rt
				FROM
					smac_report.campaign_author_daily_stats
				WHERE
					campaign_id={$campaign_id} AND author_id='{$twitter_id}'
					{$filter}
					GROUP BY dtpost
					ORDER BY id ASC LIMIT 365";
		$rs = $this->fetch($sql,1);
		return $rs;
	}
	function fb_channel_stats($campaign_id,$fb_id){
		$campaign_id = intval($campaign_id);
		$fb_id = cleanXSS($fb_id);
		$fb_id = mysql_escape_string($fb_id);
		
		$rs = array("daily_volume"=>$this->fb_channel_daily($campaign_id,$fb_id),
					"summary"=>$this->fb_channel_summary($campaign_id,$fb_id)
		
		);
		return $rs;
	}
	function fb_channel_daily($campaign_id,$fb_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND published_date BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		$sql = "SELECT
				published_date as dtpost
				,SUM(mentions) AS mention
				FROM
					smac_fb.daily_fb_people_stat
				WHERE
					campaign_id={$campaign_id}
					AND author_id='{$fb_id}'
					{$filter}	
				GROUP BY published_date
				 LIMIT 365";
		$rs = $this->fetch($sql,1);
		return $rs;
	}
	function fb_channel_summary($campaign_id,$fb_id){
		if($this->from!=null&&$this->to!=null){
			$filter = "AND published_date BETWEEN '{$this->from}' AND '{$this->to}'";
		}
		$sql = "SELECT
				author_id,author_name,
				SUM(mentions) AS mention,
				SUM(likes) AS likes,
				0 AS new_like,
				0 AS unlike
				FROM
					smac_fb.daily_fb_people_stat
				WHERE
					campaign_id={$campaign_id}
					AND author_id='{$fb_id}'
					{$filter}
					GROUP BY author_id
				LIMIT 1
				;";
		$rs = $this->fetch($sql);
		
		
		$result = array(
			"author_id"=>$rs['author_id'],
			"author_name"=>$rs['author_name'],
			"author_avatar"=>"https://graph.facebook.com/{$fb_id}/picture",
			"mention"=>$rs['mention'],
			"likes"=>$rs['likes'],
			"new_like"=>$rs['new_like'],
			"unlike"=>$rs['unlike']
		);
		return $result;
	}
	function twitter_channels($campaign_id){
		$sql = "SELECT twitter_account FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$rs = $this->fetch($sql);
		$accounts = explode(",",$rs['twitter_account']);
		return $accounts;
	}
	function fb_channels($campaign_id){
		$sql = "SELECT fb_account FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$rs = $this->fetch($sql);
		
		$accounts = explode(",",$rs['fb_account']);
		$fb_id = array();
		for($i=0;$i<sizeof($accounts);$i++){
			$fb_account = str_replace("https://www.facebook.com/","",$accounts[$i]);
			$fb_account = str_replace("http://www.facebook.com/","",$fb_account);
			$sql = "SELECT id,username as name 
					FROM smac_fb.fb_user_profile WHERE username = '{$fb_account}' LIMIT 1;";
			$rs = $this->fetch($sql);
			$fb_id[] = $rs;
		}
		return $fb_id;
	}
	function twitter_channel_posts($campaign_id,$twitter_id,$start,$total=10){
		if($total>10){
			$total = 10;
		}
		$start = intval($start);
		$campaign_id = intval($campaign_id);
		$twitter_id = mysql_escape_string(cleanXSS($twitter_id));
		
		//total impressions
		$sql = "SELECT SUM(total_impression_twitter+total_rt_impression_twitter) AS true_reach
		FROM smac_report.campaign_rule_volume_history 
		WHERE campaign_id={$campaign_id};";
		$campaign = $this->fetch($sql);
		$total_impression = $campaign['true_reach'];
		$campaign=null;
		unset($campaign);
		
		$sql = "SELECT feed_id,published_datetime,author_id,author_name,author_avatar,
				content,generator,followers AS impression,rt_count AS rt_total 
				FROM smac_feeds.campaign_feeds_{$campaign_id} WHERE author_id='{$twitter_id}' LIMIT {$start},{$total};";
		$rs = $this->fetch($sql,1);
	
		$sql = "SELECT COUNT(id) as total
				FROM smac_feeds.campaign_feeds_{$campaign_id} WHERE author_id='{$twitter_id}'";
		$rows = $this->fetch($sql);
		
		for($i=0;$i<sizeof($rs);$i++){
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$rs[$i]['feed_id'],1);
			//-->end of check
			$rs[$i]['share'] = round(($rs[$i]['impression'] / $total_impression)*100,5);
			$rs[$i]['flag'] = $flag;
		}
		return array("feeds"=>$rs,"total_rows"=>$rows['total']);
	}
	function fb_channel_posts($campaign_id,$fb_id,$start,$total=10){
		if($total>10){
			$total = 10;
		}
		$start = intval($start);
		$campaign_id = intval($campaign_id);
		$fb_id = mysql_escape_string(cleanXSS($fb_id));
		
		
		$sql = "SELECT feeds_facebook_id AS feed_id,
						from_object_id,from_object_name,
						likes_count,message,created_time,
						created_time_ts,application_object_name,description
		        FROM smac_fb.campaign_fb
		        WHERE campaign_id ={$campaign_id}
		        AND from_object_id='{$fb_id}' AND is_active = 1
		        ORDER BY likes_count DESC
		        LIMIT {$start},{$total};";
		        
		$rs = $this->fetch($sql,1);
		for($i=0;$i<sizeof($rs);$i++){
			//check for flag
			$flag = $this->is_workflow_flag($campaign_id,$rs[$i]['feed_id'],2);
			//-->end of check
			$rs[$i]['flag'] = $flag;
		}
		$sql = "SELECT COUNT(id) as total
		        FROM smac_fb.campaign_fb
		        WHERE campaign_id ={$campaign_id}
		        AND from_object_id='{$fb_id}' AND is_active = 1";
		$rows = $this->fetch($sql);
		
		return array("feeds"=>$rs,"total_rows"=>$rows['total']);
	}
	/**
	 * Youtube stuffs
	 */
	 function video($campaign_id,$from=null,$to=null){
		$helper = new FBHelper(null);
		$rs = array("summary"=>$this->video_summary($campaign_id, $from, $to),
					"sentiment"=>$this->video_sentiment($campaign_id, $from, $to),
					"top_people"=>$this->video_top_people($campaign_id, $from, $to),
					"daily_volume_by_impression"=>$this->video_daily_volume_by_impression($campaign_id, $from, $to),
					"daily_volume_by_like"=>$this->video_daily_volume_by_like($campaign_id, $from, $to),
					"daily_volume_by_dislike"=>$this->video_daily_volume_by_dislike($campaign_id, $from, $to),
					"daily_volume_by_mention"=>$this->video_daily_volume_by_mention($campaign_id, $from, $to),
					"top_keywords"=>$this->video_top_keywords($campaign_id, $from, $to)
						   							
				);
		return $rs;
	 }
	 function video_top_posts($campaign_id,$from,$to,$limit=10){
		if($from!=null&&$to!=null){
			$filter = "AND published_date BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59'";
		}
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		//check campaign language setting. is it restrict to specific language ?
		$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		$sql = "SELECT id,published_date,feed_id,author_id, video_url, published_date, title, description,preview_url,
				likes_count,view_count
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id} 
				{$filter}
				ORDER BY published_date DESC LIMIT ".$limit;

		$result = $this->fetch($sql,1);
		$rs = array();
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				//check for flag
				$flag = $this->is_workflow_flag($campaign_id,$r['feed_id'],4);
				//-->end of check
				$comments = $this->fetch("SELECT COUNT(id) AS total FROM smac_youtube.video_comments 
											WHERE campaign_youtube_id = {$r['id']};");
				$rs[] = array('id'=>$r['id'],
							  'url'=>$r['video_url'],
							'link'=>$r['video_url'],
				'name'=>clean_ascii($r['author_id']),
				'title'=>clean_ascii($r['title']),
				'txt'=>clean_ascii($r['description']),
				'device'=>'',
				'total_likes'=>intval($r['likes_count']),
				'total_comments'=>intval($comments['total']),
				'total_views'=>intval($r['view_count']),
				'published_date'=>$r['published_date'],
				'preview_url'=>$r['preview_url'],
				'flag'=>$flag
				);
			}
		}
		$result = null;
		return $rs;
	}
	 function video_summary($campaign_id,$from,$to){
	 	if($this->from!=null&&$this->to!=null){
			$filter = "AND dtreport BETWEEN '{$this->from}' AND '{$this->to}'";
			$filter2 = "AND published_date BETWEEN '{$this->from} 00:00:00' AND '{$this->to} 23:59:59'";
		}
	 	$sql = "SELECT sum(total_youtube_video) as total
				FROM smac_report.campaign_rule_volume_history 
				WHERE campaign_id= {$campaign_id} ";
		$video = $this->fetch($sql);
		
		$sql ="SELECT COUNT(DISTINCT author_uri) as total 
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id} {$filter2}";
				
		$people = $this->fetch($sql);
		return array(
					"total_video"=>array("value"=>$video['total'],
								 "percent"=>0,
								 "diff"=>0),
					"total_people"=>array("value"=>$people['total'],
								 "percent"=>0,
								 "diff"=>0)
				  );
	 	
	 }
	 function video_sentiment($campaign_id,$from,$to){
	 	return array();
	 }
	 function video_top_people($campaign_id,$from,$to){
	 	if($from!=null&&$to!=null){
			$filter2 = "AND published_date BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59'";
		}
	 	$sql = "SELECT author_id, sum(view_count)  as views,preview_url,video_url
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id}
				{$filter2}
				GROUP BY author_id
				ORDER BY views DESC
				LIMIT 10;";
		$rs = $this->fetch($sql,1);
	 	return $rs;
	 }
	  function video_daily_volume_by_impression($campaign_id,$from,$to){
	 	if($from!=null&&$to!=null){
			$filter = "AND published_date BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59'";
			$limit = "LIMIT 365";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT DATE_FORMAT(published_date, '%Y-%m-%d') as dtreport, SUM(view_count) as impression
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id}
				{$filter}
				GROUP BY DATE_FORMAT(published_date, '%Y-%m-%d')
				ORDER BY dtreport DESC
				{$limit}";
		
			$results = $this->fetch($sql,1);
			
			foreach($results as $n=>$rs){
				$results[$n]['ts'] = strtotime($rs['dtreport']);
			}
			$results = subval_sort($results, 'ts'); 
			
			
			$response = array();
			
			$n_size = sizeof($results);
			$response = array();
			if($n_size>0){
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					$response[$rs['dtreport']] = intval($rs['impression']);
				}
			}
		return $response;
	 }
	 
	  function video_daily_volume_by_like($campaign_id,$from,$to){
	 	if($from!=null&&$to!=null){
			$filter = "AND published_date BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59'";
			$limit = "LIMIT 365";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT DATE_FORMAT(published_date, '%Y-%m-%d') as dtreport, sum(likes_count) as likes_count
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id}
				{$filter}
				GROUP BY DATE_FORMAT(published_date, '%Y-%m-%d')
				ORDER BY dtreport DESC
				{$limit}";
		
			$results = $this->fetch($sql,1);
			
			foreach($results as $n=>$rs){
				$results[$n]['ts'] = strtotime($rs['dtreport']);
			}
			$results = subval_sort($results, 'ts'); 
			
			
			$response = array();
			
			$n_size = sizeof($results);
			$response = array();
			if($n_size>0){
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					$response[$rs['dtreport']] = intval($rs['likes_count']);
				}
			}
		return $response;
	 }
	  function video_daily_volume_by_dislike($campaign_id,$from,$to){
	 	if($from!=null&&$to!=null){
			$filter = "AND published_date BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59'";
			$limit = "LIMIT 365";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT DATE_FORMAT(published_date, '%Y-%m-%d') as dtreport
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id}
				{$filter}
				GROUP BY DATE_FORMAT(published_date, '%Y-%m-%d')
				ORDER BY dtreport DESC
				{$limit}";
		
			$results = $this->fetch($sql,1);
			
			foreach($results as $n=>$rs){
				$results[$n]['ts'] = strtotime($rs['dtreport']);
			}
			$results = subval_sort($results, 'ts'); 
			
			
			$response = array();
			
			$n_size = sizeof($results);
			$response = array();
			if($n_size>0){
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					$response[$rs['dtreport']] = 0;
				}
			}
		return $response;
	 }
	 function video_daily_volume_by_mention($campaign_id,$from,$to){
	 	if($from!=null&&$to!=null){
			$filter = "AND published_date BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59'";
			$limit = "LIMIT 365";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT DATE_FORMAT(published_date, '%Y-%m-%d') as dtreport, COUNT(*) as mentions
				FROM smac_youtube.campaign_youtube
				WHERE campaign_id = {$campaign_id}
				{$filter}
				GROUP BY DATE_FORMAT(published_date, '%Y-%m-%d')
				ORDER BY dtreport DESC
				{$limit}";
		
			$results = $this->fetch($sql,1);
			
			foreach($results as $n=>$rs){
				$results[$n]['ts'] = strtotime($rs['dtreport']);
			}
			$results = subval_sort($results, 'ts'); 
			
			
			$response = array();
			
			$n_size = sizeof($results);
			$response = array();
			if($n_size>0){
				for($i=0;$i<$n_size;$i++){
					$rs = $results[$i];
					$response[$rs['dtreport']] = intval($rs['mentions']);
				}
			}
		return $response;
	 }
	 function video_top_keywords($campaign_id,$from,$to){
	 	$sql = "SELECT keyword, occurance
				FROM smac_youtube.top_youtube_comment_keyword
				WHERE campaign_id= {$campaign_id}
				ORDER BY occurance DESC
				LIMIT 20;";
		$keywords = $this->fetch($sql,1);
		$rs = array();
		$max_val = 0;
		$nn=0;
		for($t=0;$t<sizeof($keywords);$t++){
			$keyword = $keywords[$t];
			$max_val = max($max_val,intval($keyword['occurance']));
		}
		
		$sWords = "";
		for($n=0;$n<sizeof($keywords);$n++){
			$keyword = $keywords[$n];
			if(strlen($keyword['keyword'])>0){
				if($n>0){
					$sWords.=",";
				}
				$sWords.="'{$keyword['keyword']}'";
				$nn++;
			}
			$rs[] = array("keyword"=>$keyword['keyword'],"value"=>($keyword['occurance']/$max_val)*10);
		}
		
		if($nn>0){
			 $sql = "SELECT keyword,weight as sentiment FROM smac_sentiment.sentiment_setup_{$campaign_id}
			 		 WHERE keyword IN ({$sWords}) LIMIT {$nn}";
		
			$sentiments = @$this->fetch($sql,1);
		}
		
		if(is_array($sentiments)){
			foreach($sentiments as $s){
				foreach($rs as $n=>$word){
					if(strcmp($word['keyword'],$s['keyword'])==0){
						$rs[$n]['sentiment'] = $s['sentiment'];
						break;
					}
				}
			}
		}
		unset($sentiments);
		unset($keyword);
		
		shuffle($rs);
	 	return $rs;
	 }
	/** these is for web channel specific data
	 * currently only available for blog, news site, and forum.
	 * default : blog
	 * @param $campaig_id
	 * @param $type -> 0. Common Web, 1. Blog, 2.Forum, 3. News 4. Video Sites
	 * @param $from start date filter
	 * @param $to end date filter
	 */
	 function site($campaign_id,$type=1,$from=null,$to=null){
	 	$type = intval($type);
		$helper = new FBHelper(null);
		$rs = array("summary"=>$this->site_summary($campaign_id, $type,$from, $to),
							"sentiment"=>$this->site_sentiment($campaign_id, $type,$from, $to),
							"top_websites"=>$this->site_top_sites($campaign_id, $type,$from, $to),
							"daily_volume_by_impression"=>array(),
							"daily_volume_by_mention"=>$this->site_daily_volume_by_mention($campaign_id,$type, $from, $to),
							"top_keywords"=>$this->site_top_keywords($campaign_id, $type,$from, $to)
				);
		return $rs;
	}
	function site_daily_volume_by_mention($campaign_id,$type,$from,$to){
		if($from!=null&&$to!=null){
			$filter = "AND DATE(FROM_UNIXTIME(published_ts)) BETWEEN '{$from}' AND '{$to}'";
			$limit = "LIMIT 365";
		}else{
			$limit = "LIMIT 30";
		}
		$sql = "SELECT count(*) as mentions,
				DATE(FROM_UNIXTIME(published_ts)) AS post_date
				FROM smac_report.campaign_web_feeds 
				WHERE campaign_id={$campaign_id} AND group_type_id ={$type}
				{$filter}
				GROUP BY post_date 
				ORDER BY published_ts DESC
				{$limit}";
				
		$results = $this->fetch($sql,1);
		
		if(is_array($results)){
			foreach($results as $n=>$rs){
				$results[$n]['ts'] = strtotime($rs['post_date']);
			}
			$results = subval_sort($results, 'ts'); 
		}
		$response = array();
		
		$n_size = sizeof($results);
		$response = array();
		if($n_size>0){
			for($i=0;$i<$n_size;$i++){
				$rs = $results[$i];
				$response[$rs['post_date']] = intval($rs['mentions']);
			}
		}
		return $response;	
	}
	function site_summary($campaign_id,$type,$from,$to){
		
		if($from==NULL||$to==NULL){		
			$sql = "SELECT count(distinct author_name) as total_websites, count(*) as total_posts
					FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} AND group_type_id ={$type} 
					";
							
			$sql2 = "SELECT count(distinct author_name) as total_websites, count(*) as total_posts,
					DATE(FROM_UNIXTIME(published_ts)) AS dtreport
					FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} AND group_type_id ={$type}
					GROUP BY dtreport 
					ORDER BY published_ts DESC
					LIMIT 2
					";
			
		}else{
			$from_ts  = strtotime($from);
			$to_ts = strtotime($to);			
			$sql = "SELECT count(distinct author_name) as total_websites, count(*) as total_posts
					FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} AND group_type_id ={$type} 
					AND DATE(FROM_UNIXTIME(published_ts)) BETWEEN '{$from}' AND '{$to}' ";
			
			$sql2 = "SELECT count(distinct author_name) as total_websites, count(*) as total_posts,
					DATE(FROM_UNIXTIME(published_ts)) AS dtreport
					FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} AND group_type_id ={$type}
					AND published_ts BETWEEN '{$from_ts}' AND '{$to_ts}' 
					GROUP BY dtreport 
					ORDER BY published_ts DESC
					LIMIT 2
					";
		}
		$summary = $this->fetch($sql);
		
		
		//print $sql;
		$rs = $this->fetch($sql2,1);
		if(sizeof($rs)==2){
			$h1_post = $rs[0]['total_posts'];
			$h0_post = $rs[1]['total_posts'];
			$post_diff = $h1_post - $h0_post;
			$post_change = 0;
			if($h0_post!=0){
				$post_change = round($post_diff/$h0_post*100,2);	
			}
			$h1_sites = $rs[0]['total_websites'];
			$h0_sites = $rs[1]['total_websites'];
			$site_diff = $h1_sites - $h0_sites;
			$site_change = 0;
			if($h0_sites!=0){
				$h0_sites = round($site_diff/$h0_sites*100,2);	
			}
		}else{
			$post_diff = 0;
			$post_change = 0;
			$site_diff = 0;
			$site_change = 0;
		}
		return array(
					"websites"=>array("value"=>intval($summary['total_websites']),
								 "percent"=>floatval($site_change),
								 "diff"=>intval($site_diff)),
					"total_posts"=>array("value"=>intval($summary['total_posts']),
								 "percent"=>floatval($post_change),
								 "diff"=>intval($post_diff))
				  );
	}
	function site_sentiment($campaign_id,$type,$from,$to){
		return array(array("rule"=>"positive","value"=>45),
					array("rule"=>"negative","value"=>15),
					array("rule"=>"neutral","value"=>32),
					);
	}
	function site_top_sites($campaign_id,$type,$from,$to){
		$type = intval($type);
		if($from==null||$to==NULL){
			$sql = "SELECT author_name,author_uri,COUNT(author_name) as total FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} 
					AND group_type_id = {$type}  
					GROUP BY author_name 
					ORDER BY total DESC
					LIMIT 5";
		}else{
			$start_ts = strtotime($from);
			$end_ts = strtotime($to);
			$sql = "SELECT author_name,author_uri,COUNT(author_name) as total FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} 
					AND group_type_id = {$type}
					AND DATE(FROM_UNIXTIME(published_ts)) >= {$from} AND DATE(FROM_UNIXTIME(published_ts)) <= {$to}
					GROUP BY author_name 
					ORDER BY total DESC
					LIMIT 5";
		}
		
		$kol = $this->fetch($sql,1);
		
		return $kol;
	}
	function site_top_keywords($campaign_id,$type,$from,$to){
		if($from==null||$to==NULL){	        
			$sql = "SELECT keyword,SUM(total) as occurance
					FROM smac_gcs.gcs_wordlist_{$campaign_id}
					WHERE EXISTS (
									SELECT 1 FROM smac_report.campaign_web_feeds g
									WHERE g.id = smac_gcs.gcs_wordlist_{$campaign_id}.campaign_web_feeds_id
											AND g.campaign_id = {$campaign_id} AND g.group_type_id = {$type}
							)
					GROUP BY keyword
					ORDER BY occurance DESC LIMIT 100";
		} else {

			$from_date = strtotime(mysql_escape_string($from));
			$to_date = strtotime(mysql_escape_string($to));
						
			$sql = "SELECT keyword,SUM(total) as occurance
			        FROM smac_gcs.gcs_wordlist_{$campaign_id}
			        WHERE EXISTS (
			                        SELECT 1 FROM smac_report.campaign_web_feeds g
			                        WHERE g.id = smac_gcs.gcs_wordlist_{$campaign_id}.campaign_web_feeds_id
			                                AND g.campaign_id = {$campaign_id} AND g.group_type_id = {$type}
			                                AND g.published_ts BETWEEN {$from_date} and {$to_date}
			                )
			        GROUP BY keyword
			        ORDER BY occurance DESC LIMIT 100";	
			        
		}
		$keywords = $this->fetch($sql,1);
		
		$rs = array();
		$max_val = 0;
		$nn=0;
		for($t=0;$t<sizeof($keywords);$t++){
			$keyword = $keywords[$t];
			$max_val = max($max_val,intval($keyword['occurance']));
		}
		
		$sWords = "";
		$n_words = sizeof($keywords);
		if($n_words>0){
			for($n=0;$n<$n_words;$n++){
				$keyword = $keywords[$n];
				if(strlen($keyword['keyword'])>0){
					if($n>0){
						$sWords.=",";
					}
					$sWords.="'{$keyword['keyword']}'";
					$nn++;
				}
				$rs[] = array("keyword"=>$keyword['keyword'],"value"=>($keyword['occurance']/$max_val)*10);
			}
		}
		if($nn>0){
			 $sql = "SELECT keyword,weight as sentiment FROM smac_sentiment.sentiment_setup_{$campaign_id}
			 		 WHERE keyword IN ({$sWords}) LIMIT {$nn}";
			
			$sentiments = @$this->fetch($sql,1);
		}
		
		if(is_array($sentiments)){
			foreach($sentiments as $s){
				foreach($rs as $n=>$word){
					if(strcmp($word['keyword'],$s['keyword'])==0){
						$rs[$n]['sentiment'] = $s['sentiment'];
						break;
					}
				}
			}
		}
		unset($sentiments);
		unset($keyword);
		
		shuffle($rs);
		return $rs;
		
	}
	function site_posts($campaign_id,$type,$start=0,$limit=10){
		$start = intval($start);
		$type = intval($type);
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		//check campaign language setting. is it restrict to specific language ?
		$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		if($campaign['lang']=="all"){
			$sql = "SELECT id,feed_id, link,author_name, author_uri, comments, title, summary, 
			published, source_service,generator,published_ts
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND group_type_id = {$type} 
			ORDER BY rank ASC LIMIT {$start},{$limit}";
			
			$sql2 = "SELECT COUNT(*) as total
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND group_type_id = {$type} LIMIT 1";
			
		}else{
			$sql = "SELECT id,feed_id, link,author_name, author_uri, comments, title, summary, 
			published, source_service,generator,published_ts
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND lang='{$campaign['lang']}'
			 AND group_type_id = {$type} 
			ORDER BY rank ASC LIMIT {$start},{$limit}";
			
			$sql2 = "SELECT COUNT(*) as total
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND lang='{$campaign['lang']}'
			 AND group_type_id = {$type}  LIMIT 1";
		}
		
		$result = $this->fetch($sql,1);
		$rows = $this->fetch($sql2);
		$rs = array();
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				$r['summary'] = clean_ascii($r['summary']);
				$sql = "SELECT c.keyword,c.weight FROM smac_gcs.gcs_wordlist_{$campaign_id} a
						INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} c
						ON a.keyword = c.keyword
						WHERE a.campaign_web_feeds_id = {$r['id']}
						AND c.weight <> 0;";
						
				$ksentiment = $this->fetch($sql,1);
				$r['summary'] = $this->highlight_content($r['summary'], $ksentiment);
				
				$final_sentiment = 0;
				if(is_array($ksentiment)){
					foreach($ksentiment as $k){
						$final_sentiment +=$k['weight']; 
					}
				}
				$feed_sentiment = $this->fetch("SELECT sentiment 
											FROM smac_gcs.campaign_gcs_sentiment
											WHERE campaign_web_feeds_id = {$r['id']} LIMIT 1");
				
				if(intval($feed_sentiment['sentiment'])!=0){
					$final_sentiment = intval($feed_sentiment['sentiment']);
				}
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
				'txt'=>($r['summary']),
				'published'=>date("d/m/Y",$r['published_ts']),
				'device'=>$this->get_device($r['generator'],$devices),
				'comments'=>number_format($r['comments']),
				'sentiment'=>$final_sentiment,
				'flag'=>$flag,
				'screenshot'=>$this->_getWebPic($r['id'])
				);
			}
		}
		$result = null;
		return array("feeds"=>$rs,"total_rows"=>$rows['total']);
	}
	function site_top_posts($campaign_id,$type,$from,$to,$limit=10){
		
		$type = intval($type);
		if($from!=null&&$to!=null){
			$from_ts = strtotime($from);
			$to_ts = strtotime($to);
			$filter = "AND published_ts BETWEEN {$from_ts} AND {$to_ts}";
			
		}
		//get devices
		$sql = "SELECT * FROM smac_data.device_lookup";
		$devices = $this->fetch($sql,1);
		
		//check campaign language setting. is it restrict to specific language ?
		$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		if($campaign['lang']=="all"){
			$sql = "SELECT id,feed_id, link,author_name, author_uri, comments, title, summary, 
			published, source_service,generator
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND group_type_id = {$type}
			{$filter}
			ORDER BY rank ASC LIMIT ".$limit;
		}else{
						
			$sql = "SELECT id,feed_id, link,author_name, author_uri, comments, title, summary, 
			published, source_service,generator
			FROM smac_report.campaign_web_feeds 
			WHERE campaign_id={$campaign_id} AND group_type_id = {$type} AND lang='{$campaign['lang']}'
			{$filter}
			ORDER BY rank ASC LIMIT ".$limit;
		}
		
		$result = $this->fetch($sql,1);
		$rs = array();
		if(sizeof($result)>0){
			foreach($result as $n=>$r){
				$r['summary'] = clean_ascii($r['summary']);
				$sql = "SELECT c.keyword,c.weight FROM smac_gcs.gcs_wordlist_{$campaign_id} a
						INNER JOIN smac_sentiment.sentiment_setup_{$campaign_id} c
						ON a.keyword = c.keyword
						WHERE a.campaign_web_feeds_id = {$r['id']}
						AND c.weight <> 0;";
						
				$ksentiment = $this->fetch($sql,1);
				$r['summary'] = $this->highlight_content($r['summary'], $ksentiment);
				
				$final_sentiment = 0;
				if(is_array($ksentiment)){
					foreach($ksentiment as $k){
						$final_sentiment +=$k['weight']; 
					}
				}
				$feed_sentiment = $this->fetch("SELECT sentiment 
											FROM smac_gcs.campaign_gcs_sentiment
											WHERE campaign_web_feeds_id = {$r['id']} LIMIT 1");
				
				if(intval($feed_sentiment['sentiment'])!=0){
					$final_sentiment = intval($feed_sentiment['sentiment']);
				}
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
				'txt'=>$r['summary'],
				'sentiment'=>$final_sentiment,
				'device'=>$this->get_device($r['generator'],$devices),
				'comments'=>number_format($r['comments']),
				'flag'=>$flag,
				'screenshot'=>$this->_getWebPic($r['id'])
				);
			}
		}
		$result = null;
		return $rs;
	}
	private function _getWebPic($id){
		$id = intval($id);
		$sql = "SELECT filename FROM smac_gcs.web_screenshot WHERE web_feeds_id  = {$id} LIMIT 1";
		$rs = $this->fetch($sql);
		return $rs['filename'];
	}
}
?>