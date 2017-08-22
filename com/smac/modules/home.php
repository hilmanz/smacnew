<?php
/**
 * SMAC DASHBOARD PAGE
 */
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/headerHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/favoriteWordWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/keyOpinionWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/tabNetworkWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/dateFilterWidget.php";
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/MarketHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/FBHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/WebHelper.php";
class home extends App{

	var $Request;

	var $View;

	var $menuHelper;

	//var $headerHelper;

	var $sidebarHelper;

	var $favoriteWordWidget;

	var $keyOpinionWidget;

	var $tabNetworkWidget;
	var $dateFilterWidget;
	
	var $fbhelper;
	var $webhelper;
	var $api;

	function __construct($req){
		
		$this->Request = $req;

		$this->View = new BasicView();

		$this->setVar();
		
		if(strlen($_SESSION['geo'])>0){
			
			$this->api = new marketHelper();
		}else{
			$this->api = new apiHelper();
		}
		$this->menuHelper = new menuHelper($this->user,$req);

		//$this->headerHelper = new headerHelper($this->user,$req);

		$this->sidebarHelper = new sidebarHelper($this->user,$req);
		$this->fbhelper = new FBHelper($this->user,$req);
		$this->webhelper = new WebHelper($this->user,$req);
		
		$this->favoriteWordWidget = new favoriteWordWidget($this->user,$req);

		$this->keyOpinionWidget = new keyOpinionWidget($this->user,$req);

		$this->tabNetworkWidget = new tabNetworkWidget($this->user,$req);
		$this->dateFilterWidget = new dateFilterWidget($this->user,$req);
		
	}
	function toggle_geo(){
		
		if(strlen($this->Request->getParam('geo'))==2){
			$_SESSION['geo'] = $this->Request->getParam("geo");
		}else{
			$_SESSION['geo'] = "";
		}
		sendRedirect("index.php");
		die();
	}
	function main(){
		
		if($this->Request->getParam('campaign_id') != ''){
			$_SESSION['campaign_id'] = intval($this->Request->getParam('campaign_id'));
		}
		
		if($this->Request->getParam('language') != ''){
			$_SESSION['language'] = $this->Request->getParam('language');
		}
		
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		
		//datefilter
		$this->dateFilterWidget->setPage('home');
		$this->assign("widget_datefilter",$this->dateFilterWidget->show());
		$filter_date_from = $this->dateFilterWidget->from_date();
		$filter_to_date = $this->dateFilterWidget->to_date();
		//
		if($filter_date_from!=null&&$filter_to_date!=null){
			$impact = $this->api->summaryImpactDaily($this->user->account_id,$filter_date_from,$filter_to_date,$_SESSION['campaign_id'],$_SESSION['language'],$_SESSION['geo']);
		}else{
			$impact = $this->api->summaryImpact($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$_SESSION['geo']);
		}
		
		$data_available = true;
		
		if((String)$impact->mentions=='0'||(String)$impact->impressi=='0'){
			$data_available=false;
		}
		
		
		
		$this->assign('sidebar', $this->sidebarHelper->show());
		
		if($_SESSION['geo']==null){
			$fbsummary = $this->fbhelper->get_summary($_SESSION['campaign_id'],$filter_date_from,$filter_to_date);
		}else{
			$fbsummary['total_likes'] = 0;
			$fbsummary['total_mentions'] = 0;
			$fbsummary['total_people'] = 0;
		}
		$websummary = $this->webhelper->get_summary($_SESSION['campaign_id'],$filter_date_from,$filter_to_date,$_SESSION['geo']);
		
		
		//jika data twitter kosong, cek apakah topic ini punya data web ? 
		//jika ada.. maka kita switch tab awal ke web. bukan ke twitter.
		$default_tab = "twitter";
		$web_total = $this->webhelper->getTotalFeeds($_SESSION['campaign_id']);
		
		if($data_available==false){
			if($web_total>0){
					$data_available=true;
					$default_tab = "web";
			}else if($fbsummary['total_likes']>0){
				$data_available=true;
				$default_tab = "fb";
			}
		}
		
		$this->assign("data_available",$data_available);
		
		if($data_available){
			//$people_involved = $this->api->people_involved($_SESSION['campaign_id'],$this->user->account_id,$_SESSION['language']);
			
			$this->assign("default_tab",$default_tab);
			$this->assign("web_total",$web_total);
			$this->assign('impact', floatval($impact->impact) );
			$this->assign('mention', (String) $impact->mentions );
			$this->assign('impressi', (String) $impact->impressi );
			$this->assign('fb_likes',number_format($fbsummary['total_likes']));
			$this->assign('fb_mentions',number_format($fbsummary['total_mentions']));
			if($fbsummary['performance']!=null){
				$this->assign('fb_mention_change',number_format($fbsummary['performance']['mention_change']));
				$this->assign('fb_mention_diff',number_format($fbsummary['performance']['mention_diff']));
				$this->assign('fb_like_change',number_format($fbsummary['performance']['like_change']));
				$this->assign('fb_like_diff',number_format($fbsummary['performance']['like_diff']));
			}
			
			
			$this->assign('web_mentions',number_format($websummary['total_posts']));
			$this->assign('web_comments',number_format($websummary['total_comments']));
			
			$this->assign('imp_change',(float)$impact->imp_change);
			$this->assign('mention_change',(float)$impact->mention_change);
			$this->assign('pii_change',(float)$impact->pii_change);
			
			$this->assign('imp_diff', smac_number(trim($impact->imp_diff)));
			$this->assign('mention_diff', smac_number(trim($impact->mention_diff)));
			$this->assign('pii_diff', (trim($impact->pii_diff)));
			
			//$this->assign('favoriteWord', $this->favoriteWordWidget->show($_SESSION['geo']));
			//$this->assign('keyOpinion', $this->keyOpinionWidget->show($filter_date_from,$filter_to_date,$_SESSION['geo']) );
			//$this->assign('tabNetwork', $this->tabNetworkWidget->show($filter_date_from,$filter_to_date,$_SESSION['geo']));
			$this->assign('people',(String) $impact->people);
			$this->assign('fb_people',number_format($fbsummary['total_people']));
			$total_days = intval(trim($impact->total_days));
			
			$this->assign("total_days",$total_days);
			$this->assign("total_websites",smac_number($websummary['total_websites']));
		}
		if(strlen($_SESSION['geo'])==2){
			try{
				$market = ucfirst($this->api->getCountryName($_SESSION['geo']));
			}catch(Exception $e){
				
			}
		}
		$this->assign("filter_date_from",$filter_date_from);
		$this->assign("filter_to_date",$filter_to_date);
		
		$this->assign("market",$market);
		return $this->View->toString(APPLICATION.'/home.html');
	}
	function favourite_words(){
		if($this->Request->getParam("ajax")==1){
			print $this->favoriteWordWidget->show($_SESSION['geo']);
		}
		die();
	}
	function fb_wordcloud(){
		if($this->Request->getParam("ajax")==1){
			$filter_date_from = $this->Request->getParam("filter_date_from");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			if($_SESSION['geo']==null){
				$words = $this->fbhelper->get_wordcloud($_SESSION['campaign_id'],$filter_date_from,$filter_to_date);
			}
			print $this->favoriteWordWidget->show_fb($words,$_SESSION['geo']);
		}
		die();
	}
	function web_wordcloud(){
		if($this->Request->getParam("ajax")==1){
			$filter_date_from = $this->Request->getParam("filter_date_from");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			$words = $this->webhelper->get_wordcloud($_SESSION['campaign_id'],$filter_date_from,$filter_to_date);
			print $this->favoriteWordWidget->show_fb($words,$_SESSION['geo']);
		}
		die();
	}
	function topKOL(){
		if($this->Request->getParam("ajax")==1){
			$filter_date_from = $this->Request->getParam("filter_date_from");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			print $this->keyOpinionWidget->show($filter_date_from,$filter_to_date,$_SESSION['geo']);
			
		}
		die();
	}
	function top_conversation(){
		if($this->Request->getParam("ajax")==1){
			$filter_date_from = $this->Request->getParam("filter_date_from");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			$default_tab = $this->Request->getParam("tab");
			if($default_tab==null){
				$default_tab = "twitter";
			}
			print $this->tabNetworkWidget->show($filter_date_from,$filter_to_date,$_SESSION['geo'],$default_tab);
			
		}
		die();
	}
	function fb_kol(){
		if($this->Request->getParam("ajax")==1){
			$filter_date_from = $this->Request->getParam("filter_date_from");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			if($_SESSION['geo']==null){
				$kol = $this->fbhelper->getKOL($_SESSION['campaign_id'], $filter_date_from, $filter_to_date);
			}
			print $this->keyOpinionWidget->show_fb($kol,$_SESSION['geo']);
			
		}
		die();
	}
	function web_kol(){
		if($this->Request->getParam("ajax")==1){
			$filter_date_from = $this->Request->getParam("filter_date_from");
			$filter_to_date = $this->Request->getParam("filter_to_date");
			
			if($_SESSION['geo']!=null){
				$kol = $this->webhelper->getKOLGeo($_SESSION['campaign_id'],$filter_date_from,$filter_to_date,$_SESSION['geo']);
			}else{
				$kol = $this->webhelper->getKOL($_SESSION['campaign_id'], $filter_date_from,$filter_to_date );
			}
			print $this->keyOpinionWidget->show_web($kol,$_SESSION['geo']);
			
		}
		die();
	}
	function twitter_top_conversation(){
		if($this->Request->getParam("ajax")==1){
			$this->tabNetworkWidget->show_twitter();
		}else{
			print "Not Available";
		}
		die();
	}
	function fb_top_conversation(){
		if($this->Request->getParam("ajax")==1){
			$this->tabNetworkWidget->show_fb();
		}else{
			print "Not Available";
		}
		die();
	}
	function web_top_conversation(){
		if($this->Request->getParam("ajax")==1){
			$this->tabNetworkWidget->show_web();
		}else{
			print "Not Available";
		}
		die();
	}

}
?>