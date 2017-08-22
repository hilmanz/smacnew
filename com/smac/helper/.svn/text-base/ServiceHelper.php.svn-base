<?php
class ServiceHelper extends SQLData{
	var $_user;
	/**
	 * set user profile
	 */
	public function profile($a=null){
		if($a==null){
			return $this->_user;
		}
		$this->_user = $a;
	}
	function generateAPIKey($user_id){
		$sql = "SELECT id,agency_id,account_id,email,secret
				FROM 
				smac_web.smac_user 
				WHERE id={$user_id} 
				LIMIT 1";
		$rs = $this->fetch($sql);
		
		if($rs){
			$strcon = "SMAC-SERVICE-API".$rs['id'].$rs['agency_id'].$rs['account_id'].$rs['email'].$rs['secret']."-Version-0.1";
			$api_key = sha1($strcon);
		}
		return $api_key;
	}
	function generateRequestToken($user_id){
		return data_encode(array("user_id"=>$user_id,"request_time"=>date("Y-m-d H:i:s")));
	}
	public function foo(){
		
	}
	function getAccessToken(){
		global $CONFIG;
		$profile = $this->profile();
		
		$this->open(0);
		$api_key = $this->generateAPIKey($profile->id);
		$request_token = $this->generateRequestToken($profile->id);
		$this->close();
		
		//AUTHENTICATE THE API
		$params = array('method'=>'authenticate',
		  'api_key'=>$api_key,
		  'request_token'=>$request_token);
		 
		$response = json_decode(curlGet($CONFIG['API_BASEURL'], $params),true);
		if($response['status']=="1"){
			$_SESSION['access_token'] = $response['data']['access_token'];
		}
		
		return $_SESSION['access_token'];
	}
}
?>