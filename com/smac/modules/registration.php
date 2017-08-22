<?php
global $APP_PATH,$ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Mailer.php";

class registration extends App{
	function home(){
		global $logger;
		global $CONFIG;
		$logger->info("Entering Registration Page");
		$this->open(0);
		$sql = "SELECT * FROM country 
				ORDER BY country_name;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('country',$rs);
		
		$sql = "SELECT * FROM state 
				WHERE country_id='".$rs[0]['country_id']."' 
				ORDER BY state_name;";
		$r = $this->fetch($sql,1);
		$this->View->assign('state',$r);
		
		$sql = "SELECT * FROM city 
				WHERE country_id='".$rs[0]['country_id']."' 
				AND state_id=".$r[0]['state_id']." 
				ORDER BY city_name;";	
				
		$r = $this->fetch($sql,1);
		$this->View->assign('city',$r);
		$add = intval($_POST['add']);
		
		if($add == 1){
			$logger->info(json_encode($_POST));
			$err = "The following error(s) has occured :";
		
			$_agencyName = $_POST['agency_name'];
			$_companyName = $_POST['company_name'];
			$_contactPerson = $_POST['contact_person'];
			
			$_lang = $_POST['lang'];
			$_langOther = $_POST['lang_other'];
			
			if(strtolower($_lang) == 'other'){
				$_lang = $_langOther;
			}
			
			$_agree = intval($_POST['aggrement']);
			
			$_name = $_POST['name'];
			$_contact = $_POST['contact'];
			$_address = $_POST['address'];
			$_city = $_POST['city'];
			$_cityOther = $_POST['city_other'];
			$_state = $_POST['state'];
			$_country = $_POST['country'];
			$_about = $_POST['about'];
			$_why = $_POST['why'];
			$_email = $_POST['email'];
			$_type = intval($_POST['type']);
			$_subdomain = strtolower($_POST['subdomain']);

			if( $_contact == '' || $_country == '' || $_email == '' || $_agree != 1){
				$err = text("registration_form_incomplete");
			}else{
			
				//validation
				$valid = true;
				$validErr = array();
				
				if(strlen($_name) < 2){
					array_push($validErr, text("registration_validation_name_error"));
					$valid = false;
				}
				if( !is_numeric($_contact) ){
					array_push($validErr, text("registration_validation_phone_error"));
					$valid = false;
				}
				if( !$this->is_valid_email($_email) ){
					array_push($validErr, text('registration_validation_email_error'));

					$valid = false;
				}
				
				if($_agencyName != ''){
					if(strlen($_name) < 2){
						array_push($validErr, text('registration_validation_agency_error'));
						$valid = false;
					}
					if( !is_numeric($_contactPerson) ){
						array_push($validErr, text("registration_validation_phone_error"));
						$valid = false;
					}
				}
				
				//VALIDATION EMAIL TO DB (cari di table smac_registration,smac_agency & smac_user adakah yang sama?)
				$sql = "SELECT a.email FROM
						(
						SELECT r.agency_email AS email FROM smac_web.smac_registration r WHERE n_status IN ('0','1') 
						UNION
						SELECT agency_email AS email FROM smac_web.smac_agency 
						UNION
						SELECT email FROM smac_web.smac_user
						) a
						WHERE
						a.email='".mysql_escape_string($_email)."';";
				
				$rs = $this->fetch($sql);
				
				if($rs['email'] != ''){
					array_push($validErr, text('registration_email_exists_error'));
					$valid = false;
				}
				
				//Cek subdomain
				if($_subdomain != ''){
					if( strlen($_subdomain) > 30){
						array_push($validErr, text('registration_subdomain_error'));
						$valid = false;
					}
					if( ! preg_match('/^[a-z0-9_]+$/', strtolower($_subdomain)) ){
						array_push($validErr, text('registration_subdomain_format_error'));
						$valid = false;
					}
					
					$sql = "SELECT COUNT(*) AS total FROM smac_web.smac_subdomain WHERE subdomain LIKE '%".mysql_escape_string($_subdomain)."%';";
					$sub = $this->fetch($sql);
					
					if( intval($sub['total']) > 0){
						array_push($validErr, text('registration_subdomain_available'));
						$valid = false;
					}
					
				}
				
				if($valid){
					$logger->info("Passed Argument Validations");
					$qry = "INSERT INTO smac_web.smac_registration				
								(name,
								company_name,
								contact_no,
								address,
								city,
								state,
								country,
								about_company,
								usage_reason,
								n_status,
								register_date,
								agency_email,
								registration_type,
								city_other,
								agency_name,
								agency_contact_no,
								lang,
								subdomain)
								VALUES
								('".mysql_escape_string($_name)."','".
									mysql_escape_string($_companyName)."','".
									mysql_escape_string($_contact)."','".
									mysql_escape_string($_address)."','".
									mysql_escape_string($_city)."','".
									mysql_escape_string($_state)."','".
									mysql_escape_string($_country)."','".
									mysql_escape_string($_about)."','".
									mysql_escape_string($_why)."',
									0,
									NOW(),'".
									mysql_escape_string($_email)."',
									'$_type',
									'".mysql_escape_string($_cityOther)."','".
									mysql_escape_string($_agencyName)."','".
									mysql_escape_string($_contactPerson)."','".
									mysql_escape_string($_lang)."','".
									mysql_escape_string($_subdomain)."'
									);";
				
				
					if( $this->query($qry) ){
						$logger->info("inserted into registration");
						if($CONFIG['EMAIL_PROSES']){
							$this->View->assign('baseurl',$CONFIG['BASEURL']);
							$msg = $this->View->toString(APPLICATION.'/email/email-welcome.html');
							$this->View->assign('baseurl',$CONFIG['BASEURL']);
							$plain = $this->View->toString(APPLICATION.'/email/email-welcome-plain.html');

							$mail = new Mailer();
							$mail->use_postmark(true);
							$mail->send(array(
								'to'=>array('email'=>$_email,
											 'name'=>$_name),
								'subject'=>"Welcome to Social Media Action Center (SMAC)",
								'plainText'=>$plain,
								'htmlText'=>$msg
							));
							
							//send to support
							$mail2 = new Mailer();
							$mail2->setSubject("New Trial Request - ".strtoupper($_name));
							$mail2->setSender("info@smac.me");
							$this->View->assign('baseurl',$CONFIG['BASEURL']);
							$this->View->assign('name',$_name);
							$this->View->assign('email',$_email);
							$this->View->assign('telp',$_contact);
							$this->View->assign('agency',$_agencyName);
							$msg = $this->View->toString(APPLICATION.'/email/email-support-notif.html');
							$mail2->setMessage($msg);
							$mail2->setRecipient($CONFIG['EMAIL_SUPPORT']);
							$mail2->send();
							$logger->info("sending welcome email");
						}else{
							$logger->info("not sending welcome email because the flag is off");
						}
						return $this->View->showMessage(text('registration_success'),'index.php','smac/web');
				
					}else{
						
						$err = text('registration_error');
				
					}
				}
			
			}
			
			$this->View->assign('agencyName',$_agencyName);
						$this->View->assign('companyName',$_companyName);
						$this->View->assign('contactPerson',$_contactPerson);
						$this->View->assign('lang',$_lang);
						$this->View->assign('langOther',$_langOther);
						
						$this->View->assign('name',$_name);
						$this->View->assign('contact',$_contact);
						$this->View->assign('address',$_address);
						$this->View->assign('city2',$_city);
						$this->View->assign('cityOther',$_cityOther);
						$this->View->assign('state2',$_state);
						$this->View->assign('country2',$_country);
						$this->View->assign('about',$_about);
						$this->View->assign('why',$_why);
						$this->View->assign('email',$_email);
						$this->View->assign('type',$_type);
						$this->View->assign('subdomain',$_subdomain);
		
		}
		
		$this->close();
		
		$this->View->assign('err',$err);
		
		$this->View->assign('validErr',$validErr);
		
		//link live track
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'term-of-use');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urltermofuse',$link);
		
		return $this->View->toString(APPLICATION.'/new_register.html');
		
	}
	
	function agency(){
		
		$add = intval($_POST['add']);
		
		if($add == 1){
			
			$err = "Sorry, some error(s) occur.";
		
			$_agencyName = $_POST['agency_name'];
			$_companyName = $_POST['company_name'];
			$_contact = $_POST['contact'];
			$_address = $_POST['address'];
			$_city = $_POST['city'];
			$_state = $_POST['state'];
			$_country = $_POST['country'];
			$_about = $_POST['about'];
			$_why = $_POST['why'];
			$_email = $_POST['email'];
			
			
			
			if( $_agencyName == '' || $_contact == '' || $_country == '' || $_email == ''){
			
				$err = text('registration_form_incomplete');
			
			}else{
			
				//validation
				$valid = true;
				$validErr = array();
				
				if(strlen($_agencyName) < 2){
					array_push($validErr, text('registration_validation_name_error'));
					$valid = false;
				}	
				if( !is_numeric($_contact) ){
					array_push($validErr, text('registration_validation_phone_error'));
					$valid = false;
				}
				if( !$this->is_valid_email($_email) ){
					array_push($validErr, text('registration_validation_email_error'));
					$valid = false;
				}
				
				if($valid){
				
					$num1 = rand(10000, 99999);
					$num2 = rand(10000, 99999);
					$num3 = rand(10000, 99999);
					$num4 = rand(10000, 99999);
					$secret = $num1 . $num2 . $num3 . $num4;
				
					$qry = "INSERT INTO smac_web.smac_agency				
					(agency_name,company_name,contact_no,address,city,state,country,about_company,usage_reason,n_status,register_date,agency_no,agency_email)
								VALUES
								('".mysql_escape_string($_agencyName)."','".mysql_escape_string($_companyName)."','".mysql_escape_string($_contact)."','".mysql_escape_string($_address)."','".mysql_escape_string($_city)."','".mysql_escape_string($_state)."','".mysql_escape_string($_country)."','".mysql_escape_string($_about)."','".mysql_escape_string($_why)."',0,NOW(),'$secret','".mysql_escape_string($_email)."');";
				
					$this->open(0);
				
					if( $this->query($qry) ){
						
						$last_id = mysql_insert_id();
						
						//insert nama subdomain
						$sub = ereg_replace("[^A-Za-z0-9 ]", "", $_agencyName); 
						$sub = str_replace(' ','-',strtolower($sub));
						
						$qry = "INSERT INTO smac_web.smac_subdomain (agency_id,subdomain) VALUES ('$last_id','$sub')";
						
						$this->query($qry);
					
						global $CONFIG;
					
						if($CONFIG['EMAIL_PROSES']){
					
							$msg = "<p>New Agency Registered</p>
												Agency Name:  $_agencyName<br />
												Company Name:  $_companyName<br />
												Contact No:  $_contact<br />
												Address:  $_address<br />
												City:  $_city<br />
												State:  $_state<br />
												Country:  $_country<br />
												About Company:  $_about<br />
												Usage Reason:  $_why<br />
												";
						
							$mail = new Mailer();
							$mail->setSubject("New Agency Registered");
							$mail->setSender("info@smac.me");
							$mail->setMessage($msg);
						
							$mail->setRecipient($CONFIG['EMAIL_AGENCY']);
							$mail->send();
					
						}else{
						
							$msg = "<p>New Agency Registered</p>
												Agency Name:  $_agencyName<br />
												Company Name:  $_companyName<br />
												Contact No:  $_contact<br />
												Address:  $_address<br />
												City:  $_city<br />
												State:  $_state<br />
												Country:  $_country<br />
												About Company:  $_about<br />
												Usage Reason:  $_why<br />
												";
											
							//echo $msg;exit;
						
						}
					
						return $this->View->showMessage(text('registration_success'),'index.php');
					
						//sendRedirect('index.php');
						//exit;
				
					}else{
	//					//print mysql_error();
						$err = text('registration_error');
				
					}
				}
			
			}
		
		}
		
		$this->close();
		
		$this->View->assign('err',$err);
		
		$this->View->assign('validErr',$validErr);
		
		return $this->View->toString(APPLICATION.'/registration-agency.html');
		
	}
	
	function account(){
	
		if($this->Request->getParam('s') == '23'){
		
			$subdomain = $this->Request->getParam('subdomain');
			$agencyid = intval($this->Request->getParam('id'));
			$add = intval($this->Request->getParam('add'));
			$_agency = intval($this->Request->getParam('agencyid'));
			
			//CEK AGENCY
			$this->open(0);
			$sql = "SELECT n_status FROM smac_web.smac_agency a 
					LEFT JOIN smac_web.smac_subdomain s ON a.id=s.agency_id 
					WHERE a.id=$agencyid AND s.subdomain='$subdomain' 
					LIMIT 1;";
			$rs = $this->fetch($sql);
			$this->close();
			
			if( intval($rs['n_status']) == 1){
		
				if(  $add == 1 && $_agency > 0){
		
					$_name = $_POST['name'];
					$_email = $_POST['email'];
					$_password = $_POST['password'];
					$_contact = $_POST['contact'];
					$_address = $_POST['address'];
					$_city = $_POST['city'];
					$_state = $_POST['state'];
					$_country = $_POST['country'];
					$_referred = $_POST['referred'];
					$_coupon = $_POST['coupon'];
			
					if( $_name == '' || $_email == '' || $_contact == '' || $_country == ''){
			
						$err = text('registration_form_incomplete');
				
					}else{
				
						$num1 = rand(10000, 99999);
						$num2 = rand(10000, 99999);
						$num3 = rand(10000, 99999);
						$num4 = rand(10000, 99999);
						$secret = $num1 . $num2 . $num3 . $num4;
				
						$password = sha1($_email,$_password,$secret);
			
						$sql = "insert  into smac_web.smac_account
									(`agency_id`,`name`,`email`,`password`,`contact_no`,`address`,`city`,`state`,`country`,`referred_by`,`coupon_code`,`n_status`,`register_date`,`secret`) 
									values 
									($_agency,'$_name','$_email','$password','$_contact','$_address','$_city','$_state','$_country','$_referred','$_coupon',0,NOW(),'$secret');";
				
						$this->open(0);
				
						if( $this->query($sql) ){
				
							$last_id = mysql_insert_id();
					
							global $CONFIG;
					
							if($CONFIG['EMAIL_PROSES']){
					
								$link = $CONFIG['BASEURL']."index.php?page=registration&act=accountconfirm&id=$last_id&s=$secret";
						
								$msg = "<p>
													Thank's for register to SMAC,<br />
													Your password : $_password<br />
													Confirmation Link:<br />
													<a href='$link'>$link</a>
												</p>";
						
								$mail = new Mailer();
								$mail->setSubject("New Account Registered");
								$mail->setSender("info@smac.me");
								$mail->setMessage($msg);
						
								$mail->setRecipient($_email);
								$mail->send();
					
							}
					
							sendRedirect('index.php');
							exit;
				
						}else{
				
							$err = text('registration_error');
				
						}
				
					}
			
				}
			}else{
			
				return $this->View->showMessage(text('agency_account_inactive'),'index.php');
			
			}
		
			$this->close();
			
			$this->View->assign('subdomain',$subdomain);
	
			$this->View->assign('agencyid',$agencyid);
		
			$this->View->assign('err',$err);
		
			return $this->View->toString(APPLICATION.'/registration-account.html');
		
		}else{
		
			sendRedirect('index.php');
			exit;
		
		}
		
	}
	
	function accountconfirm(){
		
		$id = intval($_GET['id']);
		
		$s = $_GET['s'];
		
		$confirm = intval($_POST['confirm']);
		
		if( $confirm == 1 ){
		
			$id = intval($_POST['id']);
			$s = $_POST['s'];
			$_password = $_POST['password'];
			
			$this->open(0);
			$sql = "SELECT * FROM smac_web.smac_account WHERE id=$id AND secret='$s' LIMIT 1;";
			$rs = $this->fetch($sql);
			
			$password = sha1($rs['email'],$_password,$s);
			
			if($password == $rs['password']){
			
				$sql = "UPDATE smac_web.smac_account SET n_status='1' WHERE id=$id;";
				
				if( $this->query($sql) ){
					
					$err = text('account_confirmation_ok');
					
				}else{
					
					$err = text('account_confirmation_error');
					
				}
			
			}else{
			
				$err = text('account_confirmation_error');
			
			}
			
			$this->close();
			
		}
		
		$this->View->assign('id',$id);
		
		$this->View->assign('s',$s);
		
		$this->View->assign('err',$err);
		
		return $this->View->toString(APPLICATION.'/registration-account-confirm.html');
		
	}
	
	function user(){
	
		$this->assign('sidebar', $this->sidebarHelper->show() );
		
		$arr = array('subdomain' => $this->Request->getParam('subdomain'),'page' => 'registration','act' => 'user');
		$link2 = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('url',$link2);
		
		if(  intval($_POST['add']) == 1 ){
		
			//CHECK ACCOUNT IS ADMIN
			if(intval($this->user->user_role) == 1){
				
				$err = "Failed create user!";
		
				$_fname = $_POST['fname'];
				$_lname = $_POST['lname'];
				$_email = $_POST['email'];
				//$_password = $_POST['password'];
				$_password = 123; //set acak
				$_title = $_POST['title'];
				$_contact = $_POST['contact'];
				$_bb = $_POST['bb'];
			
				if( $_fname == '' || $_lname == '' || $_password == '' || $_email == '' || $_contact == ''){
			
					$err = text('registration_form_incomplete');
				
				}else{
					
					//validation
					$valid = true;
					$validErr = array();
					
					if( !$this->is_valid_email($_email) ){
						array_push($validErr, text('registration_validation_email_error'));
						$valid = false;
					}
					
					if( !$this->is_email_available($_email)){
						array_push($validErr, text('registration_email_exists_error'));
						$valid = false;
					}
					
					if($valid){
				
						$num1 = rand(10000, 99999);
						$num2 = rand(10000, 99999);
						$num3 = rand(10000, 99999);
						$num4 = rand(10000, 99999);
						$secret = $num1 . $num2 . $num3 . $num4;
				
						$password = sha1($_email.$_password.$secret);
						
						//get activation code
						$num1 = rand(10000, 99999);
						$num2 = rand(10000, 99999);
						$activation_code = $num1 . $num2;
			
						$sql = "insert  into smac_web.smac_user(`account_id`,`first_name`,`last_name`,`email`,`password`,`secret`,`title`,`contact_no`,`blackberry_pin`,`n_status`,`register_date`,`user_role`,city_id,country_id,state_id,activation_code,lang) values 
						('".$this->user->account_id."','".mysql_escape_string($_fname)."','".mysql_escape_string($_lname)."','".mysql_escape_string($_email)."','$password','$secret','".mysql_escape_string($_title)."','".mysql_escape_string($_contact)."','".mysql_escape_string($_bb)."',0,NOW(),0,0,'',0,'".$activation_code."','".$this->user->lang."');";
						
						$this->open(0);
						
						global $CONFIG;
						$this->query($sql);
						$last_id = mysql_insert_id();
						if($last_id>0){
				
							
					
							//$arr = array('subdomain' => $this->Request->getParam('subdomain') );
							//$link = $CONFIG['BASEURL'].'login.php?'.$this->Request->encrypt_params($arr);
					
							if($CONFIG['EMAIL_PROSES']){
								
								//get link activation
								$arr = array('page' => 'activation', 'code' => md5($activation_code), 'email' => $_email, 'id' => $last_id, 'type' => 'user');
								$link = $CONFIG['BASEURL'].'index.php?'.$this->Request->encrypt_params($arr);
								
								$msg = "
								<h2>Congratulations! Your Trial Account request has been approved.</h2>
								<p>Hi ".$_fname.' '.$_lname.",</p>
								<p>Thank you for choosing SMAC as your social listening and action application. Please click on the activation link below to activate your account and start \"smac-ing\".</p>
								<p><a href='$link'>Activation Link</a></p>
								<p>If you can't see the link above, cut and paste the following line into your browser :</p>
								<p>$link</p>
								<p>Thanks again!</p>
								<p>SMAC Team</p>
								";
						
								$to = $_email;
								$subject = "SMAC - Account Approved";
								$from = $CONFIG['EMAIL_SYSTEM'];
								$htmlText ="
											<h2>Congratulations! Your Trial Account request has been approved.</h2>
											<p>Hi ".$_fname.' '.$_lname.",</p>
											<p>Thank you for choosing SMAC as your social listening and action application. Please click on the activation link below to activate your account and start \"smac-ing\".</p>
											<p><a href='$link'>Activation Link</a></p>
											<p>If you can't see the link above, cut and paste the following line into your browser :</p>
											<p>$link</p>
											<p>Thanks again!</p>
											<p>SMAC Team</p>
											";
								$plainText = "Congratulations! Your Trial Account request has been approved.
											  Hi ".$_fname.' '.$_lname.",
											  Thank you for choosing SMAC as your social listening and action application. Please click on the activation link below to activate your account and start \"smac-ing\".</p>
											  {$link}
												
											  
											  Thanks again!
											  SMAC Team
											";
								$headers  = "From: {$from}\n"; 
								$headers .= "Content-type: text/html; charset=iso-8859-1";
								
								$mail = new Mailer();
								$mail->setSubject("New Account Registered");
								$mail->setSender("info@smac.me");
								$mail->setMessage($msg);
								$mail->setRecipient($_email);
								$mail->use_postmark(true);
								$mail->send(array(
									'to'=>array('email'=>$_email,'name'=>"{$_fname} {$_lname}"),
									'subject'=>$subject,
									'plainText'=>$plainText,
									'htmlText'=>$htmlText
								));
								
								
								
					
							}
					
							return $this->View->showMessage(text('user_creation_success'), $link2);
						
						}else{

							$err = text('user_creation_error');
						}
						$this->close();
					}
				}
			}
		}
		
		$this->View->assign('err',$err);
		
		$this->View->assign('validErr',$validErr);		
		
		return $this->View->toString(APPLICATION.'/registration-user.html');
		
	}

	function checksubdomain(){	
		$ajax = intval($this->Request->getParam('ajax'));
		
		if( $ajax == 1 ){
		
			$data = array();
			$_subdomain = strtolower($this->Request->getParam('sb'));
			$valid = true;
			
			if($_subdomain != ''){
				if( strlen($_subdomain) > 30 && $valid){
					$data['success'] = 0;
					$data['msg'] = text('registration_subdomain_length_error');
					$valid = false;
				}
				if( ! preg_match('/^[a-z0-9_]+$/', strtolower($_subdomain)) && $valid){
					$data['success'] = 0;
					$data['msg'] = text('registration_subdomain_format_error');
					$valid = false;
				}
				
				if($valid){
					$sql = "SELECT COUNT(*) AS total FROM smac_web.smac_subdomain WHERE subdomain LIKE '%".mysql_escape_string($_subdomain)."%';";
					$this->open(0);
					$sub = $this->fetch($sql);
					$this->close();	
					if( intval($sub['total']) > 0){
						$data['success'] = 0;
						$data['msg'] = text('registration_subdomain_unavailable');
						$valid = false;
					}else{
						$data['success'] = 1;
						$data['msg'] = text('registration_subdomain_available');
					}
				}	
			}else{
				$data['success'] = 0;
				$data['msg'] = text('registration_subdomain_empty');
			}
		
			echo json_encode($data);
			exit;
		
		}else{
			
			sendRedirect('index.php');
			exit;
			
		}
		
	}
	
}
?>