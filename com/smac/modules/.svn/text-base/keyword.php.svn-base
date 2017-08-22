<?php
global $APP_PATH;
//require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/TopicRuleHelper.php";
class keyword extends App{
	var $rule;
	function setVar(){
		$this->rule = new TopicRuleHelper();
	}
	function show_all_topics(){
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$data = $this->api->getCampaignListInfo($this->user->account_id);
		
		//print_r($data);exit;
		
		$dat = array();
		
		$totalKeyword = 0;
		
		$this->open(0);
		
		foreach($data->campaign as $k){
			
			//link campaign
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'dashboard','campaign_id' => (String) $k->campaign_id);
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			
			//link edit keyword
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword','act' => 'addremove','campaign_id' => (String) $k->campaign_id);
			$addremovelink = 'index.php?'.$this->Request->encrypt_params($arr);
			
			//get keyword
			//$sql = "SELECT GROUP_CONCAT(label,'') AS labels,GROUP_CONCAT(keyword_txt,'') AS keyword, COUNT(keyword_txt) AS total FROM smac_web.tbl_campaign_keyword k LEFT JOIN smac_web.tbl_keyword_power p ON k.keyword_id=p.keyword_id WHERE campaign_id=".(String) $k->campaign_id;
			$sql = "SELECT label,keyword_txt as keyword 
			FROM smac_web.tbl_campaign_keyword k INNER JOIN smac_web.tbl_keyword_power p ON k.keyword_id=p.keyword_id 
			WHERE campaign_id=".(String) $k->campaign_id;
			$this->force_utf8(false);
			
			$r = $this->fetch($sql,1);
			foreach($r as $n=>$v){
				$r[$n]['keyword'] = trim(translate_rule($r[$n]['keyword']));
				
			}
			$this->force_utf8(true);
			
			$dat[] = array('id' => (String) $k->campaign_id,
							'name' => (String) $k->campaign_name,
							'start' => (String) $k->campaign_start,
							'end' => (String) $k->campaign_end,
							'added' => (String) $k->campaign_added,
							'tracking' => (String) $k->tracking_method,
							'mentions' => (String) $k->mentions,
							'people' => (String) $k->people,
							'sentiment_positive' => (String) $k->sentiment_positive,
							'sentiment_negative' => (String) $k->sentiment_negative,
							'potential_impression' => (String) $k->potential_impression,
							'potential_impact_score' => (String) $k->potential_impact_score,
							'link' => $link,
							'group_id'=>(String) $k->group_id,
							'group_name'=>(String) $k->group_name,
							'keyword' => $r,
							'addremovelink' => $addremovelink);
							
			$totalKeyword += intval($r['total']);
		
		}
		
		$this->close();
		
		$groups = array();
		$group_id = $dat[0]['group_id'];
		$group_name = $dat[0]['group_name'];
		if($group_name==""){
			$group_name = "General Topic(s)";
		}
		$groups[0]['name'] =  $group_name;
		$groups[0]['group_id'] =  $group_id;
		$n=0;
		foreach($dat as $d){
			if($d['group_id']!=$group_id){
				$group_id = $d['group_id'];
				$group_name = $d['group_name'];
				$n++;
				$groups[$n]['name'] =  $group_name;
				$groups[$n]['group_id'] =  $group_id;
			}
			$groups[$n]['data'][] = $d;
		}
		
		$this->View->assign('campaign',$groups);
		$this->View->assign('totalKeyword',$totalKeyword);
		$this->View->assign('totalCampaign',count($data->campaign));
		
		return $this->View->toString(APPLICATION.'/my-keyword.html');
	
	}
	
	function home(){
		$campaign_id = intval($_SESSION['campaign_id']);
		if($campaign_id==null){
			return $this->show_all_topics();
		}
		$client_id = $this->user->account_id;
		$this->assign('sidebar', $this->sidebarHelper->show() );
		//link campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'dashboard','campaign_id' => $campaign_id);
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		
		//link edit keyword
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword','act' => 'addremove','campaign_id' => $campaign_id);
		$addremovelink = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->open(0);
		$sql = "SELECT id,campaign_name FROM smac_web.tbl_campaign WHERE id={$campaign_id} AND client_id = {$client_id} LIMIT 1";
		$campaign = $this->fetch($sql);
		
		$sql = "SELECT label,keyword_txt as keyword 
				FROM smac_web.tbl_campaign_keyword k INNER JOIN smac_web.tbl_keyword_power p ON k.keyword_id=p.keyword_id 
				WHERE campaign_id=".$campaign_id;
		$this->force_utf8(false);
		$r = $this->fetch($sql,1);
		foreach($r as $n=>$v){
			$r[$n]['keyword'] = trim(translate_rule($r[$n]['keyword']));
			
		}
		$this->force_utf8(true);
		$campaign['data'] = $r;
		$data  = array("id"=>$campaign['id'],"name"=>$campaign['campaign_name'],"keyword"=>$r,'link'=>$link,'addremovelink' => $addremovelink);
		$groups = array(
			array("name"=>"Topic Rules","data"=>array($data))
		);
		
		$this->close();
		$this->View->assign("campaign_name",$campaign['campaign_name']);
		$this->View->assign("totalKeyword",sizeof($r));
		$this->View->assign('campaign',$groups);
		return $this->View->toString(APPLICATION.'/my-keyword.html');
	}
	function update_label(){
		$status=0;
		if($this->Request->getParam('ajax')==1){
			$this->open(0);
			/*$keyword_id = mysql_real_escape_string($this->Request->getParam("id"),
											  $this->getConnection());
			$label = mysql_real_escape_string($this->Request->getParam("label"),
											  $this->getConnection());
											  */
			$keyword_id = cleanXSS($this->Request->getParam('id'));
			$label = cleanXSS($this->Request->getParam('label'));
			$campaign_id = intval(cleanXSS($this->Request->getParam('cid')));
			$sql = "UPDATE smac_web.tbl_campaign_keyword
					SET
					label = '{$label}' 
					WHERE campaign_id={$campaign_id}
					AND keyword_id={$keyword_id}";
			
			$q = $this->query($sql);
			$this->close();
			if($q){
				$status=1;
			}
		}
		print json_encode(array("status"=>$status));
		die();
	}
	function add(){
		$country = array("id"=>"indonesia","my"=>"malaysia","sg"=>"singapore","ph"=>"philippines");
		$add = intval( $this->Request->getPost('add') );
		$campaign_id = intval($this->Request->getParam('campaign_id'));
		//$keyword = mysql_escape_string($this->Request->getPost('keyword'));
		//tidak boleh ada operator lang:*
		if(eregi('(lang\:.*)',$this->Request->getPost("keyword1"))){
			$add=0;
		}
		if(eregi('(lang\:.*)',$this->Request->getPost("keyword2"))){
			$add=0;
		}
		if(eregi('(lang\:.*)',$this->Request->getPost("keyword3"))){
			$add=0;
		}
		//link edit keyword
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword','act' => 'addremove','campaign_id' => $campaign_id);
		$addremovelink = 'index.php?'.$this->Request->encrypt_params($arr);
		
		if($add == 1){
			$this->open(0);
			//get campaign language
			$sql = "SELECT geotarget as geo,lang,channels FROM smac_web.tbl_campaign WHERE id=".$campaign_id." LIMIT 1";
			$campaign = $this->fetch($sql);
			
			if($campaign['lang']!="all" && $campaign['lang']!=NULL){
				$lang = $campaign['lang'];
				//sementara exclude malay.
				if($campaign['lang']!="my"){
					$keyword.=" lang:".$lang;
				}else{
					$lang = "";
				}
			}
			if($campaign['lang']!='all'&&$campaign['lang']!='ar'&&$campaign['lang']!='my'){
				$language = "(lang:{$campaign['lang']})";
			}else{
				$language = "";
			}
			
			$label = mysql_escape_string(strip_tags($this->Request->getPost("label")));
			
			$arrKeywords = array("all"=>$this->Request->getPost("keyword1"),
								"any"=>$this->Request->getPost("keyword2"),
								"except"=>$this->Request->getPost("keyword3"),
								"lang"=>$lang);
			$this->rule->keywords($arrKeywords);
			$keyword = $this->rule->toString();
			
			if($campaign['geo']=='loc'){
				$sql = "SELECT id,geo FROM smac_web.tbl_campaign_coverage WHERE campaign_id={$campaign_id} LIMIT 10;";
				$coverage = $this->fetch($sql,1);
			}
			if(sizeof($coverage)==0){
				$keyword = $keyword." {$language}";
				$qry = "SELECT keyword_id FROM smac_web.tbl_keyword_power WHERE keyword_txt='".trim($keyword)."' LIMIT 1";
				$r = $this->fetch($qry);
				
				if(intval($r['keyword_id']) > 0){
					$qry = "SELECT COUNT(*) AS total FROM smac_web.tbl_campaign_keyword WHERE campaign_id=".$campaign_id." AND keyword_id=".$r['keyword_id']." LIMIT 1;";
					$rr = $this->fetch($qry);
					if(intval($rr['total']) > 0){
						$this->assign('sidebar', $this->sidebarHelper->show() );
						$strHTML =  $this->View->showMessage('Sorry, cannot add the already existing keyword. Please input another keyword !',$addremovelink);
					}else{
						$qry = "INSERT INTO smac_web.tbl_campaign_keyword(campaign_id,keyword_id,label,n_status) VALUES (". $campaign_id .",". $r['keyword_id'] .",'{$label}',1);";
						if( $this->query($qry) ){
							//reactivate the keyword
							$sql = "UPDATE smac_web.tbl_keyword_power SET n_status=0 WHERE keyword_id=".$r['keyword_id']."";
							$this->query($sql);
							$this->assign('sidebar', $this->sidebarHelper->show() );
							$strHTML =  $this->View->showMessage('The new keyword has been added successfully!',$addremovelink);
						}else{
							$this->assign('sidebar', $this->sidebarHelper->show() );
							$strHTML =  $this->View->showMessage('Cannot add the keyword, please try again !',$addremovelink);
						}
					}
				}else{
					$qry = "INSERT IGNORE INTO smac_web.tbl_keyword_power(keyword_txt,n_status) VALUES ('".trim($keyword)."',0);";
					if( $this->query($qry) ){
						$qry = "INSERT INTO smac_web.tbl_campaign_keyword(campaign_id,keyword_id,label,n_status) VALUES (".$campaign_id.",". mysql_insert_id() .",'{$label}',1)";
						if( $this->query($qry) ){
							$this->assign('sidebar', $this->sidebarHelper->show() );
							$strHTML = $this->View->showMessage('The new keyword has been added successfully!',$addremovelink);
						}else{
							$this->assign('sidebar', $this->sidebarHelper->show() );
							$strHTML = $this->View->showMessage('Cannot add the keyword, please try again !',$addremovelink);
						}
					}else{
						$this->assign('sidebar', $this->sidebarHelper->show() );
						$strHTML = $this->View->showMessage("Cannot add new keyword, please try again later !",$addremovelink);
					}
				}
			}else{
				//these is for adding a keyword for geo-specific topic.
				foreach($coverage as $c){
					
					$kw = mysql_escape_string(cleanXSS("(bio_location_contains:{$country[$c['geo']]}) {$language} ".$keyword));
					$qry = "INSERT IGNORE INTO smac_web.tbl_keyword_power(keyword_txt,n_status) VALUES ('".trim($kw)."',0);";
					if($this->query($qry)){
						$rule_id = intval(mysql_insert_id());
						if($rule_id==0){
							$sql = "SELECT keyword_id as id,keyword_txt FROM smac_web.tbl_keyword_power WHERE keyword_txt='".trim($kw)."' LIMIT 1";
							$rs = $this->fetch($sql);
							$rule_id = intval($rs['id']);
						}
						if($rule_id>0){
							$sql = "INSERT IGNORE INTO smac_web.tbl_campaign_keyword(campaign_id,keyword_id,label,n_status) VALUES (".$campaign_id.",". $rule_id .",'{$label}',1)";
							$q = $this->query($sql);
							$topic_rule_id = intval(mysql_insert_id());
							$sql = "INSERT IGNORE INTO smac_web.tbl_rule_coverage
												(c_id,t_id)
												VALUES({$c['id']},{$topic_rule_id})";
							
							$q = $this->query($sql);
						}
					}
				}
				if($q){
					$this->assign('sidebar', $this->sidebarHelper->show() );
					$strHTML = $this->View->showMessage('The new keyword has been added successfully!',$addremovelink);
				}else{
					$this->assign('sidebar', $this->sidebarHelper->show() );
					$strHTML = $this->View->showMessage("Cannot add new keyword, one or more rule(s) are already exists !",$addremovelink);
				}
			}
			
			$this->close();
			return $strHTML;
		}else{
			$this->assign('sidebar', $this->sidebarHelper->show() );
			return $this->View->showMessage('Cannot add new keyword, please try again later !',$addremovelink);
		}
		
	}
	
	function addremove(){
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$campaign_id = intval( $this->Request->getParam('campaign_id') );
		$topic = $this->menuHelper->getTopicDetail($campaign_id);
		$this->assign("location",$topic['country']);
		$this->assign("language",$topic['lang_str']);
		//get keyword
		$sql = "SELECT ck.campaign_id,kp.keyword_id,kp.keyword_txt,ck.label FROM smac_web.tbl_campaign_keyword ck LEFT JOIN smac_web.tbl_keyword_power kp ON ck.keyword_id=kp.keyword_id WHERE campaign_id=".$campaign_id.";";
		$this->open(0);
		$this->force_utf8(false);
		$r = $this->fetch($sql,1);
		$this->force_utf8(true);
		$this->close();
		
		$num = count($r);
		
		for($i=0;$i<$num;$i++){
			$r[$i]['keyword_txt'] = trim(translate_rule($r[$i]['keyword_txt']));
			//link delete keyword
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword','act' => 'remove','campaign_id' => $campaign_id, 'keyword_id' => $r[$i]['keyword_id']);
			$removelink = 'index.php?'.$this->Request->encrypt_params($arr);
			$r[$i]['remove_link'] = $removelink;
			$r[$i]['label'] = htmlspecialchars($r[$i]['label'],ENT_QUOTES);
			
			
		}
		
		
		$this->assign("n_rules",$num);
		$this->assign('list',$r);
		
		//link add keyword
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword','act' => 'add','campaign_id' => $campaign_id);
		$addlink = 'index.php?'.$this->Request->encrypt_params($arr);
		
		$this->View->assign('addlink',$addlink);
		$this->View->assign("cid",$campaign_id);
		return $this->View->toString(APPLICATION.'/my-keyword-list.html');
	
	}
	
	function remove(){
		
		$campaign_id = intval( $this->Request->getParam('campaign_id') );
		$keyword_id = intval( $this->Request->getParam('keyword_id') );
		$erase = intval($this->Request->getParam('erase'));
		
		
		if($erase==0){
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),
					'page' => 'keyword','act' => 'remove','campaign_id' => $campaign_id, 
					'keyword_id' => $keyword_id,'erase'=>1);
			$removelink1 = 'index.php?'.$this->Request->encrypt_params($arr);
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),
					'page' => 'keyword','act' => 'remove','campaign_id' => $campaign_id, 
					'keyword_id' => $keyword_id,'erase'=>2);
			$removelink2 = 'index.php?'.$this->Request->encrypt_params($arr);
			
			$this->assign('sidebar', $this->sidebarHelper->show() );
			return $this->View->confirm(text('keyword_delete_confirm1'),
								array("url"=>$removelink2,"label"=>"Erase Data"),
								array("url"=>$removelink1,"label"=>"Do not erase"));
		}else{
			$qry = "DELETE FROM smac_web.tbl_campaign_keyword WHERE campaign_id=".$campaign_id." AND keyword_id=".$keyword_id." LIMIT 1;";
			$this->open(0);
			$r = $this->query($qry);
			if($r){
				if($erase==2){
					$this->remove_feeds($campaign_id,$keyword_id);
				}
			}
			$this->close();
		}
		//link edit keyword
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword','act' => 'addremove','campaign_id' => $campaign_id);
		$addremovelink = 'index.php?'.$this->Request->encrypt_params($arr);
		
		if($r){
			$this->assign('sidebar', $this->sidebarHelper->show() );
			return $this->View->showMessage('Remove keyword success!',$addremovelink);
		}else{
			$this->assign('sidebar', $this->sidebarHelper->show() );
			return $this->View->showMessage('Remove keyword failed!',$addremovelink);
		}
	}
	/**
	 * create a job for removing feeds after a rule is removed / deleted
	 * UPDATE 
	 * 26/07/2012 - duf
	 * the old job-remove-rule bot only deletes the feeds from the rule.
	 * the new one also do report regeneration.
	 * 
	 * @param $campaign_id
	 * @param $keyword_id
	 * @return unknown_type
	 */
	function remove_feeds($campaign_id,$keyword_id){
		$sql = "INSERT INTO smac_report.job_remove_rule_queue (campaign_id, keyword_id) 
				VALUES ({$campaign_id}, {$keyword_id});";
		return $this->query($sql);
	}
}
?>