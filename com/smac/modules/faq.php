<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/sidebarHelper.php";
class faq extends App{
	
	var $Request;
	
	var $View;
	
	var $sidebarHelper;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->setVar();
		
		$this->sidebarHelper = new sidebarHelper($this->user,$req);
	
	}
	
	function home(){
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		return $this->View->toString(APPLICATION.'/faq.html');
	
	}
	
}
?>