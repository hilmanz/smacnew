<?php
global $APP_PATH,$ENGINE_PATH;

class activation extends App{
	
	function home(){
		$code = $this->Request->getParam('code');
		$email = mysql_escape_string(strtolower(urldecode($this->Request->getParam('email'))));
		$id = intval($this->Request->getParam('id'));
		$type = $this->Request->getParam('type');
		
		$err = text('activation_syserror');
		$url = "index.php";
		
		if($type == 'account'){
			
			$this->open(0);
		
			$sql = "SELECT 
						a.*,
						u.secret,
						s.subdomain 
					FROM 
						smac_web.smac_account a 
						LEFT JOIN smac_web.smac_user u 
						ON a.email=u.email AND a.id=u.account_id
						LEFT JOIN smac_web.smac_subdomain s
						ON a.id=s.account_id 
					WHERE 
						a.id='$id' AND a.email='$email' LIMIT 1;";
			
			$rs = $this->fetch($sql);
			
			if($code == md5($rs['activation_code']) && intval($rs['n_status']) == 0){
				
				//password default
				$_password = floor(rand(10000, 99999));
				$password = sha1($rs['email'].$_password.$rs['secret']);
				
				$sql = "UPDATE smac_web.smac_account SET n_status='1' WHERE id='$id' AND email='$email' AND activation_code='".$rs['activation_code']."';";
				$sql2 = "UPDATE smac_web.smac_user SET n_status='1', password='".$password."' WHERE account_id='$id' AND email='$email';";
				
				if($this->query($sql) && $this->query($sql2)){
					
					global $CONFIG;
					//EMAIL TO ACCOUNT
					if($CONFIG['EMAIL_PROSES']){
						
						//link login
						$arr = array('subdomain' => $rs['subdomain']);
						$link = $CONFIG['BASEURL'].'index.php?'.$this->Request->encrypt_params($arr);
						$sublink = $rs['subdomain'].'.'.$CONFIG['BASEURL_NO_HTTP'];
						
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$rs['name']);
						$this->View->assign('link',$sublink);
						$this->View->assign('email',$rs['email']);
						$this->View->assign('password',$_password);
						$this->View->assign('sublink',$sublink);
						$msg = $this->View->toString(APPLICATION.'/email/email-confirm.html');
						
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$rs['name']);
						$this->View->assign('link',$sublink);
						$this->View->assign('email',$rs['email']);
						$this->View->assign('password',$_password);
						$this->View->assign('sublink',$sublink);
						$plain = $this->View->toString(APPLICATION.'/email/email-confirm-plain.html');
						
						
						$mail = new Mailer();
						$mail->use_postmark(true);
						$mail->send(array(
							'to'=>array('email'=>$rs['email'],
										 'name'=>$rs['name']),
							'subject'=>"SMAC - Account Activation",
							'plainText'=>$plain,
							'htmlText'=>$msg
						));
						
					}
					
					//Save email data
					$sql = "INSERT INTO smac_web.smac_email_copy (account_id,register_email,date,user_id,agency_id) VALUES (".$id.",'".htmlspecialchars($msg,ENT_QUOTES)."',NOW(),0,0);";
					$this->query($sql);
					
					$err = text('activation_success');
				}
			}
			$url = 'https://'.$sublink;
			$this->close();	
			
		}else if($type == 'agency'){
			
			$this->open(0);
		
			$sql = "SELECT 
						a.*
					FROM 
						smac_web.smac_agency a
					WHERE 
						a.id='$id' AND a.agency_email='$email' LIMIT 1;";
			
			$rs = $this->fetch($sql);
			
			if($code == md5($rs['activation_code']) && intval($rs['n_status']) == 0){
				
				//password default
				$_password = floor(rand(10000, 99999));
				$password = sha1($rs['agency_email'].$_password.$rs['secret']);
				
				$sql = "UPDATE smac_web.smac_agency SET n_status='1', password='$password' WHERE id='$id' AND agency_email='$email' AND activation_code='".$rs['activation_code']."';";
				
				if($this->query($sql)){
					
					global $CONFIG;
					//EMAIL TO ACCOUNT
					if($CONFIG['EMAIL_PROSES']){
						
						$link = $CONFIG['BASEURL'];
						
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$rs['name']);
						$this->View->assign('link',$link);
						$this->View->assign('email',$rs['agency_email']);
						$this->View->assign('password',$_password);
						$this->View->assign('sublink',$link);
						$msg = $this->View->toString(APPLICATION.'/email/email-confirm.html');
						
						$mail = new Mailer();
						$mail->setSubject("SMAC - Account Activation");
						$mail->setSender("info@smac.me");
						$mail->setMessage($msg);
						$mail->setRecipient($rs['agency_email']);
						$mail->send();
						
					}
					
					//Save email data
					$sql = "INSERT INTO smac_web.smac_email_copy (agency_id,register_email,date,account_id,user_id) VALUES (".$id.",'".htmlspecialchars($msg,ENT_QUOTES)."',NOW(),0,0);";
					$this->query($sql);
					
					$err = text('activation_success');
				}
			}
			$url = 'https://'.$sublink;
			$this->close();	
			
		}else if($type == 'user'){
			
			$this->open(0);
		
			$sql = "SELECT 
						u.*,
						s.subdomain 
					FROM 
						smac_web.smac_user u 
						LEFT JOIN smac_web.smac_subdomain s
						ON u.account_id=s.account_id 
					WHERE 
						u.id='$id' AND u.email='$email' LIMIT 1;";
			
			$rs = $this->fetch($sql);
			
			if($code == md5($rs['activation_code']) && intval($rs['n_status']) == 0){
				
				//password default
				$_password = floor(rand(10000, 99999));
				$password = sha1($rs['email'].$_password.$rs['secret']);
				
				//echo $rs['email'].' - '.$_password.' - '.$rs['secret'];
				//exit;
				
				$sql = "UPDATE smac_web.smac_user SET n_status='1', password='".$password."' WHERE id='$id' AND email='$email';";
				
				//echo 'test: '.$sql;
				//exit;
				
				if($this->query($sql)){
					
					global $CONFIG;
					//EMAIL TO ACCOUNT
					if($CONFIG['EMAIL_PROSES']){
						
						//link login
						$arr = array('subdomain' => $rs['subdomain']);
						$link = $CONFIG['BASEURL'].'index.php?'.$this->Request->encrypt_params($arr);
						$sublink = $rs['subdomain'].'.'.$CONFIG['BASEURL_NO_HTTP'];
						
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$rs['first_name'].' '.$rs['last_name']);
						$this->View->assign('link',$sublink);
						$this->View->assign('email',$rs['email']);
						$this->View->assign('password',$_password);
						$this->View->assign('sublink',$sublink);
						$msg = $this->View->toString(APPLICATION.'/email/email-confirm.html');
						
						$mail = new Mailer();
						$mail->setSubject("SMAC - Account Activation");
						$mail->setSender("info@smac.me");
						$mail->setMessage($msg);
						$mail->setRecipient($rs['email']);
						$mail->send();
						
					}
					
					//Save email data
					$sql = "INSERT INTO smac_web.smac_email_copy (user_id,register_email,date,account_id,agency_id) VALUES (".$id.",'".htmlspecialchars($msg,ENT_QUOTES)."',NOW(),0,0);";
					$this->query($sql);
					
					$err = text('activation_success');
				}
			}
			
			//echo $sql.'<br />'.mysql_error();exit;
			$url = 'https://'.$sublink;
			$this->close();	
			
		}
		
		$this->View->assign('err',$err);
		$this->View->assign('url',$url);
		
		return $this->View->toString(APPLICATION.'/message-activation.html');
	
	}
	
}
?>