<?php
class workflow extends API{
	function execute(){
		$this->loadModel("smac_api","workflow_model");
		$this->loadModel("smac_api","workflow_fb_model");
		$this->loadModel("smac_api","workflow_gcs_model");
		$this->loadModel("smac_api","workflow_site_model");
		$this->workflow_model->setRequestHandler($this->Request);
		$this->workflow_fb_model->setRequestHandler($this->Request);
		$this->workflow_gcs_model->setRequestHandler($this->Request);
		$this->workflow_site_model->setRequestHandler($this->Request);
		return $this->call();
	}
	function folders(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->folders($campaign_id);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_folders(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->folders($campaign_id);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_folders(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->folders($campaign_id);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_folders(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->folders($campaign_id,$this->Request->getParam('type'));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function keywords(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->keywords($campaign_id,$this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_keywords(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->keywords($campaign_id,$this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_keywords(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->keywords($campaign_id,$this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_keywords(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->keywords($campaign_id,
													$this->Request->getParam('type'),
													$this->Request->getParam("folder_id"));
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
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->tweets($campaign_id,
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function all_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->all_tweets($campaign_id,
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->feeds($campaign_id,
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_all_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->all_feeds($campaign_id,
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->feeds($campaign_id,
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_all_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->all_feeds($campaign_id,
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->feeds($campaign_id,
													$this->Request->getParam('type'),
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_all_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->all_feeds($campaign_id,
													$this->Request->getParam('type'),
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function excluded_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->excluded_tweets($campaign_id,
												  $this->Request->getParam("filter_by"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_excluded_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->excluded_feeds($campaign_id,
												  $this->Request->getParam("filter_by"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_excluded_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->excluded_feeds($campaign_id,
												  $this->Request->getParam("filter_by"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_excluded_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->excluded_feeds($campaign_id,
													$this->Request->getParam('type'),
												  $this->Request->getParam("filter_by"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function add_folder(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->add_folder($campaign_id,
												  $this->Request->getPost("folder_name"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function remove_folder(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->remove_folder($campaign_id,
												  $this->Request->getPost("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function remove_tweet(){
		
	}
	function fb_remove_feed(){
		
	}
	function web_remove_feed(){
		
	}
	function move(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->move($campaign_id,
												  $this->Request->getPost("from_folder"),
												  $this->Request->getPost("to_folder"),
												  $this->Request->getPost("feed_id")
												  );
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_move(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->move($campaign_id,
												  $this->Request->getPost("from_folder"),
												  $this->Request->getPost("to_folder"),
												  $this->Request->getPost("feed_id")
												  );
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_move(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->move($campaign_id,
												  $this->Request->getPost("from_folder"),
												  $this->Request->getPost("to_folder"),
												  $this->Request->getPost("feed_id")
												  );
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_move(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->move($campaign_id,
												  $this->Request->getPost('type'),
												  $this->Request->getPost("from_folder"),
												  $this->Request->getPost("to_folder"),
												  $this->Request->getPost("feed_id")
												  );
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function move_all(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->move_all($campaign_id,
												  $this->Request->getPost("from_folder"),
												  $this->Request->getPost("to_folder"),
												  $this->Request->getPost("keyword")
												  );
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_move_all(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->move_all($campaign_id,
												  $this->Request->getPost("from_folder"),
												  $this->Request->getPost("to_folder"),
												  $this->Request->getPost("keyword")
												  );
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_move_all(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->move_all($campaign_id,
												  $this->Request->getPost("from_folder"),
												  $this->Request->getPost("to_folder"),
												  $this->Request->getPost("keyword")
												  );
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_move_all(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->move_all($campaign_id,
													$this->Request->getPost('type'),
												  $this->Request->getPost("from_folder"),
												  $this->Request->getPost("to_folder"),
												  $this->Request->getPost("keyword")
												  );
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function apply_exclude(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->apply_exclude($campaign_id,
												  		$this->Request->getPost("feed_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_apply_exclude(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->apply_exclude($campaign_id,
												  		$this->Request->getPost("feed_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_apply_exclude(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->apply_exclude($campaign_id,
												  		$this->Request->getPost("feed_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_apply_exclude(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->apply_exclude($campaign_id,
														$this->Request->getPost('type'),
												  		$this->Request->getPost("feed_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function exclude_all(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->exclude_all($campaign_id,
												  		$this->Request->getPost("keyword"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_exclude_all(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->exclude_all($campaign_id,
												  		$this->Request->getPost("keyword"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_exclude_all(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->exclude_all($campaign_id,
												  		$this->Request->getPost("keyword"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_exclude_all(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->exclude_all($campaign_id,
														$this->Request->getPost('type'),
												  		$this->Request->getPost("keyword"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function person(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->person($campaign_id,
												  $this->Request->getParam("person"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_person(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->person($campaign_id,
												  $this->Request->getParam("person"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_person(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->website_detail($campaign_id,
												   $this->Request->getParam('type'),
												  $this->Request->getParam("person"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_person(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->website_detail($campaign_id,$this->Request->getParam("type"),
												  $this->Request->getParam('type'),
												  $this->Request->getParam("person"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function person_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->twitter_profile_feeds($campaign_id,
												  $this->Request->getParam("person"),
												  $this->Request->getParam("start"),4);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_person_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->fb_profile_feeds($campaign_id,
												  $this->Request->getParam("person"),
												  $this->Request->getParam("start"),4);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	/**
	 * website feeds for analyze tab
	 */
	function web_person_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->website_feeds($campaign_id,
												  $this->Request->getParam("person"),
												  $this->Request->getParam("start"),4);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_person_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->website_feeds($campaign_id,
												  $this->Request->getParam("type"),
												  $this->Request->getParam("person"),
												  $this->Request->getParam("start"),4);
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function send_reply(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->send_tweet($campaign_id,
												  $this->getOwnerId(),
												  $this->Request->getParam("status"),
												  $this->Request->getParam("person"),
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("feed_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_send_reply(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->send_reply($campaign_id,
												  $this->getOwnerId(),
												  $this->Request->getParam("status"),
												  $this->Request->getParam("person"),
												  $this->Request->getParam("folder_id"),
												  $this->Request->getParam("feed_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function keyword_conversation(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->keyword_conversation($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function folder_sentiment(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->folder_sentiment($campaign_id,
												  $this->Request->getParam("type"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_folder_sentiment(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->folder_sentiment($campaign_id,
												  $this->Request->getParam("type"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_folder_sentiment(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->folder_sentiment($campaign_id,
												  $this->Request->getParam("type"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	/**
	 * @param $campaign_id 
	 * @param $type 1->positive, else-> negative
	 * @param $group_type web channel group_type.
	 */
	function site_folder_sentiment(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->folder_sentiment($campaign_id,
												  $this->Request->getParam("type"),
												   $this->Request->getParam("group"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_keyword_conversation(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->keyword_conversation($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_keyword_conversation(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->keyword_conversation($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_keyword_conversation(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->keyword_conversation($campaign_id,
												   $this->Request->getParam("type"),
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("start"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function flag(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->flag($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_flag(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->flag($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_flag_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->flag_feeds($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function unflag(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->unflag($campaign_id,
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_unflag(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->unflag($campaign_id,
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_unflag(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->unflag($campaign_id,
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_flag(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->flag($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_flag_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->flag_feeds($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	
	function site_unflag(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->unflag($campaign_id,
												 $this->Request->getParam("type"),
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_flag(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->flag($campaign_id,
												$this->Request->getParam("group_type"),
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_flag_feeds(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->flag_feeds($campaign_id,
												  $this->Request->getParam("type"),
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("feed_id"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	
	function flag_keyword(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_model->flag_keyword($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function fb_flag_keyword(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_fb_model->flag_keyword($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function web_flag_keyword(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_gcs_model->flag_keyword($campaign_id,
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
	function site_flag_keyword(){
		$campaign_id = $this->Request->getParam("campaign_id");
		$status = 0;
		$msg = "No data returned";
		$this->open(0);
		if($this->isOwner($campaign_id)){
			$this->dashboard_model->conn = $this->conn;
			$data = $this->workflow_site_model->flag_keyword($campaign_id,
			  										$this->Request->getParam("group_type"),
												  $this->Request->getParam("keyword"),
												  $this->Request->getParam("folder_id"));
			$status = 1;
			$msg = "SUCCESS";
		}
		$this->close();
		return $this->toJson($status,$msg, $data);
	}
}
?>