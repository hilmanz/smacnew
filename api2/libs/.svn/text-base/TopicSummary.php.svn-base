<?php
class TopicSummary{
	var $conn;
	var $campaign_id;
	var $client_id;
	var $lang;
	var $geo;
	function __construct($conn){
		$this->conn = $conn;
	}
	function set_campaign_id($campaign_id){
		$this->campaign_id = $campaign_id;
	}
	function set_client_id($client_id){
		$this->client_id = $client_id;
	}
	function set_language($lang){
		$this->lang = $lang;
	}
	function set_geo($geo){
		$this->geo = $geo;
	}
	function topic_summary(){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		if($lang=='all'){
			$sql = "SELECT potential_impact_score,mentions,sentiment_positive,sentiment_negative,true_reach 
			FROM smac_market.dashboard_summary 
			WHERE campaign_id='".$campaign_id."' AND geo='{$this->geo}' AND client_id='$client_id' LIMIT 1;";
		}else{
			$sql = "SELECT potential_impact_score,mentions,sentiment_positive,sentiment_negative,true_reach 
			FROM smac_market.dashboard_summary_lang 
			WHERE campaign_id='".$campaign_id."' AND geo='{$this->geo}' AND client_id='$client_id' LIMIT 1;";	
		}
		
		$data = fetch($sql,$this->conn);
		$data['pii_score'] = round(($data['potential_impact_score']/$data['mentions']),2);
		return array("action"=>"topic_summary","status"=>1,"message"=>"OK","data"=>$data);
	}
	function top_keywords(){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		$sql = "SELECT keyword,occurance,total_people FROM smac_market.campaign_top_keywords 
				WHERE campaign_id=".$campaign_id." AND geo='{$this->geo}' ORDER BY occurance DESC";
		
		$data = fetch_many($sql,$this->conn);
		
		return array("action"=>"topic_summary","status"=>1,"message"=>"OK","data"=>$data);
	}
	function top_keyword_conversation(){
		//
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		
		$sql = "SELECT true_reach FROM smac_market.dashboard_summary WHERE campaign_id=".$campaign_id." AND geo='{$this->geo}' LIMIT 1";
		$campaign = fetch($sql,$this->conn);
		
		$sql = "SELECT author_id,author_name,author_avatar,content,keyword,impression
				FROM smac_market.campaign_topword_conversation 
				WHERE campaign_id=".$campaign_id." AND geo='{$this->geo}' ORDER BY keyword";
		
		$rs = fetch_many($sql,$this->conn);
		foreach($rs as $r){
			if(@$data[$r['keyword']]==null){
				@$data[$r['keyword']] = array();
			}
			 array_push($data[$r['keyword']],$r);
		}
		return array("action"=>"top_keyword_conversation","status"=>1,"message"=>"OK","data"=>$data,"true_reach"=>$campaign['true_reach']);
	}
	function top_influencers(){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		
		$sql = "SELECT true_reach FROM smac_market.dashboard_summary WHERE campaign_id=".$campaign_id." AND geo='{$this->geo}' LIMIT 1";
		$campaign = fetch($sql,$this->conn);
		
		$sql = "SELECT a.author_id,b.author_name as author,a.author_avatar as pic,a.sentiment,a.pii_score,b.total_mentions,b.impression,b.rt_impression,b.rt_mention 
				FROM smac_market.campaign_ambas a
				INNER JOIN smac_market.campaign_people_summary b
				ON a.author_id = b.author_id
				WHERE a.campaign_id = ".$campaign_id." AND b.campaign_id=".$campaign_id."
				AND a.geo='{$this->geo}'
				AND a.geo='{$this->geo}'
				AND a.campaign_id=b.campaign_id
				ORDER BY sentiment DESC LIMIT 25";
		$ambas = fetch_many($sql,$this->conn);
		foreach($ambas as $n=>$v){
			$ambas[$n]['share'] = round((($ambas[$n]['impression']+$ambas[$n]['rt_impression'])/$campaign['true_reach'])*100,6);
		}
		
		$sql = "SELECT a.author_id,b.author_name as author,a.author_avatar as pic,a.sentiment,a.pii_score,
				b.total_mentions,b.impression,b.rt_impression,b.rt_mention 
				FROM smac_market.campaign_trolls a
				INNER JOIN smac_market.campaign_people_summary b
				ON a.author_id = b.author_id
				WHERE a.campaign_id = ".$campaign_id." AND b.campaign_id=".$campaign_id."
				AND a.geo='{$this->geo}'
				AND b.geo='{$this->geo}'
				AND a.campaign_id=b.campaign_id
				ORDER BY sentiment ASC LIMIT 25";
		
		$troll = fetch_many($sql,$this->conn);
		
		foreach($troll as $n=>$v){
			$troll[$n]['share'] = round((($troll[$n]['impression']+$troll[$n]['rt_impression'])/$campaign['true_reach'])*100,6);
		}
		
		$data = array("ambassador"=>$ambas,"troll"=>$troll);
		return array("action"=>"top_influencers","status"=>1,"message"=>"OK","data"=>$data);
	}
	function top_100_posts(){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		$sql = "SELECT true_reach FROM smac_report.dashboard_summary WHERE campaign_id=".$campaign_id." LIMIT 1";
		$campaign = fetch($sql,$this->conn);
		
		$sql = "SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, published_date,followers as imp,generator,content
				FROM (SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
				FROM smac_report.campaign_feeds a
				INNER JOIN smac_data.tbl_rt_content b
				ON a.feed_id = b.feed_id
				INNER JOIN smac_report.country_twitter c
				ON a.feed_id = c.feed_id
				WHERE a.campaign_id=".$campaign_id."
				AND c.country_code='{$this->geo}'
				GROUP BY b.feed_id) a
				ORDER BY rt_total DESC LIMIT 100";
		
		$rs = fetch_many($sql,$this->conn);
		return array("action"=>"top_100_posts","status"=>1,"message"=>"OK","data"=>$rs,"true_reach"=>$campaign['true_reach']);
	}
	function key_issues(){
		$lang = $this->lang;
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		$sql = "SELECT keyword,occurance,sentiment FROM smac_market.campaign_keyissues 
				WHERE campaign_id=".$campaign_id."
				AND geo='{$this->geo}'
				ORDER BY occurance DESC;";
		
		$data = fetch_many($sql,$this->conn);
		
		return array("action"=>"key_issues","status"=>1,"message"=>"OK","data"=>$data);
	}
	function branded_account_report(){
		$campaign_id = $this->campaign_id;
		$client_id = $this->client_id;
		$sql = "SELECT author_id FROM smac_market.campaign_brand_feeds
				WHERE campaign_id=".$campaign_id." AND geo='{$this->geo}' GROUP BY author_id";
		
		$authors = fetch_many($sql,$this->conn);
		foreach($authors as $n=>$v){
			$author_id = $v['author_id'];
			
			//total impressions
			$sql = "SELECT true_reach as total_impression
					FROM smac_market.dashboard_summary 
					WHERE campaign_id=".$campaign_id."
					AND geo='{$this->geo}' 
					LIMIT 1";
			$rs = fetch($sql,$this->conn);
		
			$total_impression = $rs['total_impression'];
			$rs = null;
			
			$sql = "SELECT author_id,FLOOR(SUM(followers)/COUNT(author_id)) as followers 
						FROM smac_report.campaign_feeds a
						INNER JOIN smac_report.country_twitter b
						ON a.feed_id = b.feed_id
						WHERE a.campaign_id=".$campaign_id." AND b.country_code='{$this->geo}' 
						AND a.author_id='".mysql_escape_string($author_id)."' GROUP BY author_id LIMIT 1";
			
			$rs = fetch($sql,$this->conn);
			$followers = $rs['followers'];
			$rs = null;
			//retrieve stats
			
			
			$sql = "SELECT total_mentions,impression,rt_impression,
					rt_mention,rank
					FROM smac_market.campaign_people_summary a
					INNER JOIN smac_market.campaign_people_rank b
					ON a.id = b.ref_id
					WHERE a.campaign_id=".$campaign_id." 
					AND a.author_id='".mysql_escape_string($author_id)."'
					AND a.geo='{$this->geo}' 
					LIMIT 1";
			
			$rs = fetch($sql,$this->conn);
			$rs['total_impression'] = ($rs['impression']+$rs['rt_impression']);
			$rs['share_percentage'] = round(($rs['total_impression'] / $total_impression) * 100,2);
			$rs['overall_impression'] = $total_impression;
			$rs['rt_percentage'] = round($rs['rt_mention'] / ($rs['rt_mention']+$rs['total_mentions']) * 100,2);
			$rs['followers'] = $followers;
			$authors[$n]['stats'] = $rs;
			$rs = null;
			
			//retrieve tweets
			$sql = "SELECT a.author_id,a.feed_id,a.imp,a.rt,a.rt_imp,b.published_date,b.content,b.author_name,b.author_avatar 
					FROM smac_market.campaign_brand_feeds a
					INNER JOIN smac_report.campaign_feeds b
					ON a.feed_id = b.feed_id
					INNER JOIN smac_report.country_twitter c
					ON b.feed_id = c.feed_id
					WHERE a.campaign_id=".$campaign_id." 
					AND a.author_id='".mysql_escape_string($author_id)."'
					AND b.campaign_id=".$campaign_id."
					AND c.country_code = '{$this->geo}' 
					ORDER BY rt DESC LIMIT 10";
			$rs = fetch_many($sql,$this->conn);
			foreach($rs as $nn=>$v){
				$rs[$nn]['share'] = round((($v['imp']+$v['rt_imp'])/$total_impression) * 100,5);
			}
			$authors[$n]['tweets'] = $rs;
			$rs = null;
			
			
		}	
		return array("action"=>"branded_account_report","status"=>1,"message"=>"OK","data"=>$authors);
	}
}
?>