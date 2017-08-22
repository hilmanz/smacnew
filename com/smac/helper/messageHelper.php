<?php
class messageHelper extends Application{
	
	var $Request;
	
	var $View;
	
	var $user;
	
	function __construct($req=null,$user=null){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->user = $user;
		
	}
	
	function check(){
		
		$have_msg = false;
		
		$msg = array();
		
		//check limit
		if( ! $this->checkLimit() ){
			$have_msg = true;
			$msg[] = 'Your account is limited';
		}
		
		//check payment
		if( ! $this->checkPayment() ){
			$have_msg = true;
			$msg[] = 'Your account have payment issue';
		}
		
		//Check message from admin
		$qry = "SELECT * FROM smac_web.smac_message WHERE account_id='".$this->user->account_id."' AND type='personal';";
		$this->open(0);
		$list = $this->fetch($qry,1);
		if( count($list) > 0){
			$have_msg = true;
			foreach($list as $m){
				$msg[] = $m['notification'];
			}
		}
		$qry = "SELECT * FROM smac_web.smac_message WHERE type='all';";
		
		$list = $this->fetch($qry,1);
		if( count($list) > 0){
			$have_msg = true;
			foreach($list as $m){
				$msg[] = $m['notification'];
			}
		}
		$this->close();
		if( $have_msg ){
			return $msg;
		}
		
		return false;
		
	}
	
	/*
	 *	Check account limit
	 *	return true/false
	 */
	function checkLimit(){
		
		//code here
		
		return true;
		
	}
	
	/*
	 *	Check account payment
	 *	return true/false
	 */
	function checkPayment(){
		
		//code here
		
		return true;
		
	}
	
}
?>