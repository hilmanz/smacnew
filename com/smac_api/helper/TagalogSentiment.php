<?php
class TagalogSentiment extends API{
	function getSentiments($text){
		$text = urldecode($text);
		$wordlist = extract_words($text);
		$sqlStr = $this->toSqlArray($wordlist);
		$this->open(0);
		$sql = "SELECT keyword,weight as sentiment 
				FROM smac_locale.default_sentiment_val_ph
				WHERE keyword IN ($sqlStr);";
						
		$rs = @$this->fetch($sql,1);
		$this->close();
		$arr = explode(",",$wordlist);
		for($i=0;$i<sizeof($arr);$i++){
			$kw = trim($arr[$i]);
			if(strlen($kw)>0){
				$flag = true;
				for($j=0;$j<sizeof($rs);$j++){
					if(strcmp(strtolower($rs[$j]['keyword']),strtolower($kw))==0){
						$flag = false;
						break;
					}
				}
				if($flag){
					$rs[] = array("keyword"=>$kw,"sentiment"=>0);
				}	
			}
		}
		return $rs;
	}
}
?>