<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/ReplayHelper.php";
require_once $APP_PATH . APPLICATION . "/modules/twitter.php";
require_once $APP_PATH . APPLICATION . "/helper/BotHelper.php";
class workflow extends App{
	var $replay;
	var $bot;
	function setVar(){
		$this->replay = new ReplayHelper($this->Request);
		$this->twitter = new twitter($this->Request);
	}
	function home(){
		global $APP_PATH;
		//datefilter
		$this->dateFilterWidget->setPage('home');
		$this->assign("widget_datefilter",$this->dateFilterWidget->show());
		$filter_date_from = $this->dateFilterWidget->from_date();
		$filter_to_date = $this->dateFilterWidget->to_date();
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		
		global $CONFIG;
		
		$host = explode('.',$_SERVER['HTTP_HOST']);
		if(($host[0] == 'smacapp' || $host[1] == 'smacapp') && ($host[1] == 'loc' || $host[2] == 'loc')){
			$google_api = $CONFIG['GOOGLE_MAP_KEY_LOC'];
		}elseif(($host[0] == 'smacapp' || $host[1] == 'smacapp') && ($host[1] == 'com' || $host[2] == 'com')){
			$google_api = $CONFIG['GOOGLE_MAP_KEY_APP'];
		}else{
			$google_api = $CONFIG['GOOGLE_MAP_KEY_ME'];
		}  
		$this->assign('google_map_key',$google_api);
		$this->assign("keywords",$this->get_keywords());
		$this->assign("filter_by",$this->Request->getParam("filter_by"));
		$this->assign("folder",$this->get_custom_folders($_SESSION['campaign_id'],$this->Request->getParam("filter_by")));
		$this->assign("all_folder",$this->get_all_folders($_SESSION['campaign_id'],$this->Request->getParam("filter_by")));
		if($this->twitter->is_authorized()){
			$is_authorized = 1;
		}else{
			$is_authorized = 0;
		}
		 
		$this->assign("is_authorized",$is_authorized);
		
		
		$_SESSION['curr_req'] = "?".$this->Request->encrypt_params(array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'workflow'));
		
		return $this->View->toString("smac/workflow.html");
	}
	function get_custom_folders($campaign_id,$filter_by){
		$sql = "SELECT id as folder_id,folder_name FROM smac_report.workflow_folder 
				WHERE campaign_id = ".$campaign_id." 
				ORDER BY id ASC LIMIT 20";
		$this->open(0);
		$data = $this->fetch($sql,1);
		foreach($data as $n=>$v){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getTabContent','ajax'=>1,'type'=>$data[$n]['folder_id'],'filter_by'=>$filter_by);
			$url = str_replace("req=","",$this->Request->encrypt_params($c));
			$data[$n]['url'] = $url;
		}
		$this->close();
		return $data;
	}
	function get_all_folders($campaign_id,$filter_by){
		$sql = "SELECT id as folder_id,folder_name FROM smac_report.workflow_folder 
				WHERE campaign_id=0 OR campaign_id = ".$campaign_id." 
				ORDER BY id ASC LIMIT 20";
		$this->open(0);
		$data = $this->fetch($sql,1);
		foreach($data as $n=>$v){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getTabContent','ajax'=>1,'type'=>$data[$n]['folder_id'],'filter_by'=>$filter_by);
			$url = str_replace("req=","",$this->Request->encrypt_params($c));
			$data[$n]['url'] = $url;
		}
		$this->close();
		return $data;
	}
	function get_keywords(){
		$sql = "SELECT keyword FROM smac_report.workflow_marked_tweets 
		WHERE campaign_id=".$_SESSION['campaign_id']."
		GROUP BY keyword
		ORDER BY keyword ASC
		LIMIT 10000";
		$this->open(0);
		$rs = @$this->fetch($sql,1);
		$this->close();
		if(sizeof($rs)>0){
			foreach($rs as $n=>$v){
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','filter_by'=>$v['keyword']);
				$rs[$n]['url'] = "?".$this->Request->encrypt_params($c);
			}
		}
		return $rs;
	}
	
	function flag(){
		if($this->Request->getParam('ajax')=="1"){
			$json = $this->api->workflow_flag_tweet($_SESSION['campaign_id'],$this->Request->getParam('keyword'),$this->Request->getParam('feed_id'),$this->Request->getParam('opt'),$this->Request->getParam('type'));
			print $json;
			die();
		}else{
			print json_encode(array("status"=>"0","keyword"=>$this->Request->getParam('keyword')));
			die();
		}
	}
	/**
	 * move all tweets under the keyword to workflow folder.
	 * the job will be handled asynchronously, so all we have to do is to flag the keyword for moving process.
	 * @return void
	 */
	function flag_all(){
		if($this->Request->getParam('ajax')=="1"){
			$keyword = mysql_escape_string($this->Request->getParam('keyword'));
			$campaign_id = mysql_escape_string($_SESSION['campaign_id']);
			$posted_date_ts = time();
			$folder_id = mysql_escape_string($this->Request->getParam('type'));
			
			$sql = "INSERT IGNORE INTO smac_web.workflow_keyword_flag
					(campaign_id,keyword,folder_id,submit_date,n_status)
					VALUES
					({$campaign_id},'{$keyword}',{$folder_id},NOW(),0)
					";
						
			$this->open(0);
			$q = $this->query($sql);
			$this->close();
			if($q){
				print json_encode(array("status"=>"1","keyword"=>$this->Request->getParam('keyword')));
			}else{
				print json_encode(array("status"=>"0","keyword"=>$this->Request->getParam('keyword')));
			}
			die();
		}else{
			print json_encode(array("status"=>"0","keyword"=>$this->Request->getParam('keyword')));
			die();
		}
	}
	function twitter_wrapper(){
		if($this->Request->getParam('ajax')=="1"){
			$uri = "https://api.twitter.com/1/users/show.json?screen_name=".$this->Request->getParam('screen_name');
			print file_get_contents($uri);
			die();
		}else{
			print json_encode(array("status"=>"0","keyword"=>$this->Request->getParam('keyword')));
			die();
		}
	}
	function gmap_wrapper(){
		if($this->Request->getParam('ajax')=="1"){
			$x = floatval($this->Request->getParam("x"));
			$y = floatval($this->Request->getParam("y"));
			$uri = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$x.",".$y."&sensor=false";
			
			print file_get_contents($uri);
			die();
		}else{
			print json_encode(array("status"=>"0","keyword"=>$this->Request->getParam('keyword')));
			die();
		}
	}
	function exclude_person(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = mysql_escape_string($this->Request->getParam("campaign_id"));
			$author_id = mysql_escape_string($this->Request->getParam("author_id"));
			
			$this->open(0);
			//jobnya. and just simply reset the exclusion when duplicate.
			$sql = "INSERT INTO smac_workflow.workflow_people_exclude(campaign_id,author_id,submit_date,n_status)
					VALUES(".$campaign_id.",'".$author_id."',NOW(),0)
					ON DUPLICATE KEY
					UPDATE
					n_status = VALUES(n_status);
					";
			$q = $this->query($sql);
			
			//flagnya
			$sql = "INSERT IGNORE INTO smac_workflow.workflow_marked_people(campaign_id,author_id,flag,apply_date,apply_date_ts)
					VALUES(".$campaign_id.",'".$author_id."',1,NOW(),".time().")";
			$q = $this->query($sql);
			$this->close();
			if($q){
				$status = 1;
			
				
			}else{
				$status = 0;
			}
			print json_encode(array("status"=>$status));
		}else{
			print json_encode(array("status"=>0));
		}
		die();
	}
	function get_profile_wordcloud(){
		if($this->Request->getParam('ajax')=="1"){
			$author_id = $this->Request->getParam('person');
			$type = intval($this->Request->getParam('type'));
			//wordcloud
			$word = $this->api->getPersonGlobalWordcloud($author_id,$_SESSION['campaign_id'],$type);
			
			$dat = array();
			foreach($word as $k => $v){
				$dat[] = array('keyword'=>$v->keyword,'total'=>$v->total, 'sentiment' => $v->sentiment, 'is_main' => $v->is_main);
			}
			//Worldcloud Baru ===============
			$wc = array();
			$m=0;
			$mm=0;
			foreach($dat as $w){
				$m = max($m,$w['total']);
				$mm = min($mm,$w['total']);
				$wc[] = array("txt"=>$w['keyword'],"amount"=>$w['total'],"weight"=>$w['total'],"url"=>"link","is_main"=> $w['is_main'],"sentiment"=> $w['sentiment'],"title"=> "");
			}
			foreach($wc as $n=>$v){
				$weight = ceil(($v['amount']/($m-$mm))*9);
				$wc[$n]['weight'] = $weight;
				$wc[$n]['max'] = $m;
				$wc[$n]['min'] = $mm;
			}
			
			//$_GET['key'] = "'$kw'";
			//$_GET['id'] = "'nouse'";
			$wordcloud = new wordcloudHelper(300,300);
			$wordcloud->urlto="javascript:void(0);";
			//$wordcloud->callback_func="addKeyword";
			$wordcloud->setHandler('homewordcloud');
			$wordcloud->set_sentiment_style(array("positive"=>"wcstat1","negative"=>"wcstat2","neutral"=>"wcstat0","main_keyword"=>"wcstat3"));
			$dwc = $wordcloud->draw($wc);
			print $dwc;
		}else{
			print "";
		}
		die();
	}
	function getTabContent(){
		$total = 10;
		if($this->Request->getParam('ajax')=="1"){
			$json = $this->api->get_workflow_content($_SESSION['campaign_id'],$this->Request->getParam("type"),$this->Request->getParam("filter_by"),$this->Request->getParam("start"),$total);
			$o = json_decode($json);
			$prev_url = "";
			$next_url = "";
			if($this->Request->getParam('start')>0){
				$a = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getTabContent','type'=>$this->Request->getParam('type'),'ajax'=>1,'start'=>$this->Request->getParam('start')-$total);
				$prev_url = $this->Request->encrypt_params($a);
			}
			if($this->Request->getParam('start')+$total<=$o->total){
				$a = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getTabContent','type'=>$this->Request->getParam('type'),'ajax'=>1,'start'=>$this->Request->getParam('start')+$total);
				$next_url = $this->Request->encrypt_params($a);
			}
			//url untuk popup profile analyze
			//if($this->Request->getParam("type")){
			foreach($o->data as $n=>$v){
				$o->data[$n]->rt_imp = intval($o->data[$n]->rt_imp);
				$a = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getPersonTweets','type'=>"myself",'ajax'=>1,'person'=>$v->author_id);
				$my_url = $this->Request->encrypt_params($a);
				$a = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getPersonTweets','type'=>"global",'ajax'=>1,'person'=>$v->author_id);
				$global_url = $this->Request->encrypt_params($a);
				$o->data[$n]->my_url = $my_url;
				$o->data[$n]->global_url = $global_url;
				
				$folders = $this->get_all_folders($_SESSION['campaign_id'],$this->Request->getParam("filter_by"));
				//$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$v->keyword,'feed_id'=>$v->feed_id,'opt'=>1,'ajax'=>1,'type'=>1);
				$o->data[$n]->flags = array();
				foreach($folders as $folder){
					$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$v->keyword,'feed_id'=>$v->feed_id,'opt'=>1,'ajax'=>1,'type'=>$folder['folder_id']);
					$o->data[$n]->flags[] = array("folder_name"=>$folder['folder_name'],"url"=>str_replace("req=","",$this->Request->encrypt_params($c)));
				}
				//-->
				//url utk wordcloud
				//global
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'get_profile_wordcloud','person'=>$v->author_id,'ajax'=>1,'type'=>1);
				$o->data[$n]->gwc_url = str_replace("req=","",$this->Request->encrypt_params($c));
				//campaign only
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'get_profile_wordcloud','person'=>$v->author_id,'ajax'=>1,'type'=>0);
				$o->data[$n]->cwc_url = str_replace("req=","",$this->Request->encrypt_params($c));
				
				//untuk url profiling global
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'profiling_estimation','person'=>$v->author_id,'ajax'=>1);
				$o->data[$n]->p_url = str_replace("req=","",$this->Request->encrypt_params($c));
				
				//untuk url add search profiling replay baru
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'add_profiling_job','person'=>$v->author_id,'ajax'=>1);
				$o->data[$n]->search_url = str_replace("req=","",$this->Request->encrypt_params($c));
				
				//url untuk influencer of
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'influencer_of','person'=>$v->author_id,'ajax'=>1);
				$o->data[$n]->inf_url = str_replace("req=","",$this->Request->encrypt_params($c));
				
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'influenced_by','person'=>$v->author_id,'ajax'=>1);
				$o->data[$n]->infby_url = str_replace("req=","",$this->Request->encrypt_params($c));
				
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag_replied','feed_id'=>$v->feed_id,'ajax'=>1);
				$o->data[$n]->flag_url = str_replace("req=","",$this->Request->encrypt_params($c));
				
				//exclude person url
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'exclude_person','campaign_id'=>$_SESSION['campaign_id'],'author_id'=>$v->author_id,'ajax'=>1);
				$o->data[$n]->ex_person = str_replace("req=","",$this->Request->encrypt_params($c));
				
			}
			//}
			$arr = array("status"=>$o->status,
						 "data"=>$o->data,"jobs"=>$o->jobs,
						 "next_url"=>$next_url,
						 "prev_url"=>$prev_url,
						 "total"=>$o->total,
						  "per_page"=>$total);
			print json_encode($arr);
			die();
		}else{
			print json_encode(array("status"=>"0"));
			die();
		}
	}
	function influencer_of(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$person = $this->Request->getParam('person');
			$type = $this->Request->getParam('type');
			if(strlen($person)>0){
				$json = $this->api->influencer_of($campaign_id,$person,$type);
				print $json;
				
			}else{
				print json_encode(array("status"=>"0"));
			}
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function influenced_by(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$person = $this->Request->getParam('person');
			$type = $this->Request->getParam('type');
			if(strlen($person)>0){
				$json = $this->api->influenced_by($campaign_id,$person,$type);
				print $json;
			}
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function getPersonTweets(){
		if($this->Request->getParam('ajax')=="1"){
			$type = $this->Request->getParam('type');
			$campaign_id = $_SESSION['campaign_id'];
			$start = intval($this->Request->getParam('start'));
			$person = $this->Request->getParam('person');
			if($type="myself"){
				$json = $this->api->getProfileTweets($campaign_id,$start,4,$person);	
			}else{
				$json = $this->api->getProfileTweetsAll($campaign_id,$start,4,$person);
			}
			print $json;
			//print json_encode(array("status"=>"1"));
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function profiling_estimation(){
		if($this->Request->getParam('ajax')=="1"){
			$person = $this->Request->getParam('person');
			if(strlen($person)>0){
				global $APP_PATH;
				include_once $APP_PATH."smac/helper/TopsySearch.php";
				$keyword = "from:".$person." OR @".$person;
				$topsy = new TopsySearch();
				$rs = $topsy->searchCount($keyword);
				$o = json_decode($rs);
				
				print json_encode($o->response);
				
			}
		}else{
			
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function get_search_queue(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$rs = $this->replay->get_queue($campaign_id);
			print json_encode(array("status"=>1,"data"=>$rs));
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function add_profiling_job(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$person = $this->Request->getParam('person');
			$interval = intval($this->Request->getParam('interval'));
			$start_from = date("YmdHi",mktime(0,0,0,date("m"),date("d")-($interval+1),date("Y")));
			$until = date("YmdHi",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
			if(strlen($person)>0){
				print $this->replay->addSubject($campaign_id,$person,$start_from,$until);
				
			}
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function getTweets(){
		if($this->Request->getParam('ajax')=="1"){
			$json = $this->api->get_workflow_tweets($_SESSION['campaign_id'],$this->Request->getParam('keyword'),$this->Request->getParam('start'),16);
			//print json_encode(array("status"=>"1","keyword"=>$this->Request->getParam('keyword'),'campaign_id'=>$_SESSION['campaign_id']));
			$o = json_decode($json);
			if($o->status==1){
				$a = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getTweets','keyword'=>$this->Request->getParam('keyword'),'ajax'=>1,'start'=>$this->Request->getParam('start')+100);
				$nextUrl = str_replace("req=","",$this->Request->encrypt_params($a));
				if(($this->Request->getParam('start')-100)>0){
					$b = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getTweets','keyword'=>$this->Request->getParam('keyword'),'ajax'=>1,'start'=>$this->Request->getParam('start')-100);
				}else{
					$b = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'getTweets','keyword'=>$this->Request->getParam('keyword'),'ajax'=>1);
				}
				$prevUrl = str_replace("req=","",$this->Request->encrypt_params($b));
				foreach($o->data as $n=>$v){
					$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$this->Request->getParam('keyword'),'feed_id'=>$v->feed_id,'opt'=>1,'ajax'=>1,'type'=>1);
					$o->data[$n]->mark_url = str_replace("req=","",$this->Request->encrypt_params($c));
					$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$this->Request->getParam('keyword'),'feed_id'=>$v->feed_id,'opt'=>1,'ajax'=>1,'type'=>2);
					$o->data[$n]->reply_url = str_replace("req=","",$this->Request->encrypt_params($c));
					$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$this->Request->getParam('keyword'),'feed_id'=>$v->feed_id,'opt'=>1,'ajax'=>1,'type'=>3);
					$o->data[$n]->analize_url = str_replace("req=","",$this->Request->encrypt_params($c));
					$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$this->Request->getParam('keyword'),'feed_id'=>$v->feed_id,'opt'=>1,'ajax'=>1,'type'=>4);
					$o->data[$n]->exclude_url = str_replace("req=","",$this->Request->encrypt_params($c));
					
					$folders = $this->get_all_folders($_SESSION['campaign_id'],$this->Request->getParam("filter_by"));
					
					$o->data[$n]->flags = array();
					foreach($folders as $folder){
						$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$this->Request->getParam('keyword'),'feed_id'=>$v->feed_id,'opt'=>1,'ajax'=>1,'type'=>$folder['folder_id']);
						$o->data[$n]->flags[] = array("folder_name"=>$folder['folder_name'],"url"=>str_replace("req=","",$this->Request->encrypt_params($c)));
						
						$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag_all','keyword'=>$this->Request->getParam('keyword'),'opt'=>1,'ajax'=>1,'type'=>$folder['folder_id']);
						$o->data[$n]->flags2[] = array("folder_name"=>$folder['folder_name'],"url"=>str_replace("req=","",$this->Request->encrypt_params($c)));
					}
				}
				
				$arr = array("status"=>$o->status,"data"=>$o->data,"total"=>$o->total,"total_unmark"=>$o->total_unmarked,"next_url"=>$nextUrl,"prev_url"=>$prevUrl,"keyword"=>$this->Request->getParam('keyword'));
				$o = null;
				print json_encode($arr);
				$arr = null;
			}else{
				print json_encode(array("status"=>"0","keyword"=>$this->Request->getParam('keyword')));
			}
			die();
		}else{
			print json_encode(array("status"=>"0","keyword"=>$this->Request->getParam('keyword')));
			die();
		}
	}
	function flag_replied(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$feed_id = $this->Request->getParam("feed_id");
			$sql = "INSERT IGNORE INTO smac_report.workflow_replied(campaign_id,feed_id,reply_date)
					VALUES(".$campaign_id.",'".$feed_id."',NOW())";
			$this->open(0);
			$q = $this->query($sql);
			$this->close();
			if($q){
				$status=1;
				$data = array("reply_time"=>date("d/m/Y H:i:s"));
			}else{
				$status=0;
			}
			print json_encode(array("status"=>$status,"data"=>$data));
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function get_folders(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$sql = "SELECT id as folder_id,folder_name FROM smac_report.workflow_folder 
					WHERE campaign_id=0
					OR campaign_id = ".$campaign_id." 
					ORDER BY id ASC LIMIT 20";
			$this->open(0);
			$data = $this->fetch($sql,1);
			foreach($data as $n=>$v){
				$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$this->Request->getParam('keyword'),'feed_id'=>$this->Request->getParam('feed_id'),'opt'=>1,'ajax'=>1,'type'=>$data[$n]['folder_id']);
				$url = str_replace("req=","",$this->Request->encrypt_params($c));
				$data[$n]['folder'] = $url;
			}
			$this->close();
			
			if(is_array($data)){
				$status=1;
			}else{
				$status=0;
			}
			print json_encode(array("status"=>$status,"data"=>$data));
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function add_folder(){
		$status=0;
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$folder_name = cleanXSS($this->Request->getParam("name"));
			if(strlen($folder_name)>0){
				$sql = "INSERT INTO smac_report.workflow_folder(campaign_id,folder_name)
						VALUES(".$campaign_id.",'".$folder_name."')";
				$this->open(0);
				$q = $this->query($sql);
				$folder_id = intval(mysql_insert_id());
				$this->close();
				if($q){
					$status=1;
					$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'flag','keyword'=>$this->Request->getParam('keyword'),'feed_id'=>$this->Request->getParam('feed_id'),'opt'=>1,'ajax'=>1,'type'=>$folder_id);
					$url = str_replace("req=","",$this->Request->encrypt_params($c));
					$data = array("url"=>$url);
				}
			}
			print json_encode(array("status"=>$status,"data"=>$data));
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function remove_folder(){
		$status=0;
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			
			$folder_id = intval($this->Request->getParam('id'));
			$this->open(0);
			$sql = "SELECT COUNT(*) as total FROM smac_report.workflow_marked_tweets WHERE folder_id={$folder_id} LIMIT 1";
			
			$row = $this->fetch($sql);
			if(intval($row['total'])==0){
				
				if(intval($folder_id)>4){
					$sql = "DELETE FROM smac_report.workflow_folder WHERE id={$folder_id} AND campaign_id={$campaign_id}";
					$q = $this->query($sql);
					if($q){
						$status=1;
					}
				}
			}else{
				
				$status=2; //the folder is not empty
			}
			$this->close();
			print json_encode(array("status"=>$status));
		}else{
			print json_encode(array("status"=>"0"));
		}
		die();
	}
	function apply_exclude(){
		
		if($this->Request->getParam('ajax')=="1"){
			$this->bot = new BotHelper(null);
			$campaign_id = $_SESSION['campaign_id'];
			$feed_id = mysql_escape_string($this->Request->getParam("feed_id"));
			if(strlen($feed_id)>0){
				$this->open(0);
				$sql = "INSERT IGNORE INTO smac_report.campaign_feeds_exc
				(id, ref_id, campaign_id, raw_id, feed_id, published, published_date, published_datetime, hour_time, updated, title, summary, link, source_context, source_service, source_service_url, generator, generator_url, content, author_name, 
				author_link, 
				author_avatar, 
				author_id, 
				matching_rule, 
				location, 
				coordinate_x, 
				coordinate_y, 
				twitter_geo, 
				google_location, 
				n_status, 
				retrieve_date, 
				tag_id, 
				following, 
				followers, 
				lists, 
				total_mentions, 
				klout_score, 
				wordlist
				)
				SELECT id, ref_id, campaign_id, raw_id, feed_id, published, published_date, published_datetime, hour_time, updated, title, summary, link, source_context, source_service, source_service_url, generator, generator_url, content, author_name, 
					author_link, 
					author_avatar, 
					author_id, 
					matching_rule, 
					location, 
					coordinate_x, 
					coordinate_y, 
					twitter_geo, 
					google_location, 
					n_status, 
					retrieve_date, 
					tag_id, 
					following, 
					followers, 
					lists, 
					total_mentions, 
					klout_score, 
					wordlist 
				FROM smac_report.campaign_feeds WHERE campaign_id={$campaign_id} AND feed_id='{$feed_id}';";
				$q = $this->query($sql);
				if($q){
					$sql = "DELETE FROM smac_report.campaign_feeds WHERE campaign_id={$campaign_id} AND feed_id='{$feed_id}'";
					$q = $this->query($sql);
				
					if($q){
						$start_time = date("Y-m-d H:i:s",time()+(60*60));
						$status=1;
					}else{
						$status=0;
					}
				}else{
					$status=0;
				}
				$this->close();
				if($status==1){
					$this->bot->open(0);
					$this->bot->refresh_report($campaign_id,$start_time);
					$this->bot->close();
				}
			}else{
				$status = 0;
			}
		}else{
			$status = 0;
		}
		print json_encode(array("status"=>$status,));
		die();
	}
	function apply_undo(){
		
		if($this->Request->getParam('ajax')=="1"){
			$this->bot = new BotHelper(null);
			$campaign_id = $_SESSION['campaign_id'];
			$feed_id = mysql_escape_string($this->Request->getParam("feed_id"));
			if(strlen($feed_id)>0){
				$this->open(0);
				$sql = "INSERT IGNORE INTO smac_report.campaign_feeds
				(id, ref_id, campaign_id, raw_id, feed_id, published, published_date, published_datetime, hour_time, updated, title, summary, link, source_context, source_service, source_service_url, generator, generator_url, content, author_name, 
				author_link, 
				author_avatar, 
				author_id, 
				matching_rule, 
				location, 
				coordinate_x, 
				coordinate_y, 
				twitter_geo, 
				google_location, 
				n_status, 
				retrieve_date, 
				tag_id, 
				following, 
				followers, 
				lists, 
				total_mentions, 
				klout_score, 
				wordlist
				)
				SELECT id, ref_id, campaign_id, raw_id, feed_id, published, published_date, published_datetime, hour_time, updated, title, summary, link, source_context, source_service, source_service_url, generator, generator_url, content, author_name, 
					author_link, 
					author_avatar, 
					author_id, 
					matching_rule, 
					location, 
					coordinate_x, 
					coordinate_y, 
					twitter_geo, 
					google_location, 
					n_status, 
					retrieve_date, 
					tag_id, 
					following, 
					followers, 
					lists, 
					total_mentions, 
					klout_score, 
					wordlist 
				FROM smac_report.campaign_feeds_exc WHERE campaign_id={$campaign_id} AND feed_id='{$feed_id}';";
				$q = $this->query($sql);
				
				//make sure that the data is already in campaign_feeds
				$sql = "SELECT campaign_id,feed_id FROM smac_report.campaign_feeds WHERE campaign_id={$campaign_id} AND feed_id='{$feed_id}' LIMIT 1";
				$check = $this->fetch($sql);
				if($check['campaign_id']==$campaign_id&&$check['feed_id']==$feed_id){
				//if($q){
					$sql = "DELETE FROM smac_report.campaign_feeds_exc WHERE campaign_id={$campaign_id} AND feed_id='{$feed_id}'";
					$q = $this->query($sql);
					if($q){
						$status=1;
						$start_time = date("Y-m-d H:i:s",time()+(60*60));
						$this->bot->refresh_report($campaign_id,$start_time);
					}else{
						$status=0;
					}
				}else{
					$status=0;
				}
				$this->close();
				if($status==1){
					$this->bot->open(0);
					$this->bot->refresh_report($campaign_id,$start_time);
					$this->bot->close();
				}
			}else{
				$status = 0;
			}
		}else{
			$status = 0;
		}
		print json_encode(array("status"=>$status,));
		die();
	}
	
	function apply_exclude_all(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$keyword = mysql_escape_string($this->Request->getParam("kw"));
			$sql = "INSERT IGNORE INTO smac_web.workflow_apply_exclude
					(campaign_id,keyword,submit_date,n_status)
					VALUES
					({$campaign_id},'{$keyword}',NOW(),0)
					";
			$this->open(0);
			$q = $this->query($sql);
			$this->close();
			if($q){
				$status = 1;
			}else{
				$status = 0;
			}
		}else{
			$status = 0;
		}
		print json_encode(array("status"=>$status));
		die();
	}
	/**
	 * get the last 24 hours running proccesses
	 * @return unknown_type
	 */
	function job_list(){
		if($this->Request->getParam('ajax')=="1"){
			$campaign_id = $_SESSION['campaign_id'];
			$this->open(0);
			//keyword to folder job
			$sql = "SELECT *,DATE_FORMAT(submit_date,'%d/%m %H:%i') as tgl FROM smac_web.workflow_keyword_flag WHERE campaign_id={$campaign_id} AND n_status=0 OR n_status=1 OR 
					(n_status=2 AND finished_date > (NOW() - INTERVAL 24 HOUR)) ORDER BY submit_date ASC LIMIT 1000";
			$keyword_to_folder = $this->fetch($sql,1);
			
			//apply exclusion jobs
			$sql = "SELECT *,DATE_FORMAT(submit_date,'%d/%m %H:%i') as tgl FROM smac_web.workflow_apply_exclude 
					WHERE campaign_id={$campaign_id} AND n_status=0 OR n_status=1 OR 
					(n_status=2 AND finished_date > (NOW() - INTERVAL 24 HOUR)) ORDER BY submit_date ASC LIMIT 1000";
			$apply_all = $this->fetch($sql,1);
			
			//people exclusion jobs
			$sql = "SELECT *,DATE_FORMAT(submit_date,'%d/%m %H:%i') as tgl FROM smac_web.workflow_people_exclude 
					WHERE campaign_id={$campaign_id} AND n_status=0 OR n_status=1 OR 
					(n_status=2 AND finished_date > (NOW() - INTERVAL 24 HOUR)) ORDER BY submit_date ASC LIMIT 1000";
			$people = $this->fetch($sql,1);
			
			$jobs = array();
			$n=1;
			foreach($keyword_to_folder as $job){
				$jobs[] = array("no"=>$n,"job_type"=>1,"detail"=>$job,"descriptions"=>"Mark all tweets which mentioned '{$job['keyword']}'");
				$n++;
			}
			foreach($apply_all as $job){
				$jobs[] = array("no"=>$n,"job_type"=>2,"detail"=>$job,"descriptions"=>"Apply Exclusion to tweets which mentioned '{$job['keyword']}'");
				$n++;
			}
			foreach($people as $job){
				$jobs[] = array("no"=>$n,"job_type"=>2,"detail"=>$job,"descriptions"=>"Exclude '{$job['author_id']}' from report.");
				$n++;
			}
			$keyword_to_folder = null;
			$apply_all = null;
			
			//PDF job
			$sql = "SELECT *,DATE_FORMAT(request_date,'%d/%m %H:%i') as tgl FROM smac_web.job_report_pdf 
				WHERE campaign_id={$campaign_id} AND (n_status=0 OR n_status=1 OR 
				(n_status=2 AND end_time > (NOW() - INTERVAL 24 HOUR))) ORDER BY request_date ASC LIMIT 1000;";
			
			
			//-->
			$pdf = $this->fetch($sql,1);
			
			foreach($pdf as $job){
				$pdf_start = date("d/m/Y",strtotime($job['report_start']));
				$pdf_end = date("d/m/Y",strtotime($job['report_end']));
				$jobs[] = array("no"=>$n,"job_type"=>3,"detail"=>$job,"descriptions"=>"Generating Report {$pdf_start} - {$pdf_end}, {$job['progress']} of {$job['task_count']} Report(s) Created");
				$n++;
			}
			
			$this->assign("jobs",$jobs);
			
			$this->close();
			
			$content = $this->View->toString("smac/workflow_job_list.html");
			
			$response = array("status"=>1,"content"=>$content);
			print json_encode($response);
			die();
		}
		$response = array("status"=>0);
		die();
		
	}
}
?>