<?php
class Campaign{
	function __construct($logger){
		$this->logger = $logger;
	}
	function create_campaign(){
		global $conn,$SCHEMA_WEB;
		$logger = $this->logger;
		$client_id = intval($_REQUEST['client_id']);
		$campaign_name = mysql_escape_string(urldecode($_REQUEST['campaign_name']));
		$campaign_start = mysql_escape_string(urldecode($_REQUEST['campaign_start']));
		$campaign_end = mysql_escape_string(urldecode($_REQUEST['campaign_end']));
		$tracking_method = mysql_escape_string(urldecode($_REQUEST['tracking_method']));
		$channels = mysql_escape_string(urldecode($_REQUEST['channels']));
		$group_id = mysql_escape_string(intval(urldecode($_REQUEST['group_id'])));
		$historical_interval = mysql_escape_string(intval(urldecode($_REQUEST['historical_interval'])));
		$lang = mysql_escape_string(urldecode($_REQUEST['lang']));
		$geo = mysql_escape_string(urldecode($_REQUEST['geo']));
		$twitter_account = mysql_escape_string(urldecode($_REQUEST['twitter_account']));
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
						"ru"=>"ru");
		
		//$p_keyword = str_replace('\r\n','\n',$_REQUEST['keywords']);
		$p_keyword = (base64_decode($_REQUEST['keywords']));
		$logger->info("Raw Keywords : ".($p_keyword));
		//$keyword = $p_keyword ? nl2br($p_keyword) : '';
		
		//$logger->info("actual keywords : ".$p_keyword);
		
		//$keywords = explode(",",trim($p_keyword));
		
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
				$campaign_end,$channels,$tracking_method,$lang,$geo,$twitter_account,$hastag,$group_id,$historical_interval);
		
		$logger->info("new campaign id : ".$id);
		
		//-->
		if($tracking_method == 1){
			$tbl_keyword = "tbl_keyword_power";
		}else{
			$tbl_keyword = "tbl_keyword";
		}
		$logger->info("using schema : ".$tbl_keyword);
		$resp = array();
		if($id>0){
			if(sizeof($keywords)){
				
				
				$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".".$tbl_keyword." (keyword_txt) VALUES ";
				$keys = "";
				$t = 0;
				foreach($keywords as $k => $v){
					$kw = trim(urldecode($v));
					//$invalid_op = @eregi('(lang\:.*)',$kw);
					if( $kw != '' && !$invalid_op){
						/*
						if(@$pt_lang[$lang]!=null&&@$pt_lang[$lang]!="all"){
							$kw.=" lang:".$pt_lang[$lang];
						}
						*/
						$qry .= "('".mysql_escape_string( $kw )."'), ";
						if($t>0){
							$keys.=",";
						}							
						$keys.="'".mysql_escape_string( $kw )."'";
						$t++;
					}
				}
				$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
				$logger->info("SQL 1 : ".$qry);
				
				//print $qry."<br/>";
				if(!mysql_query($qry,$conn)){
					$logger->error("cannot insert new keyword into ".$tbl_keyword);
					$resp = array("status"=>0,"message"=>"cannot insert new keyword(s)","data"=>$qry);
					return json_encode($resp);
				}else{
					$sql = "SELECT keyword_id,keyword_txt FROM ".$SCHEMA_WEB.".".$tbl_keyword." WHERE keyword_txt IN (".$keys.")";
					//print $sql."<br/>";
					$rs1 = $this->fetch($sql,1);
					$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".tbl_campaign_keyword (keyword_id,campaign_id,label) VALUES ";
					
					foreach($rs1 as $kk => $vv){
						$kwds = trim($vv['keyword_id']);
						//insert labels
						foreach($keywords as $nn=>$kk){
							if(trim($kk)==$vv['keyword_txt']){
								$label = mysql_escape_string($labels[$nn]);
							}
						}
						if( $kwds != '' ){
							$qry .= "('".mysql_escape_string( $kwds )."',".$id.",'{$label}'), ";
						
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
					
					
					//upload keyword replay
					if($historical_interval>0){
						$this->upload_replay_keyword($id,$keywords,$historical_interval,$conn);
					}
					
					$resp = array("status"=>1,"campaign_id"=>$id);
					$logger->info(json_encode($resp));
					return json_encode($resp);
					//return "<status>1</status><campaign_id>".$id."</campaign_id>";
				}
			}
		}
	}
	function upload_replay_keyword($campaign_id,$keywords,$interval,$conn){
		global $SCHEMA_WEB,$logger;
		$start_from = date("YmdHi",mktime(0,0,0,date("m"),date("d")-($interval+1),date("Y")));
		$until = date("YmdHi",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".tbl_keyword_replay(keyword_txt) VALUES ";
		$keys = "";
		$t = 0;
		
		$nn=0;
		foreach($keywords as $k => $v){
			$kw = trim(urldecode($v));
			//$invalid_op = @eregi('(lang\:.*)',$kw);
			if( $kw != '' && !$invalid_op){
				$kw = trim(reformat_rule($kw));
				if($nn>0){
					$qry.=",";
				}
				$qry .= "('".mysql_escape_string( $kw )."')";
				$nn++;
				if($t>0){
					$keys.=",";
				}							
				$keys.="'".mysql_escape_string( $kw )."'";
				$t++;
			}
		}
		
		$q = mysql_query($qry,$conn);
		$logger->status($qry,$q);
		if(strlen($keys)>0){
			$sql = "SELECT keyword_id FROM ".$SCHEMA_WEB.".tbl_keyword_replay WHERE keyword_txt IN (".$keys.")";
			
			$rs1 = $this->fetch($sql,1);
			$qry = "INSERT IGNORE INTO smac_web.tbl_campaign_replay 
					(campaign_id, keyword_id, n_status, start_from, until, author_id) VALUES";
			
			$n=0;
			foreach($rs1 as $kk => $vv){
				$kwds = trim($vv['keyword_id']);
				if( $kwds != '' ){
					if($n>0){
						$qry.=",";
					}
					$qry .= "({$campaign_id},'".mysql_escape_string( $kwds )."',1,'{$start_from}','{$until}','')";
					$n++;
				}
			}
			$q = mysql_query($qry,$conn);
			$logger->status($qry,$q);
			$sql = "UPDATE ".$SCHEMA_WEB.".tbl_keyword_replay SET n_status=0 
							WHERE keyword_id IN (SELECT keyword_id FROM ".$SCHEMA_WEB.".tbl_campaign_replay
										WHERE campaign_id = ".intval($campaign_id).")";
						
			$q = mysql_query($sql,$conn);
			$logger->status($sql,$q);
			return $q;
		}
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
	function add_campaign($client_id,$campaign_name,$campaign_start,$campaign_end,$channels,$tracking_method,$lang,$geo,$twitter_account,$hastag,$group_id=0,$historical_interval=0){
		global $conn,$SCHEMA_WEB;
		$logger = $this->logger;
		//insert campaign
		$qry = "INSERT INTO ".$SCHEMA_WEB.".tbl_campaign 		
			(client_id,campaign_name,campaign_start,
			campaign_end,campaign_added,channels,tracking_method,lang,geotarget,twitter_account,hastag,group_id,historical_interval) 
			VALUES
			(".$client_id.",'".$campaign_name."',
			'".$campaign_start."','".$campaign_end."',NOW(),
			'".$channels."',".$tracking_method.",'".$lang."','".$geo."','".$twitter_account."','".$hastag."',".$group_id.",".$historical_interval.");";
		
		$q = mysql_query($qry,$conn);		
		$logger->status($qry,$q);
		$id = mysql_insert_id();
		return $id;
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
		$sql = "SELECT id as campaign_id,campaign_name,campaign_start,campaign_end,campaign_added,channels,tracking_method 
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
		$sql  =" SELECT a.id as campaign_id,campaign_name,campaign_start,campaign_end,campaign_added,lang,channels,tracking_method,b.mentions,b.people,b.sentiment_positive,b.sentiment_negative,
				b.true_reach as potential_impression,
				potential_impact_score ,a.n_status,c.total_usage,c.total_limit,a.group_id,d.group_name
				FROM ".$SCHEMA_WEB.".tbl_campaign a
				LEFT JOIN smac_report.dashboard_summary b
				ON a.id=b.campaign_id 
				LEFT JOIN smac_report.topic_usage c
				ON b.campaign_id = c.campaign_id
				LEFT JOIN smac_web.tbl_topic_group d
				ON a.group_id = d.id
				WHERE a.client_id = ".$client_id." AND a.n_status <> 2
				ORDER BY group_id ASC
				LIMIT 1000";
		
		
		$results = $this->fetch($sql,1);
		

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
		
		$sql = "SELECT a.id as campaign_id,group_id,b.group_name,a.n_status FROM smac_web.tbl_campaign a
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
			}
		}
		$results = null;
		if(sizeof($results)>0){
			$rs = $this->rearrange_topics($rs);
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
