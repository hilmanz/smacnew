<?php
/**
 * API Authentication service
 * the service will returns a valid access token upon successfull login
 * @author duf
 */
class authentication extends API{
	var $helper;
	function execute(){
		$this->debug->setAppName('Authentication');
		$api_key = $this->param("api_key");
		$request_token = $this->param("request_token");
		
		$data = array("api_key"=>$api_key,"request_token"=>$request_token);
		$this->debug->log("Auth Request : ".json_encode($data));
		
		if($this->validate($data)){
			$access_token = $this->getAccessToken($data);
			if(strlen($access_token)>0){
				$response = array("access_token"=>$access_token);
				$msg = "SUCCESS";
				$status = 1;
			}else{
				$msg = "Invalid Request Token";
				$status = 0;
			}
		}else{
			$status = 0;
			$msg = "Invalid API Key";
			$response = array();
		}
		return $this->toJson($status,$msg,$response);
	}
	private function validate($data){
		return $this->helper->validateApiKey($data);
	}
	private function getAccessToken($data){
		return $this->helper->getAccessToken($data);
	}
}
?>