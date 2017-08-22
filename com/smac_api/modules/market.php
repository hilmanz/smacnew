<?php
class market extends API{
	function execute(){
		$this->loadModel("smac_api","market_model");
		$this->market_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function summary(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->market_model->summary($campaign_id);
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
			$data = $this->market_model->feeds($campaign_id,$this->Request->getParam("country_code"),
									$this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
}
?>