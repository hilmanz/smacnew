<?php
/**
 * Feeds WebService
 */
class feeds extends API{
	function execute(){
		$this->loadModel("smac_api","feeds_model");
		$this->feeds_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function by_date(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->feeds_model->feed_by_date($campaign_id,$this->Request->getParam("channel"),
														  $this->Request->getParam("date"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_by_date(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->feeds_model->site_feed_by_date($campaign_id,$this->Request->getParam("channel"),
														  $this->Request->getParam("date"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function video_comments(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->feeds_model->video_comments($campaign_id,$this->Request->getParam("video_id"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	
}
?>