<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/menuHelper.php";
class tracking extends App{
	
	var $Request;
	
	var $View;
	
	var $menuHelper;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->setVar();
		
		$this->menuHelper = new menuHelper($this->user);
	
	}
	
	function home(){
		
		$this->assign('menu', $this->menuHelper->showMenu() );
		
		return $this->View->toString(APPLICATION.'/tracking.html');
	
	}

}
?>