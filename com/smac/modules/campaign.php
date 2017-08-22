<?php
global $APP_PATH,$ENGINE_PATH;
require_once $APP_PATH . APPLICATION . "/helper/TopicGroupHelper.php";
class campaign extends App{
	
	
	function setVar(){
		$this->billing = new BillingHelper($this->Request);
		$this->group = new TopicGroupHelper($req);
	}
	function getAccountType(){
		$this->open(0);
		$qry = "SELECT account_type FROM smac_web.smac_account WHERE id='".$this->user->account_id."';";
		$rs = $this->fetch($qry);
		$this->close();
		return $rs['account_type'];
	}
	function home(){
		
		$account_type = $this->getAccountType();
		if($account_type>=1){
			$removeable = 1;
		}else{
			$removeable = 0;
		}
		
		$this->assign('sidebar', $this->sidebarHelper->show(false) );
		$data = $this->api->getAccountUsage($this->user->account_id);
		$dat = array();
		foreach($data->row as $k){
			$dat[(String) $k->attributes()->name] = (String) $k->attributes()->total;
		}
		//print_r($dat);exit;
		
		$this->View->assign('account',$dat);
		
		//Topic Overview
		$data = json_decode($this->api->getTopicOverview($this->user->account_id));
		$num = count($data);
		
		//Jika belum punya topic
		if($num <= 0){
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign', 'act' => 'add');
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			$this->View->assign('create_topic_link',$link);
			return $this->View->toString(APPLICATION.'/welcome-page.html');
		}
		
		
		//Jika topic baru 1 (topic pertama)
		
		if($num == 1){
			$tgl = $data[0]->campaign_added;
			$tgl = explode(' ',$tgl);
			$tgl = explode('-',$tgl[0]);
			$bulan = date('m', mktime(0, 0, 0, $tgl[1], $tgl[2], $tgl[0]));
			$hari = date('d', mktime(0, 0, 0, $tgl[1], $tgl[2], $tgl[0])) + 1;
			$tahun = date('Y', mktime(0, 0, 0, $tgl[1], $tgl[2], $tgl[0]));
			
			$dt = strtoupper(date('l, d F', mktime(0, 0, 0, $bulan, $hari, $tahun)));
			$now = strtoupper(date('l, d F', mktime(0, 0, 0, date('m'), date('d'), date('Y'))));
			
			
			if( intval($data[0]->people) == 0 &&  intval($data[0]->potential_impression) == 0 && intval(trim($data[0]->mentions)) == 0 ){
				//if( strcmp($dt,$now) != 0){
					$this->View->assign('first_data',$dt);
					//link add campaign
					$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack', 'campaign_id' => $_SESSION['campaign_id']);
					$link = 'index.php?'.$this->Request->encrypt_params($arr);
					$this->View->assign('linklivetrack',$link);
				//}
				$this->View->assign('empty_data',1);
			}else{
				$this->View->assign('empty_data',0);
			}
		}
		$group_topics = array();
		for($i=0;$i<$num;$i++){
			if($group_topics[$data[$i]->group_id]==null){
				$group_topics[$data[$i]->group_id] = 0;
			}
			$group_topics[$data[$i]->group_id]++;
			$tgl = $data[$i]->campaign_added;
			$tgl = explode(' ',$tgl);
			$tgl = explode('-',$tgl[0]);
			$bulan = date('m', mktime(0, 0, 0, $tgl[1], $tgl[2], $tgl[0]));
			$hari = date('d', mktime(0, 0, 0, $tgl[1], $tgl[2], $tgl[0])) + 1;
			$tahun = date('Y', mktime(0, 0, 0, $tgl[1], $tgl[2], $tgl[0]));
			$avts = mktime(0, 0, 0, $tgl[1]+1, $tgl[2], $tgl[0]);
			$dt = strtoupper(date('l, F dS', mktime(0, 0, 0, $bulan, $hari, $tahun)));
			$now = strtoupper(date('l, F dS', mktime(0, 0, 0, date('m'), date('d'), date('Y'))));
			$no_data = 0;
			
			if( intval($data[$i]->people) == 0 &&  intval($data[$i]->potential_impression) == 0 && intval(trim($data[$i]->mentions)) == 0 ){
				//if( strcmp($dt,$now) != 0){
					if((time()-(60*60*7))>mktime(0, 0, 0, $hari, $bulan, $tahun)){
						$no_data = 1;
						$data[$i]->first_data = false;
					}else{
						$no_data = 0;
						$data[$i]->first_data = true;
					}
				
					$data[$i]->ts = time();
					$data[$i]->avts = strtotime($dt);
					$data[$i]->no_data = $no_data;
					
					
				
				
			}
			//if $dt is less than current date, then we assume the report will be processed tommorow.
			if(time()>strtotime($dt)){
				$dt = strtoupper(date("l, F dS",time()+(60*60*24)));
			}
			$data[$i]->availability_date = $dt;
			//link add campaign
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack', 'campaign_id' => $data[$i]->campaign_id);
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			$data[$i]->live_track_url = $link;
			//link edit keyword
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword', 'campaign_id' => $data[$i]->campaign_id);
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			$data[$i]->keyword_url = $link;
			//link campaign
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'home','act' => 'main','campaign_id' => (String) $data[$i]->campaign_id);
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			
			//link change status
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign','act' => 'changestatus','cid' => (String) $data[$i]->campaign_id,'status' => (int) $data[$i]->n_status);
			$linkchange = 'index.php?'.$this->Request->encrypt_params($arr);
				
			//link delete topic (hide topic)
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign','act' => 'remove','cid' => (String) $data[$i]->campaign_id);
			$linkremove = 'index.php?'.$this->Request->encrypt_params($arr);
			//print_r($data[$i]);
			//break;
			if(strlen(trim($data[$i]->mentions))==0){
				$data[$i]->mentions = 0;
			}
			
			$data[$i]->campaign_link = $link;
			$data[$i]->link_change = $linkchange;
			$data[$i]->link_remove = $linkremove;
			$data[$i]->removeable = $removeable;
			
			$data[$i]->n_mentions = intval(trim($data[$i]->mentions));
			$data[$i]->total_mentions = smac_number(trim($data[$i]->mentions));
			$data[$i]->n_potential_impression = ($data[$i]->potential_impression);
			$data[$i]->potential_impression = smac_number($data[$i]->potential_impression);
			$data[$i]->n_people = ($data[$i]->people);
			$data[$i]->people = number_format($data[$i]->people);
			$data[$i]->performance->imp_diff = smac_number($data[$i]->performance->imp_diff);
			$data[$i]->performance->mention_diff = smac_number($data[$i]->performance->mention_diff);
			$data[$i]->performance->pii_diff = floatval(round($data[$i]->performance->pii_diff,2));
			$data[$i]->group_id = $data[$i]->group_id;
			$data[$i]->group_name = $data[$i]->group_name;
		}
		$this->View->assign("group_topics",json_encode($group_topics));
		$this->View->assign('raw_num',count($data));
		
		//echo 'raw num: '.count($data);exit;
		
		$data = json_encode($data);
		
		$this->View->assign('raw',$data);
		
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign','act' => 'add');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlnewcampaign',$link);
		$this->View->assign("username",$this->user->first_name." ".$this->user->last_name);
		return $this->View->toString(APPLICATION.'/my-campaign.html');
	
	}
	function confirm_remove(){
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),
					'page' => 'campaign',
					'act' => 'remove',
					'cid' =>$_SESSION['campaign_id']);
		$linkremove = 'index.php?'.$this->Request->encrypt_params($arr);
		
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'campaign_id' => $_SESSION['campaign_id']);
		$urlback= 'index.php?'.$this->Request->encrypt_params($arr);
		$this->open(0);
		$sql = "SELECT campaign_name FROM smac_web.tbl_campaign WHERE id = {$_SESSION['campaign_id']}";
		$campaign = $this->fetch($sql);
		$this->close();
		return $this->View->confirm(text('topic_remove_confirmation',array('topic_name'=>$campaign['campaign_name'])),
									array("url"=>$linkremove,"label"=>"Remove Topic"),
									array("url"=>$urlback,"label"=>"Cancel"));
	}
	function remove(){
		$account_id = $this->user->account_id;
		$account_type = $this->getAccountType();
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'overview');
		$urlback= 'index.php?'.$this->Request->encrypt_params($arr);
		if($account_type>=1){
			$this->open(0);
			$sql = "UPDATE smac_web.tbl_campaign SET n_status=2 WHERE id=".intval($this->Request->getParam('cid'))."
					AND client_id={$account_id}";
			$q = $this->query($sql);
			//also disactivate the topic's rules
			if($q){
				$sql = "UPDATE smac_web.tbl_campaign_keyword SET n_status=0 
						WHERE campaign_id=".intval($this->Request->getParam('cid'));
				$this->query($sql);	
				$sql = "UPDATE smac_web.tbl_campaign_replay SET n_status=0 
						WHERE campaign_id=".intval($this->Request->getParam('cid'));
				$this->query($sql);	
			}
			$this->close();
			if($q){
				return $this->View->showMessage(text('topic_remove_success'),$urlback);
			}else{
				return $this->View->showMessage(text('topic_remove_error'),$urlback);
			}
		}else{
			return $this->View->showMessage(text('topic_remove_unavailable'),$urlback);
		}
	}
	function getAddons(){
		$rs = $this->fetch("SELECT a.id,a.name,a.description,a.price 
							FROM smac_web.tbl_addon_ref a 
							WHERE a.n_status=1 
							ORDER BY a.id ASC;",1);
		return $rs;
	}
	function setTopicCost($account_type){
		global $CONFIG;
		//at the moment, there's only 4 account types
		if($account_type==99){
			$account_type=0;
		}
		if(intval($account_type)>4){
			$account_type=1;
		}
		$this->View->assign("cost",$CONFIG['ACCOUNT_COST'][$account_type]);
	}
	function add(){
		global $logger,$APP_PATH,$TRIAL,$CONFIG;
		$_add = intval($_POST['add']);
		$account_id = $this->user->account_id;

		//-->
		//Cek apakah account trial?
		$sql = "SELECT account_type,email,name FROM smac_web.smac_account WHERE id=".$this->user->account_id.";";
		$this->open(0);
		$rs = $this->fetch($sql);
		$email_account = $rs['email'];
		$account_name = $rs['name'];
		
		//daftar addons
		$addons = $this->getAddons();
		$this->View->assign("addons",json_encode($addons));
		$sql = "SELECT COUNT(*) AS total FROM smac_web.tbl_campaign WHERE client_id=".$this->user->account_id.";";
		$topic = $this->fetch($sql);
		$total_topic = intval($topic['total']);
		$account_type = $rs['account_type'];
		$this->setTopicCost($account_type);
		//Cek jika account trial
		if( intval($rs['account_type']) <= 0 ){
			
			//makesure TOPIC_LIMIT in config.php has a value.
			if($TRIAL['TOPIC_LIMIT']==null){
				$TRIAL['TOPIC_LIMIT'] = 2;
			}
			//Cek jika account trial sudah punya 2 topic
			if( $total_topic >= $TRIAL['TOPIC_LIMIT'] ){
					
				global $CONFIG;
				//EMAIL TO ACCOUNT
				if($CONFIG['EMAIL_PROSES']){
						
					$this->View->assign('baseurl',$CONFIG['BASEURL']);
					$this->View->assign('name',ucfirst($this->user->first_name).' '.ucfirst($this->user->last_name));
					$msg = $this->View->toString(APPLICATION.'/email/email-trial-limit.html');
					
					$this->View->assign('baseurl',$CONFIG['BASEURL']);
					$this->View->assign('name',ucfirst($this->user->first_name).' '.ucfirst($this->user->last_name));
					$plain = $this->View->toString(APPLICATION.'/email/email-trial-limit-plain.html');
					if( strcmp(strtolower($this->user->email),strtolower($email_account)) == 0 ){
						//email ke account
						$mail = new Mailer();
						$mail->use_postmark(true);
						$mail->send(array(
							'to'=>array('email'=>$email_account,
										 'name'=>$this->user->first_name." ".$this->user->last_name),
							'subject'=>"SMAC - TRIAL LIMIT",
							'plainText'=>$plain,
							'htmlText'=>$msg
						));
						
					}else{
						//email ke user
						$mail = new Mailer();
						$mail->use_postmark(true);
						$mail->send(array(
							'to'=>array('email'=>$this->user->email,
										 'name'=>$this->user->first_name." ".$this->user->last_name),
							'subject'=>"SMAC - TRIAL LIMIT",
							'plainText'=>$plain,
							'htmlText'=>$msg
						));
						
						//email ke account
						$mail = new Mailer();
						$mail->use_postmark(true);
						$mail->send(array(
							'to'=>array('email'=>$email_account,
										 'name'=>$account_name),
							'subject'=>"SMAC - TRIAL LIMIT",
							'plainText'=>$plain,
							'htmlText'=>$msg
						));
					}
				}
				$this->assign('sidebar', $this->sidebarHelper->show() );
				return $this->View->showMessage(text('account_creation_limit_reached'),'index.php');
					
			}
		}
		$this->close();
		
		
		
		if( intval($rs['account_type']) <= 0 ){
			$cost=0;
			$this->View->assign('maxMainKeyword', 5);
			$this->View->assign('maxRelatedKeyword', 45);
			$this->View->assign('totalKeyword', 50);
			$this->View->assign('max_topic', 1);
			$this->View->assign('quota_limit', 15000);
			
		}else{
			//$cost = 1;
			$this->View->assign('maxMainKeyword', 5);
			$this->View->assign('maxRelatedKeyword', 45);
			$this->View->assign('totalKeyword', 50);
			$this->View->assign('max_topic', 1);
			$this->View->assign('quota_limit', 100000);
			//check saldo
			$saldo = $this->billing->get_saldo($account_id);
			$this->View->assign("saldo",$saldo);
		}
		
		//account_type
		$this->View->assign("account_type",intval($rs['account_type']));
		
		if($_add == 1){
			$group_id = $this->post('topicgroup');
			/* Tambah data baru 30 jan 2012 */
	
			$_twitter_account = mysql_escape_string($_POST['twitter_account']);
			$_fb_account = mysql_escape_string($_POST['fb_account']);
			if($_twitter_account == 'Your Twitter Account'){
				$_twitter_account = '';
			}
			$_twitter_account = str_replace("@","",$_twitter_account);	
			$_name = mysql_escape_string($_POST['name']);
			$_desc = mysql_escape_string($_POST['topicDesc']);
			$_lang = mysql_escape_string($_POST['lang']);
			//$_geo = mysql_escape_string($_POST['coverage']);
			$_geo = urlencode64(serialize(array('coverage'=>$_POST['coverage'],'localmarket'=>$_POST['localmarket'])));
			$_additional_sites = $_POST['a_site'];
			$_addons = $_POST['addon'];
			$at = $account_type;
			if($at==99){$at=0;}
			$orders = array(array('item_id'=>0,
									'description'=>'Topic : '.$_name.' 1 Month Subscription',
									'price'=>$CONFIG['ACCOUNT_COST'][$at]));
			$total_cost = $CONFIG['ACCOUNT_COST'][$at];
			
			if(sizeof($_addons)>0){
				foreach($_addons as $a){
					$addon_id = intval($a);
					foreach($addons as $aa){
						if($aa['id']==$addon_id){
							$orders[] = array('item_id'=>$addon_id,
									'description'=>$aa['name'],
									'price'=>$aa['price']);
							$total_cost+=$aa['price'];
						}
					}
				}
			}
			
			$_hastag = mysql_escape_string($_POST['hastag']);
			
			//$_start = str_replace("/","-",mysql_escape_string($_POST['start']));
			
			$_start = explode("/",mysql_escape_string($_POST['start']));
			$_to_date = $_start[2].'-'.$_start[1].'-'.$_start[0];
			$historical = intval($this->post('historical'));
			$_start = date("Y-m-d",mktime(0,0,0,$_start[1],($_start[0]-$historical),$_start[2]));
			
			//$_end = str_replace("/","-",mysql_escape_string($_POST['end']));
			$_end = '2015-12-31'; //hardcore sementara
			$_ctw = $_POST['ctwitter'];
			$_cfb = $_POST['cfacebook'];
			$_cblog = $_POST['cblog'];
			$_method = intval($_POST['method']);
			$n_rules = intval($this->post('n_rules'));
			$n_rules2 = intval($this->post('n_rules2'));
			
			$keywords = array();
			
			//time to format the rules accordingly
			include_once $APP_PATH.APPLICATION."/helper/TopicRuleHelper.php";
			$rule = new TopicRuleHelper();
			$labels = array();
			if($n_rules2==0){
				for($i=0;$i<$n_rules;$i++){
					$label = $this->post('rulelabel'.$i);
					$all = $this->post('ruleall'.$i);
					$any = $this->post('ruleany'.$i);
					$exc = $this->post('ruleexc'.$i);
					if(strlen($all)>0){
						$kw = array('all'=>$all,'any'=>$any,'except'=>$exc,'lang'=>$_lang);
						$rule->keywords($kw);
						$keywords[] = $rule->toString();
						$labels[] = $label;
					}
				}
			}else{
				for($i=0;$i<$n_rules2;$i++){
					$label = $this->post('rulelabel2'.$i);
					$all = $this->post('ruleall2'.$i);
					if(strlen($all)>0){
						$keywords[] = ($all);
						$labels[] = mysql_escape_string($label);
					}
				}
			}
			
			$_keywords = base64_encode(serialize(array("labels"=>$labels,"keywords"=>$keywords)));
			
			$_channels = implode(",",$this->post('channels'));
			
			if($_name != '' && $_start != '' && $_end != '' && $_keywords != '' && $_method && $_lang != '' && $_geo != ''){
				//$logger->info("all variables set, send data to API");
				$opt = array("name" => $_name,
								 "start" => $_start,
								 "end" => $_end,
								 "channels" => $_channels,
								 "keywords" => $_keywords,
								 "method" => $_method,
								 "desc" => $_desc,
								 "lang" => $_lang,
								 "geo" => $_geo,
								 "twitter_account" => $_twitter_account,
				 				 "fb_account" => $_fb_account,
								 "hastag" => $_hastag,
								 "brand" => $_brand,
								 "event" => $_event,
								 "twitter_account" => $_twitter_account,
								 "group_id"=>intval($group_id),
								 "historical_interval"=>$historical,
								 "competitor" => $_competitor,
								 "account_type"=>$account_type);
				
				$data = $this->api->addCampaign($this->user->account_id,$opt);
				
				$o = json_decode($data);
				//paid with smac credit
				$new_campaign_id = $o->campaign_id;
				$billing_rs = array();
				if($new_campaign_id>0){
					//$billing_rs[] = $this->billing->purchase_topic($account_id,$new_campaign_id,1*$cost);
					if(intval($rs['account_type'])==0||intval($rs['account_type'])==99||intval($rs['account_type']==5)){
						//for free account, we add credit and debit automatically.
						$this->billing->addCredit($account_id, $total_cost,0);
						$this->billing->purchase_topic($account_id,$new_campaign_id,$total_cost);
						//for free account, we create an order with paid status.
						$this->billing->create_order($account_id, $new_campaign_id, $orders,1);
					}else{
						//for paid account, we only create purchase order
						$this->billing->create_order($account_id, $new_campaign_id, $orders);
					}
					$this->addInitialOverviewData($this->user->account_id,$new_campaign_id,$group_id,$opt);
					if(sizeof($_additional_sites)>0){
						$this->set_additional_sites($new_campaign_id,$_additional_sites,$_POST['localmarket'],$_lang);
					}
					$this->addTopicAddons($new_campaign_id,$_addons);
				}
				//-->
				
				if(intval($o->status) == 1){
					
					//Jika ini topic yang pertama, maka kirim email
					if( intval($topic['total']) == 0 ){
					
						global $CONFIG;
						//EMAIL TO ACCOUNT
						if($CONFIG['EMAIL_PROSES']){
							$this->View->assign('baseurl',$CONFIG['BASEURL']);
							$this->View->assign('name',ucfirst($this->user->first_name).' '.ucfirst($this->user->last_name));
							$this->View->assign('topic',ucfirst($_name));
							$msg = $this->View->toString(APPLICATION.'/email/email-first-topic.html');
							
							$this->View->assign('baseurl',$CONFIG['BASEURL']);
							$this->View->assign('name',ucfirst($this->user->first_name).' '.ucfirst($this->user->last_name));
							$this->View->assign('topic',ucfirst($_name));
							$plain = $this->View->toString(APPLICATION.'/email/email-first-topic-plain.html');
							
							if( strcmp(strtolower($this->user->email),strtolower($email_account)) == 0 ){
								//email ke account
								$mail = new Mailer();
								$mail->use_postmark(true);
								$mail->send(array(
									'to'=>array('email'=>$email_account,
												 'name'=>$account_name),
									'subject'=>"SMAC - YOUR FIRST TOPIC",
									'plainText'=>$plain,
									'htmlText'=>$msg
								));
							}else{
								//email ke user
								$mail = new Mailer();
								$mail->use_postmark(true);
								$mail->send(array(
									'to'=>array('email'=>$this->user->email,
												 'name'=>ucfirst($this->user->first_name).' '.ucfirst($this->user->last_name)),
									'subject'=>"SMAC - YOUR FIRST TOPIC",
									'plainText'=>$plain,
									'htmlText'=>$msg
								));
								
								//email ke account
								$mail = new Mailer();
								$mail->use_postmark(true);
								$mail->send(array(
									'to'=>array('email'=>$email_account,
												 'name'=>$account_name),
									'subject'=>"SMAC - YOUR FIRST TOPIC",
									'plainText'=>$plain,
									'htmlText'=>$msg
								));
							}
						}
						
						//link add campaign
						$_SESSION['campaign_id'] = intval($o->campaign_id);
						$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'liveTracked', 
																		'campaign_id' => intval($o->campaign_id));
						$link = 'index.php?'.$this->Request->encrypt_params($arr);
						$this->View->assign('linklivetrack',$link);
						return $this->View->toString(APPLICATION.'/banner-create-topic.html');
						
					}
					
					//link add campaign
					$_SESSION['campaign_id'] = intval($o->campaign_id);
					if($account_type==0||$account_type==99||$account_type==5){
						$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'liveTracked', 'campaign_id' => intval($o->campaign_id));
					}else{
						$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account', 'act' => 'credit_usage');
					}
					$link = 'index.php?'.$this->Request->encrypt_params($arr);
					$this->assign('sidebar', $this->sidebarHelper->show() );
					
					return $this->View->toString(APPLICATION."/widgets/finish_topic.html");
				
				}else{
					
					$err = text('create_topic_failed');
				
				}
				
			}else{
			
				$err = text('create_topic_incomplete_form');
			
			}
		
		}
		
		$this->View->assign('err',$err);
		
		//link add campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign','act' => 'add');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urladdcampaign',$link);
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		global $ERROR;
		$this->assign('errCampaignName', $ERROR['CAMPAIGN_NAME']);
		$this->assign('errCampaignDesc', $ERROR['CAMPAIGN_DESCRIPTION']);
		$this->assign('errCampaignDate', $ERROR['CAMPAIGN_DATE']);
		$this->assign('errCampaignSource', $ERROR['CAMPAIGN_SOURCE']);
		$this->assign('errCampaignKeyword', $ERROR['CAMPAIGN_KEYWORD']);
		
		$this->assign("group_type",$group_type);
		$this->assign("total_topic",intval($total_topic));
		
		return $this->View->toString(APPLICATION.'/new-campaign.html');
	
	}
	function addTopicAddons($campaign_id,$addons){
		
		if(is_array($addons)){
			$this->open(0);
			foreach($addons as $addon){
				$sql = "INSERT INTO `smac_web`.`tbl_topic_addons` 
						(
						`campaign_id`, 
						`addon_id`, 
						`addon_added`
						)
						VALUES
						(
						'{$campaign_id}', 
						'$addon', 
						NOW()
						);";
				$q = $this->query($sql);
			}
			$this->close();
		}
	}
	function set_additional_sites($campaign_id,$additional_sites,$country,$lang=null){
		$hostlist = array("id"=>"google.co.id","my"=>"google.com.my","ph"=>"google.com.ph","sg"=>"google.com.sg");
		$googlehost = "google.com";
		//country_id
		$web_lang = null;
		
		if(strlen($lang)>0&&$lang!='all'){
			$web_lang = "lang_".$lang;
		}
		$this->open(0);
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
		foreach($additional_sites as $sites){
			$sites = mysql_escape_string(trim($sites));
			if(strlen($sites)>0){
				if(!eregi("http://",$sites)){
					$sites = "http://".$sites;
				}
				
				
				if(sizeof($country)>0){
					foreach($country as $c){
						$googlehost = "google.com";
						if(isset($hostlist[$c])){
							$googlehost = $hostlist[$c];
						}
						$sql = "INSERT INTO `smac_web`.`tbl_campaign_site_whitelist` 
							(
							`campaign_id`, `site_url`, `site_type`, `country_id`,googlehost_restrict
							)
							VALUES
							(
							{$campaign_id}, '{$sites}', '1', '{$c}','{$googlehost}'
							);";
						$q = $this->query($sql);
					}
				}else{
					$sql = "INSERT INTO `smac_web`.`tbl_campaign_site_whitelist` 
						(
						`campaign_id`, `site_url`, `site_type`, `country_id`
						)
						VALUES
						(
						{$campaign_id}, '{$sites}', '1', 'all'
						);";
					$q = $this->query($sql);
				}
				
			}
		}
		$this->close();
	}
	function get_group_types(){
		$group_type = $this->group->get_types();	
		print json_encode($group_type);
		die();
	}
	function get_groups(){
		$group_type = $this->group->get_groups($this->user->account_id);	
		print json_encode($group_type);
		die();
	}
	function create_group(){
		$status=0;
		$group_id = 0;
		if($this->Request->getParam("ajax")=="1"){
			$group_name=$this->Request->getParam("group_name");
			$group_type=$this->Request->getParam("group_type");
			$group_id = intval($this->group->create_group($this->user->account_id,$group_name,$group_type));
			if($group_id>0){
				$status=1;
			}
		}
		print json_encode(array("status"=>$status,"group_id"=>$group_id));
		die();
	}
	function edit(){
		
		$_add = intval($_POST['add']);
		$this->open(0);
		$sql = "SELECT id,group_name 
				FROM smac_web.tbl_topic_group 
				WHERE client_id={$this->user->account_id} AND group_name <> '' 
				ORDER BY group_name ASC;";
		$topicgroup = $this->fetch($sql,1);
		
		$sql = "SELECT * FROM smac_web.tbl_campaign 
				WHERE id = {$_SESSION['campaign_id']} 
				AND client_id={$this->user->account_id} 
				LIMIT 1";
		$data = $this->fetch($sql);
		
		$this->View->assign("topicgroup",$topicgroup);
		if($_add == 1){
			$name = mysql_escape_string(cleanXSS($this->Request->getPost('name')));
			$desc = mysql_escape_string(cleanXSS($this->Request->getPost('desc')));
			$group_id = intval($this->Request->getPost('topicgroup'));
			$sql = "UPDATE smac_web.tbl_campaign SET campaign_name='{$name}',campaign_desc='{$desc}',group_id={$group_id}
					WHERE id = {$_SESSION['campaign_id']} AND client_id = {$this->user->account_id}";
			
			$q = $this->query($sql);
			if($q){
				$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign','act'=>'edit');
				$link = 'index.php?'.$this->Request->encrypt_params($arr);
				$this->assign('sidebar', $this->sidebarHelper->show() );
				$this->assign('sidebar', $this->sidebarHelper->show() );
				return $this->View->showMessage(text('edit_campaign_success'),$link);
			
			}else{
				$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign','act'=>'edit');
				$link = 'index.php?'.$this->Request->encrypt_params($arr);
				$this->assign('sidebar', $this->sidebarHelper->show() );
				return $this->View->showMessage(text('edit_campaign_error'),$link);
			}
		}
		$this->close();
		
		
		$this->View->assign('err',$err);
		
		//link add campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign','act' => 'edit');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urleditcampaign',$link);
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		
		$this->assign('campaign',$data);
		
		return $this->View->toString(APPLICATION.'/edit-campaign.html');
	
	}
	
	function getcampaignurl(){
		
		if($_GET['ajax'] == 1){
		
			$_SESSION['campaign_id'] = $_GET['id'];
			
			$data = array();
			
			/*
			$page = ($this->Request->getParam('page') != '') ? $this->Request->getParam('page') : 'home' ;
			$subdomain = $this->Request->getParam('subdomain');
			$act = ($this->Request->getParam('act') != '') ? $this->Request->getParam('act') : 'home' ;
			
			$arr = array("subdomain"=> $subdomain,'page' => $page, 'act'=> $act, "campaign_id" => $_GET['id']);
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			*/
			
			$data['success'] = 1;
			//$data['url'] = $link; 
			
			echo json_encode($data);
			
			exit;
		}
		
	}
	
	function getrelatedkeywords(){
		$ajax=intval($this->Request->getParam('ajax'));
		if($ajax == 1){
			$start = intval($_REQUEST['next']);
			$limit = 20;
			$kw = mysql_escape_string($_GET['kw']);
			if(eregi("([A-Za-z0-9\,\ ]+)",$kw)){
				$dat = array();
				$i = 0;
				//lets try to search for 60 results
				$start=0;
				for($i=0;$i<2;$i++){
					$start*=$i;
					$response = $this->retrieve_from_topsy($kw,$start,$limit);
				}
				
				$rs = $this->retrieve_related_words($kw,100);
				
				if(sizeof($rs)==0){
					$data['status'] = 99; //no data
				}else{
					$data['status'] = 1;
					
					$tabel = '';
					
					$wc = array();
					$m=0;
					$mm=0;
					foreach($rs as $w){
						$m = max($m,$w['total']);
						$mm = min($m,$w['total']);
						$wc[] = array("txt"=>$w['keyword'],"amount"=>$w['total'],"weight"=>$w['total'],"url"=>"link","is_main"=>"","sentiment"=>"","title"=> "Click to select keyword");
						
						$tabel .= "<option onclick=\"addKeyword('".$kw."','".$w['keyword']."','nouse')\">".$w['keyword']."</option>";
					}
					$str = '<div class="suggestedkwct">';
					shuffle($wc);
					foreach($wc as $n=>$v){
						$weight = ceil(($v['amount']/($m-$mm))*9);
						$wc[$n]['weight'] = $weight;
						$wc[$n]['max'] = $m;
						$wc[$n]['min'] = $mm;
						$fontSize = ceil(($v['amount']/($m-$mm))*40)+8;
						$str.='<span><a class="suggestedkw" href="javascript:void(0);" style="font-size:'.$fontSize.'px;" 
							onclick="addKeyword(\''.$kw.'\',\''.$v['txt'].'\',\'nouse\');return false;">'.$v['txt'].'</a> </span>';
					}
					$str.="</div>";
					$strHTML = $str;
				}
			}else{
				$data['status'] = 0; //no data
			}
			//echo json_encode($data);
			echo $strHTML;
			exit;
		}
		
	}
	function estimate_from_topsy(){
		$ajax=intval($this->Request->getParam('ajax'));
		if($ajax == 1){
			global $APP_PATH;
			include_once $APP_PATH."smac/helper/TopsySearch.php";
			$keyword = mysql_escape_string($_GET['kw']);
			$topsy = new TopsySearch();
			$rs = $topsy->searchCount($keyword);
			print $rs;
			die();
		}
	}
	/**
	 *@param $keyword a string of topsy query
	 *@todo
	 * we have to extract every keywords from querystring, 
	 * and then assigned it into keyword1-keyword2 relations
	 * 
	 */
	function retrieve_from_topsy($keyword,$start=0,$total=100){
		global $APP_PATH;
		include_once $APP_PATH."smac/helper/TopsySearch.php";
		$topsy = new TopsySearch();
		if(intval($_REQUEST['since'])>0){
			$ts = intval($_REQUEST['since']);
		}else{
			$ts = -1;
		}
		if($_REQUEST['lang']!=null){
			$lang = $_REQUEST['lang'];
		}else{
			$lang = "";
		}
		/*
		$chunks = explode("OR",$keyword);
		$main_clause = trim(str_replace(","," ",$chunks[0]));
		$clauses = explode(" ",$main_clause);
		*/
		$fs = str_replace(","," ",$keyword);
		$fs = str_replace("OR ","",$fs);
		$clauses = explode(" ",$fs);
		
		$rs = $topsy->search($keyword,$start,$total,$lang,$ts);
		$o = json_decode($rs);
		
		//insert into database
		$sql = "INSERT IGNORE INTO 
				smac_data.related_word(keyword1,keyword2,occurance,update_time,topsy_ts,lang)
				VALUES ";
		$n=0;
		$this->open(0);
		if($o->status==1){
			if($lang==""){$lang="id";}
			foreach($o->data as $kw=>$occurance){
				//update existing
				//$sql2 = "UPDATE smac_data.related_word SET occurance=occurance+".$occurance." 
				//	WHERE keyword1='".$keyword."' AND keyword2='".$kw."'";
				//$this->query($sql2);
				foreach($clauses as $cc){
					$cc = trim($cc);
					if(strlen($cc)>2){
						if($n==1){
							$sql.=",";
						}
						$sql.="('".strtolower($cc)."','".mysql_escape_string(strtolower($kw))."',".$occurance.",NOW(),".$o->trackback_date.",'".$lang."')";
						$n=1;
					}
				}
				
			}
		}
		
		if($n>0){
			$this->query($sql);
			//clean keyword
			if($lang==""){
				$lang = "id";
			}else if($lang=="all"){
				$lang = "en";
			}
			
			if($lang=="all"){
				$sql = "DELETE FROM smac_data.related_word WHERE keyword2 IN (SELECT keyword from smac_locale.stopword_en)";
				$this->query($sql);
				$sql = "DELETE FROM smac_data.related_word WHERE keyword2 IN (SELECT keyword from smac_locale.stopword_id)";
				$this->query($sql);
				$sql = "DELETE FROM smac_data.related_word WHERE keyword2 IN (SELECT keyword from smac_locale.stopword_es)";
				$this->query($sql);
				$sql = "DELETE FROM smac_data.related_word WHERE keyword2 IN (SELECT keyword from smac_data.tb_stop)";
				$this->query($sql);
			}else{
				$sql = "DELETE FROM smac_data.related_word WHERE keyword2 IN (SELECT keyword from smac_locale.stopword_{$lang})";
				$this->query($sql);
				$sql = "DELETE FROM smac_data.related_word WHERE keyword2 IN (SELECT keyword from smac_data.tb_stop)";
				$this->query($sql);
			}
			
		}
		$this->close();
		return 1;
	}
	function retrieve_related_words($keyword,$limit=200){
		global $CONFIG;
		if($_REQUEST['lang']!=null&&strlen($_REQUEST['lang'])==2){
			$lang = $_REQUEST['lang'];
		}else{
			$lang = "";
		}
		$fs = str_replace(","," ",$keyword);
		$fs = str_replace("OR ","",$fs);
		$clauses = explode(" ",$fs);
		$strKeyword = "";
		foreach($clauses as $n=>$v){
			if($n>0){
				$strKeyword.=",";
			}
			$v = mysql_escape_string(trim($v));
			$strKeyword.="'{$v}'";
		}
		$this->open(0);
		//var_dump($CONFIG);
		if($lang==""){
			$sql = "SELECT keyword2 as keyword,SUM(occurance) as total 
				FROM smac_data.related_word 
				WHERE keyword1 IN ({$strKeyword})
				GROUP BY keyword2
				ORDER BY total DESC LIMIT ".$limit;
		}else{
			$sql = "SELECT keyword2 as keyword,SUM(occurance) as total 
				FROM smac_data.related_word 
				WHERE keyword1 IN ({$strKeyword}) AND lang='".$lang."'
				GROUP BY keyword2
				ORDER BY total DESC LIMIT ".$limit;
		}
		
		$rs = $this->fetch($sql,1);
		
		$this->close();
		return $rs;
	}
	function changestatus(){
		global $APP_PATH;
		require_once $APP_PATH . APPLICATION . "/helper/BillingHelper.php";
		$billing = new BillingHelper();
		$status = intval( $this->Request->getParam('status') );
		$cid = intval( $this->Request->getParam('campaign_id') );
		
		//link my campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'overview');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		
		if($status==0){
			$check = $billing->check_topic_status($cid);
			if($check>0){
				switch($check){
					case 1:
						$msg = "Sorry, the topic has been expired. Please extend the topic to resume.";
						$link = "?".$this->Request->encrypt_params(array("page"=>"account","act"=>"credit_usage"));
					break;
					default:
						$msg = "Sorry, you don't have enough budget to run your topic. Please make the payment first.";
						$link = "?".$this->Request->encrypt_params(array("page"=>"account","act"=>"credit_usage"));
					break;
				}
				return $this->View->showMessage($msg, $link);
			}
		}
		
		$data = $this->api->changeCampaignStatus($this->user->account_id,$cid,$status);
	
		if($status==0){
			$status_str = "running";
		}else{
			$status_str = "paused";
		}
		if($data){
			$this->assign('sidebar', $this->sidebarHelper->show() );
			return $this->View->showMessage('The Campaign is now '.$status_str,$link);
		}else{
			$this->assign('sidebar', $this->sidebarHelper->show() );
			return $this->View->showMessage('Change campaign status failed',$link);
		}
		
	}
	function addInitialOverviewData($client_id,$campaign_id,$group_id,$data){
		$arr = array("campaign_id"=>$campaign_id,
					"campaign_name"=>$data['name'],
					"description"=>$data['desc'],
					"campaign_start"=>$data['start'],
					"campaign_end"=>$data['end'],
					"campaign_added"=>date("Y-m-d H:i:s"),
					"channels"=>$data['channels'],
					"tracking_method"=>$data['method'],
					"mentions"=>0,
					"people"=>0,
					"sentiment_positive"=>0,
					"sentiment_negative"=>0,
					"potential_impression"=>0,
					"potential_impact_score"=>0,
					"n_status"=>1,
					"total_usage"=>0,
					"total_limit"=>0,
					"group_id"=>$data['group_id'],
					"sentiment"=>array("positive"=>0,"negative"=>0,"netral"=>0),
					"performance"=>array("imp_change"=>0,"mention_change"=>0,
					                       "pii_change"=>0,"imp_diff"=>0,"mention_diff"=>0,
					                       "pii_diff"=>0),
					"source"=>array("twitter"=>0,"facebook"=>0,"blog"=>0)
					);
		$content = mysql_escape_string(serialize($arr));
		if($group_id==null){
			$group_id=0;
		}
		$sql = "INSERT IGNORE INTO smac_report.campaign_topic_overview(client_id,group_id,campaign_id,content,last_update)
				VALUES({$client_id},{$group_id},{$campaign_id},'{$content}',NOW())";
		//print $sql;
		$this->open(0);
		$q = $this->query($sql);
		//print mysql_error();
		$this->close();
		return $q;
	}
	function edit_topic_group(){
		$account_id = $this->user->account_id;
		$this->open(0);
		if($this->Request->getPost("update_group")==1){
			$id = $this->Request->getPost("id");
			$name = trim(cleanXSS(mysql_escape_string($this->Request->getPost("name"))));
			$sql = "UPDATE smac_web.tbl_topic_group SET group_name='{$name}' WHERE client_id={$account_id} AND id={$id}";
			$q = $this->query($sql);
			if($q){
				$msg = "Update Completed !";
			}else{
				$msg = "Cannot save your change, please try again later !";
			}
		}
		$sql = "SELECT * FROM smac_web.tbl_topic_group WHERE client_id={$account_id} LIMIT 1000";
		$groups = $this->fetch($sql,1);
		
		foreach($groups as $n=>$group){
			if(strlen($group['group_name'])==0){
				$groups[$n]['group_name'] = "untitled";
			}
			//get the related topics
			$sql = "SELECT id,campaign_name as name FROM smac_web.tbl_campaign 
					WHERE client_id={$account_id} AND group_id={$groups[$n]['id']} LIMIT 1000;";
			$groups[$n]['topics'] = $this->fetch($sql,1);
		}
		$this->close();
		
		//link to update topic's group
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign','act' => 'update_topic_group');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		
		$this->assign("groups",$groups);
		$this->assign('sidebar', $this->sidebarHelper->show());
		$this->assign("msg",$msg);
		$this->assign("update_url",$link);
		return $this->View->toString("smac/edit_group.html");
	}
	function update_topic_group(){
		$group_id = intval($this->Request->getPost("group_id"));
		$topic_id = intval($this->Request->getPost("topic_id"));
		if($group_id>0){
			$sql = "UPDATE smac_web.tbl_campaign SET group_id={$group_id}
					WHERE id = {$topic_id}
					AND client_id = {$this->user->account_id}";
			$this->open(0);
			$q = $this->query($sql);
			$this->close();
			if($q){
				print "1";die();
			}else{
				print "0";die();
			}
		}
		print "0";
		die();
	}
	
}
?>