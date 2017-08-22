<?php
/**
 * Credential Helper
 */
global $APP_PATH;
include_once $APP_PATH."smac_api/helper/AuthHelper.php";
include_once $APP_PATH."smac_api/models/base_model.php";
class SmacAPI extends API{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $loginHelper; 
	var $user = array();
	var $_require_auth = false;
	var $Auth;
	function __construct($req){
		API::__construct($req);
		$this->Auth = new AuthHelper($req);
	}
	function requireAuth($flag=null){
		if($flag!=null){
			$this->_require_auth = $flag;
		}else{
			return $this->_require_auth;
		}
	}
	function setVar(){
		$this->loginHelper = new loginHelper();
		$this->user = $this->loginHelper->getProfile();
	}
	function isAllowed(){}
	
		
	
	function verifyToken(){
		$access_token = mysql_escape_string($_REQUEST['access_token']);
		$this->open(0);
		$rs = $this->Auth->validateAccessToken($access_token);
		$this->close();
		return $rs;
		
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
		if($method!=''&&$method!='authenticate'){
			require_once 'modules/'. $this->clean($method).'.php';
			if(class_exists($method)){
				$obj = new $method($this->Request);
				$obj->Auth = $this->Auth;
				//if authentication is required, we validate access token first before executing the API method.
				if($this->requireAuth()){
					if(!$this->verifyToken()){
						return $this->toJson(401,'invalid access token',null);
					}
				}
				$beforeFilter = $obj->beforeFilter();
				if(strlen($beforeFilter)>0){
					return $beforeFilter;
				}
				return $obj->execute();
			}else{
				return $this->toJson(405,'failed to instantiate the object',null);
			}
		}else if($method=='authenticate'){
			require_once "modules/authentication.php";
			$obj = new authentication($this->Request);
			$obj->helper = $this->Auth;
			return $obj->execute();
		}else{
			return $this->toJson(404,'Method Not found',null);
		}
	}
}
?>