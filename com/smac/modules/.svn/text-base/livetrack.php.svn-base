<?php
global $APP_PATH;
/*
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/headerHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/favoriteWordWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/keyOpinionWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/tabNetworkWidget.php";
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/MarketHelper.php";
 * *'
 */
class livetrack extends App{
	/*var $Request;
	var $View;
	var $menuHelper;
	//var $headerHelper;
	var $sidebarHelper;
	var $api;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->setVar();
		
		$this->menuHelper = new menuHelper($this->user,$req);
		
		//$this->headerHelper = new headerHelper($this->user,$req);
		
		$this->favoriteWordWidget = new favoriteWordWidget($this->user,$req);
		
		$this->sidebarHelper = new sidebarHelper($this->user,$req);
		
		$this->api = new apiHelper();
		$this->market = new MarketHelper();
		
	}
	*/
	/*
	function home(){
		//reset wordlist (wordcloud) in session.
		$_SESSION['wl'] = null;
		
		//initial data
		if(intval($this->Request->getParam('campaign_id'))>0){
			$campaign_id = intval($this->Request->getParam('campaign_id'));
		}else{
			$campaign_id = $_SESSION['campaign_id'];
		}
		$data = $this->api->livetrackStatus($this->user->account_id,$campaign_id);
		
		$this->assign('talking',$data->row[0]->attributes()->total);
		$this->assign('mentions',$data->row[1]->attributes()->total);
		$this->assign('reach',$data->row[2]->attributes()->total);
		
		global $CONFIG;
		
		$host = explode('.',$_SERVER['HTTP_HOST']);
		if(($host[0] == 'smacapp' || $host[1] == 'smacapp') && ($host[1] == 'loc' || $host[2] == 'loc')){
			$google_api = $CONFIG['GOOGLE_MAP_KEY_LOC'];
		}elseif(($host[0] == 'smacapp' || $host[1] == 'smacapp') && ($host[1] == 'com' || $host[2] == 'com')){
			$google_api = $CONFIG['GOOGLE_MAP_KEY_APP'];
		}else{
			$google_api = $CONFIG['GOOGLE_MAP_KEY_ME'];
		}  
		
		$campaign = $this->get_campaign_info($_SESSION['campaign_id']);
		$this->assign("campaign_status",$campaign['n_status']);
		$this->assign('google_map_key',$google_api);
		
		
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		
		
		
		
		//link livetrack
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urllivetrack',$link);
		
		//link livetrack historical
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack','act' =>'historical');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlhistorical',$link);
		
		//link livetrack stream
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack','act' =>'alltrack');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlalltrack',$link);
		
		
		if(strlen($_SESSION['geo'])==2){
			try{
				$market = ucfirst($this->market->getCountryName($_SESSION['geo']));
			}catch(Exception $e){
				
			}
		}
		$this->assign("market",$market);
		$this->View->assign('geo',$_SESSION['geo']);
		
		
		
		return $this->View->toString(APPLICATION.'/live-track.html');
	
	}*/
	function home(){
		$arr = array("page=liveTracked");
		sendRedirect("?".$this->Request->encrypt_params($arr));
		die();
	}
	function livestreamstat(){
		$data = $this->api->livestreamstats($this->user->account_id,$_SESSION['campaign_id']);
		print $data;
		exit();
	}
	function alltrack(){
		//reset wordlist (wordcloud) in session.
		$_SESSION['wl'] = null;
		
		//initial data
		$campaign = $this->get_campaign_info($_SESSION['campaign_id']);
		
		$data = $this->api->livetrackStatus($this->user->account_id,$_SESSION['campaign_id']);
		$this->assign('dt_from', date($campaign['campaign_start'], mktime(0, 0, 0, date("m"),date("d")-1,date("Y"))) );
		$this->assign('dt_to', date('Y-m-d'));
		$this->assign('talking',$data->row[0]->attributes()->total);
		$this->assign('mentions',$data->row[1]->attributes()->total);
		$this->assign('reach',$data->row[2]->attributes()->total);
		
		global $CONFIG;
		
		$host = explode('.',$_SERVER['HTTP_HOST']);
		if(($host[0] == 'smacapp' || $host[1] == 'smacapp') && ($host[1] == 'loc' || $host[2] == 'loc')){
			$google_api = $CONFIG['GOOGLE_MAP_KEY_LOC'];
		}elseif(($host[0] == 'smacapp' || $host[1] == 'smacapp') && ($host[1] == 'com' || $host[2] == 'com')){
			$google_api = $CONFIG['GOOGLE_MAP_KEY_APP'];
		}else{
			$google_api = $CONFIG['GOOGLE_MAP_KEY_ME'];
		}  
		
		
		$this->assign("campaign_status",$campaign['n_status']);
		$this->assign('google_map_key',$google_api);
		
		
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		$this->assign('favoriteWord', $this->favoriteWordWidget->show());
		
		
		
		//link livetrack
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urllivetrack',$link);
		
		//link livetrack historical
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack','act' =>'historical');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlhistorical',$link);
		
		//link livetrack all
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack','act' =>'alltrack');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlalltrack',$link);
		
		
		if(strlen($_SESSION['geo'])==2){
			try{
				$market = ucfirst($this->market->getCountryName($_SESSION['geo']));
			}catch(Exception $e){
				
			}
		}
		$this->assign("market",$market);
		$this->View->assign('geo',$_SESSION['geo']);
		return $this->View->toString(APPLICATION.'/live-track-all.html');
	
	}
	function get_campaign_info($campaign_id){
		$this->open(0);
		$sql = "SELECT * FROM smac_web.tbl_campaign WHERE id=".$campaign_id." LIMIT 1";
		
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
	function getfeed(){
	
		$ajax = intval($this->Request->getParam('ajax'));
	
		$dat = array();
		
		if($ajax == 1){
		
			
		
			$last_id = intval($_GET['last_id']);
		
			$data = $this->api->getFeed($this->user->account_id,$_SESSION['campaign_id'],$last_id);
			
			//print_r($data);exit;
			
			$feed = array();
			
			foreach($data->row as $k){
				
				$feed[] = array('id' => $k->id,
								'published' => $k->published,
								'hour_time' => $k->hour_time,
								'name' => $k->name,
								'txt' => $k->txt,
								'keyword' => $k->keyword,
								'lat' => $k->lat,
								'lon' => $k->lon,
								'reach' => $k->reach,
								'image' => $k->profile_image_url,
								'author_id' => $k->author_id,
								'wordlist' => explode(',',$k->wordlist),
								'insert_date' => $k->insert_date);
			}
			
			$dat['success'] = 1;
			$dat['feed'] = $feed;
		
		}else{
		
			$dat['success'] = 0;
		
		}
		
		echo json_encode($dat);
		
		exit;
	
	}
	
	function getmapfeed(){
		$ajax = intval($this->Request->getParam('ajax'));
		$dat = array();
		
		if($ajax == 1){
			$last_id = intval($_GET['last_id']);
			$data = $this->api->getMapFeed($this->user->account_id,$_SESSION['campaign_id'],$last_id);
			
			$feed = array();
			
			foreach($data->row as $k){
			
				if($k->lat != 0 && $k->lon != 0){
			
					$feed[] = array('id' => $k->id,
									'published' => $k->published_datetime,
									'hour_time' => $k->hour_time,
									'name' => $k->name,
									'txt' => $k->txt,
									'keyword' => $k->keyword,
									'lat' => $k->lat,
									'lon' => $k->lon,
									'image' => $k->profile_image_url,
									'reach' => $k->reach,
									'author_id' => $k->author_id,
									'wordlist' => explode(',',$k->wordlist),
									'wl'=>urlencode64($k->wordlist),
									'insert_date' => $k->insert_date);
				}
			}
			
			$dat['success'] = 1;
			$dat['feed'] = $feed;
		
		}else{
		
			$dat['success'] = 0;
		
		}
		
		echo json_encode($dat);
		
		exit;
	
	}
	
	function getmapfeedhistorical(){
		$ajax = intval($this->Request->getParam('ajax'));
		$dat = array();
		
		if($ajax == 1){
			$last_id = intval($_GET['last_id']);
			
			$from_date = str_replace('/','-',mysql_escape_string( $this->Request->getParam('from_date') ) );
			$to_date = str_replace('/','-',mysql_escape_string( $this->Request->getParam('to_date') ) );
			$limit = 10;
			
			if(strlen($_SESSION['geo'])==2){
				$api = new MarketHelper();
				$data = $api->getMapFeedHistorical($this->user->account_id,$_SESSION['campaign_id'],$last_id,$from_date,$to_date,$limit,$_SESSION['geo']);
			}else{
				$data = $this->api->getMapFeedHistorical($this->user->account_id,$_SESSION['campaign_id'],$last_id,$from_date,$to_date,$limit);
			}
			$feed = array();
			
			foreach($data->row as $k){
			
				if($k->lat != 0 && $k->lon != 0){
			
					$feed[] = array('id' => $k->id,
									'published' => $k->published_datetime,
									'hour_time' => $k->hour_time,
									'name' => $k->name,
									'txt' => $k->txt,
									'keyword' => $k->keyword,
									'lat' => $k->lat,
									'lon' => $k->lon,
									'image' => $k->profile_image_url,
									'reach' => $k->reach,
									'wordlist' => explode(',',$k->wordlist),
									'wl'=>urlencode64($k->wordlist),
									'insert_date' => $k->insert_date);
				}
			}
			
			$dat['success'] = 1;
			$dat['feed'] = $feed;
		
		}else{
		
			$dat['success'] = 0;
		
		}
		
		echo json_encode($dat);
		
		exit;
	
	}
	function getallfeeds(){
		$ajax = intval($this->Request->getParam('ajax'));
		$dat = array();
		
		
		
		if($ajax == 1){
			$last_id = intval($_GET['last_id']);
			
			$from_date = str_replace('/','-',mysql_escape_string( $this->Request->getParam('from_date') ) );
			$to_date = str_replace('/','-',mysql_escape_string( $this->Request->getParam('to_date') ) );
			$limit = 20;
			$start = intval($_GET['start']);
			$data = $this->api->getAllFeeds($this->user->account_id,$_SESSION['campaign_id'],$last_id,$from_date,$to_date,$start,$limit);
			                               
			$feed = array();
			
			foreach($data->row as $k){
				
				//if($k->lat != 0 && $k->lon != 0){
					
					$feed[] = array('id' => $k->id,
									'published' => array(jakarta_time($k->published_datetime)),
									'hour_time' => $k->hour_time,
									'name' => $k->name,
									'txt' => $k->txt,
									'keyword' => $k->keyword,
									'lat' => $k->lat,
									'lon' => $k->lon,
									'pic' => $k->author_avatar,
									'image' => $k->profile_image_url,
									'reach' => $k->reach,
									'wordlist' => explode(',',$k->wordlist),
									'wl'=>urlencode64($k->wordlist),
									'offset'=>$k->offset,
									'next_offset'=>$k->nextOffset,
									'prev_offset'=>$k->prevOffset,
									'insert_date' => $k->insert_date);
					
				//}
				
			}
			
			$dat['success'] = 1;
			$dat['feed'] = $feed;
		
		}else{
		
			$dat['success'] = 0;
		
		}
		
		echo json_encode($dat);
		
		exit;
	
	}
	function getwordcloud(){
		
		$ajax = intval($this->Request->getRequest('ajax'));
		$reset = intval($this->Request->getRequest('reset'));
		if($ajax == 1){
			
			$data = $this->api->getLivetrackWordcloud($this->user->account_id,$_SESSION['campaign_id'],'all',$reset,50);
			$words = json_decode($data);
			$rs = array();
			foreach($words as $w){
				$rs[] = array("keyword"=>$w->keyword,"amount"=>$w->amount);
			}
			$strHTML = $this->favoriteWordWidget->getWordCloudFromArray($rs);
			$wordlist = null;
			print $strHTML;
		}
		exit;
	}

	function historical(){
		$campaign = $this->get_campaign_info($_SESSION['campaign_id']);
		$from_date = mysql_escape_string($this->Request->getParam('dt_from'));
		$to_date = mysql_escape_string($this->Request->getParam('dt_to'));
		
		if( $from_date != '' ){
			$this->assign('dt_from',$from_date);
		}else{
			//$this->assign('dt_from', date('Y/m/d', mktime(0, 0, 0, date("m"),date("d")-1,date("Y"))) );
			$this->assign('dt_from', date($campaign['campaign_start'], mktime(0, 0, 0, date("m"),date("d")-1,date("Y"))) );
		}
		
		if( $to_date != '' ){
			$this->assign('dt_to',$to_date);
		}else{
			$this->assign('dt_to', date('Y/m/d'));
		}
		
		//reset wordlist (wordcloud) in session.
		$_SESSION['wl'] = null;
		
		$data = $this->api->livetrackStatus($this->user->account_id,$_SESSION['campaign_id']);
		
		$this->assign('talking',$data->row[0]->attributes()->total);
		$this->assign('mentions',$data->row[1]->attributes()->total);
		$this->assign('reach',$data->row[2]->attributes()->total);
		
		global $CONFIG;
		
		$host = explode('.',$_SERVER['HTTP_HOST']);
		if(($host[0] == 'smacapp' || $host[1] == 'smacapp') && ($host[1] == 'loc' || $host[2] == 'loc')){
			$google_api = $CONFIG['GOOGLE_MAP_KEY_LOC'];
		}elseif(($host[0] == 'smacapp' || $host[1] == 'smacapp') && ($host[1] == 'com' || $host[2] == 'com')){
			$google_api = $CONFIG['GOOGLE_MAP_KEY_APP'];
		}else{
			$google_api = $CONFIG['GOOGLE_MAP_KEY_ME'];
		}  
		
		
		$this->assign("campaign_status",$campaign['n_status']);
		$this->assign('google_map_key',$google_api);
		
		//$this->assign('header', $this->headerHelper->show() );
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		
		$this->assign('favoriteWord', $this->favoriteWordWidget->show());
		
		//link livetrack
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urllivetrack',$link);
		//link livetrack historical
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack','act' =>'historical');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlhistorical',$link);
		$this->View->assign('urlhistorical_noindex', str_replace('req=','',$this->Request->encrypt_params($arr)));
		
		//link livetrack all
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack','act' =>'alltrack');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlalltrack',$link);
		
		if(strlen($_SESSION['geo'])==2){
			try{
				$market = ucfirst($this->market->getCountryName($_SESSION['geo']));
			}catch(Exception $e){
				
			}
		}
		$this->assign("market",$market);
		$this->View->assign('geo',$_SESSION['geo']);
		return $this->View->toString(APPLICATION.'/live-track-historical.html');
	
	}
}
?>