<?php
/**
 * a helper for common Topic Information
 * @author duf
 */
class TopicHelper extends Application{
	function getTopics($client_id){
		$languages = array("id"=>"Bahasa Indonesia",
							"my"=>"Malay",
							"en"=>"English",
							"de"=>"German",
							"fr"=>"French",
							"it"=>"Italian",
							"ja"=>"Japanese",
							"nl"=>"Dutch",
							"ko"=>"Korean",
							"no"=>"Norwegian",
							"pt"=>"Portuguese",
							"es"=>"Spanish",
							"sv"=>"Swedish",
							"all"=>"Global",
							"ru"=>"Russian",
							"ar"=>"Arabic");
		$sql = "SELECT id,campaign_name as name,campaign_start as start,campaign_end as end,
				campaign_added as added,n_status,tracking_method,lang,'' as lang_str 
				FROM smac_web.tbl_campaign WHERE client_id={$client_id} AND n_status <> 2 LIMIT 1000";
				
		$this->open(0);
		$this->force_utf8(false);
		$rs = $this->fetch($sql,1);
		$this->force_utf8(true);
		$this->close();
		$n = sizeof($rs);
		
		for($i=0;$i<$n;$i++){
			$rs[$i]['lang_str'] = $languages[$rs[$i]['lang']];
		}
		return $rs;
	}
	function getTopicDetail($id){
		$languages = array("id"=>"Bahasa Indonesia",
							"my"=>"Malay",
							"en"=>"English",
							"de"=>"German",
							"fr"=>"French",
							"it"=>"Italian",
							"ja"=>"Japanese",
							"nl"=>"Dutch",
							"ko"=>"Korean",
							"no"=>"Norwegian",
							"pt"=>"Portuguese",
							"es"=>"Spanish",
							"sv"=>"Swedish",
							"all"=>"Global",
							"ru"=>"Russian",
							"ar"=>"Arabic");
			$sql = "SELECT campaign_name as name,campaign_start as start,campaign_end as end,
					campaign_added as added,n_status,tracking_method,lang,'' as lang_str 
					FROM smac_web.tbl_campaign WHERE id={$id} LIMIT 1";
			$this->open(0);
			$rs = $this->fetch($sql);
			$rs['start'] = $this->getCollectingDataStartDate($id);
			$rs['lang_str'] = $languages[$rs['lang']];
			$this->close();
			return $rs;
	}
	function getTopicUsage($campaign_id,$account_type=0){
		global $CONFIG;
		$this->open(0);
		$sql = "SELECT SUM(t_usage) as total_usage,SUM(t_limit) as total_limit FROM 
				(SELECT (traffic+traffic_fb+traffic_web) as t_usage,0 as t_limit FROM smac_web.campaign_traffic
				WHERE campaign_id={$campaign_id}
				UNION ALL 
				SELECT 0 as t_usage,total_limit as t_limit FROM smac_report.topic_usage WHERE campaign_id={$campaign_id}) c;";
		$rs = $this->fetch($sql);
		$this->close();
		
		$rs['total_limit'] = intval($CONFIG['ACCOUNT_CAP'][$account_type]);
		
		$rs['percentage'] = @round($rs['total_usage'] / $rs['total_limit'] * 100,2);
		return $rs;
	}
	function getEnterpriseUsage($account_id,$account_type){
		if($account_type==5){
			$this->open(0);
			$topics = $this->fetch("SELECT id FROM smac_web.tbl_campaign 
									WHERE client_id = {$account_id}",1);
			$topic_id = "";
			if(is_array($topics)){
				foreach($topics as $n=>$v){
					if($n>0){
						$topic_id.=",";
					}
					$topic_id.=intval($v['id']);
				}
			}
			if(strlen($topic_id)>0){
				$sql = "SELECT SUM(t_usage) as total_usage,SUM(t_limit) as total_limit FROM 
						(SELECT (traffic+traffic_fb+traffic_web) as t_usage,0 as t_limit FROM smac_web.campaign_traffic
						WHERE campaign_id IN ({$topic_id})
						UNION ALL 
							SELECT 0 as t_usage,total_limit as t_limit 
							FROM smac_report.topic_usage 
							WHERE campaign_id IN ({$topic_id})) c;";
				$rs = $this->fetch($sql);
				
			}
			$max_line = $this->fetch("SELECT max_lines FROM smac_web.smac_account_ebm 
									  WHERE account_id = {$account_id} LIMIT 1");
			$this->close();
		}
		if(is_array($rs)){
			$rs['total_limit'] = intval($max_line['max_lines']);
			$rs['percentage'] = @round($rs['total_usage'] / $rs['total_limit'] * 100,2);
		}else{
			$rs = array();
			$rs['total_limit'] = intval($max_line['max_lines']);
			$rs['total_usage'] = 0;
			$rs['percentage'] = 0;
		}
		return $rs;
	}
	public function disable_topic($campaign_id){
		$this->open(0);
		$sql = "UPDATE smac_web.tbl_campaign SET n_status=2 WHERE id = {$campaign_id}";
		$q = $this->query($sql);
		if($q){
			$sql = "UPDATE smac_web.tbl_campaign_keyword SET n_status=0 WHERE campaign_id={$campaign_id}";
			$q1 = $this->query($sql);
		}
		$this->close();
		if($q1){
			return true;
		}
	}
	public function reset_topic($campaign_id){
		$this->open(0);
		//check if these is new topic or not.
		$sql = "SELECT COUNT(*) as total FROM smac_web.campaign_traffic WHERE campaign_id={$campaign_id}";
		$check = $this->fetch($sql);
		if($check['total']==1){
			if($this->_add_reset_history($campaign_id)){
				$q = $this->_reset_usage($campaign_id);
			}
		}else{
			$q = true;
		}
		$this->close();
		return $q;
	}
	private function _add_reset_history($campaign_id){
		$sql = "INSERT INTO smac_web.campaign_reset_history
				(campaign_id, dtreset, month_traffic, month_traffic_fb, month_traffic_web)
				SELECT {$campaign_id}, NOW(), traffic, traffic_fb, traffic_web
				FROM smac_web.campaign_traffic 
				WHERE campaign_id = {$campaign_id};";
		return $this->query($sql);
	}
	private function _reset_usage($campaign_id){
		$sql = "UPDATE smac_web.campaign_traffic
				SET traffic=0,traffic_fb=0,traffic_web=0
				WHERE campaign_id={$campaign_id}";
		return $this->query($sql);
	}
}
?>