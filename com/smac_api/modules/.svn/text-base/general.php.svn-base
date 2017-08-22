<?php
/**
 * general API
 */
class general extends API{
	function execute(){
		$this->loadModel("smac_api","general_model");
		$this->general_model->setRequestHandler($this->Request);
		return $this->call();
	}
	//show / hide introductionary tips
	function toggle_tips(){
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		$user_id = $this->getCurrentUser();
		$data = $this->general_model->toggle_tips($user_id);
		$status = 1;
		$msg = "SUCCESS";
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	/**
	 * edit post's sentiment
	 * @param campaign_id
	 * @param feed_id
	 * @param old_sentiment
	 * @param new_sentiment
	 */
	function update_sentiment(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$channel = intval($this->Request->getParam('channel'));
			//set default channel to twitter.
			if($channel==0){$channel=1;}
			
			$feed_id = mysql_escape_string($this->Request->getParam('id'));
			$new_sentiment = intval($this->Request->getParam('sentiment'));
			
			if($channel==1){
				if(strlen($feed_id)>0){
					$sql = "SELECT a.id,sentiment FROM smac_feeds.campaign_feeds_{$campaign_id} a
							INNER JOIN smac_sentiment.campaign_feed_sentiment_{$campaign_id} b
							ON a.id = b.feed_id 
							WHERE a.feed_id='{$feed_id}' LIMIT 1;";
					
					$rs = $this->fetch($sql);
					$status = 1;
					$msg = "SUCCESS";
					$id = $rs['id'];
					$old_sentiment = $rs['sentiment'];
					
					$sql = "UPDATE smac_sentiment.campaign_feed_sentiment_{$campaign_id}
							SET sentiment = {$new_sentiment}
							WHERE feed_id = {$id}";
					
					$q = $this->query($sql);
					if($q){
						//add queue
						$sql = "INSERT INTO `smac_report`.`queue_sentiment_feed_update`
								(campaign_id, campaign_feeds_id, feed_id, old_sentiment, new_sentiment)
								VALUES ({$campaign_id}, {$id}, '{$feed_id}', {$old_sentiment},{$new_sentiment});";
						$qq = $this->query($sql);
						if($qq) $status = 1;
					}
				}
			}else if($channel==2){
				if(strlen($feed_id)>0){
					
					$sql = "SELECT a.id,sentiment 
							FROM smac_fb.campaign_fb a 
							LEFT JOIN smac_fb.campaign_fb_sentiment b 
							ON a.id = b.campaign_fb_id 
							WHERE a.campaign_id={$campaign_id} 
							AND a.feeds_facebook_id='{$feed_id}'
							LIMIT 1;";
					$rs = $this->fetch($sql);
					
					$status = 1;
					$msg = "SUCCESS";
					$campaign_fb_id = $rs['id'];
					$old_sentiment = intval($rs['sentiment']);
					
					$sql = "INSERT INTO smac_fb.campaign_fb_sentiment
							(campaign_fb_id,sentiment) 
							VALUES({$campaign_fb_id},{$new_sentiment})
							ON DUPLICATE KEY UPDATE
							sentiment = VALUES(sentiment);";
					
					$q = $this->query($sql);
					if($q){
						//add queue
						$sql = "INSERT INTO smac_report.queue_sentiment_fb_feed_update 
								(campaign_id,campaign_fb_id,old_sentiment,new_sentiment)
								VALUES ({$campaign_id}, {$campaign_fb_id}, {$old_sentiment}, {$new_sentiment});";
						$qq = $this->query($sql);
						if($qq) $status = 1;
					}
					
				}
					
			}else if($channel==3){
				if(strlen($feed_id)>0){
					
					$sql = "SELECT a.id,sentiment 
							FROM smac_report.campaign_web_feeds a 
							LEFT JOIN smac_gcs.campaign_gcs_sentiment b 
							ON a.id = b.campaign_web_feeds_id
							WHERE a.campaign_id={$campaign_id} 
							AND a.feed_id='{$feed_id}'
							LIMIT 1;";
					$rs = $this->fetch($sql);
					
					$status = 1;
					$msg = "SUCCESS";
					$campaign_web_feeds_id = $rs['id'];
					$old_sentiment = intval($rs['sentiment']);
					
					$sql = "INSERT INTO smac_gcs.campaign_gcs_sentiment
							(campaign_web_feeds_id,sentiment) 
							VALUES({$campaign_web_feeds_id},{$new_sentiment})
							ON DUPLICATE KEY UPDATE
							sentiment = VALUES(sentiment);";
					
					$q = $this->query($sql);
					if($q){
						//add queue
						$sql = "INSERT INTO smac_report.queue_sentiment_gcs_feed_update 
								(campaign_id,campaign_web_feeds_id,old_sentiment,new_sentiment)
								VALUES ({$campaign_id}, {$campaign_web_feeds_id}, {$old_sentiment}, {$new_sentiment});
								";
						$qq = $this->query($sql);
						if($qq) $status = 1;
					}
					
				}
			}else{
				$new_sentiment = 0;
				$old_sentiment = 0;
				$status = 1;
			}
		}
		$this->close();
		return $this->toJson($status,"update_sentiment", array('id'=>$feed_id,'new_sentiment'=>$new_sentiment,
											'old_sentiment'=>$old_sentiment));
	}
}
?>