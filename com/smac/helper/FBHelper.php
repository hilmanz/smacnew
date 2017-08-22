<?php
/**
 * 
 * @author duf
 * @todo
 * need to build paypal integration
 */
class FBHelper extends Application{
	function get_summary($campaign_id,$from,$to){
		if($from==null||$to==NULL){
			$sql = "SELECT COUNT(author_id) AS total_people,SUM(mentions) AS total_mentions,SUM(likes) AS total_likes
					FROM smac_fb.daily_fb_people_stat
					WHERE campaign_id={$campaign_id} LIMIT 1;";
		}else{
			$sql = "SELECT COUNT(author_id) AS total_people,SUM(mentions) AS total_mentions,SUM(likes) AS total_likes
					FROM smac_fb.daily_fb_people_stat
					WHERE campaign_id={$campaign_id}
					AND published_date >='{$from}'
					AND published_date <='{$to}'
					LIMIT 1;";
		}
		$this->open(0);
		$rs = $this->fetch($sql);
		
		//temporarily query the stats from smac_report.fb_people_stats
		$performance = $this->getPerformanceIndicator($campaign_id,$from,$to);
		$rs['performance'] = $performance;
		$this->close();
		return $rs;
	}
	function getPerformanceIndicator($campaign_id,$from,$to){
		$this->open(0);
		

		if($from!=null&&$to!=null){
			$sql = "SELECT * FROM (
					SELECT published_date,SUM(mentions) as mentions 
					FROM smac_fb.daily_fb_people_stat 
					WHERE campaign_id={$campaign_id} AND published_date BETWEEN {$from} AND {$to}  
					GROUP BY published_date) a
					WHERE mentions <> 0 ORDER BY published_date DESC LIMIT 2;";
			$rs = $this->fetch($sql,1);
			
			
			$sql = "SELECT * FROM (
					SELECT published_date,SUM(likes) as total_likes 
					FROM smac_fb.daily_fb_people_stat 
					WHERE campaign_id={$campaign_id} AND published_date BETWEEN {$from} AND {$to} 
					GROUP BY published_date) a
					WHERE total_likes <> 0 ORDER BY published_date DESC LIMIT 2;";
			
			$rs2 = $this->fetch($sql,1);
		}else{
			$sql = "SELECT * FROM (
					SELECT published_date,SUM(mentions) as mentions 
					FROM smac_fb.daily_fb_people_stat 
					WHERE campaign_id={$campaign_id} AND published_date
					GROUP BY published_date) a
					WHERE mentions <> 0 ORDER BY published_date DESC LIMIT 2;";
			$rs = $this->fetch($sql,1);
			
			
			$sql = "SELECT * FROM (
					SELECT published_date,SUM(likes) as total_likes 
					FROM smac_fb.daily_fb_people_stat 
					WHERE campaign_id={$campaign_id} AND published_date 
					GROUP BY published_date) a
					WHERE total_likes <> 0 ORDER BY published_date DESC LIMIT 2;";
			
			$rs2 = $this->fetch($sql,1);
		}
		//-->
		
		$this->close();
		$mention_change = 0;
		$like_change = 0;
		$like_diff = 0;
		$mention_diff = 0;
		
		
		if(sizeof($rs)==2){
			$mention_diff = $rs[0]['mentions'] - $rs[1]['mentions'];
			$mention_change = round((($rs[0]['mentions'] - $rs[1]['mentions'])/$rs[1]['mentions'])*100,2);
		}else{
			$mention_diff = intval($rs[0]['mentions']);
		}
		if(sizeof($rs2)==2){
			$like_change = round((($rs2[0]['total_likes'] - $rs2[1]['total_likes'])/$rs2[1]['total_likes'])*100,2);
			$like_diff = ($rs2[0]['total_likes'] - $rs2[1]['total_likes']);
		}else{
			$like_diff = intval($rs2[0]['total_likes']);
		}
		$result['mention_change'] = $mention_change;
		$result['like_change'] = $like_change;
		$result['mention_diff']= $mention_diff;
		$result['like_diff'] = $like_diff;

		return $result;
	}
	function getKOL($campaign_id,$from,$to){
		if($from==null||$to==NULL){
			$sql = "SELECT author_id,author_name,SUM(likes) as total,SUM(mentions) as mentions
			        FROM smac_fb.daily_fb_people_stat
			        WHERE campaign_id={$campaign_id}
			        GROUP BY author_id,author_name
			        ORDER BY total DESC
			        LIMIT 5";
			
		}else{
			$sql = "SELECT author_id,author_name,SUM(likes) as total,SUM(mentions) as mentions
			        FROM smac_fb.daily_fb_people_stat
			        WHERE campaign_id={$campaign_id}
			        AND published_date >='{$from}'
					AND published_date <='{$to}'
			        GROUP BY author_id,author_name
			        ORDER BY total DESC
			        LIMIT 5";
		}
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function get_wordcloud($campaign_id,$from,$to){
			
		$from_date_ts = strtotime(mysql_escape_string($from));
		$to_date_ts = strtotime(mysql_escape_string($to));
		
			
		
		if($from==null||$to==NULL){
			$sql = "SELECT a.keyword,COUNT(a.keyword) as total
			        FROM smac_fb.fb_wordlist_{$campaign_id} a
	                INNER JOIN smac_fb.campaign_fb b
	                ON a.fid = b.id
			        WHERE b.campaign_id={$campaign_id}
			        GROUP BY keyword
			        ORDER BY total DESC LIMIT 30";
		}else{
			$sql = "SELECT a.keyword,COUNT(a.keyword) as total
			        FROM smac_fb.fb_wordlist_{$campaign_id} a
	                INNER JOIN smac_fb.campaign_fb b
	                ON a.fid = b.id
	                AND b.created_time_ts BETWEEN {$from_date_ts} and {$to_date_ts}
			        WHERE b.campaign_id={$campaign_id}
			        GROUP BY keyword
			        ORDER BY total DESC LIMIT 30";
		}
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
}
?>