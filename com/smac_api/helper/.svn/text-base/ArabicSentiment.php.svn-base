<?php
class ArabicSentiment extends API{
	private $found_words;
	private $negators;
	private $dict;
	private $sentiments;
	
	
	function getSentiments($text,$debugger){
		$this->debug= $debugger;
		$this->open(0);
		$this->sentiments = array();
		$this->getNegators();
		//$this->getKeywordsFromDictionary();
		$text = $this->prepareText($text);
		
		$wordlist = explode(",",extract_words($text));
		$this->dictionaryScan($wordlist,$text);
		
		//$this->wordInBigWordScan($wordlist,$text);
		$this->close();
		
		$this->debug->log("--------------DONE------------");
		
		//merge non-found keywords to be inserted into sentiment_setup
		//$arr = explode(",",$wordlist);
		$arr = $wordlist;
		for($i=0;$i<sizeof($arr);$i++){
			$kw = trim($arr[$i]);
			if(strlen($kw)>0){
				$flag = true;
				for($j=0;$j<sizeof($this->sentiments);$j++){
					if(strcmp(strtolower($this->sentiments[$j]['keyword']),strtolower($kw))==0){
						$flag = false;
						break;
					}
				}	
				if($flag){
					$this->sentiments[] = array("keyword"=>$kw,"sentiment"=>0);
				}
			}
		}
		return $this->sentiments;
	}
	
	function prepareText($text){
		$this->found_words = array();
		$text = strip_non_chars($text);
		return $text;
	}
	function getNegators(){
		//retrieve all negators
		$memcache = new Memcache();
		$data = array("foo"=>"bar");
		$memcache->connect('10.183.192.73', 6969) or die ("Could not connect");
		
		$data = $memcache->get('sentiment_ar_negators');
		$this->debug->info("negators-->".json_encode($data));
		if($data){
			$this->debug->info("negators already in memcache");
			$this->negators = $data;
		}else{
			$sql = "SELECT keyword FROM smac_locale.negator_ar";
			//print $sql.PHP_EOL;
			$this->negators = $this->fetch($sql,1);
			$this->debug->info("getting negators and save it to memcache");
			$memcache->set('sentiment_ar_negators',$this->negators);
		}
		$data = null;
		unset($data);
	}
	function getKeywordsFromDictionary(){
		$memcache = new Memcache();
		$data = array("foo"=>"bar");
		$memcache->connect('10.183.192.73', 6969) or die ("Could not connect");
		$key = "sentiment_ar_";
		$total_data = $memcache->get('sentiment_ar_total_key');
		if($total_data){
			//$this->dict = $data;
			//$this->debug->info("getting dictionary from memcache");
			
			for($i=0;$i<$total_data;$i++){
				$this->dict[] = $memcache->get($key.$i);
			}
			$this->debug->info("getting dictionary and stores it into memcache");
		}else{
			//store all words into memory
			$sql = "SELECT id,keyword,sentiment FROM smac_locale.default_sentiment_val_ar";
			$q = $this->query($sql);
			$n = 0;
			
			while($f = mysql_fetch_assoc($q)){
				$this->dict[] = $f;
				$memcache->set($key.$n,$f);
				$n++;
			}
			 $memcache->set('sentiment_ar_total_key',$n);
			$this->debug->info("getting dictionary and stores it into memcache");
		}
		$data = null;
		unset($data);
	}
	
	function dictionaryScan($wordlist,$text){
		$is_found = false;
		foreach($wordlist as $keyword){
			$keyword = trim($keyword);
			$sql = "SELECT * FROM smac_locale.default_sentiment_ar_utf8 WHERE keyword='{$keyword}' LIMIT 1";
			
			$rs = $this->fetch($sql,1);
			if(sizeof($rs)>0){
				$this->debug->info("{$keyword} found -> ({$rs[0]['id']}) {$rs[0]['keyword']} -> {$rs[0]['sentiment']}");
				$this->debug->info("check for negator");
				$has_negators = $this->negatorScan($keyword,$text);
				if(!$has_negators){
					$this->sentiments[] = array(
										"keyword"=>$rs[0]['keyword'],
										"sentiment"=>$rs[0]['sentiment'],"negator"=>"");
				}
				$this->found_words[] = $keyword;
			}
		}
	}
	function negatorScan($keyword,$text){
		$has_negator = false;
		$negator = "";
		foreach($this->negators as $negate){
			$strCheck = " ".$negate['keyword']." ".$keyword;
			$this->debug->info("check negator {$strCheck}");
			if(@eregi($strCheck,$text)){
				$has_negator=true;
				$negator = $negate['keyword'];
				if($rs[0]['sentiment']>0){
					$rs[0]['sentiment']= -1;
				}else if($rs[0]['sentiment']<0){
					$rs[0]['sentiment']= 1;
				}else{
					$rs[0]['sentiment']= 0;
				}
				$debug->info("negator {$strCheck} found");
				$this->sentiments[] = array(
									"keyword"=>$rs[0]['keyword'],
									"sentiment"=>$rs[0]['sentiment'],
									"negator"=>$negator);
				$is_found = true;
			}
		}
		return $has_negator;
	}
	function wordInBigWordScan($wordlist,$text){
		$this->debug->info("word in bigword scan");
		$this->debug->info("only search for unfound words");
		$this->debug->info("before : ".$text);
		foreach($this->found_words as $found){
			$text = str_replace($found,"",$text);
		}
		$this->debug->info("after : ".$text);
		$strContent = $text;
		$wordlist2 = explode(",",extract_words($strContent));
		$w_found = array();
		foreach($this->dict as $d){
			$k = $d['keyword'];
			$sentiment = $d['sentiment'];
			$pattern = '(.*)('.$k.')(.*)';
			if(@eregi($pattern,$strContent)){
				//$log->info("FOUND {$k}");
				foreach($wordlist2 as $w){
					if(@$c_words[$w]==null){
						$c_words[$w]=0;
					}
					if(@$w_found[$w]==null){
						$w_found[$w] = array();
					}
					if(@eregi($k,$w)){
						$this->debug->info("{$k} -> {$w}");
						$c_words[$w] = 1;
						$w_found[$w][] = array("keyword"=>$d['keyword'],
												"sentiment"=>$d['sentiment']);
					}
				}
			}
		}
		foreach($w_found as $kk=>$vv){
			$big_str = "";
			$sentiment = 0;
			foreach($vv as $nn){
				if(strlen($nn['keyword'])>strlen($big_str)){
					$big_str = $nn['keyword'];
					$sentiment = $nn['sentiment'];
					$feed_id = $nn['feed_id'];
					$keyword_id = $nn['keyword_id'];
				}
			}
			$this->debug->info("we choose {$kk} --> {$big_str}");
			if(strlen($big_str)>0){
				$this->debug->info("check if there's more than keyword(s)");
				foreach($wordlist as $wl){
					if($wl==$kk){
						$this->debug->info("{$wl} == {$kk}");
						$this->debug->info("{$big_str},{$sentiment}");
						$this->sentiments[] = array(
										"keyword"=>$big_str,
										"sentiment"=>$sentiment,"negator"=>'');
					}
				}
			}
			$big_str = "";
		}
		$w_found = null;
		unset($wordlist2);
	}
	function clean_up(){
		unset($this->negators);
	}
}
?>