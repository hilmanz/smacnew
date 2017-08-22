<?php
/**
 * Sentiment Service
 * it will returns the keyword sentiments and also updating the topic's campaign_sentiment_setup for
 * new keyword/sentiment.
 * @author duf
 * 
 */
class sentiment extends API{
	function execute(){
		$this->debug->setAppName('Sentiment_Webservice');
		if(strlen($this->_request('lang'))>=2){
			return $this->getSentiment();
		}else{
			return $this->toJson('0','no request',null);
		}
	}
	function getSentiment(){
		$lang = $this->Request->getParam('lang');
		$text = base64_decode($this->Request->getParam('text'));
		$campaign_id = $this->Request->getParam('campaign_id');
		
		$this->debug->info("get sentiment for campaign_id [{$campaign_id}]");
		$this->debug->info("[{$campaign_id}][{$lang}] {$text}");	
		
		$this->debug->info("retrieve from sentiment setup first");
		$existing = $this->getExistingSentiment($campaign_id,$text);
		
		if(sizeof($existing['unfound'])>0){
			$unfound = implode(' ',$existing['unfound']);
			$this->debug->info("these keywords are not found : {$unfound}");
			switch($lang){
				case "ar":
					$this->getArabicSentiment($campaign_id,$unfound);
					$this->getEnglishSentiment($campaign_id,$unfound);
				break;
				case "id":
					$this->getBahasaSentiment($campaign_id,$unfound);
					$this->getEnglishSentiment($campaign_id,$unfound);
				break;
				case "ph":
					$this->getTagalogSentiment($campaign_id,$unfound);
					$this->getEnglishSentiment($campaign_id,$unfound);
				break;
				default :
					//english
					$this->getEnglishSentiment($campaign_id,$unfound);
				break;
			}
			$existing = $this->getExistingSentiment($campaign_id,$text);
			
		}
		$result = array();
		$result[$lang] = $existing['found'];
		$this->debug->info("Response : ".json_encode($result));
		return $this->toJson('1','getSentiment',$result);
	}
	function getExistingSentiment($campaign_id,$text){
		$wordlist = extract_words($text);
		$sqlStr = $this->toSqlArray($wordlist);
		
		$found = array();
		$unfound = array();
		
		$this->open(0);
		//we will change these to default_sentiment_en ,later.		
		$sql = "SELECT keyword,weight as sentiment 
				FROM smac_sentiment.sentiment_setup_{$campaign_id}
				WHERE keyword IN ($sqlStr);";
		$rs = @$this->fetch($sql,1);
		$this->close();
		if(sizeof($rs)>0){
			foreach($rs as $r){
				$found[] = array("keyword"=>$r['keyword'],"sentiment"=>$r['sentiment']);
			}
		}
		$raw = explode(",",$wordlist);
		
		foreach($raw as $r){
			$is_found = false;
			foreach($found as $f){
				if($f['keyword']==trim($r)){
					$is_found = true;
					break;
				}
			}
			if(!$is_found){
				$unfound[] = $r;
			}
		}
		
		return array("found"=>$found,"unfound"=>$unfound);
	}
	function getBahasaSentiment($campaign_id,$text){
		global $APP_PATH;
		include_once $APP_PATH."smac_api/helper/BahasaSentiment.php";
		$helper = new BahasaSentiment($this->Request);
		$result = $helper->getSentiments($text,$this->debug);
		
		//update sentiment setup
		$q = $this->update_sentiment_setup($campaign_id,$result);
		$this->debug->status("update sentiment setup",$q);
		return $result;
	}
	function getTagalogSentiment($campaign_id,$text){
		global $APP_PATH;
		include_once $APP_PATH."smac_api/helper/TagalogSentiment.php";
		$helper = new TagalogSentiment($this->Request);
		$result = $helper->getSentiments($text,$this->debug);
		
		//update sentiment setup
		$q = $this->update_sentiment_setup($campaign_id,$result);
		$this->debug->status("update sentiment setup",$q);
		return $result;
	}
	function getArabicSentiment($campaign_id,$text){
		global $APP_PATH;
		include_once $APP_PATH."smac_api/helper/ArabicSentiment.php";
		$helper = new ArabicSentiment($this->Request);
		$result = $helper->getSentiments($text,$this->debug);
		
		//update sentiment setup
		$q = $this->update_sentiment_setup($campaign_id,$result);
		$this->debug->status("update sentiment setup",$q);
		return $result;
	}
	function getEnglishSentiment($campaign_id,$text){
		global $APP_PATH;
		include_once $APP_PATH."smac_api/helper/EnglishSentiment.php";
		
		$helper = new EnglishSentiment($this->Request);
		$result = $helper->getSentiments($text);
		//update sentiment setup
		$q = $this->update_sentiment_setup($campaign_id,$result);
		$this->debug->status("update sentiment setup",$q);
		return $result;
	}
	function update_sentiment_setup($campaign_id,$sentiments){
		global $APP_PATH;
		$ok = true;
		//we insert ignore the keyword+sentiment pair into campaign_sentiment_setup
		
		if(sizeof($sentiments)>0){
			$this->open(0);
			foreach($sentiments as $sentiment){
				$keyword = mysql_escape_string(trim($sentiment['keyword']));
				$sentiment = intval($sentiment['sentiment']);
				$sql = "call smac_sentiment.p_insert_sentiment_setup({$campaign_id},'{$keyword}',{$sentiment});";
				$q = $this->query($sql);
				$this->debug->status($sql,$q);
				if(!$q){$ok=false;}
			}
			$this->close();
		}
		return $ok;
	}
}
?>