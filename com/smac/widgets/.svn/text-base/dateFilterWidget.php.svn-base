<?php
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
class dateFilterWidget extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $user;
	var $api;
	var $base_url="";
	var $_params = "";
	var $start_date = '';
	var $end_date = '';
	function __construct($user,$req=null){
		
		$this->user = $user;
		$this->Request = $req;
		$this->api = new apiHelper();
		$this->View = new BasicView();
		
	}
	function setPage($pageName='home',$act='home',$filter=''){
		switch($pageName){
			case "keywordanalysis":
				$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis','act' => $act,'filter' => $filter);
				$link = 'index.php?'.$this->Request->encrypt_params($arr);
			break;
			case "keyopinionleader":
				$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyopinionleader','act' => $act);
				$link = 'index.php?'.$this->Request->encrypt_params($arr);
			break;
			case "workflow":
				$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'workflow','act' => $act);
				$link = 'index.php?'.$this->Request->encrypt_params($arr);
			break;
			case "topsummary":
				$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'topsummary','act' => $act);
				$link = 'index.php?'.$this->Request->encrypt_params($arr);
			break;
			default:
				$arr = array("subdomain"=> $this->Request->getParam('subdomain'));
				$link = 'index.php?'.$this->Request->encrypt_params($arr);
			break;
		}
		$this->_params = $this->Request->encrypt_params($arr);
		$this->base_url = $link;
	}
	function show(){
		$data = $this->api->get_campaign_duration($_SESSION['campaign_id'],$_SESSION['language']);
		$obj = json_decode($data);
		$a_dates = array();
		foreach($obj as $o){
			array_push($a_dates,array("value"=>$o->published_date,"label"=>date("d/m/Y",strtotime($o->published_date))));
		}
	
		//13/08/2012
		//tanggal dibatasi hanya untuk menampilkan halaman default (tanpa ada filter tanggal sama sekali)
		//helper ini berfungsi untuk menyediakan output date range, dimana nanti berdasarkan date range ini lah
		//kita akan mengquery / meretrieve data dari database.
		//default valud didapat jika user tidak menfilter tanggal sama sekali.. 
		//so, from_date(), dan to_date will simply an empty string.
		
		$this->end_date = $a_dates[count($a_dates) - 1]['value'];
		
		if($this->from_date()==null){
			$this->start_date = date("Y-m-d",strtotime($a_dates[count($a_dates) - 1]['value'])-(60*60*24*7));
		}else{
			$this->start_date = $a_dates[0]['value'];
		}
		
		
		$this->View->assign("a_dates",$a_dates);
		$this->View->assign("a_dates_num", count($a_dates) - 1);
		$this->View->assign("dt_from",$this->from_date());
		$this->View->assign("dt_to",$this->to_date());
		$this->View->assign("datefilter_url_param",str_replace("req=","",$this->_params));
		return $this->View->toString(APPLICATION . "/widgets/datefilter.html");
	}
	function from_date(){
		$str = $this->Request->getParam("dt_from");
		if(eregi("/",$str)){
			$a1 = explode("/",$str);
			$formatted = $a1[2].'-'.$a1[1].'-'.$a1[0];
			return $formatted;
		}else{
			return $str;
		}
	}
	function to_date(){
		$str = $this->Request->getParam("dt_to");
		if(eregi("/",$str)){
			$a1 = explode("/",$str);
			$formatted = $a1[2].'-'.$a1[1].'-'.$a1[0];
			return $formatted;
		}else{
			return $str;
		}
	}
	function getStartDate(){
		return $this->start_date;
	}
	
	function getEndDate(){
		return $this->end_date;
	}
}
?>