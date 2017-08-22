<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/WordCloud.php";
class wordcloudHelper extends Wordcloud{
	var $sentiment_style = array();
	var $handler="wc";
	var $urlto = "javascript:void(0);";
	var $callback_func = "";
	var $use_callback = true;
	function setHandler($handler){
		$this->handler = $handler;
	}
	function set_sentiment_style($style){
		$this->sentiment_style = $style;
	}
	function callback($params){
		if($this->callback_func!=""){
			$this->urlto="javascript:void(0);";
			return "onclick=\"".$this->callback_func."(".($params).")\"";
		}
	}
	/*
	function writeHTML($t,$n=0){
		global $req;
		if($t['is_main']==1){
			$style = $this->sentiment_style['main_keyword'];
		}else{
			if($t['sentiment']>0){
				$style = $this->sentiment_style['positive'];
			}else if($t['sentiment']<0){
				$style = $this->sentiment_style['negative'];
			}else{
				$style = $this->sentiment_style['neutral'];
			}
		}
		// class="poplight" rel="popup-unmark"
		$arr = array("subdomain"=>$req->getParam('subdomain'),'page' => 'workflow','act'=>'getTweets','keyword'=>$t['txt'],'ajax'=>1);
		$workflow_url = str_replace("req=","",$req->encrypt_params($arr));
		$str= "<span id='".$this->handler.$n."' class='wc".ceil($t['size'])." ".$style."' style='top:".$t['y']."px;left:".$t['x']."px;position:absolute;overflow:hidden;float:left;'><a href='".$this->urlto.$workflow_url."' class='poplight ".$style."' style='float:left;text-decoration:none;font-family:verdana;width:".strlen($t['txt'])."em;height:".$t['height']."px;' ".$this->callback("'".$_GET['key']."','".$t['txt']."',".$_GET['id'])." rel=\"popup-unmark\">".$t['txt']."</a></span>";
		
		return $str;
	}
	*/
	function writeHTML($t,$n=0){
		global $req;
		if($t['is_main']==1){
			$style = $this->sentiment_style['main_keyword'];
		}else{
			if($t['sentiment']>0){
				$style = $this->sentiment_style['positive'];
			}else if($t['sentiment']<0){
				$style = $this->sentiment_style['negative'];
			}else{
				$style = $this->sentiment_style['neutral'];
			}
		}
		// class="poplight" rel="popup-unmark"
		
		
		$arr = array("subdomain"=>$req->getParam('subdomain'),'page' => 'workflow','act'=>'getTweets','keyword'=>$t['txt'],'ajax'=>1);
		$workflow_url = str_replace("req=","",$req->encrypt_params($arr));
		if($this->urlto=="javascript:void(0);"||$this->urlto=="#"){
			$urlto = $this->urlto;
		}else{
			$urlto = $this->urlto.$workflow_url;
		
		}
		if(!$this->use_callback){
			$urlto="#";
		}
			$str= "<span id='".$this->handler.$n."' class='wc".ceil($t['size'])." ".$style."' ><a id='a_".$this->handler.$n."' href='".$urlto."' class='poplight ".$style."'  style='position:absolute;float:left;text-decoration:none;font-family:verdana;padding:2px;top:".$t['y']."px;left:".$t['x']."px' ".$this->callback("'".$_GET['key']."','".$t['txt']."',".$_GET['id'])." rel=\"popup-unmark\">".$t['txt']."</a></span>";
		
		return $str;
	}
}
?>