<?php
class livetrack extends API{
	function execute(){
		$this->loadModel("smac_api","livetrack_model");
		$this->livetrack_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function stats(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->livetrack_model->stats($campaign_id);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function recent_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->livetrack_model->recent_posts($campaign_id,$this->Request->getParam("start"),10);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function map_data(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->livetrack_model->map_data($campaign_id,$this->Request->getParam('since_id'));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function map_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->livetrack_model->map_feeds($campaign_id,$this->Request->getPost("feeds"),$this->Request->getPost('start'));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
}
?>