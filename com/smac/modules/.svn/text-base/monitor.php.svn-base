<?php
/**
 * Monitor Module
 * these module is act as webservice. all it does is informing the user if there's job is being processed,
 * errors, and also notified when the job's done.
 * triggered asynchronousely, advised for each 30s per interval.
 * JOB TYPES: 
 * 1. moving all keywords to workflow folder (workflow_keyword_flag)
 */
global $APP_PATH,$ENGINE_PATH;

include_once $ENGINE_PATH."Utility/Mailer.php";
class monitor extends App{
	var $Request;
	var $View;
	
	function home(){}
	/**
	 * get recent info.
	 * we display info with these priorities : 
	 * 1. get_jobs_done_notifications
	 * 2. get jobs in progress
	 * 3. get jobs in queue
	 * 4. get failed jobs
	 * @return unknown_type
	 */
	function info(){
		
		$ajax = $this->Request->getParam("ajax");
		//$campaign_id = mysql_escape_string($this->Request->getParam("campaign_id"));
		$campaign_id = $_SESSION['campaign_id'];
		if($ajax==1){
			$rs = $this->get_workflow_in_progress_jobs($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$rs = $this->get_workflow_jobs_done_notification($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$rs = $this->get_workflow_in_queue_jobs($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			
			$rs = $this->get_workflow_exc_progress_jobs($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$rs = $this->get_workflow_exc_done_notification($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$rs = $this->get_workflow_exc_queue_jobs($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			
			
			$rs = $this->get_workflow_people_exc_progress($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$rs = $this->get_workflow_peole_exc_notification($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$rs = $this->get_workflow_people_queue_jobs($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			
			//pdf report
			$rs = $this->get_pdf_generator_progress($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$rs = $this->get_pdf_generator_done($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$rs = $this->get_pdf_generator_queue($campaign_id);
			if(strlen($rs)>0){
				$this->response($rs);
			}
			$this->response(json_encode(array("status"=>"0")));
		}else{
			$this->response(json_encode(array("status"=>"500")));
		}
	}
	/**
	 * check for apply to all keyword exclusion status
	 * @return unknown_type
	 */
	function exc_status(){
		$ajax = $this->Request->getParam("ajax");
		$campaign_id = $_SESSION['campaign_id'];
		if($ajax==1){
			$queue = $this->get_workflow_exclude_status($campaign_id,0);
			$wip = $this->get_workflow_exclude_status($campaign_id,1);
			
			$total = intval($queue+$wip);
			print json_encode(array("status"=>1,"total"=>$total));
		}
		die();
	}
	function response($str){
		print $str;
		die();
	}
	function get_workflow_jobs_done_notification($campaign_id){
		
		$workflow = $this->get_workflow_flagged_status($campaign_id,2);
		$jobs = $this->get_workflow_flagged_status($campaign_id,0);
		
		if($_SESSION['workflow']!=null){
			$_SESSION['workflow']-=$workflow;
			
			$unfinished_job = $_SESSION['workflow'];
			if($jobs==0){
				$msg = "All tweets has been moved to workflow.";
			}else{
				$msg = "{$unfinished_job} left in the processing queue.";
			}
		}else{
			$_SESSION['workflow'] = $this->get_workflow_flagged_status($campaign_id,0);
			$unfinished_job = $workflow;
		}
		
		if($unfinished_job>=0&&$jobs>$workflow&&$workflow>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$workflow,"message"=>$msg,"link"=>$link));
		}
	}
	function get_workflow_exc_done_notification($campaign_id){
		
		$workflow = $this->get_workflow_exclude_status($campaign_id,2);
		$jobs = $this->get_workflow_exclude_status($campaign_id,0);
		
		if($_SESSION['workflow']!=null){
			$_SESSION['workflow']-=$workflow;
			
			$unfinished_job = $_SESSION['workflow'];
			if($jobs==0){
				$msg = "All tweets has been removed from report.";
			}else{
				$msg = "{$unfinished_job} left in the processing queue.";
			}
		}else{
			$_SESSION['workflow'] = $this->get_workflow_exclude_status($campaign_id,0);
			$unfinished_job = $workflow;
		}
		
		if($unfinished_job>=0&&$jobs>$workflow&&$workflow>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$workflow,"message"=>$msg,"link"=>$link));
		}
	}
	function get_workflow_in_progress_jobs($campaign_id){
		$n_jobs = 0;
		$wip = $this->get_workflow_flagged_status($campaign_id,1);
		$queue = $this->get_workflow_flagged_status($campaign_id,0);
		
		if($_SESSION['workflow_prog']==null){
			$_SESSION['workflow_prog'] = ($wip);
		}
		
		$msg = ($wip)." of ".($wip+$queue)." keyword(s) is being processed.";
		if($wip>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$queue,"message"=>$msg,"link"=>$link));
		}
	}
	function get_workflow_exc_progress_jobs($campaign_id){
		$n_jobs = 0;
		$wip = $this->get_workflow_exclude_status($campaign_id,1);
		$queue = $this->get_workflow_exclude_status($campaign_id,0);
		
		if($_SESSION['workflow_prog']==null){
			$_SESSION['workflow_prog'] = ($wip);
		}
		
		$msg = ($wip)." of ".($wip+$queue)." keyword(s) is being processed.";
		if($wip>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$queue,"message"=>$msg,"link"=>$link));
		}
	}
	function get_workflow_in_queue_jobs($campaign_id){
		
		$workflow = $this->get_workflow_flagged_status($campaign_id,0);
		
		
		$_SESSION['workflow'] = ($workflow);
		
		$msg = "There are ".$workflow." processes in queue. It will be processed in a few minutes.";
		if($workflow>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$workflow,"message"=>$msg,"link"=>$link));
		}
	}
	function get_workflow_exc_queue_jobs($campaign_id){
		$workflow = $this->get_workflow_exclude_status($campaign_id,0);
		$_SESSION['workflow'] = ($workflow);
		
		$msg = "There are ".$workflow." keyword exclusion process(es) in queue. It will be processed in a few minutes.";
		if($workflow>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$workflow,"message"=>$msg,"link"=>$link));
		}
	}
	function get_workflow_peole_exc_notification($campaign_id){
		
		$workflow = $this->get_workflow_people_exc_status($campaign_id,2);
		$jobs = $this->get_workflow_people_exc_status($campaign_id,0);
		
		if($_SESSION['workflow']!=null){
			$_SESSION['workflow']-=$workflow;
			
			$unfinished_job = $_SESSION['workflow'];
			if($jobs==0){
				$msg = "All people has been removed from report.";
			}else{
				$msg = "{$unfinished_job} left in the processing queue.";
			}
		}else{
			$_SESSION['workflow'] = $this->get_workflow_exclude_status($campaign_id,0);
			$unfinished_job = $workflow;
		}
		
		if($unfinished_job>=0&&$jobs>$workflow&&$workflow>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$workflow,"message"=>$msg,"link"=>$link));
		}
	}
	function get_workflow_people_queue_jobs($campaign_id){
		$workflow = $this->get_workflow_people_exc_status($campaign_id,0);
		$_SESSION['workflow'] = ($workflow);
		
		$msg = "There are ".$workflow." people exclusion process(es) in queue. It will be processed in a few minutes.";
		if($workflow>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$workflow,"message"=>$msg,"link"=>$link));
		}
	}
	function get_workflow_people_exc_progress($campaign_id){
		$n_jobs = 0;
		$wip = $this->get_workflow_people_exc_status($campaign_id,1);
		$queue = $this->get_workflow_people_exc_status($campaign_id,0);
		
		if($_SESSION['workflow_prog']==null){
			$_SESSION['workflow_prog'] = ($wip);
		}
		
		$msg = ($wip)." of ".($wip+$queue)." account(s) are being excluded.";
		if($wip>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$queue,"message"=>$msg,"link"=>$link));
		}
	}
	function get_errors(){
		
	}
	function get_workflow_flagged_status($campaign_id,$n_status){
		$this->open(0);
		$sql = "SELECT COUNT(*) as total
				FROM smac_web.workflow_keyword_flag 
				WHERE campaign_id={$campaign_id} AND n_status={$n_status} LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		return $rs['total'];
	}	
	function get_workflow_exclude_status($campaign_id,$n_status){
		$this->open(0);
		$sql = "SELECT COUNT(*) as total
				FROM smac_web.workflow_apply_exclude
				WHERE campaign_id={$campaign_id} AND n_status={$n_status} LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		
		return $rs['total'];
	}
	function get_workflow_people_exc_status($campaign_id,$n_status){
		$this->open(0);
		$sql = "SELECT COUNT(*) as total
				FROM smac_web.workflow_people_exclude
				WHERE campaign_id={$campaign_id} AND n_status={$n_status} LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		
		return $rs['total'];
	}	
	
	//pdf generator process
	function get_pdf_generator_status($campaign_id,$n_status){
		$this->open(0);
		$sql = "SELECT COUNT(*) as total
				FROM smac_web.job_report_pdf
				WHERE campaign_id={$campaign_id} AND n_status={$n_status} LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		
		return $rs['total'];
	}	
	function get_pdf_generator_queue($campaign_id){
		$queue = $this->get_pdf_generator_status($campaign_id,0);
		$_SESSION['queue'] = ($queue);
		$msg = "There are ".$queue." Report(s) in queue. It will be processed in a few minutes.";
		if($queue>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$queue,"message"=>$msg,"link"=>$link));
		}
	}
	function get_pdf_generator_progress($campaign_id){
		$n_jobs = 0;
		$wip = $this->get_pdf_generator_status($campaign_id,1);
		$queue = $this->get_pdf_generator_status($campaign_id,0);
		
		if($_SESSION['pdf_prog']==null){
			$_SESSION['pdf_prog'] = ($wip);
		}
		$msg = "Generating Report ".($wip)." of ".($wip+$queue).".";
		if($wip>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$queue,"message"=>$msg,"link"=>$link));
		}
	}
	function get_pdf_generator_done($campaign_id){
		
		$queue = $this->get_pdf_generator_status($campaign_id,2);
		$jobs = $this->get_pdf_generator_status($campaign_id,0);
		
		if($_SESSION['queue']!=null){
			$_SESSION['queue']-=$queue;
			
			$unfinished_job = $_SESSION['queue'];
			if($jobs==0){
				$msg = "All Report(s) are available to download.";
			}else{
				$msg = "{$unfinished_job} left in the processing queue.";
			}
		}else{
			$_SESSION['queue'] = $this->get_pdf_generator_status($campaign_id,0);
			$unfinished_job = $workflow;
		}
		
		if($unfinished_job>=0&&$jobs>$queue&&$queue>0){
			$c = array("subdomain"=>$this->Request->getParam('subdomain'),'page' => 'workflow','act'=>'job_list','ajax'=>1);
			$link = $this->Request->encrypt_params($c);
			return json_encode(array("status"=>1,"total"=>$queue,"message"=>$msg,"link"=>$link));
		}
	}
}
?>