<?php
class Campaign{
	function __construct($logger){
		$this->logger = $logger;
	}
	function create_campaign(){
		global $conn,$SCHEMA_WEB;
		$country = array("id"=>"indonesia","my"=>"malaysia","sg"=>"singapore","ph"=>"philippines");
		$logger = $this->logger;
		$account_type = intval($_REQUEST['account_type']);
		
		if($account_type==0||$account_type==99||$account_type=5){
			$n_status = 1;
		}else{
			$n_status = 0;
		}
		$client_id = intval($_REQUEST['client_id']);
		$campaign_name = mysql_escape_string(urldecode($_REQUEST['campaign_name']));
		$campaign_start = mysql_escape_string(urldecode($_REQUEST['campaign_start']));
		$campaign_end = mysql_escape_string(urldecode($_REQUEST['campaign_end']));
		$tracking_method = mysql_escape_string(urldecode($_REQUEST['tracking_method']));
		$channels = mysql_escape_string(urldecode($_REQUEST['channels']));
		$group_id = mysql_escape_string(intval(urldecode($_REQUEST['group_id'])));
		$historical_interval = mysql_escape_string(intval(urldecode($_REQUEST['historical_interval'])));
		$lang = mysql_escape_string(urldecode($_REQUEST['lang']));
		if($lang!='all'){
				$language = "(lang:{$lang}) ";
		}else{
			$language = "";
		}
		//$geo = mysql_escape_string(urldecode($_REQUEST['geo']));
		$market = unserialize(urldecode64(mysql_escape_string($_REQUEST['geo'])));
		
		if($market['coverage']=="global"){
			$geo = "all";
		}else{
			$geo = "loc";
		}
		
		$localmarket = $market['localmarket'];
		
		$twitter_account = mysql_escape_string(urldecode($_REQUEST['twitter_account']));
		$fb_account = mysql_escape_string(urldecode($_REQUEST['fb_account']));
		$hastag = mysql_escape_string(urldecode($_REQUEST['hastag']));
		
		$pt_lang = array("id"=>"id",
						"en"=>"en",
						"de"=>"de",
						"fr"=>"fr",
						"it"=>"it",
						"ja"=>"ja",
						"nl"=>"nl",
						"ko"=>"ko",
						"no"=>"no",
						"pt"=>"pt",
						"es"=>"es",
						"sv"=>"sv",
						"ru"=>"ru",
						 "msa"=>"msa");
		
		
		$p_keyword = (base64_decode($_REQUEST['keywords']));
		$logger->info("Raw Keywords : ".($p_keyword));
		
		
		$arr_keywords = unserialize($p_keyword);
		$keywords= $arr_keywords['keywords'];
		$labels = $arr_keywords['labels'];
		
		$arr_keywords = null;
		//debug aja
		//$tracking_method = 1;
		
		$logger->info("adding campaign : ".$client_id.",
						".$campaign_name.",".$campaign_start.",
						".$campaign_end.",".$channels.",
						".$tracking_method.",".$lang.",
						".$geo.",".$twitter_account.",
						".$hastag."");
		
		$id=$this->add_campaign($client_id,$campaign_name,$campaign_start,
				$campaign_end,$channels,$tracking_method,$lang,$geo,$twitter_account,
				$hastag,$group_id,$historical_interval,$fb_account,$account_type);
		
		$logger->info("new campaign id : ".$id);
		$logger->info("foo");
		if(@eregi("3",$channels)){
			$logger->info("lang : {$lang}");
			$logger->info("country : ".json_encode($localmarket));
			$logger->info("id : {id}");
			
			$this->initCustomsWebSetup($id,$lang,$localmarket,$logger);
		}else{
			$logger->info("NO WEBSITE CHANNEL !");
		}
		
		//insert new entries in tbl_campaign_coverage
		if(sizeof($localmarket)>0){
			foreach($localmarket as $market){
				$market = mysql_escape_string($market);
				$sql = "INSERT IGNORE INTO smac_web.tbl_campaign_coverage
						(campaign_id,geo)
						VALUES({$id},'{$market}')";
				$this->query($sql);
			}
		}
		
		//-->
		if($tracking_method == 1){
			$tbl_keyword = "tbl_keyword_power";
		}else{
			$tbl_keyword = "tbl_keyword";
		}
		$logger->info("using schema : ".$tbl_keyword);
		$resp = array();
		if($id>0){
			if(sizeof($keywords)>0){
				$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".".$tbl_keyword." (keyword_txt) VALUES ";
				$keys = "";
				$t = 0;
				foreach($keywords as $k => $v){
					$kw = trim(urldecode($v));
					if(sizeof($localmarket)>0){
						foreach($localmarket as $loc){
							$g_kw = "(bio_location_contains:{$country[$loc]}) {$language}".$kw;
							if( $kw != '' && !$invalid_op){
								$qry .= "('".mysql_escape_string( $g_kw )."'), ";
								if($t>0){
									$keys.=",";
								}							
								$keys.="'".mysql_escape_string( $g_kw )."'";
								$t++;
							}
						}
					}else{
						if( $kw != '' && !$invalid_op){
							$kw.=" {$language}";
							$kw = trim($kw);
							$qry .= "('".mysql_escape_string( $kw )."'), ";
							if($t>0){
								$keys.=",";
							}							
							$keys.="'".mysql_escape_string( $kw )."'";
							$t++;
						}
					}
				}
				$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
				$logger->info("SQL 1 : ".$qry);
				
				
				if(!mysql_query($qry,$conn)){
					$logger->error("cannot insert new keyword into ".$tbl_keyword);
					$resp = array("status"=>0,"message"=>"cannot insert new keyword(s)","data"=>$qry);
					return json_encode($resp);
				}else{
					$sql = "SELECT keyword_id,keyword_txt FROM ".$SCHEMA_WEB.".".$tbl_keyword." WHERE keyword_txt IN (".$keys.")";
					
					$rs1 = $this->fetch($sql,1);
					$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".tbl_campaign_keyword (keyword_id,campaign_id,label,n_status) VALUES ";
					
					foreach($rs1 as $kk => $vv){
						$kwds = trim($vv['keyword_id']);
						//insert labels
						foreach($keywords as $nn=>$kk){
							if(strcmp(trim($kk),trim($vv['keyword_txt']))==0){
								$label = mysql_escape_string($labels[$nn]);
							}
						}
						if( $kwds != '' ){
							$qry .= "('".mysql_escape_string( $kwds )."',".$id.",'{$label}',{$n_status}), ";
						}
						
					}
					$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
					
					$logger->info("SQL 2: ".$qry);
					
					mysql_query($qry,$conn);
					$sql = "UPDATE ".$SCHEMA_WEB.".".$tbl_keyword." SET n_status=0 
							WHERE keyword_id IN (SELECT keyword_id FROM ".$SCHEMA_WEB.".tbl_campaign_keyword
										WHERE campaign_id = ".intval($id).")";
						
					$q = mysql_query($sql,$conn);
					$logger->status($sql,$q);
					
					//linkage the coverage with their respective keywords
					if(sizeof($localmarket)>0){
						$id = intval($id);
						$sql = "SELECT a.id,b.keyword_txt 
								FROM 
								smac_web.tbl_campaign_keyword a 
								INNER JOIN 
								smac_web.tbl_keyword_power b
							 	ON a.keyword_id = b.keyword_id WHERE a.campaign_id={$id}";
						
						$topic_rules = $this->fetch($sql,1);
						foreach($localmarket as $loc){
							$cnt = $country[$loc];
							$sql = "SELECT id FROM smac_web.tbl_campaign_coverage WHERE campaign_id={$id} AND geo='{$loc}' LIMIT 1";
							
							$coverage = $this->fetch($sql);
							foreach($topic_rules as $rule){
								
								if(@eregi("bio_location_contains:{$cnt}",$rule['keyword_txt'])){
									$rule_id = $rule['id'];
									$coverage_id = $coverage['id'];
									$sql = "INSERT IGNORE INTO smac_web.tbl_rule_coverage
											(c_id,t_id)
											VALUES({$coverage_id},{$rule_id})";
									
									$this->query($sql);
								}
							}
						}
					}
					//upload keyword replay
					if($historical_interval>0){
						$this->upload_replay_keyword($id,$keywords,$historical_interval,$conn);
					}
					
					//mark the campaign for using a new schema.
					$this->setFlag($id,$conn);
					$this->force_topic_refresh($id,$logger);
					//-->
					$resp = array("status"=>1,"campaign_id"=>$id);
					$logger->info(json_encode($resp));
					return json_encode($resp);
					//return "<status>1</status><campaign_id>".$id."</campaign_id>";
				}
			}
		}
	}
	function force_topic_refresh($campaign_id,$logger){
	
		$logger->info("force refresh {$campaign_id}");
		if($campaign_id>0){
			$sql = "insert into smac_report.job_report_refresh (campaign_id, n_status) values ({$campaign_id}, 0)";
			$q = $this->query($sql);
			$logger->status($sql,$q);
		}
	}
	/*
	 * a custom setup for web search
	 * only run these method if, the topic is including web channel.
	 */
	function initCustomsWebSetup($campaign_id,$lang,$country,$logger){
		
		$hostlist = array("id"=>"google.co.id","my"=>"google.com.my","ph"=>"google.com.ph","sg"=>"google.com.sg");
		$googlehost = "google.com";
		$n_country = sizeof($country);
		$logger->info("getting country list : ".json_encode($country));
		$logger->info("n_country:{$n_country}");
		$logger->info("lang : {$lang}");
		$web_lang = null;
		if(strlen($lang)>0&&$lang!='all'){
			$web_lang = "lang_".$lang;
		}
		$logger->info("web_lang:{$web_lang}");
		if($n_country>0){
			
			if($web_lang!=null){
				$sql = "INSERT INTO smac_bot.tbl_custom_campaign_bot_run
						(campaign_id, googlehost_restrict, language_restrict, max_index) 
						VALUES ($campaign_id,'{$googlehost}','{$web_lang}', 31);";
			}else{
				$sql = "INSERT INTO smac_bot.tbl_custom_campaign_bot_run
						(campaign_id, googlehost_restrict, language_restrict, max_index) 
						VALUES ($campaign_id,'{$googlehost}',NULL, 31);";
			}
		 	$this->query($sql);
		 	$logger->info($sql);
		
		 	//for every country, we insert the tbl_campaign_country_restrict
		 	for($i=0;$i<$n_country;$i++){
		 		$c = $country[$i];
		 		if(isset($hostlist[$c])){
					$googlehost = $hostlist[$c];
				}
		 		$cid = "country".strtoupper($c);
		 		if($web_lang!=null){
		 			$sql = "insert into smac_web.tbl_campaign_country_restrict 
		 				(campaign_id, country_id, country_restrict, googlehost_restrict, language_restrict, max_index)
						select {$campaign_id}, '{$c}', '{$cid}', '{$googlehost}', '{$web_lang}', 31;";
		 		}else{
		 			$sql = "insert into smac_web.tbl_campaign_country_restrict 
		 				(campaign_id, country_id, country_restrict, googlehost_restrict, language_restrict, max_index)
						select {$campaign_id}, '{$c}', '{$cid}', '{$googlehost}', NULL, 31;";
		 		}
		 		$q = $this->query($sql);
		 		$logger->info($sql);
		 		//switch back to default for the next iteration
		 		$googlehost = "google.com";
		 	}
			
		}else{
			$logger->info("no country");
			if($web_lang!=null){
				$sql = "INSERT INTO smac_bot.tbl_custom_campaign_bot_run
						(campaign_id, googlehost_restrict, language_restrict, max_index) 
						VALUES ($campaign_id,'{$googlehost}','{$web_lang}', 31);";
			}else{
				$sql = "INSERT INTO smac_bot.tbl_custom_campaign_bot_run
						(campaign_id, googlehost_restrict, language_restrict, max_index) 
						VALUES ($campaign_id,'{$googlehost}',NULL, 31);";
			}
			$this->query($sql);
			$logger->info($sql);
		}
	}
	function upload_replay_keyword($campaign_id,$keywords,$interval,$conn){
		global $SCHEMA_WEB,$logger;
		$start_from = date("YmdHi",mktime(0,0,0,date("m"),date("d")-($interval+1),date("Y")));
		$until = date("YmdHi",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		//populate replay table
		$sql = "INSERT INTO `smac_web`.`tbl_campaign_replay` 
					(
					`campaign_id`, 
					`keyword_id`, 
					`n_status`, 
					`start_from`, 
					`until`, 
					`author_id`
					)
				SELECT campaign_id,keyword_id,n_status,'{$start_from}','{$until}','' 
				FROM smac_web.tbl_campaign_keyword WHERE campaign_id={$campaign_id} LIMIT 1;
				";
		$q = $this->query($sql);
		$logger->status("put entry on campaign_replay",$q);
		
		//populate keyword_replay list
		$sql = "INSERT IGNORE INTO smac_web.tbl_keyword_replay
				(keyword_id,keyword_txt,n_status)
					SELECT keyword_id,keyword_txt,0 AS n_status 
					FROM smac_web.tbl_keyword_power
					WHERE keyword_id IN 
						(SELECT keyword_id FROM smac_web.tbl_campaign_replay 
						WHERE campaign_id={$campaign_id});";
		$q = $this->query($sql);
		$logger->status("put entry on keyword_replay",$q);
		return $q;
	}
	function update_campaign(){
		global $conn,$SCHEMA_WEB;
		$client_id = intval($_POST['client_id']);
		$id = intval($_POST['campaign_id']);
		$campaign_name = mysql_escape_string($_POST['campaign_name']);
		$campaign_start = mysql_escape_string($_POST['campaign_start']);
		$campaign_end = mysql_escape_string($_POST['campaign_end']);
		$tracking_method = mysql_escape_string($_POST['tracking_method']);
		$channels = mysql_escape_string($_POST['channels']);
		
		$keyword = $_POST['keywords'] ? nl2br($_POST['keywords']) : '';
		$keywords = explode("<br />",$keyword);
		$qq=$this->edit_campaign($client_id,$id,$campaign_name,$campaign_start,
				$campaign_end,$channels,$tracking_method);
		
		if($tracking_method == 1){
			$tbl_keyword = "tbl_keyword_power";
		}else{
			$tbl_keyword = "tbl_keyword";
		}

		if($qq>0){
			$qry = "DELETE FROM ".$SCHEMA_WEB.".tbl_campaign_keyword WHERE campaign_id=".$id.";";
				if( $this->query($qry) ){					
					$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".".$tbl_keyword." (keyword_txt) VALUES ";
					$keys = "";
					$t = 0;
					if($keywords){
						foreach($keywords as $k => $v){
							$kw = trim($v);
							if( $kw != '' ){
								$qry .= "('".mysql_escape_string( $kw )."'), ";
								if($t>0){
									$keys.=",";
								}							
								$keys.="'".mysql_escape_string( $kw )."'";
								$t++;
							}
						}
						$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
						//print $qry."<br/>";
						if(!$this->query($qry)){
							return "<result>0</result>";
						}else{
							$sql = "SELECT keyword_id FROM ".$SCHEMA_WEB.".".$tbl_keyword." WHERE keyword_txt IN (".$keys.")";
							//print $sql."<br/>";
							$rs1 = $this->fetch($sql,1);
							//var_dump($rs1);
							//print "<br/>";
							$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".tbl_campaign_keyword (keyword_id,campaign_id) VALUES ";
							foreach($rs1 as $kk => $vv){
								$kwds = trim($vv['keyword_id']);
								if( $kwds != '' ){
									$qry .= "('".mysql_escape_string( $kwds )."',".$id."), ";
						
								}
							
							}
							$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
							//print $qry."<br/>";
							$this->query($qry);
							$sql = "UPDATE ".$SCHEMA_WEB.".".$tbl_keyword." SET n_status=2 
								WHERE keyword_id NOT IN (SELECT keyword_id FROM smac.tbl_campaign_keyword)";
						
							$this->query($sql);
						
							$sql = "UPDATE ".$SCHEMA_WEB.".".$tbl_keyword." SET n_status=0 
								WHERE keyword_id IN (SELECT keyword_id FROM smac.tbl_campaign_keyword
											WHERE campaign_id = ".intval($id).")";
						
							$this->query($sql);
						}
					}
				return "<result>1</result>";	
			}else{
				return "<result>-1</result>";
			}
			return "<result>1</result>";
		}else{
			return "<result>0</result>";
		}
	}
	function fetch($sql,$f=false){
		global $conn;
		$q = mysql_query($sql,$conn);
		
		if($f){
			while($ff = mysql_fetch_assoc($q)){
				$fetch[] = $ff;
			}
		}else{
			$fetch = mysql_fetch_assoc($q);
		}
		mysql_free_result($q);
		return $fetch;
	}
	function query($sql){
		global $conn;
		$q = mysql_query($sql,$conn);
		return $q;
	}
	function add_campaign($client_id,$campaign_name,$campaign_start,$campaign_end,$channels,$tracking_method,
						$lang,$geo,$twitter_account,$hastag,$group_id=0,$historical_interval=0,$fb_account='',$account_type=0){
		global $conn,$SCHEMA_WEB;
		if($account_type==0||$account_type==99||$account_type==5){
			$n_status = 1;
		}else{
			$n_status = 0;
		}
		
		$logger = $this->logger;
		//insert campaign
		$qry = "INSERT INTO ".$SCHEMA_WEB.".tbl_campaign 		
			(client_id,campaign_name,campaign_start,
			campaign_end,campaign_added,channels,tracking_method,lang,geotarget,twitter_account,hastag,group_id,
			historical_interval,fb_account,n_status) 
			VALUES
			(".$client_id.",'".$campaign_name."',
			'".$campaign_start."','".$campaign_end."',NOW(),
			'".$channels."',".$tracking_method.",'".$lang."','".$geo."',
			'".$twitter_account."','".$hastag."',".$group_id.",".$historical_interval.",
			'{$fb_account}',{$n_status});";
		
		$q = mysql_query($qry,$conn);		
		$logger->status($qry,$q);
		$id = mysql_insert_id();
		
		//get the global last_id
		$sql = "SELECT MAX(last_id) as last_id FROM smac_report.job_campaign_feeds LIMIT 1";
		
		
		$rs = $this->fetch($sql);
		$last_id = $rs['last_id'];
		//insert new entry in job_campaign_feeds
		$sql = "INSERT IGNORE INTO smac_report.job_campaign_feeds
				(campaign_id,last_id)
				VALUES({$id},{$last_id})";
		$q = mysql_query($sql,$conn);
		return $id;
	}
	/**
	 * flag the campaign, so the campaign will use a splitted campaign_feeds, 
	 * feed_wordlists and campaign_words_databank schema 
	 * @param $campaign_id
	 */
	function setFlag($campaign_id,$conn){
		global $logger;
		$sql = "INSERT IGNORE INTO smac_report.tbl_feed_wordlist_flag(campaign_id,n_status)
				VALUES({$campaign_id},1)";
		$q = mysql_query($sql,$conn);
		$logger->status($qry,$q);
		$sql = "INSERT IGNORE INTO smac_report.campaign_feeds_split_flag 
				(campaign_id, n_status, last_exported_cfid)
				VALUES
				({$campaign_id}, 1, 0)";
		$q = mysql_query($sql,$conn);
		$logger->status($qry,$q);
	}
	function edit_campaign($client_id,$campaign_id,$campaign_name,$campaign_start,$campaign_end,$channels,$tracking_method,$group_id){
		global $conn,$SCHEMA_WEB;
		//insert campaign
		$qry = "UPDATE ".$SCHEMA_WEB.".tbl_campaign 		
			SET campaign_name='".$campaign_name."',campaign_start='".$campaign_start."',
			campaign_end='".$campaign_end."',channels='".$channels."',tracking_method=".$tracking_method.",group_id=".$group_id."
			WHERE client_id = ".$client_id." AND id = ".$campaign_id."";
		$q = mysql_query($qry,$conn);
		return $q;
	}
	function get_campaign_detail($format='json'){
		global $conn,$SCHEMA_WEB;
		$campaign_id = intval($_REQUEST['campaign_id']);
		$client_id = intval($_REQUEST['client_id']);
		$sql = "SELECT id as campaign_id,campaign_name,campaign_start,campaign_end,campaign_added,channels,tracking_method,lang
				FROM ".$SCHEMA_WEB.".tbl_campaign WHERE id=".$campaign_id." AND client_id = ".$client_id." LIMIT 1";
		$rs = $this->fetch($sql);
		$rs['channels'] = $rs['channels'];
		if($format=='json'){
			return json_encode($rs);
		}else{
			$str = "<result>";
			foreach($rs as $n=>$v){
				$str.="<".$n.">".@htmlspecialchars(stripslashes($v))."</".$n.">";
			}
			$str.="</result>";
			return $str;
		}
	}
	function get_campaign_list($format='json'){
		global $conn,$SCHEMA_WEB;
		
		$client_id = intval($_REQUEST['client_id']);
		
		$sql = "
		SELECT id as campaign_id,campaign_name,campaign_start,campaign_end,campaign_added,lang,channels,tracking_method,n_status
		FROM smac_web.tbl_campaign WHERE client_id = {$client_id}
		AND n_status <> 2 ORDER BY group_id ASC LIMIT 1000
		";
		$results = $this->fetch($sql,1);
		
		foreach($results as $n=>$v){
			//get the date when the first data collected
			$sql = "SELECT published_date FROM smac_report.campaign_daily_stats 
					WHERE campaign_id={$v['campaign_id']} 
					ORDER BY id ASC
					LIMIT 1";
			
			$report = $this->fetch($sql);
			
			if($report['published_date']!=null){
				
				$rs['campaign_start'] = $report['published_date'];
			}
			
		}

		if($format=='json'){
			return json_encode($results);
		}else{
			$str = "<result>";
			foreach($results as $rs){
				$rs['channels'] = "";
				$str .= "<campaign>";
				foreach($rs as $n=>$v){
					$str.="<".$n.">".@htmlspecialchars(stripslashes($v))."</".$n.">";
				}
				$str.="</campaign>";
				
			}
			$str.="</result>";
			return $str;
		}
		
	}
	function topic_overview($format='json'){
		global $conn,$SCHEMA_WEB;
		$client_id = intval($_REQUEST['client_id']);
		
		$sql = "SELECT a.id as campaign_id,group_id,b.group_name,a.n_status,
				 a.campaign_name, a.campaign_desc as description, a.campaign_start, a.campaign_end, ".
				"a.campaign_added, a.channels, a.tracking_method, a.n_status, false as `source`, 999999999 as total_limit
				FROM smac_web.tbl_campaign a
				LEFT JOIN ".$SCHEMA_WEB.".tbl_topic_group b
				ON a.group_id = b.id
				WHERE a.client_id = ".$client_id." AND a.n_status <> 2 
                ORDER BY a.group_id ASC";
		$results = $this->fetch($sql,1);
		
		$rs = array();
		foreach($results as $n=>$v){
			$campaign_id = $v['campaign_id'];
			$group_id = $v['group_id'];
			$n_status = $v['n_status'];
			$s_copy = $v;
			/*
			$sql = "SELECT content 
					FROM smac_report.campaign_topic_overview 
					WHERE campaign_id={$campaign_id} 
					AND group_id={$group_id} LIMIT 1";
			
			$content = $this->fetch($sql);
			$data = unserialize($content['content']);
			//print $v['group_name']."<br/>";
			if(is_array($data)){
				$data['group_name'] = $v['group_name'];
				$data['n_status'] = $n_status;
				$rs[] = $data;
//			}*/
			//Mentions
				$sql = 
					"SELECT sum(total_mention_twitter+total_mention_facebook+total_mention_web) as mentions, sum(total_people_twitter) as people, ".
							"sum(total_impression_twitter) as impression ".
					",SUM(total_mention_twitter) as mention_twitter,
					SUM(total_mention_facebook) as mention_facebook,
					SUM(total_mention_web) as mention_web 
					FROM smac_report.campaign_rule_volume_history WHERE campaign_id = ".$campaign_id."";
				$_mention = $this->fetch($sql);
				$s_copy['mentions'] = intval($_mention['mentions']);
				$s_copy['people'] = intval($_mention['people']);
				$s_copy['potential_impression'] = floatval($_mention['impression']);
				$s_copy['total_usage'] = $_mention['mentions'];
				
			//Sentiments
				$sql = 
					"SELECT sum(total_mention_positive+total_mention_negative) as sentiment_mentions, ".
							"sum(total_mention_positive) as sentiment_positive, ".
							"sum(total_mention_negative) as sentiment_negative ".	
					"FROM smac_report.daily_campaign_sentiment ".
					"WHERE campaign_id = ".$campaign_id;

				$_sentiment = $this->fetch($sql);

				$s_copy['sentiment_mentions'] = intval($_sentiment['sentiment_mentions']);
				$s_copy['sentiment_positive'] = intval($_sentiment['sentiment_positive']);
				$s_copy['sentiment_negative'] = intval($_sentiment['sentiment_negative']);

				$s_copy['sentiment']['positive'] = $_sentiment['sentiment_positive'];
				$s_copy['sentiment']['negative'] = $_sentiment['sentiment_negative'];
				$s_copy['sentiment']['netral'] = $s_copy['mentions'] - ($_sentiment['sentiment_negative']+$_sentiment['sentiment_positive']);
				
				
				$s_copy['source'] = array('twitter'=>intval($_mention['mention_twitter']),
											'facebook'=>intval($_mention['mention_facebook']),
											'blog'=>intval($_mention['mention_web']));
				$sql = 
					"SELECT round(sum_pii/(total_mention_positive+total_mention_negative),2) as pii ".
					"FROM smac_report.daily_campaign_sentiment ".
					"WHERE campaign_id = ".$campaign_id.
							" AND (sum_pii) <> 0 ".
					"ORDER BY dtreport DESC LIMIT 1";

				$_pii = $this->fetch($sql);		
				$s_copy['quality'] = floatval($_pii['pii']);

				//pii diference
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
				SUM(total_impression_twitter) as impression
				FROM smac_report.campaign_rule_volume_history 
				WHERE campaign_id={$campaign_id} 
				GROUP BY dtreport 
				ORDER BY dtreport DESC 
				LIMIT 2;";
				
				$dstats = $this->fetch($sql,1);
				if(sizeof($dstats)==2){
					$h1_mention = $dstats[0]['mentions'];
					$h0_mention = $dstats[1]['mentions'];
					$mention_diff = $h1_mention - $h0_mention;
					$mention_change = round($mention_diff/$h0_mention,2);	
					
					$h1_imp = $dstats[0]['impression'];
					$h0_imp = $dstats[1]['impression'];
					$imp_diff = $h1_imp - $h0_imp;
					$imp_change = round($imp_diff/$h0_imp,2);
					
				}else{
					$mention_diff = 0;
					$mention_change = 0;
					$imp_diff = 0;
					$imp_change = 0;
				}
				
				
				
				if(sizeof($pii)>1){
					$pii1 =  round($pii[0]['pii']/$pii[0]['total'],2);
					$pii2 =  round($pii[1]['pii']/$pii[1]['total'],2);
					$pii_diff = $pii1 - $pii2;
					$pii_change = round($pii_diff/$pii2,2);
					$pii_score = $pii1;
					
				}else if(sizeof($pii)==1){
					$pii_score = round($pii[0]['pii']/$pii[0]['total'],2);
					$pii_diff = 0;
					$pii_change = 0;
				}else{
					$pii_diff = 0;
					$pii_change = 0;
					$pii_score = 0;
				}
				//TODO ON PERFORMANCE!, since currently we're not using it
				$s_copy['performance']['current_pii'] = floatval($_pii['pii']);				
				$s_copy['performance']['mention_diff'] = 0;	
				$s_copy['performance']['imp_diff'] = $imp_diff;	
				$s_copy['performance']['pii_diff'] = $pii_diff;	
				$s_copy['performance']['mention_change'] = $mention_change;	
				$s_copy['performance']['imp_change'] = $imp_change;	
				$s_copy['performance']['pii_change'] = $pii_change;	
				$rs[] = $s_copy;
		}
		
		$str = json_encode($rs);
		
		$results = null;
		return $str;
		
	}
	
	function rearrange_topics($data){
		$no_data = array();
		$has_data = array();
		
		$n_start=0;
		$n_end = 0;
		$group_id = 0;
		$flag = true;
		foreach($data as $n=>$d){
			$n_end = $n;
			if($group_id!=$d['group_id']){
				if($flag){
					for($i=$n_start;$i<$n_end;$i++){
						array_push($no_data,$data[$i]);
					}
				}else{
					for($i=$n_start;$i<$n_end;$i++){
						array_push($has_data,$data[$i]);
					}
				}
				$group_id = $d['group_id'];
				$n_start = $n;
				$flag=true;
			}
			if($d['mentions']>0){
				$flag = false;
			}
			
		}
		$n_end+=1;
		if($flag){
			for($i=$n_start;$i<$n_end;$i++){
				array_push($no_data,$data[$i]);
			}
		}else{
			for($i=$n_start;$i<$n_end;$i++){
				array_push($has_data,$data[$i]);
			}
		}
		return array_merge($has_data,$no_data);
		
	}
	function get_campaign_source_count($campaign_id){
		$sql = "SELECT twitter,facebook,blog FROM smac_report.source_count 
				WHERE campaign_id=".$campaign_id." LIMIT 1";
		$rs = $this->fetch($sql);
		return $rs;
	}
	function get_campaign_performance($campaign_id){
		$imp_change = 0;
		$mention_change = 0;
		$pii_change = 0;
		$sql = "SELECT published_date,mentions,impressions,rt_impression 
				FROM smac_report.campaign_daily_stats 
				WHERE campaign_id=".$campaign_id." AND lang='all'
				ORDER BY id DESC LIMIT 2";
		$rows = $this->fetch($sql,1);
		$rows[0]['imp'] = $rows[0]['impressions']+$rows[0]['rt_impression'];
		$rows[1]['imp'] = $rows[1]['impressions']+$rows[1]['rt_impression'];
		$imp_change = round(($rows[0]['imp']-$rows[1]['imp'])/$rows[1]['imp']*100,2);
		$imp_diff = $rows[0]['imp']-$rows[1]['imp'];
		$mention_change = round(($rows[0]['mentions']-$rows[1]['mentions'])/$rows[1]['mentions']*100,2);
		$mention_diff = $rows[0]['mentions']-$rows[1]['mentions'];
		//pii change
		$sql = "SELECT sum(pii) as pii_total 
				FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.campaign_feed_sentiment b
				ON a.id = b.feed_id
				WHERE campaign_id =".$campaign_id."
				AND published_date =  '".$rows[1]['published_date']."'";
		$p1 = $this->fetch($sql);
		
		$sql = "SELECT sum(pii) as pii_total 
				FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.campaign_feed_sentiment b
				ON a.id = b.feed_id
				WHERE campaign_id =".$campaign_id."
				AND published_date =  '".$rows[0]['published_date']."'";
		$p2 = $this->fetch($sql);
		
		$pii_change = round(($p2['pii_total']-$p1['pii_total'])/$p1['pii_total']*100,2);
		$pii_diff = $p2['pii_total']-$p1['pii_total'];
		
		
		//we only count accumulation if the last date is d-1.
		$yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		//$ts = mktime(0,0,0,10,4,2011);
		//$yesterday = date("Y-m-d",mktime(0,0,0,date("m",$ts),date("d",$ts),date("Y",$ts)));
		
		//$yesterday = "2011-10-03";
		if(strcmp($yesterday,$rows[0]['published_date'])!=0){
			$imp_change = 0;
			$imp_diff = 0;
			$mention_change = 0;
			$mention_diff = 0;
			$pii_diff = 0;
			$pii_change = 0;
		}
		
		//free up some memories
		$p1 = null;
		$p2 = null;
		$rows = null;
		//-->
		return array("imp_change"=>$imp_change,"mention_change"=>$mention_change,"pii_change"=>$pii_change,
					"imp_diff"=>$imp_diff,"mention_diff"=>$mention_diff,"pii_diff"=>$pii_diff);
	}
	function get_campaign_sentiment($campaign_id){
		$sql = "SELECT COUNT(a.id) as total FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.campaign_feed_sentiment b
				ON a.id = b.feed_id WHERE a.campaign_id=".$campaign_id." AND b.sentiment > 0 LIMIT 1;";
		$rs = $this->fetch($sql);
		$positive = $rs['total'];
		
		$sql = "SELECT COUNT(a.id) as total FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.campaign_feed_sentiment b
				ON a.id = b.feed_id WHERE a.campaign_id=".$campaign_id." AND b.sentiment = 0 LIMIT 1;";
		$rs = $this->fetch($sql);
		$netral = $rs['total'];
		
		$sql = "SELECT COUNT(a.id) as total FROM smac_report.campaign_feeds a
				INNER JOIN smac_report.campaign_feed_sentiment b
				ON a.id = b.feed_id WHERE a.campaign_id=".$campaign_id." AND b.sentiment < 0 LIMIT 1;";
		$rs = $this->fetch($sql);
		$negative = $rs['total'];
		$rs =  null;
		$arr = array("positive"=>$positive,"negative"=>$negative,"netral"=>$netral);
		$positive = null;
		$negative = null;
		$netral = null;
		return $arr;
	}
	function toggle_campaign(){
		//do something here.
	}
}

?>
