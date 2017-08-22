<?php
require_once $APP_PATH . APPLICATION . "/helper/apiHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/wordcloudHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/MarketHelper.php";
class favoriteWordWidget extends Application{
	
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
	
	function show($geo=''){
		
		//link to keyword and analysis
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		$from = $this->Request->getParam('filter_date_from');
		$to = $this->Request->getParam('filter_to_date');
		if($geo){
			$word = $this->api->getWordcloud($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$geo,$from,$to);
		}else{
			$word = $this->api->getWordcloud($this->user->account_id,$_SESSION['campaign_id'],$_SESSION['language'],$from,$to);
		}
		
		$words = json_decode($word);
		
		$rs = array();
		$m=0;
		foreach($words as $w){
			$m = max($m,$w->amount);
			$mm = min($m,$w->amount);
			
			$rs[] = array("txt"=>$w->keyword,"amount"=>$w->amount,"weight"=>$w->amount,"url"=>$link,"is_main"=>$w->is_main,"sentiment"=>$w->sentiment,"title"=> "Click to see detailed analysis");
		}
		foreach($rs as $n=>$v){
			
			$weight = ceil(($v['amount']/($m-$mm))*9);
			$rs[$n]['weight'] = $weight;
			$rs[$n]['ratio'] = $v['amount']/($m-$mm);
			$rs[$n]['max'] = $m;
			$rs[$n]['min'] = $mm;
		}
		
		
		$wordcloud = new wordcloudHelper(300,300);
		$wordcloud->urlto="#?w=650&keyword=";
		$wordcloud->setHandler('homewordcloud');
		$wordcloud->set_sentiment_style(array("positive"=>"wcstat1","negative"=>"wcstat2","neutral"=>"wcstat0","main_keyword"=>"wcstat3"));
	
		$this->View->assign("wordcloud",$wordcloud->draw($rs));
		
		//-->
		
		return $this->View->toString(APPLICATION . "/widgets/favorite-word.html");
	
	}
	function show_fb($words,$geo=''){
		
		//link to keyword and analysis
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		
		
		$rs = array();
		$m=0;
		$mm=0;
		foreach($words as $w){
			$m = max($m,$w['total']);
			$mm = min($m,$w['total']);
			
			$rs[] = array("txt"=>$w['keyword'],"amount"=>$w['total'],"weight"=>0,"url"=>"#","is_main"=>"","sentiment"=>"","title"=> "Click to see detailed analysis");
		}
		foreach($rs as $n=>$v){
			
			$weight = @ceil(($v['amount']/($m-$mm))*9);
			$rs[$n]['weight'] = $weight;
			if($m-$mm>0){
				$rs[$n]['ratio'] = @$v['amount']/($m-$mm);
			}else{
				$rs[$n]['ratio'] = 0;
			}
			$rs[$n]['max'] = $m;
			$rs[$n]['min'] = $mm;
		}
		
		
		$wordcloud = new wordcloudHelper(300,300);
		$wordcloud->urlto="#";
		$wordcloud->setHandler('homewordcloud');
		$wordcloud->set_sentiment_style(array("positive"=>"wcstat1","negative"=>"wcstat2","neutral"=>"wcstat0","main_keyword"=>"wcstat3"));
		$wordcloud->use_callback = false;
		$this->View->assign("wordcloud",$wordcloud->draw($rs));
		
		//-->
		
		return $this->View->toString(APPLICATION . "/widgets/favorite-word.html");
	
	}
	function getWordCloudFromArray($rs){
		//link to keyword and analysis
		$arr = array("subdomain"=> $this->Request->getParam('subdomain'),'page' => 'keywordanalysis');
		$link = 'index.php?'.$this->Request->encrypt_params($arr);
		
		$m=0;
		$mm=0;
		
		foreach($rs as $w){
			$m = max($m,$w['amount']);
			$mm = min($m,$w['amount']);
		}
		foreach($rs as $n=>$v){
			$weight = ceil(($v['amount']/($m-$mm))*9);
			$rs[$n]['weight'] = $weight;
			$rs[$n]['max'] = $m;
			$rs[$n]['min'] = $mm;
			$rs[$n]['txt'] = $rs[$n]['keyword'];
		}
		
		$wordcloud = new wordcloudHelper(300,300);
		$wordcloud->urlto=$link;
		$wordcloud->setHandler('homewordcloud');
		$wordcloud->set_sentiment_style(array("positive"=>"wcstat1","negative"=>"wcstat2","neutral"=>"wcstat0","main_keyword"=>"wcstat3"));
		$this->View->assign("wordcloud",$wordcloud->draw($rs));
		$rs = null;
		//-->
		return $this->View->toString(APPLICATION . "/widgets/favorite-word.html");
	}
	function bublesort($arr,$key){
		$is_done = false;
		
		while(!$is_done){
			$n = sizeof($rs);
			$swap = false;
			for($i=1;$i<$n;$i++){
				if($rs[$i-1][$key]<$rs[$i][$key]){
					$old= $rs[$i-1];
					$rs[$i-1] = $rs[$i];
					$rs[$i] = $old;
					$swap = true;
				}
			}
			if(!$swap){
				$is_done = true;
			}
		}
		return $rs;
	}
	
	
}
?>