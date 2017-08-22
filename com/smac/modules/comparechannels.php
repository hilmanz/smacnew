<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/headerHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/favoriteWordWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/keyOpinionWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/tabNetworkWidget.php";
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
class comparechannels extends App{
	
	var $Request;
	
	var $View;
	
	var $menuHelper;
	
	//var $headerHelper;
	
	var $sidebarHelper;
	
	var $favoriteWordWidget;
	
	var $keyOpinionWidget;
	
	var $tabNetworkWidget;
	
	var $api;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->setVar();
		
		$this->api = new apiHelper();
		
		$this->menuHelper = new menuHelper($this->user,$req);
		
		//$this->headerHelper = new headerHelper($this->user,$req);
		
		$this->sidebarHelper = new sidebarHelper($this->user,$req);
		
		$this->favoriteWordWidget = new favoriteWordWidget($this->user,$req);
		
		$this->keyOpinionWidget = new keyOpinionWidget($this->user,$req);
		
		$this->tabNetworkWidget = new tabNetworkWidget($this->user,$req);
	
	}
	
	function home(){
		global $logger;
		//link back
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'));
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlback',$link);
	
		$this->assign('menu', $this->menuHelper->showMenu() );
	
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$detail = json_decode($this->api->getCampaignDetail($this->user->account_id,$_SESSION['campaign_id']));
		$c = explode(',', $detail->channels);
		$channels = array(false,false,false,false);
		foreach($c as $k){
			$channels[$k] = true;
		}
		$this->View->assign("channels",$channels);
		
		
		//source count
		//$source = $this->api->getSourceCount($this->user->account_id,$_SESSION['campaign_id']);
		$source = $this->api->getSourceCount($this->user->account_id,$from_date,$to_date,$_SESSION['campaign_id'],$_SESSION['language']);
		
		$twitter_count = (String) $source->twitter;
		$fb_count = (String) $source->fb;
		$blog_count= (String) $source->blog;
		$n_count = $twitter_count+$fb_count+$blog_count;
		$source_count = array('twitter'=>round(($twitter_count/$n_count)*100,2),
							'fb'=>round(($fb_count/$n_count)*100,2),
							'blog'=>round(($blog_count/$n_count)*100,2));
		
		$this->View->assign("source_count",$source_count);
		
		if($channels[1]){
			//twitter
			$tw = $this->api->getTwitterList($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],400);
			
			$totalPerPage = 5;
			$this->View->assign('totalPerPage',$totalPerPage);
			$this->View->assign('numPageTw',ceil(count($tw->row) / $totalPerPage));
			
			$data = array();
			
			foreach($tw->row as $k){
				
				$data[] = array('id'=> (String) $k->id,
								'author_id' => (String) $k->author_id,
								'name' => (String) $k->name,
								'txt' => htmlspecialchars((String) $k->txt),
								'image' => (String) $k->profile_image_url,
								'insert_date' => (String) $k->insert_date,
								'published_date' => date("d/m/Y",strtotime((String) $k->published)),
								'reach' => (String) $k->reach,
								'imp' => (String) $k->imp,
								'rt' => (String) $k->rt,
								'share' => (String) $k->share,
								'rt_imp' => (String) $k->rt_imp,
								'device' => (String) $k->device
								);
				
			}
			$this->View->assign('tw',$data);
		}
		
		if($channels[2]){
			//fb
			$language = $detail->lang;
			if($language==""){
				$language = "all";
			}
			$fb = json_decode($this->api->getFBList($this->user->account_id,$_SESSION['campaign_id'],$language,400));
			//$logger->info($_SESSION['campaign_id']."-facebook : ".json_encode($fb));
			$totalPerPage = 5;
			$this->View->assign('totalPerPage',$totalPerPage);
			$this->View->assign('numPageFB',ceil(count($fb) / $totalPerPage));
			
			$data = array();
			
			foreach($fb as $k){
				
				$data[] = array('id'=> (String) $k->id,
								'name' => (String) $k->name,
								'url' => (String) $k->url,
								'txt' => htmlspecialchars((String) $k->txt),
								'image' => (String) $k->profile_image_url,
								'insert_date' => (String) $k->insert_date,
								'reach' => (String) $k->reach,
								'sentiment' => (String) $k->sentiment,
								'device' => (String) $k->device
								);
				
			}
			$this->View->assign('fb',$data);
		}
		
		if($channels[3]){
			//blog search
			$blog = json_decode($this->api->getBlogList($this->user->account_id,$_SESSION['campaign_id'],400));
			$data = array();
			foreach($blog as $k){
				$data[] = array('id'=> (String) $k->id,
								'name' => (String) $k->name,
								'txt' => htmlspecialchars((String) $k->txt),
								'image' => (String) $k->profile_image_url,
								'insert_date' => (String) $k->insert_date,
								'reach' => (String) $k->reach,
								'sentiment' => (String) $k->sentiment,
								'url' => (String) $k->url,
								'title' => (String) $k->title,
								'link' => (String) $k->link
								);
				
			}
			$this->View->assign('blog',$data);
			$this->View->assign('numPageBLOG',ceil(sizeof($data) / $totalPerPage));
		}
		
		return $this->View->toString(APPLICATION.'/compare-channels.html');
	
	}

}
?>