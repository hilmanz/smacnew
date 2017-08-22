<?php
global $APP_PATH;
class allpost extends App{
	function home(){
	
		return $this->View->toString(APPLICATION.'/allpost.html');
	
	}
	

}
?>