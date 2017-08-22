<?php
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/MarketHelper.php";
class keyOpinionWidget extends Application{
	
	var $Request;
	
	var $View;
	
	var $_mainLayout="";
	
	var $user;
	
	var $api;
	
	function __construct($user,$req=null){
		
		$this->user = $user;
		
		$this->Request = $req;
		
		if(strlen($_SESSION['geo'])>0){
			$this->api = new marketHelper();
		}else{
			$this->api = new apiHelper();
		}
		
		$this->View = new BasicView();
		
	}
	
	function show($from_date=null,$to_date=null,$geo=''){
		

		//link key opinion
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyopinionleader');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeyopinion',$link);
		
		if($from_date!=null&&$to_date!=null){
			$opinion = $this->api->getSummaryOpinionDaily($this->user->account_id,$from_date,$to_date,$_SESSION['campaign_id'],$_SESSION['language'],$geo);
		}else{
			$opinion = $this->api->getSummaryOpinion($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$geo);
		}
		//print_r($opinion->user[0]->attributes());
		//echo $opinion->user[0]->attributes()->image;
		//exit;
		
		$data = array();
		
		foreach($opinion->user as $k){
			
			$data[] = array('image'=> (String) $k->attributes()->image,
							'name' => (String) $k->attributes()->name,
							'rt' => (String) $k->attributes()->rt,
							'share' => (String) $k->attributes()->share,
							'followers' => (String) $k->attributes()->followers
							);
			
		}
		$this->View->assign('opinion',$data);
		return $this->View->toString(APPLICATION . "/widgets/key-opinion.html");
	
	}
	function show_fb($kol,$from_date=null,$to_date=null,$geo=''){
		//link key opinion
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyopinionleader');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeyopinion',$link);

		$data = array();
		
		foreach($kol as $k){
			$data[] = array('image'=> "https://graph.facebook.com/".$k['author_id']."/picture",
							'name' => $k['author_name'],
							'author_id'=> $k['author_id'],
							'likes' => $k['total'],
							'mentions'=>$k['mentions']
							);
			
		}
		$this->View->assign('opinion',$data);
		return $this->View->toString(APPLICATION . "/widgets/key-opinion-fb.html");
	
	}
	function show_web($kol,$from_date=null,$to_date=null,$geo=''){
		//link key opinion
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keyopinionleader');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlkeyopinion',$link);

		$data = array();
		
		foreach($kol as $k){
			$data[] = array('image'=> "content/default-blog.jpg",
							'name' => $k['author_name'],
							'likes' => $k['likes'],
							'mentions'=>$k['total'],
							'author_uri'=>$k['author_uri']
							);
			
		}
		$this->View->assign('opinion',$data);
		return $this->View->toString(APPLICATION . "/widgets/key-opinion-web.html");
	
	}
}
?>