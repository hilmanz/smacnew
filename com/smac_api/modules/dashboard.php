<?php
class dashboard extends API{
	function execute(){
		$this->loadModel("smac_api","dashboard_model");
		$this->dashboard_model->setDateRange($this->Request->getParam("from"),$this->Request->getParam("to"));
		$this->dashboard_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function summary(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->dashboard_model->summary($campaign_id);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->twitter($campaign_id);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->fb($campaign_id,
												$this->Request->getParam("from"),
												$this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->web($campaign_id,$this->Request->getParam("from"),$this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	/**
	 * @param campaign_id
	 * @param type -> 0. Common Web, 1. Blog, 2.Forum, 3. News 4. Video Sites
	 * currently using  1. Blog, 2.Forum, 3. News
	 */
	function site(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->site($campaign_id,$this->Request->getParam('type'),
												 $this->Request->getParam("from"),
												 $this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function video(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->video($campaign_id,$this->Request->getParam("from"),$this->Request->getParam("to"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function my_channel(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		return $this->toJson($status,$msg, $data);
	}
	function summary_top_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->summary_top_posts($campaign_id,
																$this->Request->getParam("from"),
																$this->Request->getParam("to"));
		
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_top_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->twitter_top_posts($campaign_id,
																$this->Request->getParam("from"),
																$this->Request->getParam("to"));
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_top_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->fb_top_posts($campaign_id,
																$this->Request->getParam("from"),
																$this->Request->getParam("to"));
		
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_top_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->web_top_posts($campaign_id,
																$this->Request->getParam("from"),
																$this->Request->getParam("to"));
		
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_top_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->site_top_posts($campaign_id,
																$this->Request->getParam("type"),
																$this->Request->getParam("from"),
																$this->Request->getParam("to"));
		
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function video_top_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->video_top_posts($campaign_id,
																$this->Request->getParam("from"),
																$this->Request->getParam("to"));
		
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->twitter_posts($campaign_id,
																$this->Request->getParam("start"),
																10);
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->fb_posts($campaign_id,
																$this->Request->getParam("start"),
																10);
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->web_posts($campaign_id,
														$this->Request->getParam("start"),
														10);
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->site_posts($campaign_id,$this->Request->getParam("type"),
														$this->Request->getParam("start"),
														10);
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_channel_stats(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->twitter_channel_stats($campaign_id,
														$this->Request->getParam("twitter_id"));
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_channel_stats(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->fb_channel_stats($campaign_id,
														$this->Request->getParam("fb_id"));
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_channels(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->twitter_channels($campaign_id);
		
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_channels(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->fb_channels($campaign_id);
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_channel_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->twitter_channel_posts($campaign_id,
																	$this->Request->getParam("twitter_id"),
																	$this->Request->getParam("start"));
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_channel_posts(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->fb_channel_posts($campaign_id,
																	$this->Request->getParam("fb_id"),
																	$this->Request->getParam("start"));
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function post_count(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->dashboard_model->post_count($campaign_id,
														$this->Request->getParam("from"),
														$this->Request->getParam("to"));
			
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
}
?>