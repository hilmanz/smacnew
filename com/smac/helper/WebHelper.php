<?php
/**
 * 
 * @author duf
 * @todo
 * WebHelper
 * a helper to show Web Feeds data
 */
class WebHelper extends Application{
	
	function get_summary($campaign_id,$from,$to,$geo=''){
		if($geo!=null){
			return $this->get_summary_geo($campaign_id,$from,$to,$geo);
		}else{
			if($from==NULL||$to==NULL){
				$sql = "SELECT
						SUM(total_mention_web) as total_posts, 
						SUM(total_author_web) as total_websites
						FROM smac_report.campaign_rule_volume_history 
						WHERE campaign_id={$campaign_id};";
			}else{
				$sql = "SELECT
						SUM(total_mention_web) as total_posts, 
						SUM(total_author_web) as total_websites
						FROM smac_report.campaign_rule_volume_history 
						WHERE campaign_id={$campaign_id}
						AND dtreport BETWEEN '{$from}' AND '{$to}' ";
			}
			$this->open(0);
			$rs = $this->fetch($sql);
			$this->close();
			return $rs;
		}
	}
	function get_summary_geo($campaign_id,$from,$to,$geo=''){
		
			if($from==NULL||$to==NULL){
				$sql = "SELECT
						SUM(total_mention) as total_posts, 
						SUM(total_author) as total_websites
						FROM smac_report.daily_country_volume 
						WHERE campaign_id={$campaign_id} AND country_id='{$geo}' 
						AND channel=3;";
			}else{
				$sql = "SELECT
						SUM(total_mention) as total_posts, 
						SUM(total_author) as total_websites
						FROM smac_report.daily_country_volume 
						WHERE campaign_id={$campaign_id} AND country_id='{$geo}' 
						AND channel=3
						AND dtreport BETWEEN '{$from}' AND '{$to}' ";
			}
			$this->open(0);
			$rs = $this->fetch($sql);
			$this->close();
			
			return $rs;
		
	}
	function getKOLGeo($campaign_id,$from,$to,$geo=''){
		$geo = mysql_escape_string($geo);
		if($from==null||$to==NULL){
			$sql = "SELECT author_name,author_uri,COUNT(author_name) as total FROM smac_report.campaign_web_feeds a
					INNER JOIN smac_report.campaign_web_country b
					ON a.id = b.fid
					WHERE a.campaign_id={$campaign_id} 
					AND b.geo='{$geo}'
					GROUP BY author_name 
					ORDER BY total DESC
					LIMIT 5";
		}else{
			$start_ts = strtotime($from);
			$end_ts = strtotime($to);
			$sql = "SELECT author_name,author_uri,COUNT(author_name) as total FROM smac_report.campaign_web_feeds a
					INNER JOIN smac_report.campaign_web_country b
					ON a.id = b.fid 
					WHERE campaign_id={$campaign_id} AND b.geo='{$geo}'
					AND published_ts >= {$start_ts} AND published_ts<= {$end_ts}
					GROUP BY author_name 
					ORDER BY total DESC
					LIMIT 5";
		}
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function getKOL($campaign_id,$from,$to){
		
		if($from==null||$to==NULL){
			$sql = "SELECT author_name,author_uri,COUNT(author_name) as total FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} GROUP BY author_name 
					ORDER BY total DESC
					LIMIT 5";
		}else{
			$start_ts = strtotime($from);
			$end_ts = strtotime($to);
			$sql = "SELECT author_name,author_uri,COUNT(author_name) as total FROM smac_report.campaign_web_feeds 
					WHERE campaign_id={$campaign_id} 
					AND published_ts >= {$start_ts} AND published_ts<= {$end_ts}
					GROUP BY author_name 
					ORDER BY total DESC
					LIMIT 5";
		}
				
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function get_wordcloud($campaign_id,$from,$to){
		
		if($from==null||$to==NULL){
		
			$sql = "SELECT keyword,SUM(total) as occurance
			        FROM smac_gcs.gcs_wordlist_{$campaign_id}
			        WHERE EXISTS (
			                        SELECT 1 FROM smac_report.campaign_web_feeds g
			                        WHERE g.id = smac_gcs.gcs_wordlist_{$campaign_id}.campaign_web_feeds_id
			                                AND g.campaign_id = {$campaign_id}
			                )
			        GROUP BY keyword
			        ORDER BY occurance DESC LIMIT 50";
		} else {

			$from_date = strtotime(mysql_escape_string($from));
			$to_date = strtotime(mysql_escape_string($to));
						
			$sql = "SELECT keyword,SUM(total) as occurance
			        FROM smac_gcs.gcs_wordlist_{$campaign_id}
			        WHERE EXISTS (
			                        SELECT 1 FROM smac_report.campaign_web_feeds g
			                        WHERE g.id = smac_gcs.gcs_wordlist_{$campaign_id}.campaign_web_feeds_id
			                                AND g.campaign_id = {$campaign_id}
			                                AND g.published_ts BETWEEN {$from_date} and {$to_date}
			                )
			        GROUP BY keyword
			        ORDER BY occurance DESC LIMIT 50";		
					}
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$keywords = array();
		foreach($rs as $r){
			if(strlen($r['keyword'])>1){
				$keywords[] = $r;
			}
		}
		$this->close();
		$rs = null;
		
		return $keywords;
	}
	function getTotalFeeds($campaign_id){
		$this->open(0);
		$sql = "SELECT COUNT(*) as total FROM smac_report.campaign_web_feeds WHERE campaign_id={$campaign_id} LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		return $rs['total'];
	}
}
?>