<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Mailer.php";
class login extends App{
	
	var $Request;
	
	var $View;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	
	function home(){
		$this->clear_cache();
		if(strlen($this->Request->getParam("subdomain"))>0
					&&strtolower($this->Request->getParam("subdomain"))!="www"
					&&strtolower($this->Request->getParam("subdomain"))!="smacapp.com"){
			$this->assign('subdomain',$this->Request->getParam('subdomain'));
			return $this->View->toString(APPLICATION.'/login.html');
		}else{
			return $this->View->toString(APPLICATION.'/login-default.html');
		}
	
	}
	function reset_password(){
		global $APP_PATH,$ENGINE_PATH;
		if($this->Request->getPost("email")){
			if($check = $this->validate_email($this->Request->getPost("email"))){
				if($this->reset_password_input_email($check)){
					$this->View->assign("msg",text('password_reset_request_sent'));
					$this->View->assign("msg2",text('password_reset_check_email'));
				}else{
					$this->View->assign("msg",text('password_reset_request_error'));
				}
			}else{
				$this->View->assign("msg",text('password_reset_invalid_email'));
				$this->View->assign("show_form","1");
			}
		}else{
			$this->View->assign("msg",
								text('password_reset_brief'));
			$this->View->assign("show_form","1");
		}
		return $this->View->toString(APPLICATION.'/reset_password.html');
	}
	function validate_email($email){
		$email = cleanXSS(cleanString($email));
		$this->open(0);
		$sql = "SELECT id,email,first_name,last_name 
				FROM smac_web.smac_user 
				WHERE email='{$email}' LIMIT 1";
		$rs = $this->fetch($sql);
		$r = false;
		if($rs['id']!=null&&strcmp($email,$rs['email'])==0){
			$r = true;
		}
		$this->close();
		if($r){
			return $rs;
		}
	}
	function reset_password_input_email($info){
		global $CONFIG;
		
		$user_id = $info['id'];
		$name = $info['first_name']." ".$info['last_name'];
		$email = $info['email'];
		$confirmation_hash = sha1($user_id.$email.rand(100000,9999999));
		$request_time = date("Y-m-d");
		$valid_until = date("Y-m-d",time()+24*60*60);
		$request_hash = array("user_id"=>$user_id,"email"=>$email,"confirmation_hash"=>$confirmation_hash);
		
		$arr = array('page' => 'login','act'=>"reset_password_confirm","reset_code"=>urlencode64(serialize($request_hash)));
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$request_link = $CONFIG['BASEURL'].$link;
	
		$sql = "
				INSERT INTO `smac_web`.`reset_password` 
					(
					`request_time`, 
					`user_id`, 
					`confirmation_hash`, 
					`email`, 
					`n_status`, 
					`valid_until`
					)
					VALUES
					(
					'{$request_time}', 
					{$user_id}, 
					'{$confirmation_hash}', 
					'{$email}', 
					0, 
					'{$valid_until}'
					);";
		$this->open(0);
		$q = $this->query($sql);
		$this->close();
		if($q){
			//send email
			$this->View->assign('baseurl',$CONFIG['BASEURL']);
			$this->View->assign('name',$name);
			$this->View->assign('link',$request_link);
			$this->View->assign('email',$email);
			$this->View->assign('sublink',"http://www.smacapp.com");
			$msg = $this->View->toString(APPLICATION.'/email/email-reset-password.html');
			
			$this->View->assign('baseurl',$CONFIG['BASEURL']);
			$this->View->assign('name',$name);
			$this->View->assign('link',$request_link);
			$this->View->assign('email',$email);
			$this->View->assign('sublink',"http://www.smacapp.com");
			$plain = $this->View->toString(APPLICATION.'/email/email-reset-password-plain.html');
			
			
			$mail = new Mailer();
			$mail->use_postmark(true);
			return $mail->send(array(
				'to'=>array('email'=>$email,
							 'name'=>$name),
				'subject'=>"SMAC - Reset Password Confirmation",
				'plainText'=>$plain,
				'htmlText'=>$msg
			));
			
		}
		return $q;
	}
	
	function reset_password_confirm(){
		$reset_code = $this->Request->getParam("reset_code");
		$chunk = unserialize(urldecode64($reset_code));
		if($this->validate_confirmation($chunk)){
			$new_password = $this->reset_new_password();
			$q = $this->reset_user_password($chunk['user_id'],$new_password);
			if($q){
				$this->assign("msg",text('password_reset_success'));
				$this->assign("msg2",text('password_reset_success_detail'));
				$this->View->assign("confirm","1");
				//disabled all password reset request for the current user.
				$sql = "UPDATE smac_web.reset_password SET n_status=1 WHERE user_id={$id}";
				$this->open(0);
				$q = $this->query($sql);
				$this->close();
			}else{
				$this->assign("msg",text('password_reset_error'));
			}
		}else{
			$this->assign("msg",text('password_reset_error'));
		}
		return $this->View->toString(APPLICATION.'/reset_password.html');
	}
	private function reset_user_password($id,$newpass){
		$this->open(0);
		
		$sql = "SELECT * FROM smac_web.smac_user WHERE id={$id} LIMIT 1";
		$up = $this->fetch($sql);
		
		$password = sha1($up['email'].$newpass.$up['secret']);
		
		$sql2 = "UPDATE smac_web.smac_user SET password='".$password."' WHERE email='".$up['email']."';";
		$q = $this->query($sql2);
		
		$this->close();
		
		if($q){
			//send email
			$this->View->assign('baseurl',$CONFIG['BASEURL']);
			$this->View->assign('name',$up['first_name']." ".$up['last_name']);
			$this->View->assign('password',$newpass);
			$this->View->assign('email',$up['email']);
			$this->View->assign('sublink',"http://www.smacapp.com");
			$msg = $this->View->toString(APPLICATION.'/email/email-new-password.html');
			
			$this->View->assign('baseurl',$CONFIG['BASEURL']);
			$this->View->assign('name',$up['first_name']." ".$up['last_name']);
			$this->View->assign('password',$newpass);
			$this->View->assign('email',$up['email']);
			$this->View->assign('sublink',"http://www.smacapp.com");
			$plain = $this->View->toString(APPLICATION.'/email/email-new-password-plain.html');
	
			
			$mail = new Mailer();
			$mail->use_postmark(true);
			return $mail->send(array(
				'to'=>array('email'=>$up['email'],
							 'name'=>$up['first_name']." ".$up['last_name']),
				'subject'=>"SMAC - Your password has been changed",
				'plainText'=>$plain,
				'htmlText'=>$msg
			));
			
		}
		
		
		return $q;
	}
	private function reset_new_password(){
		$a = array('A','B','C','D','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
		$b = array('a','b','c','d','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9');
		$new_pass = $a[rand(0,34)].$b[rand(0,34)].
					$a[rand(0,34)].$b[rand(0,34)].$a[rand(0,34)].
					$a[rand(0,34)].$a[rand(0,34)].
					$b[rand(0,34)].$b[rand(0,34)].$a[rand(0,34)].
					$a[rand(0,34)].$a[rand(0,34)];
		return $new_pass;
	}
	function validate_confirmation($info){
		$ok = false;
		if($info['user_id']!=null&&strlen($info['email'])>0 && strlen($info['confirmation_hash'])>30){
			$this->open(0);
			$sql = "SELECT * FROM smac_web.reset_password WHERE user_id = {$info['user_id']} AND email = '{$info['email']}'
					AND confirmation_hash = '{$info['confirmation_hash']}' AND n_status=0 LIMIT 1";
			$rs = $this->fetch($sql);
			
			if($rs['email']==$info['email']&&$rs['user_id']==$info['user_id']&&$rs['confirmation_hash']==$info['confirmation_hash']){
				$now = time();
				$valid_until = strtotime($rs['valid_until']);
				if($valid_until>$now){
					$ok = true;
				}
			}
			$this->close();
		}
		return $ok;
	}
}
?>