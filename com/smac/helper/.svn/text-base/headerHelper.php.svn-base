<?php
require_once 'messageHelper.php';
class headerHelper extends Application{
	function __construct($user,$req=null){
		parent::__construct($req);
		$this->user = $user;
		$this->message = new messageHelper($req,$user);
		
	}
	
	function show(){
		//$arr = array("subdomain"=> $this->Request->getParam('subdomain'));
		//$link = 'index.php?'.$this->Request->encrypt_params($arr);
		//link my campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'overview');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlhome',$link);
		$this->View->assign('fname',$this->user->first_name);
		$this->View->assign('lname',$this->user->last_name);
		$this->View->assign('email',$this->user->email);
		$billing = new BillingHelper();
		$this->View->assign("saldo",$billing->get_saldo($this->user->account_id));
		$this->View->assign('status', intval($this->user->account_type));
		$this->View->assign('user_id', $this->user->id);
		$this->View->assign('message', $this->message->check());
		return $this->View->toString(APPLICATION . "/header.html");
	
	}
	
}
?>