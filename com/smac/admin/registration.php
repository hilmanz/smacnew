<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $ENGINE_PATH."Utility/Mailer.php";
class registration extends SQLData{
	
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		//$this->User = new UserManager();
	}
	
	function admin(){
		if($this->Request->getPost('act')){
			$act = $this->Request->getPost('act');
			if($act == 'editAccount'){
				return $this->editAccount();
			}else if($act=="editRegister"){
				return $this->editRegister();
			}
		}else{
			$act = $this->Request->getParam('act');
			if( $act == 'account' ){
				return $this->accountList();
			}elseif( $act == 'editAgency' ){
				return $this->editAgency();
			}elseif( $act == 'detailAccount' ){
				return $this->detailAccount();
			}elseif( $act == 'editRegister' ){
				return $this->editRegister();
			}elseif( $act == 'agency' ){
				return $this->agencyList();
			}elseif( $act == 'user' ){
				return $this->userList();
			}elseif( $act == 'editUser' ){
				return $this->editUser();
			}elseif( $act == 'view-email' ){
				return $this->viewEmail();
			}elseif( $act == 'account-reset-password' ){
				return $this->accountResetPassword();
			}elseif( $act == 'directlogin' ){
				return $this->directlogin();
			}elseif( $act == 'raw_log' ){
				return $this->raw_log();
			}elseif( $act == 'user_raw_log' ){
				return $this->user_raw_log();
			}elseif( $act == 'download_log' ){
				return $this->download_log();
			}else{
				return $this->registerList();
			}
		}
	}
	function raw_log(){
		$this->View->assign("dt",$this->open_log_dir());
		if($this->Request->getParam("dt")!=null){
			$dt = urldecode64($this->Request->getParam("dt"));
			$this->open_log_file($dt);
		}
		return $this->View->toString("smac/admin/raw_activity_log.html");
	}
	function user_raw_log(){
		$user_id = $this->Request->getParam("id");
		$this->View->assign("user",$this->getUserDetail($user_id));
		$this->View->assign("dt",$this->open_log_dir(true));
		
		
		if($this->Request->getParam("dt")!=null){
			$dt = urldecode64($this->Request->getParam("dt"));
			$this->read_user_log($user_id,$dt);
			
		}
		return $this->View->toString("smac/admin/raw_user_log.html");
	}
	function download_log(){
		$user_id = $this->Request->getParam("id");
		$dt = $this->open_log_dir();
		header("Content-type:application/zip");
		header('Content-Disposition: attachment; filename="activitylog.'.$user_id.'.'.date("YmdHis").'.csv"');
		ob_clean();
		flush();
		if(is_array($dt)){
			print '"DATE";"ACCOUNT ID";"ACTION";"TOPIC ID";"IP"'.PHP_EOL;
			foreach($dt as $d){
				$this->stream_download_log($user_id,urldecode64($d['value']));
			}
		}
		
		die();
	}
	function stream_download_log($user_id,$filename){
		global $GLOBAL_PATH;
		if($user_id>0){
			$target = ($GLOBAL_PATH."logs/".$filename);
			
			$fp = fopen($target,"r");
			if($fp){
				//$str = fread($fp,filesize($target));
				$this->found_user_log = false;
				while(!feof($fp)){
					$str = $this->get_user_log($user_id, fgets($fp,4096));
					
					if(strlen($str)>0){
						$arr = explode("|",$str);
						$col1 = explode("[",$arr[0]);
						$account = str_replace("]","",$col1[1]);
						$arr[2] = trim(str_replace("Action : ","",$arr[2]));
						$topic_id = explode(":",$arr[3]);
						$topic_id[1] = trim($topic_id[1]);
						print "\"{$col1[0]}\";\"{$account}\";\"{$arr[2]}\";\"{$topic_id[1]}\";\"{$arr[4]}\"".PHP_EOL;
						//print_r($arr);
					}
				}
				fclose($fp);
			}
		}
	}
	function getUserDetail($user_id){
		$user_id = intval($user_id);
		$rs = $this->fetch("SELECT * FROM smac_web.smac_user WHERE id={$user_id} LIMIT 1");
		return $rs;
	}
	function open_log_file($f){
		global $GLOBAL_PATH;
		$target = ($GLOBAL_PATH."logs/".$f);
		$fp = fopen($target,"r");
		//$str = fread($fp,filesize($target));
		while(!feof($fp)){
			print nl2br(fgets($fp,4096));
		}
		fclose($fp);
		die();
	}
	function read_user_log($id,$f){
		global $GLOBAL_PATH;
		$target = ($GLOBAL_PATH."logs/".$f);
		$fp = fopen($target,"r");
		//$str = fread($fp,filesize($target));
		$this->found_user_log = false;
		while(!feof($fp)){
			print nl2br($this->get_user_log($id, fgets($fp,4096)));
		}
		if($this->found_user_log==false){
			print "There's no activity at these date";
		}
		fclose($fp);
		die();
	}
	function get_user_log($id,$str){
		$arr = explode("|",$str);
		$c = explode(":",$arr[1]);
		if(intval(trim($c[1]))==$id){
			$this->found_user_log = true;
			return $str;
		}
	}
	function open_log_dir($reverse=false){
		$rs = array();
		//read the log files and capture the available dates
		if ($handle = opendir('../logs')) {
		  
		    /* This is the correct way to loop over the directory. */
		    while (false !== ($entry = readdir($handle))) {
		    	
		    	
		        if(@eregi("smacapp-",$entry)){
					$arr = explode("-",$entry);
					$ts = strtotime(trim(str_replace(".log","",$arr[1])));
					
					if($ts<strtotime(date("Y-m-d 00:00:00"))){
						$rs[] = array("text"=>date("d/m/Y",$ts),
									  "value"=>urlencode64($entry),
									  "ts"=>strtotime(trim(str_replace(".log","",$arr[1]))));
					}
		        }
		    }

		    closedir($handle);
		}
		if($reverse){
			$rs = subval_rsort($rs,'ts');
		}else{
			$rs = subval_sort($rs,'ts');
		}
		return $rs;
	}
	function userList(){
		$start = intval($this->Request->getParam('st'));
		
		$s = mysql_escape_string($this->Request->getParam('find'));
		$where = '';
		$param = '';
		
		if($s != ''){
			$where = "AND (u.first_name LIKE '%".$s."%' OR u.last_name LIKE '%".$s."%') ";
			$param = '&s='.$s;
		}
		
		$qry = "SELECT count(*) total FROM smac_web.smac_user u WHERE n_status < 2 $where;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT u.*,a.name account_name, ag.agency_name,a.account_type 
				FROM smac_web.smac_user u 
				INNER JOIN 
				smac_web.smac_account a 
				ON u.account_id=a.id 
				INNER JOIN 
				smac_web.smac_agency ag 
				ON a.agency_id=ag.id 
				WHERE u.n_status < 2 $where 
				LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		
		$num = count($list);
		for($i=0;$i<$num;$i++){
			$arr = array("s"=>"register","act" => 'directlogin', "id" => $list[$i]['id']);
			$list[$i]['direct_link'] = 'index.php?'. $this->Request->encrypt_params($arr);
		}
		
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=register&act=user".$param));
		
		return $this->View->toString("smac/admin/register-user.html");
		
	}
	
	function registerList(){
		
		$start = intval($this->Request->getParam('st'));
		$s = mysql_escape_string($this->Request->getParam('find'));
		$where = '';
		$param = '';
		
		if($s != ''){
			$where = "AND name LIKE '%".$s."%'";
			$param = '&s='.$s;
		}
		
		$qry = "SELECT count(*) total FROM smac_web.smac_registration WHERE 1 $where;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT * FROM smac_web.smac_registration WHERE 1 $where ORDER BY register_date DESC LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		//echo $qry.'<hr />' . mysql_error();exit;
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=register".$param));
		
		return $this->View->toString("smac/admin/register.html");
		
	}
	
	function agencyList(){
		
		$start = intval($this->Request->getParam('st'));
		
		$s = mysql_escape_string($this->Request->getParam('find'));
		$where = '';
		$param = '';
		
		if($s != ''){
			$where = "AND agency_name LIKE '%".$s."%'";
			$param = '&s='.$s;
		}
		
		$qry = "SELECT count(*) total FROM smac_web.smac_agency WHERE 1 $where;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT * FROM smac_web.smac_agency WHERE 1 $where LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=register&act=agency".$param));
		
		return $this->View->toString("smac/admin/register-agency.html");
		
	}
	
	function accountList(){
		
		$start = intval($this->Request->getParam('st'));
		
		$s = mysql_escape_string($this->Request->getParam('find'));
		$where = '';
		$param = '';
		
		if($s != ''){
			$where = "AND ac.name LIKE '%".$s."%'";
			$param = '&s='.$s;
		}
		
		$qry = "SELECT count(*) total FROM smac_web.smac_account ac WHERE ac.n_status < 2 $where;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT ac.*,ag.agency_name FROM smac_web.smac_account ac 
				INNER JOIN smac_web.smac_agency ag ON ac.agency_id=ag.id 
				WHERE ac.n_status < 2 $where 
				LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=register&act=account".$param));
		
		return $this->View->toString("smac/admin/register-account.html");
		
	}
	
	function editAgency(){
		
		$id = intval($_GET['id']);
		$edit = intval($_GET['edit']);
		
		$qry = "SELECT * FROM smac_web.smac_agency ag WHERE ag.id=$id;";
		$list = $this->fetch($qry);
		$this->View->assign('list',$list);
		
		if( $edit == 1){
		
			$_status = intval($_GET['status']);
			
			$_name = mysql_escape_string($_GET['name']);
			$_cname = mysql_escape_string($_GET['cname']);
			$_contact = mysql_escape_string($_GET['contact']);
			$_email = mysql_escape_string(strtolower($_GET['email']));
			$_address = mysql_escape_string($_GET['address']);
			$_city = mysql_escape_string($_GET['city']);
			$_state = mysql_escape_string($_GET['state']);
			$_country = mysql_escape_string($_GET['country']);
			$_about = mysql_escape_string($_GET['about']);
			$_use = mysql_escape_string($_GET['use']);
			
			$qry = "UPDATE smac_web.smac_agency SET 
					n_status='$_status', 
					agency_name='$_name',
					company_name='$_cname',
					contact_no='$_contact',
					agency_email='$_email',
					address='$_address',
					city='$_city',
					state='$_state',
					country='$_country',
					about_company='$_about',
					usage_reason='$_use'
					WHERE id=$id;";
					
			//echo $qry;exit;
			
			if( $this->query($qry) ){
				
				global $CONFIG;
				
				if( $_status == 0){
					$status = 'Pending';
				}elseif( $_status == 1){
					$status = 'Active';
				}elseif( $_status == 2){
					$status = 'Disable';
				}elseif( $_status == 3){
					$status = 'Banned';
				}
				
				//$arr = array("page"=>"registration","act"=>"account", "id" => $list['id'],"s" => 23, 'subdomain' => $list['subdomain']);
				//$link = $CONFIG['BASEURL'].'?'.$this->Request->encrypt_params($arr);
				//exit;
					
				if($CONFIG['EMAIL_PROSES']){
					
					if($_status==1){
						$msg = "<p>".$list['agency_name'].",</p>
										<p>Lorem ipsum dolor sit amet</p>
										<p>Status: $status</p>";
							
						$mail = new Mailer();
						$mail->setSubject("Update Agency");
						$mail->setSender($CONFIG['EMAIL_SYSTEM']);
						$mail->setMessage($msg);
						
						$mail->setRecipient($list['agency_email']);
						
					}
				}else{
					
					$msg = "<p>".$list['agency_name'].",</p>
										<p>Lorem ipsum dolor sit amet</p>
										<p>Status: $status</p>";
										
					//echo $msg;
					
					//exit;
					
				}
				
				return $this->View->showMessage('Edit Success','index.php?s=register&act=agency');
				//sendRedirect('index.php?s=register&act=agency');
				//exit;
				
			}else{
				$err = "Edit failed, please try again!";
			}
			
		}
	
		$this->View->assign('err',$err);
		
		return $this->View->toString("smac/admin/register-edit-agency.html");
	
	}
	function editAccount(){
		global $CONFIG;
		
		$_status = intval($_POST['status']);
		$id = intval($_POST['id']);
		$_name = mysql_escape_string($_POST['name']);
		$_contact = mysql_escape_string($_POST['contact']);
		$_address = mysql_escape_string($_POST['address']);
		$_city = mysql_escape_string($_POST['city']);
		$_state = mysql_escape_string($_POST['state']);
		$_country = mysql_escape_string($_POST['country']);
		$_account_type = mysql_escape_string($_POST['account_type']);
		$_contract_no = mysql_escape_string($_POST['contract_no']);
		$ebm_limit = intval(mysql_escape_string($_POST['ebm']));
		$qry = "UPDATE smac_web.smac_account SET 
				n_status='{$_status}', 
				name='{$_name}',
				contact_no='{$_contact}',
				address='{$_address}',
				city='{$_city}',
				state='{$_state}',
				country='{$_country}',
				account_type='{$_account_type}',
				contract_no='{$_contract_no}'
				WHERE id={$id};";
		
		//echo $qry;exit;
		
		if( $this->query($qry) ){
			
			if($_account_type==5){
				//insert bulk limit
				$sql = "
						INSERT INTO `smac_web`.`smac_account_ebm` 
							(
							`account_id`, 
							`max_lines`, 
							`dtinserted`
							)
							VALUES
							( 
							'{$id}', 
							'{$ebm_limit}', 
							NOW()
							)
						ON DUPLICATE KEY UPDATE
						max_lines=VALUES(max_lines)";
				$this->query($sql);
			}
			
			
			if( $_status == 0){
				$status = 'Disabled';
			}elseif( $_status == 1){
				$status = 'Actived';
			}
			
			//$arr = array("page"=>"registration","act"=>"account", "id" => $list['id'],"s" => 23, 'subdomain' => $list['subdomain']);
			//$link = $CONFIG['BASEURL'].'?'.$this->Request->encrypt_params($arr);
			//exit;
				
			if($CONFIG['EMAIL_PROSES']){
				
				if($_status==1){
					$msg = "<p>".$list['name'].",</p>
									<p>Lorem ipsum dolor sit amet</p>
									<p>Status: $status</p>";
						
					$mail = new Mailer();
					$mail->setSubject("Update Agency");
					$mail->setSender($CONFIG['EMAIL_SYSTEM']);
					$mail->setMessage($msg);
					
					$mail->setRecipient($list['email']);
					
				}
			}else{
				
				$msg = "<p>".$list['name'].",</p>
									<p>Lorem ipsum dolor sit amet</p>
									<p>Status: $status</p>";
									
				//echo $msg;
				
				//exit;
				
			}
			
			return $this->View->showMessage('Edit Success','index.php?s=register&act=account');
			//sendRedirect('index.php?s=register&act=agency');
			//exit;
			
		}else{
			
			$err = "Edit failed, please try again!";
		}
		return $this->View->showMessage($err,'index.php?s=register&act=account');
	}
	function detailAccount(){
		
		$id = intval($_GET['id']);
		$edit = intval($_GET['edit']);
		
		$qry = "SELECT ac.*,ag.agency_name FROM smac_web.smac_account ac LEFT JOIN smac_web.smac_agency ag ON ac.agency_id=ag.id WHERE ac.id=$id;";
		$list = $this->fetch($qry);
		$this->View->assign('list',$list);
		
		
		
		
		$sql = "SELECT * FROM smac_web.smac_email_copy WHERE account_id=".$id." ORDER BY date DESC";
		$rs = $this->fetch($sql,1);
		$this->View->assign('email',$rs);
		$this->View->assign('email_num',count($rs));
		
		return $this->View->toString("smac/admin/register-account-detail.html");
		
	}
	
	function editRegister(){
		
		$id = intval($_REQUEST['id']);
		$edit = intval($_REQUEST['edit']);
		
		$qry = "SELECT 
						ag.*,
						c.country_name,
						s.state_name,
						ct.city_name
					FROM 
						smac_web.smac_registration ag
						LEFT JOIN smac_web.country c
						ON ag.country=c.country_id
						LEFT JOIN state s
						ON ag.state=s.state_id AND ag.country=s.country_id
						LEFT JOIN city ct
						ON ag.city=ct.loc_id AND ag.state=ct.state_id AND ag.country=ct.country_id 
					WHERE 
						ag.id=$id;";
		$list = $this->fetch($qry);
		$this->View->assign('list',$list);
		
		$err = "";
		
		if( $edit == 1){
			
			$_status = intval($this->Request->getPost('status'));
			$_subdomain = strtolower($this->Request->getPost('_subdomain'));
			$_type = intval($this->Request->getPost('type'));
			$_accountType = intval($this->Request->getPost('account_type'));
			
			//check subdomain
			if($_type == 0 && $_status == 1){
				$qry = "SELECT * FROM smac_web.smac_subdomain WHERE subdomain='$_subdomain' LIMIT 1;";
				$rs = $this->fetch($qry);
				if( intval($rs['account_id']) > 0 || $_subdomain == ''){
						
					$err = "Subdomain not available.";
				
					$this->View->assign('err',$err);
		
					return $this->View->toString("smac/admin/register-edit.html");
				
				}
			}
		
			if( $_status == 1&& $_REQUEST['approved_date'] == ''){
				$qry = "UPDATE smac_web.smac_registration SET n_status='$_status', approved_date=NOW() WHERE id=$id;";
			}else{
				$qry = "UPDATE smac_web.smac_registration SET n_status='$_status' WHERE id=$id;";
			}
			
			if( $this->query($qry) ){
			
				//ACCOUNT
				if($_type == 0 && $_status == 1){
				
					$num1 = rand(10000, 99999);
					$num2 = rand(10000, 99999);
					$num3 = rand(10000, 99999);
					$num4 = rand(10000, 99999);
					$secret = $num1 . $num2 . $num3 . $num4;
				
					//password default
					$_password = trim("".rand(10000, 99999)."");
				
					//echo $list['agency_email'].' - '.$_password.' - '.$secret.'<hr />';
				
					$password = sha1($list['agency_email'].$_password.$secret);
					//echo $_password .' - '.$password .' - '.$list['agency_email'].' - '.$secret.' - '.sha1($list['agency_email'],$_password,$secret);
					//exit;
					
					//get activation code
					$num1 = rand(10000, 99999);
					$num2 = rand(10000, 99999);
					$activation_code = $num1 . $num2;
				
					$sql = "insert into smac_web.smac_account				 
					(`agency_id`,`name`,`email`,`contact_no`,`address`,`city`,`state`,`country`,`n_status`,`register_date`,activation_code,city_other,lang,account_type) 
					values 
					(1,'".$list['name']."','".strtolower($list['agency_email'])."','".$list['contact_no']."','".$list['address']."','','','".$list['country']."',0,NOW(),'$activation_code','".$list['city_other']."','".$list['lang']."','".$_accountType."');";
							
					$this->query($sql);
					/*
					echo $sql.'<hr />';
					echo mysql_error();
					exit;
					*/
					$account_id = mysql_insert_id();
					if($account_id < 1){
						$err = "Some error has occured, please try again !";
						$this->View->assign('err',$err);
		
						return $this->View->toString("smac/admin/register-edit.html");
					}
					//insert nama subdomain
					//$sub = ereg_replace("[^A-Za-z0-9 ]", "", $list['name']); 
					//$sub = str_replace(' ','-',strtolower($sub));
					
					$qry = "INSERT INTO smac_web.smac_subdomain (account_id,subdomain) VALUES ('".mysql_escape_string($account_id)."','".mysql_escape_string($_subdomain)."')";
						
					$this->query($qry);
					
					//get link activation
					$arr = array('page' => 'activation', 'code' => md5($activation_code), 'email' => $list['agency_email'], 'id' => $account_id, 'type' => 'account');
					global $CONFIG;
					$link = $CONFIG['ACTIVATION_URL'].'index.php?'.$this->Request->encrypt_params($arr);
					//exit;
					
					//EMAIL TO ACCOUNT
					if($CONFIG['EMAIL_PROSES']){
						
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$list['name']);
						$this->View->assign('link',$link);
						$msg = $this->View->toString(APPLICATION.'/email/email-approved.html');
						
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$list['name']);
						$this->View->assign('link',$link);
						$plain = $this->View->toString(APPLICATION.'/email/email-approved-plain.html');
						
						$mail = new Mailer();
						$mail->use_postmark(true);
						$mail->send(array(
							'to'=>array('email'=>$list['agency_email'],
										 'name'=>$list['name']),
							'subject'=>"SMAC - Account Approved",
							'plainText'=>$plain,
							'htmlText'=>$msg
						));
						
					}
					
					$sql = "insert  into 
								smac_web.smac_user
								(`account_id`,`first_name`,`last_name`,`email`,`password`,`secret`,`title`,`contact_no`,`blackberry_pin`,`n_status`,`register_date`,`user_role`,address,country_id,city_other,lang) 
								values 
								('$account_id','".$list['name']."','','".strtolower($list['agency_email'])."','$password','$secret',NULL,'".$list['contact_no']."',NULL,0,NOW(),1,'".$list['address']."','".$list['country']."','".$list['city_other']."','".$list['lang']."');";
					
					$this->query($sql);
					
									
				}elseif($_type == 1 && $_status == 1){
					
					//AGENCY
					$num1 = rand(10000, 99999);
					$num2 = rand(10000, 99999);
					$num3 = rand(10000, 99999);
					$num4 = rand(10000, 99999);
					$secret = $num1 . $num2 . $num3 . $num4;
					
					//get activation code
					$num1 = rand(10000, 99999);
					$num2 = rand(10000, 99999);
					$activation_code = $num1 . $num2;
				
					$qry = "INSERT INTO smac_web.smac_agency				
					(agency_name,company_name,contact_no,address,city,state,country,about_company,usage_reason,n_status,register_date,approved_date,agency_no,agency_email,activation_code,city_other,name,lang,agency_contact_no,password)
								VALUES
								('".$list['agency_name']."','".$list['company_name']."','".$list['contact_no']."','".$list['address']."','".$list['city']."','".$list['state']."','".$list['country']."','".$list['about_company']."','".$list['usage_reason']."',0,'".$list['register_date']."',NOW(),'$secret','".strtolower($list['agency_email'])."','".$activation_code."','".$list['city_other']."','".$list['name']."','".$list['lang']."','".$list['agency_contact_no']."','');";
								
					$this->query($qry);
					
					$agency_id = mysql_insert_id();
					
					global $CONFIG;
					
					if($CONFIG['EMAIL_PROSES']){
						
						//get link activation
						$arr = array('page' => 'activation', 'code' => md5($activation_code), 'email' => strtolower($list['agency_email']), 'id' => $agency_id, 'type' => 'agency');
						
						$link = $CONFIG['ACTIVATION_URL'].'index.php?'.$this->Request->encrypt_params($arr);
						
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$list['name']);
						$this->View->assign('link',$link);
						$msg = $this->View->toString(APPLICATION.'/email/email-approved.html');
	
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$list['name']);
						$this->View->assign('link',$link);
						$plain = $this->View->toString(APPLICATION.'/email/email-approved-plain.html');
						
						$mail = new Mailer();
						$mail->use_postmark(true);
						$mail->send(array(
							'to'=>array('email'=>$list['agency_email'],
										 'name'=>$list['name']),
							'subject'=>"SMAC - Account Approved",
							'plainText'=>$plain,
							'htmlText'=>$msg
						));
						
						
					}
					
				
				}else{
			
				
					global $CONFIG;
				
					if( $_status == 0){
						$status = 'Pending';
					}elseif( $_status == 1){
						$status = 'Active';
					}elseif( $_status == 2){
						$status = 'Disable';
					}elseif( $_status == 3){
						$status = 'Banned';
					}
					
					
				
				}
				
				return $this->View->showMessage('Edit Success','index.php?s=register');
				
			}else{
				$err = "Edit failed, please try again!";
			}
			
		}
	
		$this->View->assign('err',$err);
		
		return $this->View->toString("smac/admin/register-edit.html");
	
	}
	
	function editUser(){
		
		$id = intval($_GET['id']);
		$edit = intval($_GET['edit']);
		
		$qry = "SELECT u.*,a.name account_name, ag.agency_name FROM smac_web.smac_user u LEFT JOIN smac_web.smac_account a ON u.account_id=a.id LEFT JOIN smac_web.smac_agency ag ON a.agency_id=ag.id WHERE u.id=$id;";
		$list = $this->fetch($qry);
		$this->View->assign('list',$list);
		
		
		if( $edit == 1){
		
			$_status = intval($_GET['status']);
			
			$_fname = mysql_escape_string($_GET['fname']);
			$_lname = mysql_escape_string($_GET['lname']);
			$_title = mysql_escape_string($_GET['title']);
			$_contact = mysql_escape_string($_GET['contact']);
			$_bb = mysql_escape_string($_GET['bb']);
			
			$qry = "UPDATE smac_web.smac_user SET 
					n_status='$_status', 
					first_name='$_fname',
					last_name='$_lname',
					title='$_title',
					contact_no='$_contact',
					blackberry_pin='$_bb'
					WHERE id=$id;";
					
			//echo $qry;exit;
			
			if( $this->query($qry) ){
				
				global $CONFIG;
				
				if( $_status == 0){
					$status = 'Disabled';
				}elseif( $_status == 1){
					$status = 'Actived';
				}
				
				
					
				if($CONFIG['EMAIL_PROSES']){
					
					if($_status==1){
						$msg = "<p>".$list['first_name'].",</p>
										<p>Lorem ipsum dolor sit amet</p>
										<p>Status: $status</p>";
							
						$mail = new Mailer();
						$mail->setSubject("Update Agency");
						$mail->setSender($CONFIG['EMAIL_SYSTEM']);
						$mail->setMessage($msg);
						
						$mail->setRecipient($list['email']);
						
					}
				}
				
				return $this->View->showMessage('Edit Success','index.php?s=register&act=user');
				
				
			}else{
				$err = "Edit failed, please try again!";
			}
			
		}
		
		
		return $this->View->toString("smac/admin/register-edit-user.html");
		
	}
	
	function viewEmail(){
		
		$id = intval($_GET['id']);
		$qry = "SELECT * FROM smac_web.smac_email_copy WHERE id=".$id.";";
		$list = $this->fetch($qry);
		$this->View->assign('email',htmlspecialchars_decode($list['register_email'],ENT_QUOTES));
		
		return $this->View->toString("smac/admin/register-view-email.html");
		
	}
	
	function accountResetPassword(){
		
		$id = intval($_GET['id']);
		
		if($this->Request->getPost('change') == 1){
			
			$newpass = $this->Request->getPost('np');
			
			$len = strlen($newpass);
			
			$valid = true;
			if($len < 5){
				
				$err = "Password must be at least 5 Digits!";
				$valid = false;
			}
			
			if($valid){
				
				$sql = "SELECT * FROM smac_web.smac_account a LEFT JOIN smac_web.smac_subdomain s ON a.id=s.account_id WHERE id=".$id;
				$rs = $this->fetch($sql);
				
				$sql = "SELECT * FROM smac_web.smac_user WHERE email='".$rs['email']."';";
				$up = $this->fetch($sql);
				
				$password = sha1($up['email'].$newpass.$up['secret']);
				
				$sql2 = "UPDATE smac_web.smac_user SET password='".$password."' WHERE email='".$up['email']."';";
				
				if( $this->query($sql2) ){
					
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
						$this->View->assign('password',$newpass);
						$this->View->assign('sublink',$sublink);
						$msg = $this->View->toString(APPLICATION.'/email/email-new-password.html');
						
						$this->View->assign('baseurl',$CONFIG['BASEURL']);
						$this->View->assign('name',$rs['name']);
						$this->View->assign('link',$sublink);
						$this->View->assign('email',$rs['email']);
						$this->View->assign('password',$newpass);
						$this->View->assign('sublink',$sublink);
						$plain = $this->View->toString(APPLICATION.'/email/email-new-password-plain.html');
						
						$mail = new Mailer();
						$mail->use_postmark(true);
						$mail->send(array(
							'to'=>array('email'=>$rs['email'],
										 'name'=>$rs['name']),
							'subject'=>"SMAC - Password Resetted",
							'plainText'=>$plain,
							'htmlText'=>$msg
						));
						
						
					}
					
					//Save email data
					$sql = "INSERT INTO smac_web.smac_email_copy (account_id,register_email,date,user_id,agency_id) VALUES (".$id.",'".htmlspecialchars($msg,ENT_QUOTES)."',NOW(),0,0);";
					$this->query($sql);
					
					return $this->View->showMessage("Change Password Success","index.php?s=register&act=account");
					
				}else{
					
					$err = "Change password failed, please try again!";
					
				}
				
			}
			
		}
		
		$this->View->assign('id',$id);
		$this->View->assign('err',$err);
		return $this->View->toString("smac/admin/register-account-reset-password.html");
		
	}
	
	function directlogin(){
		
		//secret
		$secret1 = md5('09ew78d0wediosohosioh098');
		$secret2 = md5(date('MDjYghimnyd'));
		$key = md5(date('jmYGHiyntd').date('YGjmHntdiy').$secret1);
		
		$user_id = intval($this->Request->getParam('id'));
		
		$qry = "SELECT 
						*
					FROM
						smac_web.smac_user u
						LEFT JOIN smac_web.smac_subdomain s
						ON u.account_id=s.account_id
					WHERE
						id=$user_id;";
		
		$rs = $this->fetch($qry);
		
		global $CONFIG;
		$arr = array("subdomain"=> $rs['subdomain'],"page"=>"admin","act" => 'directlogin', "u1" => $rs['email'], "u2" => $secret2 . $rs['password'] . $secret1, "u3" => $user_id, "u4" => $key);
		HEADER("Location: http://".$rs['subdomain'].".".$CONFIG['BASEURL_NO_HTTP'] . '/index.php?'. $this->Request->encrypt_params($arr));
		exit;
		
	}
	
}
?>