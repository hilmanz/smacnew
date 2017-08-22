<?php
/**
 * Helper class for utilizing Replay mechanism.
 * @author Hapsoro Renaldy N <hapsoro.renaldy@kana.co.id>
 */
//include_once "apiHelper.php";
class ReplayHelper extends App{
	//var $Request;
	//var $View;
	//var $_mainLayout="";
	//var $user;
	//var $api;
	/*
	function __construct($user=null,$req=null){
		$this->user = $user;
		$this->Request = $req;
		$this->api = new apiHelper();
		$this->View = new BasicView();
	}*/
	function addSubject($campaign_id,$person,$start_from,$until){
		$this->open(0);
		$rule = "from:".$person." OR to:".$person." OR retweets_of:".$person;
		$rule = mysql_escape_string($rule);
		//1. add new rule in tbl_keyword_power with n_status=3
		// we only need the keyword_id.
		$sql="INSERT IGNORE INTO smac_web.tbl_keyword_power(keyword_txt,n_status)
			  VALUES('".$rule."',3)";
		$q = $this->query($sql);
		
		if($q){
			
			$sql = "SELECT keyword_id FROM smac_web.tbl_keyword_power WHERE keyword_txt = '{$rule}' LIMIT 1";
			
			$rs = $this->fetch($sql);
			if($rs['keyword_id']!=null){
				$sql="INSERT IGNORE INTO smac_web.tbl_keyword_replay(keyword_id,keyword_txt)
					  VALUES({$rs['keyword_id']},'".$rule."')";
				$q = $this->query($sql);
			}
		}else{
			
			$q = null;
		}
		if($q){
			//create the job
			$sql = "SELECT keyword_id FROM smac_web.tbl_keyword_replay WHERE keyword_txt = '".mysql_escape_string($rule)."' LIMIT 1";
			$row = $this->fetch($sql);
			
			$keyword_id = $row['keyword_id'];
			$sql = "INSERT IGNORE INTO smac_web.tbl_campaign_replay 
					(campaign_id, keyword_id, n_status, start_from, until, author_id)
					VALUES
					(".$campaign_id.",".$keyword_id.",1,".$start_from.",".$until.",'".mysql_escape_string($person)."')
					";
			if($this->query($sql)){
				$status = 1;
			}else{
				$status = 0;
			}
		}else{
			$status = 0;
		}
		$this->close();
		return json_encode(array("status"=>$status,"response"=>null)); 
	}
	function getSearchStatus($campaign_id,$person){
		
	}
	function getStats($campaign_id,$person){
		
	}
	/**
	 * retrieve the active search queue information
	 * @param $campaign_id
	 */
	function get_queue($campaign_id){
		$this->open(0);
		$sql = "SELECT author_id FROM smac_web.tbl_campaign_replay WHERE campaign_id=".$campaign_id." AND n_status=1 LIMIT 10000";
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
}
?>