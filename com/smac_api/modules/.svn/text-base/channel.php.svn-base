<?php
class channel extends API{
	function execute(){
		$this->loadModel("smac_api","channel_model");
		$this->channel_model->setDateRange($this->Request->getParam("from"),$this->Request->getParam("to"));
		$this->channel_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function summary(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->channel_model->conn = $this->conn;
			$data = $this->channel_model->summary($campaign_id,
												$this->Request->getParam('twitter_id'));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function wordcloud(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->channel_model->conn = $this->conn;
			$data = $this->channel_model->wordcloud($campaign_id,
												$this->Request->getParam('twitter_id'));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->channel_model->conn = $this->conn;
			$data = $this->channel_model->feeds($campaign_id,
												$this->Request->getParam('twitter_id'),
												$this->Request->getParam("last_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function unique_rt_people(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->channel_model->conn = $this->conn;
			$data = $this->channel_model->unique_rt_people($campaign_id,
												$this->Request->getParam('twitter_id'));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
}
?>