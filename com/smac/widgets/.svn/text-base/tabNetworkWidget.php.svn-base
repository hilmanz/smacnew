<?php
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/MarketHelper.php";
class tabNetworkWidget extends Application{
	
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
	function getSourceCount($campaign_id){
		
	}
	function show($from_date=null,$to_date=null,$geo='',$default='twitter'){
		
		//compare
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'comparechannels');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$this->View->assign('urlcompare',$link);
		
		$detail = json_decode($this->api->getCampaignDetail($this->user->account_id,$_SESSION['campaign_id']));
		$c = explode(',', $detail->channels);
		$channels = array(false,false,false,false);
		foreach($c as $k){
			$channels[$k] = true;
		}
		$this->View->assign("channels",$channels);
		$this->View->assign("default",$default);
		//source count
		$source = $this->api->getSourceCount($this->user->account_id,$from_date,$to_date,$_SESSION['campaign_id'],$_SESSION['language'],$_SESSION['geo']);
		
		$twitter_count = (String) $source->twitter;
		$fb_count = (String) $source->fb;
		$blog_count= (String) $source->blog;
		$n_count = intval($twitter_count)+intval($fb_count)+intval($blog_count);
		if($n_count>0){
			$source_count = array('twitter'=>round(($twitter_count/$n_count)*100,2),
							'fb'=>round(($fb_count/$n_count)*100,2),
							'blog'=>round(($blog_count/$n_count)*100,2));
		}else{
			$source_count = array('twitter'=>0,
							'fb'=>0,
							'blog'=>0);
		}
		$this->View->assign("source_count",$source_count);
		
		
		$this->assign("filter_date_from",$from_date);
		$this->assign("filter_to_date",$to_date);
		return $this->View->toString(APPLICATION . "/widgets/tabNetwork.html");
	
	}
	function show_twitter(){
		
		$from_date = $this->Request->getParam("filter_date_from");
		$to_date = $this->Request->getParam("filter_to_date");
		$geo = $_SESSION['geo'];
		//twitter feed
		if($from_date!=null&&$to_date!=null){
			$tw = $this->api->getTwitterDailyList($this->user->account_id,$from_date,$to_date,$_SESSION['campaign_id'],$_SESSION['language'],5,$geo);
		}else{
			
			$tw = $this->api->getTwitterList($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],5,$geo);
		}
		$data = array();
		
		foreach($tw->row as $k){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),
					 'page' => 'workflow','act'=>'flag',
					 'keyword'=>'N/A',
					 'feed_id'=>trim((String)$k->id),'opt'=>1,'ajax'=>1,'type'=>2);
			$reply_url = str_replace("req=","",$this->Request->encrypt_params($c));
			$data[] = array('id'=> (String) $k->id,
							'author_id' => (String) $k->author_id,
							'name' => (String) $k->name,
							'txt' => htmlspecialchars((String) $k->txt),
							'image' => (String) $k->profile_image_url,
							'insert_date' => (String) $k->insert_date,
							'published_date' => date("d/m/Y",strtotime((String) $k->published)),
							'reach' => (String) $k->reach,
							'imp' => (String) $k->imp,
							'rt' => (String) $k->rt,
							'share' => (String) $k->share,
							'rt_imp' => (String) $k->rt_imp,
							'device' => (String) $k->device,
							'reply_url'=>$reply_url
							);
			
		}
		if(sizeof($data)>0){
			$this->View->assign('tw',$data);
			print $this->View->toString("smac/widgets/top_conversation_twitter.html");
		}else{
			print "Not available";
		}
	}
	function show_fb(){
		$from_date = $this->Request->getParam("filter_date_from");
		$to_date = $this->Request->getParam("filter_to_date");
		if (empty($from_date)) {
			$from_date = null;
		}
		if (empty($to_date)) {
			$to_date = null;
		}

		$geo = $_SESSION['geo'];
		if($geo==null){
			//fb feed
			//quick fix
			$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id={$_SESSION['campaign_id']} LIMIT 1";
			$this->open(0);
			$cmp = $this->fetch($sql);
			$this->close();
			$language = $cmp['lang'];
			if($language==null){
				$language='all';
			}
			//need to relocate this
			if($from_date!=null&&$to_date!=null){
				$fb = json_decode($this->api->getFBDailyList($this->user->account_id,$from_date,$to_date,$_SESSION['campaign_id'],$language,5,$geo));
			}else{
				$fb = json_decode($this->api->getFBList($this->user->account_id,$_SESSION['campaign_id'],$language,5,$geo));
			}
			
			$data = array();
			foreach($fb as $k){
				$data[] = array('id'=> (String) $k->id,
								'name' => (String) $k->name,
								'url' => (String) $k->url,
								'txt' => htmlspecialchars((String) $k->txt),
								'image' => (String) $k->profile_image_url,
								'insert_date' => (String) $k->insert_date,
								'published_date' => (String) $k->published,
								'reach' => (String) $k->reach,
								'sentiment' => (String) $k->sentiment,
								'device' => (String) $k->device
								);
				
			}
			if(sizeof($data)>0){
				$this->View->assign('fb',$data);
				print $this->View->toString("smac/widgets/top_conversation_fb.html");
			}else{
				print "Not available.";
			}
		}
	}
	function show_web(){
		$from_date = $this->Request->getParam("filter_date_from");
		$to_date = $this->Request->getParam("filter_to_date");
		$geo = $_SESSION['geo'];
		
		//twitter feed
		if($from_date!=null&&$to_date!=null){
			$blog = json_decode($this->api->getBlogDailyList($this->user->account_id,$from_date,$to_date, $_SESSION['campaign_id'],5));
		}else{			
			//$blog = json_decode($this->api->getBlogList($this->user->account_id,$_SESSION['campaign_id'],5,$geo));
			$blog = json_decode($this->api->getBlogList($this->user->account_id,$_SESSION['campaign_id'],5));
		}		
		
		
		
		$data = array();
		foreach($blog as $k){
			$data[] = array('id'=> (String) $k->id,
							'name' => (String) $k->name,
							'txt' => htmlspecialchars((String) $k->txt),
							'image' => (String) $k->profile_image_url,
							'insert_date' => (String) $k->insert_date,
							'reach' => (String) $k->reach,
							'sentiment' => (String) $k->sentiment,
							'url' => (String) $k->url,
							'title' => (String) $k->title,
							'summary' => (String) $k->summary,
							'link' => (String) $k->link,
							'device' => (String) $k->device
							);
			
		}
		if(sizeof($data)>0){
			$this->View->assign('blog',$data);
			print $this->View->toString("smac/widgets/top_conversation_web.html");
		}else{
			print "Not available.";
		}
	}
	
}
?>