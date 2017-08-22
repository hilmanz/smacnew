<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Security/Authentication.php";
include_once $ENGINE_PATH."Security/Permission.php";
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Admin/PluginManager.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $ENGINE_PATH."Utility/RequestManager.php";
class Admin extends SQLData{
	var $auth ;
	var $perm;
	var $user;
	var $strHTML;
	var $View;
	var $DEBUG=false;
	var $plugin;
	var $Request;
	function Admin(){
		parent::SQLData();
		$this->Request = new RequestManager;
		$this->auth = new Authentication();
		$this->perm = new Permission();
		$this->user = new UserManager();
		$this->View = new BasicView();	
		$this->plugin = new PluginManager();
	}
	function show(){
		if(!$this->auth->isLogin()){
			$this->strHTML = $this->showLoginPage();
		}else{
			$this->strHTML = $this->showAdminPage();
		}
		if($this->DEBUG){
			print $this->getMessage();
			print $this->perm->getMessage();
			print $this->user->getMessage();
		}
	}
	function loadPlugin($request,$reqID){
		global $APP_PATH,$ENGINE_PATH;
		
		$rs = $this->plugin->getPluginByRequestID($reqID);
		
		if(file_exists($APP_PATH.$rs['plugin_path'].$rs['className'].".php")){
			include_once $APP_PATH.$rs['plugin_path'].$rs['className'].".php";
			$className =  $rs['className'];
			$instance = new $className($request);
			return $instance;
		}else{
			//print $APP_PATH.$rs['plugin_path'].$rs['className'].".php";
			return false;
		}
	}
	function showDashboard(){
		include_once "DashboardManager.php";
		// $dashboard = new DashboardManager(null);
		//$output = $dashboard->load();
		// $this->View->assign("DASHBOARD_CONTENT",$output);
		// $this->View->assign("user",array("username"=>$this->auth->Session->getVariable("username")));
		
		//Throughput Graph
		$this->open(0);
		$sql = "SELECT * FROM smac_bot.smac_dashboard_throughput WHERE 1 ORDER BY dtreport DESC LIMIT 8";
		$q = $this->fetch($sql, 1);
		$this->close();
		//calculate total char in MB
		foreach($q as $k=>$v){
			$q[$k]['total_char_count'] = round(((($v['total_char_count']*2)/1024)/1024),2);	
		}
		//reverse array, highchart requirement
		$reverse = array_reverse($q);
		foreach($reverse as $k=>$v){
			$tgl = substr($reverse[$k]['dtreport'], 8, 2)."/".substr($reverse[$k]['dtreport'], 5, 2)."/".substr($reverse[$k]['dtreport'], 0, 4);
			$lineChart['category'][] = $tgl;
			$lineChart['feed_count'][] = intval($reverse[$k]['raw_feed_gnip_count']);
			$lineChart['char_count'][] = $reverse[$k]['total_char_count'];
		}
		$start = intval($this->Request->getParam('st'));
		if($start == null){
			$start = 0;
		}
		//Active Topic
		$this->open(0);
		$sql2 = 'SELECT * FROM smac_bot.smac_dashboard_topic_active WHERE 1 ORDER BY id LIMIT '.$start.',10';
		$qry = 'SELECT count(*) total FROM smac_bot.smac_dashboard_topic_active WHERE 1 ORDER BY id';
		$q2 = $this->fetch($sql2, 1);
		$list = $this->fetch($qry);
		$this->close();
		
		
		
		$total = $list['total'];
		$total_per_page = 10;
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total));
		
		// print_r('<pre>');
		// print_r($total);exit;
		$this->View->assign('lineChart', json_encode($lineChart));
		$this->View->assign('activeTopic', $q2);
		$this->View->assign("content",$this->View->toString("common/admin/dashboard.html"));
	}
	function showAdminPage(){
        $this->View->assign("user",array("username"=>$this->auth->Session->getVariable("username")));
		return $this->View->toString("common/admin/admin.html");
	}
	function toString(){
		return $this->strHTML;
	}
	function showLoginPage(){
		if($_GET['f']==1){
			$this->View->assign("msg","Access Denied !");
		}
		return $this->View->toString("common/admin/login.html");
	}
	function execute($obj,$reqID){
		if($this->perm->isAllowed($reqID)){
			if($obj->autoconnect){
				$obj->open();
				$this->View->assign("content",$obj->admin());
				$obj->close();
			}else{
				$this->View->assign("content",$obj->admin());
			}
		}else{
			$this->View->assign("content","Access Denied !");
		}
		if($this->DEBUG){
			print $obj->getMessage();
		}
	}
	function attach($obj,$reqID,$arr,$adminMode=true){
		if($adminMode){
			if($this->perm->isAllowed($reqID)){
				$obj->open();
				
				for($i=0;$i<sizeof($arr);$i++){
					$this->View->assign("addon_".$arr[$i],$obj->addon($arr[$i]));	
				}
				$obj->close();
				
			}
		}else{
			for($i=0;$i<sizeof($arr);$i++){
				$this->View->assign("addon_".$arr[$i],$obj->addon($arr[$i]));	
			}
		}
		
		
		if($this->DEBUG){
			print $obj->getMessage();
		}
		
	
	}
}
?>