<?php
class overview extends API{
	function execute(){
		$this->loadModel("smac_api","overview_model");
		$this->overview_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function summary(){
		$group_id = intval($this->Request->getParam("group_id"));
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($group_id>0){
			if($this->isGroupOwner($group_id)){
				$data = $this->overview_model->summary($group_id);
				$status = 1;
				$msg = "SUCCESS";
			}
		}else{
			$data = $this->overview_model->summary_default($this->getOwnerId());
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function topic_groups(){
		//$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		//if($this->isOwner($campaign_id)){
		$owner_id = $this->getOwnerId();
		$data = $this->overview_model->topic_groups($owner_id,$this->Request->getParam("start"),20);
		$status = 1;
		$msg = "SUCCESS";
		//}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
}
?>