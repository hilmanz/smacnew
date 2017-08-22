<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/headerHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/favoriteWordWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/keyOpinionWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/tabNetworkWidget.php";
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/dateFilterWidget.php";


class market extends App{
	
	var $Request;

	var $View;

	var $menuHelper;

	//var $headerHelper;

	var $sidebarHelper;

	var $favoriteWordWidget;

	var $keyOpinionWidget;

	var $tabNetworkWidget;
	var $dateFilterWidget;

	var $api;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		
		$this->setVar();
		
		$this->menuHelper = new menuHelper($this->user,$req);
		
		//$this->headerHelper = new headerHelper($this->user,$req);
		
		$this->sidebarHelper = new sidebarHelper($this->user,$req);
		
		$this->dateFilterWidget = new dateFilterWidget($this->user,$req);
		
		$this->api = new apiHelper();
		
	}
	function home(){
		global $APP_PATH;
		//datefilter
		$this->dateFilterWidget->setPage('home');
		//$this->assign("widget_datefilter",$this->dateFilterWidget->show());
		//$filter_date_from = $this->dateFilterWidget->from_date();
		//$filter_to_date = $this->dateFilterWidget->to_date();
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		$this->assign('menu', $this->menuHelper->showMenu(true) );
		
		global $CONFIG;
		
		$this->open(0);
		
		//twitter
		$sql = "SELECT *,mentions as twitter_mention,0 as web_mention,0 as twitter,0 as fb, 0 as web 
				FROM smac_report.campaign_country_twitter WHERE campaign_id=".$_SESSION['campaign_id']." 
				ORDER BY mentions DESC LIMIT 300";
		$data = $this->fetch($sql,1);
		
		$sql = "SELECT SUM(mentions) as total_mention FROM smac_report.campaign_country_twitter 
				WHERE campaign_id=".$_SESSION['campaign_id']." GROUP BY campaign_id 
				LIMIT 1";
		$m = $this->fetch($sql);
		$total_mention = $m['total_mention'];
		$total_tw = $total_mention;
		//web
		$sql = "SELECT campaign_id,country_name,country_code,mentions,impression,people 
				FROM smac_report.campaign_country_web WHERE campaign_id={$_SESSION['campaign_id']}";
		$data2 = $this->fetch($sql,1);
		$sql = "SELECT SUM(mentions) as total_mention
				FROM smac_report.campaign_country_web WHERE campaign_id={$_SESSION['campaign_id']} LIMIT 1";
		$m = $this->fetch($sql);
		
		$total_mention2 = $m['total_mention'];
		$total_mention+=$total_mention2;
		$fb = 0;
		//$twitter = ($total_tw/$total_mention)*100;
		//$web = ($total_mention2/$total_mention)*100;
		
		foreach($data2 as $web){
			$is_new = true;
			$web['web_mention'] = $web['mentions'];
			$web['fb'] = 0;
			foreach($data as $n=>$v){
				
				if(strtolower($web['country_code'])==strtolower($v['country_code'])){
					$data[$n]['twitter_mention'] = $data[$n]['mentions'];
					$data[$n]['web_mention'] = $web['mentions'];
					$data[$n]['mentions']+=$web['mentions'];
					$data[$n]['fb'] = 0;
					$is_new = false;
					break;
				}
			}
			if($is_new){
				$data[] = $web;
			}
		}
		foreach($data as $n=>$v){
			
			
			$data[$n]['twitter'] = $data[$n]['twitter_mention'] / $data[$n]['mentions'] * 100;
			$data[$n]['web'] = $data[$n]['web_mention'] / $data[$n]['mentions'] * 100;
			$data[$n]['country_name'] = ucfirst($data[$n]['country_name']);
			$data[$n]['share'] = round($data[$n]['mentions']/$total_mention*100,3);
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'home','act' => 'toggle_geo','geo' => $data[$n]['country_code']);
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			$data[$n]['url'] = $link;
		}
		
		if(sizeof($data)>0){
			$data_available=1;
		}else{
			$data_available=0;
		}
		$this->View->assign('data_available',$data_available);
		$this->View->assign("data",$data);
		$this->View->assign("json_data",json_encode($data));
		$this->close();
		return $this->View->toString("smac/market.html");
	}
	
}
?>