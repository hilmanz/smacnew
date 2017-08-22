<?php
class base_model extends SQLData{
	function is_workflow_flag($campaign_id,$feed_id,$type){
		switch($type){
			case 1:
				//twitter
				$sql  = "SELECT id FROM smac_workflow.workflow_marked_tweets_{$campaign_id}
						 WHERE feed_id='{$feed_id}' LIMIT 1";
			break;
			case 2:
				//fb
				$sql  = "SELECT a.id FROM smac_workflow.workflow_marked_fb a
						INNER JOIN smac_fb.campaign_fb b
						ON a.campaign_fb_id = b.id
						WHERE a.campaign_id={$campaign_id} AND b.campaign_id={$campaign_id} 
						AND b.feeds_facebook_id = {$feed_id} 
						LIMIT 1;";
			break;
			case 3:
				//web
				$sql  = "SELECT a.id FROM smac_workflow.workflow_marked_gcs a
						INNER JOIN smac_report.campaign_web_feeds b
						ON a.campaign_web_feeds_id = b.id
						WHERE a.campaign_id={$campaign_id} AND b.campaign_id={$campaign_id}
						AND b.feed_id = {$feed_id} 
						LIMIT 1;";
			break;
			default:
				//do nothing
			break;
		}
		$rs = @$this->fetch($sql);
		if($rs['id']!=null){
			return 1;
		}
		return 0;
	}
	function get_device($subject,$devices){
		foreach($devices as $device){
			if(eregi($device['descriptor'],$subject)){
				return $device['device_type'];
			}
		}
		return "other";
	}
}
?>