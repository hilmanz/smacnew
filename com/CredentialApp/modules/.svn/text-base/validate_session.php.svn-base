<?php
global $APP_PATH;
include_once $APP_PATH."CredentialApp/helper/session_helper.php";

class validate_session extends API_Module{
	function __construct($req){
		API_Module::__construct($req);
	}
	function execute(){
		$session_token = mysql_escape_string($this->_request('token'));
		$user_id = mysql_escape_string($this->_request('user_id'));	
		$str_decode = urldecode64($session_token);
		
		$arr = explode("|",$str_decode);
		
		$current_time = time();
		if($arr[1]==$user_id&&intval($arr[0])>0&&intval($arr[2])>0){
			$start_time = intval($arr[0]);
			$end_time = intval($arr[2]);
			
			$current_time = 1320232366;
			if($current_time > $start_time && ($end_time-$current_time) > 30){
				//the session token still alive
				$code=1;
				$status = array("status"=>"1","session_token"=>$session_token);
			}else if($current_time > $start_time&&($end_time-$current_time) < 30 &&($end_time-$current_time) > 0){
				$helper = new session_helper();
				$session_token = $helper->generate_session($user_id);
				$status = array("status"=>"1","session_token"=>$session_token);
				$code = 1;
			}else{
				$code = 0;
				$status = array("status"=>"1","session_token"=>0);
			}
		}
		return $this->toJson(intval($code),"validate_session",$status);
		//print $str_decode;
	}
}
?>