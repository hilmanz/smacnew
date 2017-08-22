<?php
class menuHelper extends Application{
	function __construct($user,$req=null){
		 parent::__construct($req);
	}
	function getCollectingDataStartDate($campaign_id){
		
		$sql = "SELECT dtreport as published_date
			FROM smac_report.campaign_rule_volume_history 
			WHERE campaign_id={$campaign_id}
			ORDER BY dtreport ASC LIMIT 1;";
			
		$rs = $this->fetch($sql);
		if(intval($rs['published_date'])>0){
			return date("d/m/Y",strtotime($rs['published_date']));
		}else{
			return "-";
		}
	}
	function getCampaignList(){
		$account_id = $this->user->account_id;
		$sql = "SELECT * FROM smac_web.tbl_campaign WHERE client_id={$account_id} LIMIT 1000";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function getTopicDetail($id){
		if($id>0){
			$languages = array("id"=>"Bahasa Indonesia",
							"my"=>"Malay",
							"en"=>"English",
							"de"=>"German",
							"fr"=>"French",
							"it"=>"Italian",
							"ja"=>"Japanese",
							"nl"=>"Dutch",
							"ko"=>"Korean",
							"no"=>"Norwegian",
							"pt"=>"Portuguese",
							"es"=>"Spanish",
							"sv"=>"Swedish",
							"all"=>"Global",
							"ru"=>"Russian",
							"ar"=>"Arabic");
			$sql = "SELECT campaign_name as name,campaign_start as start,campaign_end as end,
					campaign_added as added,n_status,tracking_method,lang,'' as lang_str,geotarget 
					FROM smac_web.tbl_campaign WHERE id={$id} LIMIT 1";
			$this->open(0);
			$rs = $this->fetch($sql);
			if($rs['geotarget']=='all'){
				$rs['country'] = 'Global';
				$rs['country_single'] = true;
			}else if($rs['geotarget']=='loc'){
				$sql = "SELECT geo,country_name FROM smac_web.tbl_campaign_coverage a 
						INNER JOIN smac_web.country b 
						ON a.geo = b.country_id WHERE a.campaign_id={$id} LIMIT 5";
				$country = $this->fetch($sql,1);
				$rs['country'] = "";
				for($i=0;$i<sizeof($country);$i++){
					if($i>0){
						$rs['country'].=", ";
					}
					$rs['country'].=$country[$i]['country_name'];
				}
				$rs['country_single'] = false;
			}
			$rs['start'] = $this->getCollectingDataStartDate($id);
			$rs['lang_str'] = $languages[$rs['lang']];
			$this->close();
		}
		return $rs;
		
	}
	function showMenu($filterByGeo=false){
	
		$this->View->assign('filterByGeo', $filterByGeo);
		
		
		//GET CAMPAIGN ID FROM PARAMETER
		$_campaignId = ($_SESSION['campaign_id'] != '')? $_SESSION['campaign_id'] : $this->Request->getParam('campaign_id');
		
		$_SESSION['campaign_id'] = $_campaignId;
		$campaign = $this->getTopicDetail($_campaignId);
		
		$this->View->assign('campaign', $campaign);
		
		$user = $this->object2array($this->user);
	
		//link dashboard
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),"page"=>"dashboard");
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlhome',$link);
		
		//link key opinion
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'kol');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeyopinion',$link);
		
		//link keyword analysis
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'ka');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeywordanalysis',$link);
		
		//link live track
		//$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'livetrack');
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'liveTracked','act' =>'alltrack');
		
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urllivetrack',$link);
		
		//link autoresponder
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'autoresponder');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlautoresponder',$link);
		
		//link sentiment
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'sentiment');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlsentiment',$link);
		
		//link top summary
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'topsummary');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urltopsummary',$link);
	
		//link workflow
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'workflows');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlworkflow',$link);
		
		//link market
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'marketPage');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlmarket',$link);
		
		//LANGUAGE SESSION
		$_SESSION['language'] = ($_SESSION['language'] != '')? $_SESSION['language'] : 'all' ;
	
		$language = array();
		
		//we don't have to show market link if : 
		//1. the topic is designated for 1 market location only (tbl_campaign.geo_target)
		//2. the topic only have 1 market data.
		if($_SESSION['campaign_id']>0){
			$has_market = false;
			if($this->getTopicGeoTarget($_SESSION['campaign_id'])=="all"){
				if($this->getTotalMarket($_SESSION['campaign_id'])>1){
					$has_market = true;
				}
			}
			$this->View->assign("has_market",$has_market);
		}
		return $this->View->toString(APPLICATION . "/menu.html");
		
	}
	
	function setMenu(){
		$_page = $_GET['page'];
		if($_page == 'wom'){
		}else{
		}
	}
	function getTotalMarket($campaign_id){
		$this->open(0);
		$sql = "SELECT COUNT(*) as total FROM smac_report.campaign_country_twitter 
				WHERE campaign_id={$campaign_id} LIMIT 1;";
		$c = $this->fetch($sql);
		$this->close();
		return $c['total'];
	}
	function getTopicGeoTarget($campaign_id){
		$this->open(0);
		$sql = "SELECT geotarget FROM smac_web.tbl_campaign 
				WHERE id={$campaign_id} LIMIT 1;";
		$c = $this->fetch($sql);
		$this->close();
		return $c['geotarget'];
	}
	
}
?>