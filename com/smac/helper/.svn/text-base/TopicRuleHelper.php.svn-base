<?php
class TopicRuleHelper{
	protected $all_of_these;
	protected $any_of_these;
	protected $except_of_these;
	/**
	 * constructor
	 * 
	 */
	function __construct(){
		
	}
	/**
	 * 
	 * @param $aKeywords a set of matched keyword, exclude keywords, 
	 * and match-any-of-these keywords
	 */
	function keywords($aKeywords){
		$aKeywords['all'] = str_replace(" ",",",$aKeywords['all']);
		//$aKeywords['any'] = str_replace(" ",",",$aKeywords['any']);
		//$aKeywords['except'] = str_replace(" ",",",$aKeywords['except']);
		
		$this->all_of_these = explode(",",$aKeywords['all']);
		$this->any_of_these = explode(",",$aKeywords['any']);
		$this->except_of_these = explode(",",$aKeywords['except']);
		//$this->language = $aKeywords['lang'];
	}
	/**
	 * return a string of ruleset
	 */
	function toString(){
		$all = $this->constructAllOfThese();
		$any = $this->constructAnyOfThese();
		$exclude = $this->constructExcludeThese();
		//$lang = $this->constructLanguage();
		$str = "";
		if(strlen($all)>0){
			$str.=$all;
			if(strlen($any)>0){
				$str.=" ".$any;
			}
			if(strlen($exclude)>0){
				$str.=" ".$exclude;
			}
			if(strlen($lang)>0){
				$str.=" ".$lang;
			}
		}
		$this->cleaningUp();
		return $str;
	}
	function constructLanguage(){
		if($this->language=="my"||$this->language=="all"||$this->language==""||strlen($this->language)==0){
			return "";
		}else{
			return "lang:".$this->language;
		}
	}
	function cleaningUp(){
		$this->all_of_these = null;
		$this->any_of_these = null;
		$this->except_of_these = null;
	}
	function constructAllOfThese(){
		$all_of_these = "";
		$n=0;
		foreach($this->all_of_these as $v){
			$v = $this->reformat($v);
			if(strlen($v)>0){
				if($n!=0){
					$all_of_these.=" ";
				}
				$all_of_these.= trim($v);
				$n++;
			}
		}
		
		$n=null;
		$v=null;
		return $all_of_these;
	}
	function constructAnyOfThese(){
		
		$any_of_these = "";
		$n=0;
		
		if(sizeof($this->any_of_these)>0){
			//$any_of_these.="(";
			
			foreach($this->any_of_these as $v){
				$v = $this->reformat($v);
				if(strlen(trim($v))>0){
					if($n!=0){
						$any_of_these.=" OR ";
					}
					$any_of_these.= trim($v);
					$n++;
				}
			}
			//$any_of_these.=")";
			$v=null;
		}
		
		if($n>0){
			$any_of_these="(".$any_of_these.")";
		}
		return $any_of_these;
	}
	function constructExcludeThese(){
		$exclude = "";
		$n=0;
		foreach($this->except_of_these as $v){
			$v = $this->reformat($v);
			if(strlen($v)>0){
				if($n!=0){
					$exclude.=" ";
				}
				$exclude.= "-(".trim($v).")";
				$n++;
			}
		}
		$n=null;
		$v=null;
	
		return $exclude;
	}
	function reformat($str){
		$str = trim($str);
		$str = str_replace("“","\"",$str);
		$str = str_replace("”","\"",$str);
		$str = str_replace("’","'",$str);
		return $str;
	}
	
}
?>