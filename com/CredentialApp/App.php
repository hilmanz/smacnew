<?php
/**
 * Credential Helper
 */
global $APP_PATH;
class CredentialApp extends API{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $loginHelper; 
	var $user = array();
	
	function __construct($req){
		API::__construct($req);
	}
	
	function setVar(){
		$this->loginHelper = new loginHelper();
		$this->user = $this->loginHelper->getProfile();
	}
	
	/**
	 * 
	 * @todo tolong di tweak lagi expired_timenya.
	 */
	function main(){
		global $CONFIG;
		if($this->is_authorized()){
			return $this->run();
		}else{
			return $this->toJson(401,'unauthorized access',null);
		}
	}
	function is_authorized(){
		return true;
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
		global $APP_PATH;
		$method = $this->_request('method');
		
		if($method!=''){
			require_once 'modules/'. $this->clean($method).'.php';
			if(class_exists($method)){
				$obj = new $method($this->Request);
				return $obj->execute();
			}else{
				return $this->toJson(405,'failed to instantiate the object',null);
			}
		}else{
			return $this->toJson(404,'Method Not found',null);
		}
	}
}
?>