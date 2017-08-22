<?php
/**
 * Keyword Analysis Web Service
 */
class keyword extends API{
	function execute(){
		$this->loadModel("smac_api","keyword_model");
		$this->keyword_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function summary_by_rule(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->summary_by_rule($campaign_id,$this->Request->getParam("from"),
														  $this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	/**
	 * get summary by top keywords
	 * available options : 
	 * type=1 (top10), type=2 (top50)
	 */
	function top(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->summary_by_top_keywords($campaign_id,$this->Request->getParam("type"),
														  $this->Request->getParam("from"),
														  $this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function get_rule_breakdown(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->get_rule_breakdown($campaign_id,$this->Request->getParam("from"),
														  $this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function conversation(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->conversation($campaign_id,$this->Request->getParam("start"),5);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function conversation_by_date(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->conversation_by_date($campaign_id,
														$this->Request->getParam("rule_id"),
														$this->Request->getParam("dt"),
														$this->Request->getParam("start"),10);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_conversation_by_date(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->fb_conversation_by_date($campaign_id,
														$this->Request->getParam("rule_id"),
														$this->Request->getParam("dt"),
														$this->Request->getParam("start"),10);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_conversation_by_date(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->web_conversation_by_date($campaign_id,
														$this->Request->getParam("type"),
														$this->Request->getParam("rule_id"),
														$this->Request->getParam("dt"),
														$this->Request->getParam("start"),10);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function top_keywords(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->top_keyword_by_rule($campaign_id,$this->Request->getParam("rule_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function rule_daily(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->rule_daily($campaign_id,$this->Request->getParam("rule_id"),
													$this->Request->getParam("from"),
													$this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function top_keyword_conversation(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->top_keyword_conversation($campaign_id,
																   $this->Request->getParam("keyword"),
																   $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function keyword_sentiment(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->keyword_sentiment($campaign_id);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		//return $this->toJson($status,$msg, $data);
		return json_encode($data);
	}
	
	function sentiment_tweet(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->sentiment_tweet($campaign_id,
														$this->Request->getParam("type"),
														$this->Request->getParam("dt"),
														 $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		//return $this->toJson($status,$msg, $data);
		return json_encode($data);
	}
	function fb_sentiment_post(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->fb_sentiment_post($campaign_id,
														$this->Request->getParam("type"),
														$this->Request->getParam("dt"),
														 $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		//return $this->toJson($status,$msg, $data);
		return json_encode($data);
	}
	function web_sentiment_post(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->web_sentiment_post($campaign_id,
														$this->Request->getParam("group"),
														$this->Request->getParam("type"),
														$this->Request->getParam("dt"),
														 $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		//return $this->toJson($status,$msg, $data);
		return json_encode($data);
	}
	function sentiment_graph(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->sentiment_graph($campaign_id,$this->Request->getParam("from"),$this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function update_keyword_sentiment(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->update_keyword_sentiment($campaign_id,$this->Request->getParam("id"),$this->Request->getParam("value"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	
	function response_list(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->keyword_model->response_list($campaign_id,
														$this->Request->getParam("channel"),
														 $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		//return $this->toJson($status,$msg, $data);
		return json_encode($data);
	}
}
?>