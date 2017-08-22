<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
class overview_model extends base_model{
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
	}
	function topic_groups($owner_id,$start=0,$total=100){
		$start = intval($start);
		$sql = "SELECT * FROM smac_web.tbl_topic_group WHERE client_id={$owner_id} LIMIT {$start},{$total}";
		$rs = $this->fetch($sql,1);
		$sql = "SELECT COUNT(id) as total FROM smac_web.tbl_topic_group WHERE client_id={$owner_id} LIMIT 1";
		$rows = $this->fetch($sql);
		$data = array();
		for($i=0;$i<sizeof($rs);$i++){
			//shared author
			$sql = " SELECT author_id, author_name, author_avatar, occurance,group_id
			        FROM smac_report.client_shared_author
			        WHERE client_id = {$owner_id} AND group_id = {$rs[$i]['id']}
			        ORDER BY  occurance DESC LIMIT 20;";
			$shared_author = $this->fetch($sql,1);
			
			//shared issues
			$sql = " SELECT keyword,occurance
			        FROM smac_report.client_shared_keyword
			         WHERE client_id = {$owner_id} AND group_id = {$rs[$i]['id']}
			        ORDER BY  occurance DESC LIMIT 20;";
			$shared_issues = $this->fetch($sql,1);
			$data[] = array("id"=>$rs[$i]['id'],
							"name"=>$rs[$i]['group_name'],
							"shared_issues"=>$shared_issues,
							"shared_author"=>$shared_author);
		}
		//default group
		$sql = "SELECT author_id, author_name, author_avatar, occurance,group_id
			        FROM smac_report.client_shared_author
			        WHERE client_id = {$owner_id} AND group_id = 0
			        ORDER BY  occurance DESC LIMIT 20;";
		$shared_author = $this->fetch($sql,1);
		
		//shared issues
		$sql = " SELECT keyword,occurance
		        FROM smac_report.client_shared_keyword
		         WHERE client_id = {$owner_id} AND group_id = 0
		        ORDER BY  occurance DESC LIMIT 20;";
		$shared_issues = $this->fetch($sql,1);
		
		//check if the default group has data or not.
		$sql = "SELECT COUNT(id) as total FROM smac_web.tbl_campaign 
				WHERE client_id = {$owner_id} AND group_id=0";
		$default_content = $this->fetch($sql);
		if($default_content['total']>0){
		$data[] = array("id"=>0,
						"name"=>"default group",
						"shared_issues"=>$shared_issues,
						"shared_author"=>$shared_author);
		}
		//default group -->
		return array("groups"=>$data,"total_rows"=>$rows['total'],"offset"=>$start,"page"=>ceil($start/$total)+1);
	}
	function summary($group_id){	
			$data = array();
		
			$sql = 
				"SELECT c.id as campaign_id, c.client_id,c.campaign_name, c.campaign_desc as description, c.campaign_start, c.campaign_end, ".
					"c.campaign_added, c.channels, c.tracking_method, c.n_status, false as `source`, 999999999 as total_limit, ".
					"g.id as group_id, g.group_name ".
				"FROM smac_web.tbl_campaign c inner join smac_web.tbl_topic_group g on c.group_id = g.id ".
				"WHERE g.id = {$group_id} AND g.client_id=c.client_id AND c.n_status <> 2 LIMIT 100";
			
			$content = $this->fetch($sql,1);
			$data = array();
			if(!is_array($content)) return array();
			
			foreach($content as $d){
				$s_copy = $d;

				//Mentions
				$sql = 
					"SELECT sum(total_mention_twitter+total_mention_facebook+total_mention_web) as mentions, sum(total_people_twitter+total_people_facebook) as people, ".
							"sum(total_impression_twitter) as impression ".
					",SUM(total_mention_twitter) as mention_twitter,
					SUM(total_mention_facebook) as mention_facebook,
					SUM(total_mention_web) as mention_web 
					FROM smac_report.campaign_rule_volume_history 
					WHERE campaign_id = ".$d['campaign_id'];

				$_mention = $this->fetch($sql);

				$s_copy['mentions'] = intval($_mention['mentions']);
				$s_copy['people'] = intval($_mention['people']);
				$s_copy['impressions'] = floatval($_mention['impression']);

				$s_copy['total_usage'] = $_mention['mentions'];


				//Sentiments
				$sql = 
					"SELECT sum(total_mention_positive+total_mention_negative) as sentiment_mentions, ".
							"sum(total_mention_positive) as sentiment_positive, ".
							"sum(total_mention_negative) as sentiment_negative ".	
					"FROM smac_report.daily_campaign_sentiment ".
					"WHERE campaign_id = ".$d['campaign_id'];

				$_sentiment = $this->fetch($sql);

				
				
				$s_copy['sentiment']['postive'] = $_sentiment['sentiment_positive'];
				$s_copy['sentiment']['negative'] = $_sentiment['sentiment_negative'];
				$s_copy['sentiment']['netral'] = $s_copy['mentions'] - ($_sentiment['sentiment_negative']+$_sentiment['sentiment_positive']);
				
				
				$s_copy['source'] = array('twitter'=>intval($_mention['mention_twitter']),
											'facebook'=>intval($_mention['mention_facebook']),
											'blog'=>intval($_mention['mention_web']));
				
				$sql = "SELECT round(sum_pii/(total_mention_positive+total_mention_negative),2) as pii
						FROM smac_report.daily_campaign_sentiment 
						WHERE campaign_id = {$d['campaign_id']}
							    AND (total_mention_positive+total_mention_negative) > 0 
								AND channel = 1
						group by dtreport
						ORDER BY dtreport DESC LIMIT 1";
				$_pii = $this->fetch($sql);		
				$s_copy['pii'] = floatval($_pii['pii']);

				$arr = array("subdomain"=> $this->request->getParam('subdomain'),
							'page' => 'dashboard',
							'campaign_id' => $d['campaign_id'],
							'group_id' => $d['group_id'],
							'language' => 'all');
				$link = 'index.php?'.$this->request->encrypt_params($arr);
				$s_copy['link'] = $link;
				
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
						tc.id={$d['campaign_id']}
						AND dcs.channel = 1
					GROUP BY tc.id
					;";
				$quadrant = $this->fetch($sql);
				//viral_effect = (rt_impression / total_impression) * 100%
				$viral_effect = @round($quadrant['rt_impression']/($quadrant['impression']+$quadrant['rt_impression'])*100,2);
				$s_copy['quadrant'] = array("volume"=>intval($quadrant['impression']),"viral_score"=>floatval($viral_effect),"pii"=>floatval($_pii['pii']));
				
				
				//getting the last data is fetched
				$sql = "SELECT dtpost FROM smac_report.latest_conversation 
						WHERE campaign_id={$d['campaign_id']} ORDER BY id DESC LIMIT 1";
				$rs = $this->fetch($sql);
				if(intval($rs['dtpost'])>0){
					$s_copy['last_data_collected'] = date("d/m/Y H:i:s",strtotime($rs['dtpost']));
				}else{
					$s_copy['last_data_collected'] = "-";
				}
				$data[] = $s_copy;
			}
		if(sizeof($data)>0){
			foreach($data as $n=>$d){
				$data[$n]['topic_url'] = $this->request->encrypt_params(array("subdomain"=>$this->request->getParam("subdomain"),
																			  "page"=>"dashboard",
																			  "campaign_id"=>$data[$n]['campaign_id']));
				$data[$n]['pause_url'] = $this->request->encrypt_params(array("subdomain"=>$this->request->getParam("subdomain"),
																			  "page"=>"campaign","act"=>"changestatus","status"=>"1",
																			  "campaign_id"=>$data[$n]['campaign_id']));
																			  
				$data[$n]['resume_url'] = $this->request->encrypt_params(array("subdomain"=>$this->request->getParam("subdomain"),
																			  "page"=>"campaign","act"=>"changestatus","status"=>"0",
																			  "campaign_id"=>$data[$n]['campaign_id']));
																			  
																			  
				$data[$n]['delete_url'] = $this->request->encrypt_params(array("subdomain"=>$this->request->getParam("subdomain"),
																			  "page"=>"campaign","act"=>"remove",
																			  "cid"=>$data[$n]['campaign_id']));
			
			}
		}
		$data = subval_rsort($data,"impressions");
		return $data;
	}
	function summary_default($client_id){	
			$data = array();
		
			$sql = 
				"SELECT c.id AS campaign_id, c.client_id,c.campaign_name, c.campaign_desc AS description, 
				c.campaign_start, c.campaign_end, c.campaign_added, c.channels, c.tracking_method, c.n_status, 
				FALSE AS `source`, 999999999 AS total_limit, 0 AS group_id, 'Default Group' AS group_name
				FROM smac_web.tbl_campaign c 
				WHERE c.client_id = {$client_id} AND c.group_id=0 AND c.n_status <> 2 LIMIT 100;";
			
			$content = $this->fetch($sql,1);
			$data = array();
			if(!is_array($content)) return array();
			
			foreach($content as $d){
				$s_copy = $d;

				//Mentions
				$sql = 
					"SELECT sum(total_mention_twitter+total_mention_facebook+total_mention_web) as mentions, sum(total_people_twitter) as people, ".
							"sum(total_impression_twitter) as impression ".
					",SUM(total_mention_twitter) as mention_twitter,
					SUM(total_mention_facebook) as mention_facebook,
					SUM(total_mention_web) as mention_web 
					FROM smac_report.campaign_rule_volume_history WHERE campaign_id = ".$d['campaign_id'];

				$_mention = $this->fetch($sql);

				$s_copy['mentions'] = intval($_mention['mentions']);
				$s_copy['people'] = intval($_mention['people']);
				$s_copy['impressions'] = floatval($_mention['impression']);

				$s_copy['total_usage'] = $_mention['mentions'];


				//Sentiments
				$sql = 
					"SELECT sum(total_mention_positive+total_mention_negative) as sentiment_mentions, ".
							"sum(total_mention_positive) as sentiment_positive, ".
							"sum(total_mention_negative) as sentiment_negative ".	
					"FROM smac_report.daily_campaign_sentiment ".
					"WHERE campaign_id = ".$d['campaign_id'];

				$_sentiment = $this->fetch($sql);

				
				
				$s_copy['sentiment']['postive'] = $_sentiment['sentiment_positive'];
				$s_copy['sentiment']['negative'] = $_sentiment['sentiment_negative'];
				$s_copy['sentiment']['netral'] = $s_copy['mentions'] - ($_sentiment['sentiment_negative']+$_sentiment['sentiment_positive']);
				
				
				$s_copy['source'] = array('twitter'=>intval($_mention['mention_twitter']),
											'facebook'=>intval($_mention['mention_facebook']),
											'blog'=>intval($_mention['mention_web']));
				
				$sql = "SELECT round(sum_pii/(total_mention_positive+total_mention_negative),2) as pii
						FROM smac_report.daily_campaign_sentiment 
						WHERE campaign_id = {$d['campaign_id']}
							    AND (total_mention_positive+total_mention_negative) > 0 
								AND channel = 1
						group by dtreport
						ORDER BY dtreport DESC LIMIT 1";
				$_pii = $this->fetch($sql);		
				$s_copy['pii'] = floatval($_pii['pii']);

				$arr = array("subdomain"=> $this->request->getParam('subdomain'),
							'page' => 'dashboard',
							'campaign_id' => $d['campaign_id'],
							'group_id' => $d['group_id'],
							'language' => 'all');
				$link = 'index.php?'.$this->request->encrypt_params($arr);
				$s_copy['link'] = $link;
				
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
						tc.id={$d['campaign_id']}
						AND dcs.channel = 1
					GROUP BY tc.id
					;";
				$quadrant = $this->fetch($sql);
				//viral_effect = (rt_impression / total_impression) * 100%
				$viral_effect = @round($quadrant['rt_impression']/($quadrant['impression']+$quadrant['rt_impression'])*100,2);
				$s_copy['quadrant'] = array("volume"=>intval($quadrant['impression']),"viral_score"=>floatval($viral_effect),"pii"=>floatval($_pii['pii']));
				//getting the last data is fetched
				$sql = "SELECT dtpost FROM smac_report.latest_conversation 
						WHERE campaign_id={$d['campaign_id']} ORDER BY id DESC LIMIT 1";
				$rs = $this->fetch($sql);
				$s_copy['last_data_collected'] = date("d/m/Y H:i:s",strtotime($rs['dtpost']));
				$data[] = $s_copy;
			}
		if(sizeof($data)>0){
			foreach($data as $n=>$d){
				$data[$n]['topic_url'] = $this->request->encrypt_params(array("subdomain"=>$this->request->getParam("subdomain"),
																			  "page"=>"dashboard",
																			  "campaign_id"=>$data[$n]['campaign_id']));
				$data[$n]['pause_url'] = $this->request->encrypt_params(array("subdomain"=>$this->request->getParam("subdomain"),
																			  "page"=>"campaign","act"=>"changestatus","status"=>"1",
																			  "campaign_id"=>$data[$n]['campaign_id']));
																			  
				$data[$n]['resume_url'] = $this->request->encrypt_params(array("subdomain"=>$this->request->getParam("subdomain"),
																			  "page"=>"campaign","act"=>"changestatus","status"=>"0",
																			  "campaign_id"=>$data[$n]['campaign_id']));
																			  
																			  
				$data[$n]['delete_url'] = $this->request->encrypt_params(array("subdomain"=>$this->request->getParam("subdomain"),
																			  "page"=>"campaign","act"=>"remove",
																			  "cid"=>$data[$n]['campaign_id']));
			
			}
		}
		$data = subval_rsort($data,"impressions");
		return $data;
	}
}
?>