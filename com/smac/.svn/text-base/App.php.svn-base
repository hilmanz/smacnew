<?php
global $APP_PATH,$ENGINE_PATH;
include_once $APP_PATH . APPLICATION . "/helper/loginHelper.php";
include_once $APP_PATH . APPLICATION . "/helper/maintenanceHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/ServiceHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/BillingHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/TopicGroupHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/wordcloudHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/dateFilterWidget.php";
include_once $ENGINE_PATH."Utility/Mailer.php";
class App extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $loginHelper; 
	var $user = array();
	var $maintenance;
	var $serviceHelper;
	var $access_token = "";
	var $sidebarHelper;
	var $data_available = false;
	var $api;
	function __construct($req){
		parent::__construct($req);
		$this->beforeFilter();
		$this->setVar();
	}
	function beforeFilter(){
		
		if($this->Request->getParam("campaign_id")!=null){
			//assume that user want to go to dashboard page
			$_SESSION['campaign_id'] = $this->Request->getParam("campaign_id");
		}
		if(strlen($this->Request->getParam('page'))==0){
			$_SESSION['campaign_id']=0;
		}
		$this->dateFilterWidget = new dateFilterWidget($this->user,$this->Request);
		$this->maintenance = new maintenanceHelper();
		$this->serviceHelper = new ServiceHelper();
		$this->loginHelper = new loginHelper($this->Request);
		$this->user = $this->loginHelper->getProfile();
		
		$this->sidebarHelper = new sidebarHelper($this->user,$this->Request);
		$this->menuHelper = new menuHelper($this->user,$this->Request);
		//the old Smac modules didnt support serviceHelper, so we only retrieve getting access token only if 
		//we are using new modules.
		if(isset($this->serviceHelper)){
			if(isset($this->user->id)){
				$this->serviceHelper->profile($this->user);
				$this->access_token = $this->serviceHelper->getAccessToken();
				
			}
		}
		if($_SESSION['campaign_id']>0){
			//simply check the reporting date
			$this->open(0);
			$sql = "SELECT COUNT(*) as total FROM smac_report.campaign_rule_volume_history WHERE campaign_id={$_SESSION['campaign_id']}";
			$rs = $this->fetch($sql);
			$this->close();
			if($rs['total']>0){
				$this->data_available = true;
			}
		}
		
		//api helper
		$this->api = new apiHelper();
		
	}
	function beforeRender(){
		if(isset($this->user->id)){
			if(isset($this->sidebarHelper)){
				$this->assign('sidebar', $this->sidebarHelper->show() );
			}
			$this->assign('menu', $this->menuHelper->showMenu(true) );
			//datefilter
			$this->assign("widget_datefilter",$this->dateFilterWidget->show());
			if($this->dateFilterWidget->from_date()==null){
				$this->is_default_range = 1;
			}
			$filter_date_from = $this->dateFilterWidget->from_date() != '' ? $this->dateFilterWidget->from_date() : $this->dateFilterWidget->getStartDate();
			$filter_to_date = $this->dateFilterWidget->to_date() != '' ? $this->dateFilterWidget->to_date() : $this->dateFilterWidget->getEndDate();
			
			
			$this->View->assign("filter_from_date",$filter_date_from);
			$this->View->assign("filter_to_date",$filter_to_date);
			$this->View->assign("default_range",$this->is_default_range);
			$this->View->assign("data_available",$this->data_available);
		}
		
	}
	/**
	 * @deprecated
	 */
	function setVar(){}
	
	/**
	 * 
	 * @todo tolong di tweak lagi expired_timenya.
	 */
	function main(){
		global $CONFIG;
		
		$this->assign("campaign_id",$_SESSION['campaign_id']);
		$this->assign("PAGE",$this->Request->getParam('page'));
		$this->assign("access_token",$this->access_token);
		$this->assign("api_url",$CONFIG['API_BASEURL']);
		
		if($CONFIG['MAINTENANCE']==true){
			$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
			$this->assign('mainContent', $this->View->toString(APPLICATION . '/under-maintenance.html'));
			$this->mainLayout(APPLICATION . '/master.html');
		}else if($this->maintenance->getStatus()){
			$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
			$this->assign('mainContent', $this->View->toString(APPLICATION . '/under-maintenance.html'));
			$this->mainLayout(APPLICATION . '/master.html');
		}else{
			
			//cek login
			if( $this->loginHelper->checkLogin() ){
				
				if( $this->Request->getParam('page') == 'privacy-policy' && $this->Request->getParam('act') == '' ){
				
					$str = $this->run();
					
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
				
				}elseif( $this->Request->getParam('page') == 'term-of-use' && $this->Request->getParam('act') == '' ){
				
					$str = $this->run();
					
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
				
				}else{
					$str = $this->run();
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					
					require_once "helper/headerHelper.php";
					$header = new headerHelper($this->user,$this->Request);
					
					$this->assign('header',$header->show());
					
					
					$this->assign('footer',$this->View->toString(APPLICATION . "/footer.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
				}
			}else{
				
				if( $this->Request->getParam('page') == 'registration' && ($this->Request->getParam('act') == '' || $this->Request->getParam('act') == 'checksubdomain') ){
				
					$str = $this->run();
					
					//$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master_register.html');
				
				}elseif( $this->Request->getParam('page') == 'profile' && ($this->Request->getParam('act') == 'getstate' || $this->Request->getParam('act') == 'getcity') && $_GET['ajax'] == 1 ){
				
					$str = $this->run();
					
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
				
				}elseif( $this->Request->getParam('page') == 'term-of-use' && $this->Request->getParam('act') == '' ){
				
					$str = $this->run();
					
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
				
				}elseif( $this->Request->getParam('page') == 'privacy-policy' && $this->Request->getParam('act') == '' ){
				
					$str = $this->run();
					
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
				
				}elseif( $this->Request->getParam('page') == 'activation' && $this->Request->getParam('code') != '' && $this->Request->getParam('email') != '' && $this->Request->getParam('id') != '' ){
				
					$str = $this->run();
					
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
				
				}else if($this->Request->getParam("page")=="login"){
					$str = $this->run();
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					
					if($_POST['login']){
						
						$this->assign('errLogin',1);	
						
					}
					
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
					//print $str;
					//die();
				}else if($this->Request->getParam("page")=="under-maintenance"){
					
					$this->mainLayout(APPLICATION . '/under-maintenance.html');
					
				}else if($this->Request->getParam("page")=="admin" && $this->Request->getParam("act")=="directlogin"){
					$str = $this->run();
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('mainContent',$str);
					$this->mainLayout(APPLICATION . '/master.html');
				}else{
					
					$params = array("page"=>"login","subdomain"=>$_GET['subdomain']);
					sendRedirect("index.php?".$this->Request->encrypt_params($params));
					die();
				
				}
			}
		}
	}
	
	/*
	 *	Mengatur setiap paramater di alihkan ke class yang mengaturnya
	 *
	 *	Urutan paramater:
	 *	- page			(nama class) 
	 *	- act				(nama method)
	 *	- optional		(paramater selanjutnya optional, tergantung kebutuhan)
	 */
	function run(){
		global $APP_PATH,$logger;
		
		$page = $this->Request->getParam('page');
		$act = $this->Request->getParam('act');
		$ip = getRealIP();
		$logger->info("[ACTIVITY] AccountID : {$this->user->account_id} | User : {$this->user->id} | Action : {$page}-{$act} | topic_id : {$_SESSION['campaign_id']} | {$ip}");
		//workflow folders
		if($_SESSION['campaign_id']>0){
			$this->View->assign("wf_folders",json_encode($this->api->getWorkflowFolders($_SESSION['campaign_id'])));
		}
		if( $page != '' ){
			if( !is_file( $APP_PATH . APPLICATION . '/modules/'. $page . '.php' ) ){
				
				if( is_file( '../templates/'. APPLICATION . '/'. $page . '.html' ) ){
					
					return $this->View->toString(APPLICATION.'/'.$page.'.html');
				
				}else{
					
					sendRedirect("index.php");
					die();
				
				}
			
			}else{
				
				require_once 'modules/'. $page.'.php';
				
				
				$content = new $page($this->Request);
				
				if($page!="login"){
					$content->beforeRender();
				}
				$content->user = $this->user;
				$content->access_token = $this->access_token;
				$content->campaign_id = $_SESSION['campaign_id'];
				if( $act != '' ){
					if( method_exists($content, $act) ){
						return $content->$act();
					}else{
						return $content->home();
					}
				}else{
					return $content->home();
				}
			
			}
		}else{	 
			if($this->Request->getParam("campaign_id")!=null){
				//assume that user want to go to dashboard page
				$_SESSION['campaign_id'] = $this->Request->getParam("campaign_id");
				
				$arr = array("subdomain"=>$this->Request->getParam("subdomain"),
							  "page"=>"dashboard",
							 "campaign_id"=>$this->Request->getParam("campaign_id"));
				
			}else{
				$_SESSION['campaign_id'] = null;
				//redirect back to overview page
				$arr = array("subdomain"=>$this->Request->getParam("subdomain"),"page"=>"overview");
			}
			$link = $this->Request->encrypt_params($arr);
			sendRedirect("?".$link);
			die();
		
		}
	}
	
	function birthday($birthday){
		$birth = explode(' ',$birthday);
		list($year,$month,$day) = explode("-",$birth[0]);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		  $year_diff--;
		return $year_diff;
	}
	
	function is_valid_email($email) {
	  $result = TRUE;
	  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
		$result = FALSE;
	  }
	  return $result;
	}
	
	function is_email_available($email){
		//VALIDATION EMAIL TO DB (cari di table smac_registration,smac_agency & smac_user adakah yang sama?)
		$sql = "SELECT a.email FROM
						(
						SELECT r.agency_email AS email FROM smac_web.smac_registration r WHERE n_status IN ('0','1') 
						UNION
						SELECT agency_email AS email FROM smac_web.smac_agency 
						UNION
						SELECT email FROM smac_web.smac_user
						) a
						WHERE
						a.email='".mysql_escape_string(strtolower($email))."';";
		
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();		
		if($rs['email'] != ''){
			return false;
		}
		
		return true;
		
	}
	
	function objectToArray($object) {
		//print_r($object);exit;
		
		 if (is_object($object)) {
		    foreach ($object as $key => $value) {
		        $array[$key] = $value;
		    }
		}
		else {
		    $array = $object;
		}
		return $array;
		
	}
	
}
?>