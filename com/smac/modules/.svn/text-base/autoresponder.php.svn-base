<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
//require_once $APP_PATH . APPLICATION . "/helper/headerHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
require_once $APP_PATH . APPLICATION . "/widgets/favoriteWordWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/keyOpinionWidget.php";
require_once $APP_PATH . APPLICATION . "/widgets/tabNetworkWidget.php";
class autoresponder extends App{
	
	var $Request;
	
	var $View;
	
	var $menuHelper;
	
	//var $headerHelper;
	
	var $sidebarHelper;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->setVar();
		
		$this->menuHelper = new menuHelper($this->user,$req);
		
		//$this->headerHelper = new headerHelper($this->user,$req);
		
		//$this->favoriteWordWidget = new favoriteWordWidget($this->user,$req);
		
		$this->sidebarHelper = new sidebarHelper($this->user,$req);
		
	}
	
	function home(){
		
		//$this->assign('header', $this->headerHelper->show() );
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$this->assign('menu', $this->menuHelper->showMenu() );
		$this->assign("pageName","Auto Responders");
		//$this->assign('favoriteWord', $this->favoriteWordWidget->show());
		
		//return $this->View->toString(APPLICATION.'/auto-responder.html');
		return $this->View->toString(APPLICATION.'/lock.html');
	
	}

}
?>