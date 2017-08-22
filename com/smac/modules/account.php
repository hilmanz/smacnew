<?php
global $APP_PATH;
class account extends App{
	
	function setVar(){
		
	}
	function home(){
		global $APP_PATH;
		require_once $APP_PATH . APPLICATION . "/modules/twitter.php";
		require_once $APP_PATH . APPLICATION . "/modules/fb.php";
		
		//socmed accounts initialization
		$this->twitter = new twitter($this->Request);
		$this->fb = new fb($this->Request);
		//-->
		$this->assign('sidebar', $this->sidebarHelper->show() );
		$this->View->assign('owner',intval($this->user->user_role));
		$this->open(0);
		//If owner
		$qry = "SELECT * FROM smac_user WHERE account_id='".$this->user->account_id."' AND user_role='0';";
		$rs = $this->fetch($qry,1);
		$this->View->assign('member',$rs);
		$this->View->assign('memberNum',count($rs));
		$sql = "SELECT 
					u.*,
					c.country_name,
					s.state_name,
					ci.city_name 
				FROM 
					smac_user u 
					LEFT JOIN country c 
					ON u.country_id=c.country_id
					LEFT JOIN state s
					ON u.state_id=s.state_id AND u.country_id=s.country_id
					LEFT JOIN city ci
					ON u.city_id=ci.loc_id
				WHERE 
					id='".intval($this->user->id)."';";
		
		$rs = $this->fetch($sql);
		
		$this->View->assign('userdata',$rs);
		
		$sql = "SELECT * FROM country WHERE 1 ORDER BY country_name;";
		
		$r = $this->fetch($sql,1);
		
		$this->View->assign('country',$r);
		
		$sql = "SELECT * FROM state WHERE 1 AND country_id='".$rs['country_id']."' ORDER BY state_name;";
		
		$r = $this->fetch($sql,1);
		
		$this->View->assign('state',$r);
		
		$sql = "SELECT * FROM city WHERE 1 AND country_id='".$rs['country_id']."' AND state_id=".$rs['state_id']." ORDER BY city_name;";
		
		$r = $this->fetch($sql,1);
		
		$this->View->assign('city',$r);
		
		$this->close();
		
		//link submit info
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account', 'act' => 'editinfo');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlinfo',$link);
		
		//link submit password
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account', 'act' => 'editpassword');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlpassword',$link);
		
		$_SESSION['curr_req'] = "?".$this->Request->encrypt_params(array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account'));
		
		if($this->twitter->is_authorized()){
			$is_authorized = 1;
			$this->assign("twitter_id",$this->twitter->get_screen_name());
		}else{
			$is_authorized = 0;
		}
		if($this->fb->is_authorized()){
			$fb_authorized = 1;
			$this->assign("fb_name",$this->fb->get_screen_name());
			$this->assign("fb_access_token",$this->fb->get_access_token());
		}else{
			$fb_authorized = 0;
		}
		$this->assign("is_authorized",$is_authorized);
		$this->assign("fb_authorized",$fb_authorized);
		return $this->View->toString(APPLICATION.'/my-account.html');
	
	}
	function preferences(){
		$this->assign('sidebar', $this->sidebarHelper->show());
		if($this->post('edit')==1){
			return $this->save_preferences();
		}
		//only user with admin role my access these feature
		if($this->user->user_role==1){
			$this->View->assign("pref",$this->_getEmailNotificationPreference());
			return $this->View->toString(APPLICATION.'/preferences.html');
		}else{
			return $this->View->showMessage(text('preference_permission_error'),'?');
		}
	}
	function save_preferences(){
		$enable = intval($this->post('enable_notif'));
		$interval = intval($this->post('interval'));
		$include_pdf = intval($this->post('include_pdf'));
		
		if($enable==0){
			$interval = 0;
		}
		if($interval>30){
			$interval = 30;
		}
		$next_date = date("Y-m-d",time()+(60*60*24*$interval));
		$this->open(0);
		$q = $this->query("INSERT INTO `smac_web`.`tbl_notification_preference` 
							(
							`account_id`, 
							`reguler_email_interval`, 
							`email_pdf_report`, 
							`last_notification_date`, 
							`next_notification_date`
							)
							VALUES
							(
							{$this->user->account_id}, 
							{$interval}, 
							{$include_pdf}, 
							'',
							'{$next_date}'
							)
							ON DUPLICATE KEY UPDATE
							reguler_email_interval=VALUES(reguler_email_interval),
							email_pdf_report=VALUES(email_pdf_report),
							next_notification_date = VALUES(next_notification_date)");
		$this->close();
		if($q){
			return $this->View->showMessage(text('preferences_saved'),'index.php?page=account&act=preferences');
		}else{
			return $this->View->showMessage(text('preferences_error'),'index.php?page=account&act=preferences');
		}
		
	}
	function _getEmailNotificationPreference(){
		
		$this->open(0);
		$rs = $this->fetch("SELECT * FROM smac_web.tbl_notification_preference 
					  WHERE account_id={$this->user->account_id} LIMIT 1");
		$this->close();
		
		return $rs;
	}
	function editinfo(){
	
		//link redirect
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
	
		$_edit = intval($_POST['edit']);
		
		if($_edit == 1){
		
			$_fname = mysql_escape_string($_POST['fname']);
			$_lname = mysql_escape_string($_POST['lname']);
			$_contact = mysql_escape_string($_POST['contact']);
			$_address = mysql_escape_string($_POST['address']);
			$_country = mysql_escape_string($_POST['country']);
			$_state = mysql_escape_string($_POST['state']);
			$_city = mysql_escape_string($_POST['city']);
			$_cityOther = mysql_escape_string($_POST['city_other']);
			
			$sql = "UPDATE 
						smac_web.smac_user 
					SET 
						first_name='$_fname',
						last_name='$_lname',
						contact_no='$_contact',
						address='$_address',
						country_id='$_country',
						state_id='$_state',
						city_id='$_city',
						city_other='$_cityOther'
					WHERE
						id='".$this->user->id."'
						";
			
			$this->open(0);
			
			if($this->query($sql)){
				$this->assign('sidebar', $this->sidebarHelper->show() );
				return $this->View->showMessage(text('edit_profile_success'),$link);
				
			}else{
				$this->assign('sidebar', $this->sidebarHelper->show() );
				return $this->View->showMessage(text('edit_profile_error'),$link);
			
			}
		
		}else{
			$this->assign('sidebar', $this->sidebarHelper->show() );
			return $this->View->showMessage(text('edit_profile_error'),$link);
		
		}
		
		$this->close();
		
		//sendRedirect($link);
		//exit;
	
	}
	
	function editpassword(){
	
		//link redirect
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
	
		$_edit = intval($_POST['edit']);
		
		if($_edit == 1){
		
			$_pass = mysql_escape_string($_POST['password']);
			
			$password = sha1($this->user->email.$_pass.$this->user->secret);
			
			$sql = "UPDATE 
						smac_web.smac_user 
					SET 
						password='{$password}'
					WHERE
						id='".$this->user->id."'
						";
			
			$this->open(0);
			
			if($this->query($sql)){
				$this->assign('sidebar', $this->sidebarHelper->show() );
				return $this->View->showMessage(text('change_password_success'),$link);
				
			}else{
			
				//echo mysql_error();exit;
				$this->assign('sidebar', $this->sidebarHelper->show() );
				return $this->View->showMessage(text('change_password_error'),$link);
			
			}
		
		}else{
			$this->assign('sidebar', $this->sidebarHelper->show() );
			return $this->View->showMessage(text('change_password_error'),$link);
		
		}
		
		$this->close();
		
		//sendRedirect($link);
		//exit;
	
	}
	function credit_usage(){
		
		$this->assign('sidebar', $this->sidebarHelper->show() );
		$this->assign("name",trim($this->user->first_name." ".$this->user->last_name));
		$arr = array('page' => 'account', 'act' => 'active_subscriptions','start'=>0);
		$active_url = 'index.php?'.$this->Request->encrypt_params($arr);
		$arr = array('page' => 'account', 'act' => 'expired_subscriptions','start'=>0);
		$expired_url = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('active_url',"{$active_url}");
		$this->View->assign('expired_url',"{$expired_url}");
		if($this->user->account_type==5){
			$this->View->assign("topic_usage",$this->show_topic_usage());
		}
		return $this->View->toString(APPLICATION.'/credit_usage.html');
	}
	/**
	 * showing a widget on how many lines these topic has consumed.
	 * right now, the method only used for type 5 account (enterprise)
	 */
	private function show_topic_usage(){
		$helper = new TopicHelper(null);
		$rs = $helper->getEnterpriseUsage($this->user->account_id,$this->user->account_type);
		
		$this->View->assign('usage',$rs);
		return $this->View->toString(APPLICATION.'/line_usage.html');
	
	}
	function active_subscriptions(){
		global $APP_PATH,$ENGINE_PATH,$CONFIG;
		require_once $APP_PATH . APPLICATION . "/helper/BillingHelper.php";
		require_once $ENGINE_PATH . "/Utility/Paginate.php";
		$billing = new BillingHelper($this->Request);
		$start = intval($this->Request->getParam('st'));
		$limit = 20;
		$orders = $billing->get_orders($this->user->account_id,1,$start,$limit);
		$total_rows = $billing->get_total_orders($this->user->account_id,1);
		
		for($i=0;$i<sizeof($orders);$i++){
			$orders[$i]['campaign_name'] = utf8tohtml(($orders[$i]['campaign_name']));
		}
		
		$this->assign("orders",$orders);
		//these is for backward compatibility with legacy version
		$this->assign("default_price",$CONFIG['ACCOUNT_COST'][$this->user->account_type]);
		//-->
		$paging = new Paginate();
		$this->View->assign("paging",$paging->generate_json("active_subscriptions",$start,$limit,$total_rows,"?page=account&act=active_subscriptions"));
		$html = $this->View->toString(APPLICATION."/active_subscription.html");
		print ($html);
		//print json_encode(array("html"=>base64_encode($html)));
		die();
	}
	function utf8ToUnicodeCodePoints($str) {
	    if (!mb_check_encoding($str, 'UTF-8')) {
	        trigger_error('$str is not encoded in UTF-8, I cannot work like this');
	        return false;
	    }
	    return preg_replace_callback('/./u', function ($m) {
	        $ord = ord($m[0]);
	        if ($ord <= 127) {
	            return sprintf('\u%04x', $ord);
	        } else {
	            return trim(json_encode($m[0]), '"');
	        }
	    }, $str);
	}
	function expired_subscriptions(){
		global $APP_PATH,$ENGINE_PATH,$CONFIG;
		require_once $APP_PATH . APPLICATION . "/helper/BillingHelper.php";
		require_once $ENGINE_PATH . "/Utility/Paginate.php";
		$billing = new BillingHelper($this->Request);
		$start = intval($this->Request->getParam('st'));
		$limit = 20;
		$orders = $billing->get_orders($this->user->account_id,0,$start,$limit);
		$total_rows = $billing->get_total_orders($this->user->account_id,0);
		
		$this->assign("orders",$orders);
		//these is for backward compatibility with legacy version
		$this->assign("default_price",$CONFIG['ACCOUNT_COST'][$this->user->account_type]);
		//-->
		$paging = new Paginate();
		$this->View->assign("paging",$paging->generate_json("expired_subscriptions",$start,$limit,$total_rows,"?page=account&act=expired_subscriptions"));
		$html = $this->View->toString(APPLICATION."/expired_subscription.html");
		print $html;
		//print json_encode(array("html"=>base64_encode($html)));
		die();
	}
	function extend(){
		global $APP_PATH,$ENGINE_PATH,$CONFIG;
		require_once $APP_PATH . APPLICATION . "/helper/BillingHelper.php";
		$old_order_id = $this->Request->getParam("id");
		$billing = new BillingHelper($this->Request);
		$order = $billing->get_order($old_order_id);
		if($order_id = $billing->create_order($this->user->account_id, $order['campaign_id'], $order['items'])){
			$billing->open(0);
			$new_order = $billing->get_latest_order($order['campaign_id']);
			$billing->close();
			sendRedirect("?".$this->Request->encrypt_params(array("page"=>"account","act"=>"checkout","id"=>$new_order['id'])));
			die();
		}else{
			return $this->View->showMessage("Sorry, we cannot extend your topic. Please try again later !");
		}
		//$billing->create_order($account_id, $topic_id, $orders);
	}
	function checkout(){
		global $APP_PATH,$ENGINE_PATH,$CONFIG;
		require_once $APP_PATH . APPLICATION . "/helper/BillingHelper.php";
		require_once $APP_PATH . APPLICATION . "/helper/PaypalHelper.php";
		$order_id = $this->Request->getParam("id");
		$paypal = new PaypalHelper();
		$billing = new BillingHelper($this->Request);
		$paypal_url = $paypal->checkout($this->user,$billing->get_order($order_id),
										$CONFIG['BASEURL']."?".$this->Request->encrypt_params(array("page"=>"account","act"=>"paypal_return","id"=>$topics[$n]['last_order']['id'])),
										$CONFIG['BASEURL']."?".$this->Request->encrypt_params(array("page"=>"account","act"=>"paypal_cancel","id"=>$topics[$n]['last_order']['id']))
										);
		if(strlen($paypal_url)>0){
			sendRedirect("{$paypal_url}",1);
			die();
		}else{
			return $this->View->showMessage("Unable to checkout. Please try again later !", "?");
		}
	}
	function cancel_order(){
		if($this->Request->getParam('confirm')==1){
			if($this->doCancelOrder()){
				return $this->View->showMessage("Your order has been canceled successfully !", 
												"?".$this->Request->encrypt_params(
																	 array("page"=>"account","act"=>"credit_usage")
																	 )
												);
			}else{
				return $this->View->showMessage("Sorry, your order cannot be cancelled at the moment. Please try again later !", 
												"?".$this->Request->encrypt_params(
																	 array("page"=>"account","act"=>"credit_usage")
																	 )
												);
			}
		}else{
			$msg = "Are you sure to cancel your order ?";
			$onYes = array("label"=>"Yes","url"=>"?".$this->Request->encrypt_params(array("page"=>"account","act"=>"cancel_order","confirm"=>"1","id"=>$this->Request->getParam('id'))));
			$onNo = array("label"=>"No","url"=>"?".$this->Request->encrypt_params(array("page"=>"account","act"=>"credit_usage")));
			return $this->View->confirm($msg, $onYes, $onNo);
		}
	}
	private function doCancelOrder(){
		global $APP_PATH,$ENGINE_PATH,$CONFIG;
		require_once $APP_PATH . APPLICATION . "/helper/BillingHelper.php";
		require_once $APP_PATH . APPLICATION . "/helper/TopicHelper.php";
		$order_id = intval($this->Request->getParam("id"));
		$billing = new BillingHelper();
		$topic = new TopicHelper();
		$order = $billing->get_order($order_id);
		
		$cancel =  $billing->cancel_order($order_id);
		if($cancel){
			//disable the topic as well.
			$topic->disable_topic($order['campaign_id']);
		}
		return $cancel;
	}
	function paypal_return(){
		global $APP_PATH,$ENGINE_PATH,$CONFIG;
		require_once $APP_PATH . APPLICATION . "/helper/BillingHelper.php";
		require_once $APP_PATH . APPLICATION . "/helper/PaypalHelper.php";
		require_once $APP_PATH . APPLICATION . "/helper/TopicHelper.php";
		$paypal = new PaypalHelper();
		$billing = new BillingHelper($this->Request);
		if($paypal->finalizeTransaction($billing)){
			$billing->addCredit($this->user->account_id, $paypal->amount,2,$paypal->paypal_transaction_id);
			if($this->api->changeCampaignStatus($this->user->account_id,$paypal->campaign_id,0)){
				//reset topic here
				$topic = new TopicHelper();
				$topic->reset_topic($paypal->campaign_id);
				//-->
				//debit user.
				$billing->purchase_topic($this->user->account_id, $paypal->campaign_id, $paypal->amount);
			}
			$this->assign('sidebar', $this->sidebarHelper->show());
		
			$this->View->assign("transaction_id",$paypal->paypal_transaction_id);
			return $this->View->toString(APPLICATION."/widgets/finish_payment.html");
		}else{

			return $this->View->showMessage("Your Transaction is a failure. Please try again later !",
											"?".$this->Request->encrypt_params(array("page"=>"account",
																					"act"=>"credit_usage",
																					"id"=>$topics[$n]['last_order']['id'])));
		}
	}
	
	function paypal_cancel(){
		
	}
	
	
}
?>