<?php
/**
 * 
 * @author duf
 * @todo
 * helper for inform Reporting Bot.
 */
class BotHelper extends Application{
	function refresh_report($campaign_id,$start_time){
		$campaign_id = mysql_escape_string($campaign_id);
		$start_time = mysql_escape_string($start_time);
		//1. find unactive job for these topic
		$sql = "SELECT id,campaign_id,request_date,run_time,end_time FROM 
				smac_report.job_report_refresh 
				WHERE campaign_id={$campaign_id} 
				AND
				n_status=0
				LIMIT 1";
		$inactive = $this->fetch($sql);
		if($inactive['campaign_id']==$campaign_id){
			//there is existing job
			$sql = "UPDATE smac_report.job_report_refresh SET run_time='{$start_time}'
					WHERE id = {$inactive['id']}";
			
			$this->query($sql);
		
		}else{
			//no existing job
			$sql = "INSERT INTO smac_report.job_report_refresh 
					(campaign_id, request_date, run_time, end_time, n_status)
					VALUES
					({$campaign_id}, NOW(), '{$start_time}', '0000-00-00 00:00:00', 0)";
			
			$this->query($sql);
		}
	}
}
?>