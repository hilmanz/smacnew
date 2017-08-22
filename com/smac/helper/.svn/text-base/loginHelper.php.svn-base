<?php
include_once "SessionHelper.php";
include_once "apiHelper.php";
class loginHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $session;
	var $login = false;
	var $api;

	function __construct($req=null){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->session = new SessionHelper(APPLICATION.'_Session');
		$this->api = new apiHelper($req);
		if($this->session->get('user') ){
			$this->login = true;
			$user = json_decode(urldecode64($this->session->get('user')));
			if($this->Request->getParam("subdomain")!='www'){
				if($this->Request->getParam("subdomain")!=null&&$this->Request->getParam("subdomain")!=$user->subdomain){
					$this->login = false;
				}
			}
		}
	}
	
	function checkLogin(){
		if($this->login){
			return true;
		}else{
			if(intval($_POST['login'])){
				return $this->goLogin();
			}else{
				return false;
			}
		}
	}
	
	function loginSession(){
		global $logger;
		
		$ok = false;
		if($_POST['login']){
			if($this->goLogin()){
				$params = array("subdomain"=>$_REQUEST['subdomain'],"page"=>"overview");
				sendRedirect("index.php?".$this->Request->encrypt_params($params));
				die();
			}
			if(!$ok){
				$this->assign("login_error","1");
			}
		}
		$this->assign("subdomain",mysql_escape_string(urlencode($this->_request('subdomain'))));
		$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
		return $this->out(APPLICATION . '/login.html');
	}
	
	function goLogin(){
		global $logger;
		$username = mysql_escape_string(trim(strtolower($this->Request->getPost('username'))));
		$password = mysql_escape_string(trim($this->Request->getPost('password')));
		$subdomain = mysql_escape_string($this->Request->getPost('subdomain'));
		$response = $this->api->goLogin($username,$password,$subdomain);
		
		if(!$response){	
			return false;
		}
		if($response->status==1){
			$sql = "SELECT u.id,u.account_id,u.email,u.first_name,u.last_name,u.n_status,u.user_role,s.subdomain,u.show_tips
					FROM smac_web.smac_user u LEFT JOIN smac_web.smac_subdomain s ON u.account_id=s.account_id 
					WHERE u.email='$username' AND s.subdomain='$subdomain' LIMIT 1;";
			$this->open(0);
			$rs = $this->fetch($sql);
			//get the account type
			$sql = "SELECT account_type FROM smac_web.smac_account WHERE id={$rs['account_id']} LIMIT 1";
			$ac = $this->fetch($sql);
			$rs['account_type'] = $ac['account_type'];
			$this->close();
			if($response->data->user_id==$rs['id']){
				$rs['username']= $username;
				$rs['user_id'] = $rs['id'];
				$rs['subdomain'] = $subdomain;
				
				$this->session->set('user',urlencode64(json_encode($rs)));
				$_SESSION['sesstoken'] = $response->data->session_token;
				
				if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
			    {
			      $ip=$_SERVER['HTTP_CLIENT_IP'];
			    }
			    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
			    {
			      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			    }
			    else
			    {
			      $ip=$_SERVER['REMOTE_ADDR'];
			    }
			    $username = mysql_escape_string($username);
				$logger->info("[".$ip."][LOGIN] {$username} has login");
				
				$params = array("subdomain"=>$_REQUEST['subdomain'],"page"=>"overview");
				sendRedirect("index.php?".$this->Request->encrypt_params($params));
				die();
			}
		}
		return false;	
	}
	function getProfile(){
		$user = json_decode(urldecode64($this->session->get('user')));
		return $user;
	}
}
?>