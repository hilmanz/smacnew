<?php
include_once $APP_PATH."/smac/helper/FBHelper.php";
class channel_model extends base_model{
	protected $from;
	protected $to;
	protected $request;	
	
	/**
	 * filter data by date range (if available) 
	 */
	function setDateRange($from=null,$to=null){
		$this->from = $from;
		$this->to = $to;
	}
	function setRequestHandler($req){
		$this->request = $req;
	}
	/**
	 * only retrieve 400 days of data.
	 */
	function summary($campaign_id,$twitter_id){
		$twitter_id = mysql_escape_string(cleanXSS($twitter_id));
		$sql = "SELECT dtpost as post_date,author_id,SUM(total_mentions) AS mentions,SUM(imp) AS impression,
				SUM(rt_imp) AS rt_impression,SUM(rt_mention) AS rt_mentions,SUM(sum_sentiment) AS sentiment
				FROM smac_report.campaign_author_daily_stats 
				WHERE campaign_id={$campaign_id} 
				AND author_id='{$twitter_id}'
				GROUP BY dtpost LIMIT 400";
		$rs = $this->fetch($sql,1);
		return $rs;
	}
	/**
	 * retrieve top 100 keywords
	 */
	function wordcloud($campaign_id,$twitter_id){
		$twitter_id = mysql_escape_string(cleanXSS($twitter_id));
		$sql = "SELECT keyword,keyword_total AS total 
				FROM smac_word.campaign_people_wordcloud_{$campaign_id}
				WHERE author_id='{$twitter_id}' 
				ORDER BY keyword_total DESC LIMIT 100;";
		$rs = $this->fetch($sql,1);
		return $rs;
	}
	function feeds($campaign_id,$twitter_id,$last_id=0,$total=20){
		$twitter_id = mysql_escape_string(cleanXSS($twitter_id));
		$last_id = intval($last_id);
		$sql = "SELECT id as feed_id,author_id,published_datetime AS post_date,summary,followers AS impression,
				rt_count AS rt,local_rt_impresion AS rt_impression 
				FROM smac_feeds.campaign_feeds_{$campaign_id}
				WHERE id > {$last_id} 
				AND author_id='{$twitter_id}'
				ORDER BY id ASC
				LIMIT {$total}
				";
		$rs = $this->fetch($sql,1);
		return $rs;
	}
	/**
	 * retrieve unique people counts, people who RTed the $twitter_id
	 */
	function unique_rt_people($campaign_id,$twitter_id){
		$twitter_id = mysql_escape_string(cleanXSS($twitter_id));
		$sql = "SELECT author AS author_id,DATE(posted_date) AS dtreport, COUNT(DISTINCT rt_author) AS total
				FROM smac_rt.rt_content_{$campaign_id} 
				WHERE author='{$twitter_id}' GROUP BY DATE(posted_date);";
		$rs = $this->fetch($sql,1);
		return $rs;
	}
	
}
?>