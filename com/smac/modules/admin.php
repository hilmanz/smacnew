<?php
global $APP_PATH;
class admin extends App{
	
	function directlogin(){
		$this->session = new SessionHelper(APPLICATION . '_Session');
		//secret
		$secret1 = md5('09ew78d0wediosohosioh098');
		$secret2 = md5(date('MDjYghimnyd'));
		$key = md5(date('jmYGHiyntd').date('YGjmHntdiy').$secret1);
		
		$subdomain = $this->Request->getParam('subdomain');
		$username = $this->Request->getParam('u1');
		$password = $this->Request->getParam('u2');
		$user_id = $this->Request->getParam('u3');
		$key2 = $this->Request->getParam('u4');
		
		if(true){
			//echo $subdomain . ' - ' . $username . ' - ' . $password . ' - '.$user_id . ' - ' . $key2;
			
			$password = str_replace($secret2,'',$password);
			$password = str_replace($secret1,'',$password);
			
			$qry = "SELECT 
							*
						FROM
							smac_web.smac_user u
							INNER JOIN smac_web.smac_subdomain s
							ON u.account_id=s.account_id
						WHERE
							u.id=".intval($user_id)." AND
							s.subdomain='".mysql_escape_string($subdomain)."' 
							LIMIT 1;";
			
			$this->open(0);
			$rs = $this->fetch($qry);
			$this->close();
			
			if( strcmp($rs['password'],$password) === 0 ){
				
				session_destroy();
				sleep(5);
				session_start();
				
				$sql = "SELECT * FROM smac_web.smac_user u INNER JOIN 
						smac_web.smac_subdomain s ON u.account_id=s.account_id 
						WHERE u.email='".mysql_escape_string($username)."' 
						AND s.subdomain='".mysql_escape_string($subdomain)."' LIMIT 1;";
				$this->open(0);
				$rs = $this->fetch($sql);
				$this->close();
				
				$rs['username']= $username;
				$rs['user_id'] = $rs['id'];
				$this->session->set('user',urlencode64(json_encode($rs)));
				$_SESSION['sesstoken'] = $response->data->session_token;
					
				$params = array("subdomain"=>$subdomain,"page"=>"overview");
				sendRedirect("index.php?".$this->Request->encrypt_params($params));
				die();
			}else{
				sendRedirect('index.php');
				exit;
			}
			
		}else{
			sendRedirect('index.php');
			exit;
		}
	}
	
}
?>