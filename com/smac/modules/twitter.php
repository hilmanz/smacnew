<?php
global $APP_PATH,$ENGINE_PATH;
include_once $ENGINE_PATH."Utility/twitteroauth/twitteroauth.php";

class twitter extends App{	
	
	function home(){
		
	}
	function callback(){
		$denied = $this->Request->getParam("denied");
		if(strlen($denied)>0 || $denied==$_SESSION['oauth_token']){
			return $this->View->showMessage("Twitter Access is Denied","index.php");
		}else{
			$tOAuth = new TwitterOAuth($TWITTER['KEY'],$TWITTER['SECRET'], $_SESSION["oauth_token"], $_SESSION["oauth_token_secret"]);
			$accessToken = $tOAuth->getAccessToken();
			if($accessToken['oauth_token']!=null&&$accessToken['oauth_token_secret']!=null){
				$access_token = urlencode64(serialize($accessToken));
				if($this->save_access_token($access_token)){
					
					return $this->View->showMessage("Congratulations, your twitter account is now connected with SMAC !","index.php".$_SESSION['curr_req']);
				}else{
					return $this->View->showMessage("Twitter authorization is failed, please try again !","index.php");
				}
			}else{
				return $this->View->showMessage("Twitter authorization is failed, please try again !","index.php");
			}
		}
	}
	function test(){
		//$rs = $this->tweet("hello world !");
	}
	function tweet(){
		$ajax = $this->Request->getParam('ajax');
		$str = mysql_escape_string($this->Request->getParam('status'));
		if($ajax==1){
			$access_token = $this->get_access_token();
			$tOAuth = new TwitterOAuth($TWITTER['KEY'],$TWITTER['SECRET'], $access_token["oauth_token"], $access_token["oauth_token_secret"]);
			$uri='statuses/update';
			$params = array('status'=>$str);
			$rs = $tOAuth->post($uri,$params);
			if($rs->error){
				$status = 0;
			}else{
				$status=1;
			}
			print json_encode(array("status"=>$status));
		}
		die();
	}
	function revoke(){
		$account_id = $this->user->account_id;
		$this->open(0);
		$sql = "DELETE FROM smac_web.smac_twitter WHERE account_id=".$account_id."";
		$this->query($sql);
		$this->close();
		return $this->View->showMessage("The access permission for SMAC has been revoked successfully !","index.php");
	}
	function get_screen_name(){
		$access_token = $this->get_access_token();
		$tOAuth = new TwitterOAuth($TWITTER['KEY'],$TWITTER['SECRET'], $access_token["oauth_token"], $access_token["oauth_token_secret"]);
		$credentials = $tOAuth->get('account/verify_credentials');
	  	return $credentials->screen_name;
	}
	function get_access_token(){
		$account_id = $this->user->account_id;
		$sql = "SELECT access_token FROM smac_web.smac_twitter WHERE account_id=".$account_id." LIMIT 1";
		
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		$access_token = unserialize(urldecode64($rs['access_token']));
		return $access_token;
	}
	function is_authorized(){
		if(is_array($this->get_access_token())){
			return true;
		}
	}
	function save_access_token($access_token){
		$account_id = $this->user->account_id;
		
		
		$this->open(0);
		$sql = "DELETE FROM smac_web.smac_twitter WHERE account_id=".$account_id."";
		$this->query($sql);
		
		$sql = "INSERT INTO smac_web.smac_twitter(account_id,access_token,submit_date)
				VALUES(".$account_id.",'".mysql_escape_string($access_token)."',NOW())";
		$q = $this->query($sql);
		
		$this->close();
		return $q;
	}
	function authorize(){
		global $TWITTER;
		$tOAuth = new TwitterOAuth($TWITTER['KEY'],$TWITTER['SECRET']);
		// Generate request tokens
		$requestToken = $tOAuth->getRequestToken();
		$_SESSION["oauth_token"] = $requestToken["oauth_token"];
		$_SESSION["oauth_token_secret"] = $requestToken["oauth_token_secret"];
		$auth_url =  $tOAuth->getAuthorizeURL($requestToken["oauth_token"]);
		sendRedirect($auth_url);
		die();
	}
}
?>