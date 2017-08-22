<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $ENGINE_PATH."Utility/Mailer.php";
class statistics extends SQLData{
	
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		//$this->User = new UserManager();
		
	}
	
	function admin(){
		return $this->dashboard();	
	}
	function dashboard(){
		//daily data
		$volume = $this->fetch("SELECT dtreport AS tgl,UNIX_TIMESTAMP(dtreport) as ts,
						SUM(total_mention_twitter) AS twitter,
						SUM(total_mention_facebook) AS fb,
						SUM(total_mention_web) AS web
						FROM smac_report.campaign_rule_volume_history
						GROUP BY dtreport;",1);
		$category = array();
		$twitter = array();
		$fb = array();
		$web = array();
		if(is_array($volume)){
			foreach($volume as $v){
				$category[] = intval($v['ts'])*1000;
				$twitter[] = array(intval($v['ts'])*1000,intval($v['twitter']));
				$fb[] = array(intval($v['ts'])*1000,intval($v['fb']));
				$web[] = array(intval($v['ts'])*1000,intval($v['web']));
			}
		}
		$rs = array('category'=>$category,
					'twitter'=>$twitter,
					'fb'=>$fb,
					'web'=>$web);
		unset($category);
		unset($twitter);
		unset($fb);
		unset($web);
		unset($volume);
		$this->View->assign("rs",json_encode($rs));
		
		//overall counts
		$sql = "SELECT SUM(total_people_twitter) AS twitter,SUM(total_author_web) AS website FROM smac_report.campaign_rule_volume_history;";
		$overall = $this->fetch($sql);
		$this->View->assign("overall",$overall);
		return $this->View->toString(APPLICATION."/admin/statistics/dashboard.html");
	}
}
?>