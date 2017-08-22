<?php
/**
 * AuthHelper
 * a helper for API Access Authentication and token validator
 */
class AuthHelper extends API{
	public function getAccessToken($data){
		$request_token = data_decode($data['request_token']);
		$access_token = "";
		//check if there's an active session for the current user ?
		$sql = "SELECT * FROM smac_web.smac_api 
				WHERE user_id={$request_token['user_id']} 
				AND api_key='{$data['api_key']}' 
				AND n_status=1 LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		if($rs){
			//yes its available !
			$acc_token = sha1(date("YmdHis").$data['api_key']); //access permission secret
			$access_token = data_encode(array("user_id"=>$rs['user_id'],
												  "api_key"=>$rs['api_key'],
												  "acc_token"=>$acc_token));
		}else{
			//none available, so we put new entry then.
			$acc_token = sha1(date("YmdHis").$data['api_key']);//access permission secret
			$sql = "INSERT INTO smac_web.smac_api(user_id,api_key,auth_time,n_status,acc_token)
					VALUES({$request_token['user_id']},'{$data['api_key']}',NOW(),1,'{$acc_token}')";
			$q = $this->query($sql);
			if($q){
				$access_token = data_encode(array("user_id"=>$request_token['user_id'],
												  "api_key"=>$data['api_key'],
												  "acc_token"=>$acc_token));
			}
		}
		$this->close();
		return $access_token;
	}
	/**
	 * validate user's api key.
	 * based on supplied request token
	 */
	public function validateApiKey($data){
		$request_token = data_decode($data['request_token']);
		$user_id = $request_token['user_id'];
		$this->open(0);
		$sql = "SELECT id,agency_id,account_id,email,secret
			FROM 
			smac_web.smac_user 
			WHERE id={$user_id} 
			LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		if($rs){
			$strcon = "SMAC-SERVICE-API".$rs['id'].$rs['agency_id'].$rs['account_id'].$rs['email'].$rs['secret']."-Version-0.1";
			$api_key = sha1($strcon);
			if(strcmp($data['api_key'],$api_key)==0){
				return true;
			}
		}
	}
	public function validateAccessToken($access_token){
		$token = data_decode($access_token);
		if($token){
			$ok = false;
			$user_id = $token['user_id'];
			$api_key = $token['api_key'];
			$acc_token = $token['acc_token'];
			$sql = "SELECT * FROM smac_web.smac_api WHERE user_id={$user_id} AND api_key='{$api_key}' AND n_status=1 LIMIT 1";
			$this->open(0);
			$rs = $this->fetch($sql);
			
			if($rs['user_id']==$user_id && $rs['api_key']==$api_key && $acc_token = $rs['acc_token']){
				//for now, the access token for 2 hours.
				//print time()."-".strtotime($rs['auth_time'])."-->".(60*60*2*1000)."\n";
				if((time()-strtotime($rs['auth_time']))>60*60*2*1000){
					$sql = "UPDATE smac_web.smac_api SET n_status=0 WHERE id = {$rs['id']}";
					$this->query($sql);
				}
				$ok = true;
			}
			$this->close();
			return $ok;
		}
	}
	public function getUserId($access_token){
		$access_token = cleanXSS($access_token);
		$data = data_decode($access_token);
		return $data['user_id'];
	}
}
?>