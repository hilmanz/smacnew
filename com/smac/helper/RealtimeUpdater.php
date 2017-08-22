<?php
/**
 * 
 * @author duf
 * @todo
 * its an helper for doing real-time update activity such as edit sentiment which require
 * the sentiment calculation across the topic recalculated.
 */
class RealtimeUpdater extends Application{
	
	function update_sentiment($campaign_id,$when=0){
		$f = false;
		$campaign_id = intval($campaign_id);
		if($when==0){
			$when = time()+15*60;
		}
		$this->open(0);
		$sql = "INSERT INTO smac_report.job_recalculate_sentiment_status (campaign_id, n_status, dtscheduled)
				VALUES ({$campaign_id}, 1,FROM_UNIXTIME({$when}))
				ON DUPLICATE KEY UPDATE  
				n_status = VALUES (n_status), 
				dtscheduled = VALUES (dtscheduled);";
		
		$q = $this->query($sql);
		$this->close();
		if($q){
			$f = true;
		}
		return $f;
	}
}
?>