<?php
include_once "apiHelper.php";
include_once "TopicHelper.php";
class sidebarHelper extends Application{
	var $_mainLayout="";
	var $user;
	var $api;
	var $topic;
	function __construct($user,$req=null){
		parent::__construct($req);
		$this->user = $user;
		$this->api = new apiHelper();
		$this->topic = new TopicHelper();
	}
	
	function show($usage=true){
		
		if($this->user->account_type<2){
			$usage = true;
		}else{
			$usage =false;
		}
		
		$this->View->assign('usage_show', $usage);
		
		$campaign = $this->topic->getTopics($this->user->account_id);
		
		for($i=0;$i<sizeof($campaign);$i++){
			//link campaign
			$arr = array("subdomain"=>$this->Request->getParam('subdomain'),'campaign_id'=>$campaign[$i]['id']);
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			$campaign[$i]['link'] = $link;
			//$campaign[$i]['name'] = html_entity_decode(mb_convert_encoding($campaign[$i]['name'], 'HTML-ENTITIES', 'utf-8'), ENT_QUOTES, 'UTF-8' );
		}
		
		
		$_campaignId = ($_SESSION['campaign_id'] != '')? $_SESSION['campaign_id'] : $campaign[0]['id'];
		
		//print_r($arrCampaign);exit;
		
		$this->View->assign('campaignId', $_campaignId);
		$this->View->assign('campaign', $campaign);
	
		//link my campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'overview');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlmycampaign',$link);
		
		//link new campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign', 'act' => 'add');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlnewcampaign',$link);
		
		//link edit topic group
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign', 'act' => 'edit_topic_group');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('url_edit_group',$link);
		
		//link edit campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign', 'act' => 'edit');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urleditcampaign',$link);
		
		//link delete campaign
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'campaign', 'act' => 'confirm_remove');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urldeletecampaign',$link);
		
		
		//link my keywords
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlmykeyword',$link);
		
		//link add keywords
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyword','act' => 'add');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urladdkeyword',$link);
		
		//link my account
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlmyaccount',$link);
		
		//link FAQ
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'faq');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlfaq',$link);
		//link credit usage
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'account','act'=>'credit_usage');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlviewtransaction',$link);
		//Topic Usage
		if(intval($_campaignId)>0){
			$account_usage = $this->topic->getTopicUsage($_campaignId,$this->user->account_type);
		}
		$this->View->assign('account',$account_usage);
		
		//Jika user adalah account
		if($this->user->user_role == 1){
			
			//link add user
			$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'registration','act'=>'user');
			$link = 'index.php?'.$this->Request->encrypt_params($arr);
			$this->View->assign('urladduser',$link);
			
		}
		//only show my keyword link if there's active campaign
		if($_SESSION['campaign_id']){
			$this->assign('has_campaign',1);
		}
		return $this->View->toString(APPLICATION . "/sidebar.html");
	
	}
	
}
?>