<?php
/**
 * these class provides functionality to authenticate the facebook account into smac.
 * we store the extended access_token (more than 2 hours validity)
 */
global $APP_PATH,$ENGINE_PATH;
require_once $ENGINE_PATH."/Utility/facebook/facebook.php";

class fb extends App{	
	var $helper;
	var $profile;
	function setVar(){
		global $FACEBOOK;
		$config = array('appId'=>$FACEBOOK['APP_ID'],'secret'=>$FACEBOOK['APP_SECRET'],'fileUpload'=>false);
		$this->helper = new facebook($config);
		$this->profile = $this->getActiveProfile($this->user->account_id);
	}
	function home(){
		
	}
	function getActiveProfile($account_id){
		$sql = "SELECT * FROM smac_web.smac_facebook WHERE account_id={$account_id} LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
	function revoke(){
		$account_id = $this->user->account_id;
		$this->open(0);
		$sql = "DELETE FROM smac_web.smac_facebook WHERE account_id=".$account_id."";
		$this->query($sql);
		$this->close();
		$urlBackTo = "?".$this->Request->encrypt_params(array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account'));
		return $this->View->showMessage(text('fb_revoke_access'),$urlBackTo);
	}
	function get_screen_name(){
		try{
			$this->helper->setAccessToken($this->profile['access_token']);
			$me = $this->helper->api('me');
		}catch(Exception $e){
			print $e->getMessage();
		}
		return $me['name'];
	}
	function post_comment($post_id,$message){
		try{
			$rs = $this->helper->api("/{$post_id}/comments",'post',array('message' => $message));
			if($rs) return true;
		}catch(Exception $e){
			
		}	
	}
	function get_access_token(){
		return $this->helper->getAccessToken();
	}
	function is_authorized(){
		$account_id = $this->user->account_id;
		$sql = "SELECT * FROM smac_web.smac_facebook WHERE account_id={$account_id} LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		if(strlen($rs['access_token'])>0){
			return true;
		}
	}
	/**
	 * at the moment, an access token is bound to 1 account.
	 * so the same access token could be accessed by any users within those account.
	 */
	function save_access_token($fb_id,$access_token,$expire){
		
		$account_id = $this->user->account_id;
		$this->open(0);
		$sql = "DELETE FROM smac_web.smac_facebook WHERE account_id=".$account_id."";
		$this->query($sql);
		$valid_until = date("Y-m-d H:i:s",$expire);
		$sql = "INSERT INTO smac_web.smac_facebook(account_id,fb_id,access_token,submit_date,valid_until)
				VALUES(".$account_id.",'{$fb_id}','".mysql_escape_string($access_token)."',NOW(),'{$valid_until}')";
		$q = $this->query($sql);
		
		$this->close();
		return $q;
	}
	function authorize(){
		global $FACEBOOK;
		$user_id = $this->helper->getUser();
		if($user_id){
			//extend access token
			//(http://stackoverflow.com/questions/10903991/facebook-php-sdk-getting-long-lived-access-token-now-that-offline-access-is) 
			$this->helper->setExtendedAccessToken();
			$access_token = $this->helper->getAccessToken();
			$app_token = $FACEBOOK['APP_ID']."|".$FACEBOOK['APP_SECRET'];
			//debugging access token : https://developers.facebook.com/docs/authentication/access-token-debug/
			$response = $this->helper->api('debug_token','GET',array('input_token'=>$access_token,'access_token'=>$app_token));
			if($response){
				if($this->save_access_token($response['data']['user_id'], $access_token,$response['data']['expires_at'])){
					$msg = text('fb_auth_ok');
				}else{
					$msg = text('fb_auth_nok');
				}
				$urlBackTo = "?".$this->Request->encrypt_params(array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account'));
				return $this->View->showMessage($msg, $urlBackTo);
			}
		}else{
			//https://developers.facebook.com/docs/authentication/server-side/
			//redirect to facebook login page
			sendRedirect($this->helper->getLoginUrl(
				array('scope' => 'publish_stream,publish_actions,read_friendlists,read_stream, friends_likes')
			));
			die();
		}
		
	}
}
?>