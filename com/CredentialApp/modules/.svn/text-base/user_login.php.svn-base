<?php
global $APP_PATH;
include_once $APP_PATH."CredentialApp/helper/session_helper.php";

class user_login extends API_Module{
	var $msg = "";
	function __construct($req){
		API_Module::__construct($req);
	}
	function execute(){
		
		$rs=$this->validate_user();
		if($rs!=false){
			$helper = new session_helper();
			
			$response = array("user_id"=>$rs['id'],"session_token"=>$helper->generate_session($rs['id']));
			
			return $this->toJson(1,'SUCCESS',$response);
		}else{
			return $this->toJson(0,'Failed',$this->msg);
		}
	}
	
	function validate_user(){
		$email = mysql_escape_string($this->_request('email'));
		$password = $this->_request('password');
		$account_id = $this->_request("account_id");
		$subdomain = mysql_escape_string($this->_request("subdomain"));
		
		if(eregi("[0-9]+",$account_id)){
			//print sha1($email.$password."12345");
			
			$this->open(0);
			//$sql="SELECT * FROM smac_user WHERE email='".$email."' AND account_id=".$account_id." LIMIT 1";
			$sql = "SELECT * FROM smac_web.smac_user u LEFT JOIN smac_web.smac_subdomain s ON u.account_id=s.account_id 
					WHERE u.email='".$email."' 
					AND s.subdomain='".$subdomain."' 
					AND u.n_status = 1
					LIMIT 1;";
			//$this->msg = $sql;
			$rs = $this->fetch($sql);
			$this->close();
			
			$enc_password = sha1($email.$password.$rs['secret']);
			
			if(strcmp($enc_password,$rs['password'])==0){
				return $rs;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
?>
