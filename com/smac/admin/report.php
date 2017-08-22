<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $ENGINE_PATH."Utility/Mailer.php";
class report extends SQLData{
	var $Request;
	var $View;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();		
	}
	function admin(){
		if($this->Request->getPost('act')=="upload"){
			$this->upload();
			die();
		}else if($this->Request->getParam('act')=="remove"){
			return $this->remove();
		}else{
			return $this->dashboard();
		}	
	}
	function upload(){
		$id = intval($this->Request->getPost('id'));
		$topic_id = intval($this->Request->getPost('topic_id'));
		$filename = mysql_escape_string(cleanXSS($this->Request->getPost('filename')));
		$desc = mysql_escape_string(cleanXSS($this->Request->getPost('desc')));
		$sql = "INSERT INTO `smac_web`.`tbl_report_files` 
				(
				`account_id`, 
				`filename`, 
				`description`, 
				`upload_date`,
				campaign_id
				)
				VALUES
				(
				{$id}, 
				'{$filename}', 
				'{$desc}', 
				NOW(),
				{$topic_id}
				);";
		$q = $this->query($sql);
		if($q){
			print json_encode(array('status'=>1));
		}else{
			print json_encode(array('status'=>0));
		}
	}
	function remove(){
		global $report_upload_dir;
		$id = intval($this->Request->getParam('id'));
		$rs = $this->fetch("SELECT * FROM smac_web.tbl_report_files WHERE id={$id} LIMIT 1");
		
		
		$sql = "DELETE FROM `smac_web`.`tbl_report_files` WHERE id={$id}";
		$q = $this->query($sql);
		if($q){
			$user_folder = md5($rs['account_id']."-folder");
			$targetFolder = $report_upload_dir."{$user_folder}"; // Relative to the root
			@unlink($targetFolder.'/'.$rs['filename']);
			$msg = "The file has been removed successfully !";
		}else{
			$msg = "Cannot remove the file, please try again later !";
		}
		return $this->View->showMessage($msg, "?s=report&id={$rs['account_id']}");
	}
	function dashboard($total = 20){
		$this->use_cache = false;
		$start = intval($this->Request->getParam("st"));
		
		$id = intval($this->Request->getParam('id'));
		$account = $this->fetch("SELECT * FROM smac_web.smac_account WHERE id={$id} LIMIT 1");
		$this->View->assign("account",$account);
		$sql = "SELECT a.*,b.campaign_name as topic_name FROM smac_web.tbl_report_files a INNER JOIN 
				smac_web.tbl_campaign b ON a.campaign_id = b.id
				WHERE a.account_id={$id} LIMIT {$start},{$total}";
		$reports = $this->fetch($sql,1);
		$rows = $this->fetch("SELECT COUNT(id) as total FROM smac_web.tbl_report_files WHERE account_id={$id} LIMIT 1");
		$this->View->assign("report",$reports);
		
		//topic list
		$topic = $this->fetch("SELECT id,campaign_name FROM smac_web.tbl_campaign WHERE client_id={$account['id']} LIMIT 10000",1);
		
		$this->View->assign("topic",$topic);
		
		//for file uploader
		$timestamp = time();
		$this->View->assign("timestamp",$timestamp);
		$this->View->assign("token",md5('jonnysayshello' . $timestamp));
		//-->
		//pagination
		$paging = new Paginate();
		$this->View->assign("paging",$paging->generate($start,$total,$rows['total'],"?s=report&id={$id}"));
		//-->
		return $this->View->toString(APPLICATION.'/admin/report/dashboard.html');
	}
}
?>