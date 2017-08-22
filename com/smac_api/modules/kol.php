<?php
class kol extends API{
	function execute(){
		$this->loadModel("smac_api","kol_model");
		$this->kol_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function twitter(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->twitter($campaign_id,$this->Request->getParam("exclude"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_ambas(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->twitter_ambas($campaign_id,
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_troll(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->twitter_troll($campaign_id,
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_ambas(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->fb_ambas($campaign_id,
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_trolls(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->fb_trolls($campaign_id,
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_ambas(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->web_ambas($campaign_id,
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_trolls(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->web_trolls($campaign_id,
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function video_ambas(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->video_ambas($campaign_id,
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function video_trolls(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->video_trolls($campaign_id,
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_all_people(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->twitter_all_people($campaign_id,$this->Request);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		// return $this->toJson($status,$msg, $data);
		return json_encode($data);
	}
	function fb_all_people(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->fb_all_people($campaign_id,$this->Request);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		// return $this->toJson($status,$msg, $data);
		return json_encode($data);
		
	}
	function web_all_sites(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->web_all_sites($campaign_id,$this->Request);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		
		return json_encode($data);
	}
	function video_all_people(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->video_all_people($campaign_id,$this->Request);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		
		return json_encode($data);
	}
	function fb(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->fb($campaign_id,
												$this->Request->getParam("exclude"));
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
			$data = $this->kol_model->web($campaign_id,$this->Request->getParam("exclude"));
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
			$data = $this->kol_model->video($campaign_id,$this->Request->getParam("exclude"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_profile(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->twitter_profile($campaign_id,$this->Request->getParam("person"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_profile_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->twitter_profile_feeds($campaign_id,
															$this->Request->getParam("person"),
															$this->Request->getParam("start"),5);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function twitter_daily(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->twitter_daily_stats($campaign_id,
															$this->Request->getParam("people"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	
	/**
	 * web channel new version
	 */
	 function site(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->site($campaign_id,$this->Request->getParam("type"),$this->Request->getParam("exclude"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	 function site_ambas(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->site_ambas($campaign_id,$this->Request->getParam("type"),
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_trolls(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->site_trolls($campaign_id,
												$this->Request->getParam("type"),
														  $this->Request->getParam("exclude"),
														  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_all_sites(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$data = $this->kol_model->site_all_sites($campaign_id,$this->Request);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		
		return json_encode($data);
	}
}
?>