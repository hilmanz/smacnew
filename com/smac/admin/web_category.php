<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $ENGINE_PATH."Utility/Mailer.php";
class web_category extends SQLData{
	
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		//$this->User = new UserManager();
		
	}
	
	function admin(){
		if($this->Request->getParam('act')=="toggle"){
			return $this->toggle();
		}else{
			return $this->web_list();
		}
	}
	function toggle(){
		$id = intval($this->Request->getParam('id'));
		$group_type_id = intval($this->Request->getParam('type'));
		$sql = "SELECT url as sitename 
				FROM smac_gcs.unclassified_url 
				WHERE id = {$id} LIMIT 1";
		$feed = $this->fetch($sql);
		
		
		$sql = "INSERT IGNORE INTO `smac_gcs`.`gcs_group_type_url` 
				(
				`group_type_id`, 
				`url`
				)
				VALUES
				(
				{$group_type_id}, 
				'{$feed['sitename']}'
				);";
		$q = $this->query($sql);
		
		if($q){
			$sql = "UPDATE smac_gcs.unclassified_url SET is_process=1 WHERE id={$id}";
			$q = $this->query($sql);
			print json_encode(array("status"=>1));
		}else{
			print json_encode(array("status"=>0));
		}
		die();
	}
	function web_list($total_per_page=100){
		$start = intval($this->Request->getParam('st'));
		$total_per_page = intval($total_per_page);
		
				
		$sql = "SELECT id,url as sitename,url as link 
				FROM smac_gcs.unclassified_url 
				WHERE is_process=0
				LIMIT {$start},{$total_per_page};
				";
		$rs = $this->fetch($sql,1);
		$sql = "SELECT COUNT(id) as total 
				FROM smac_gcs.unclassified_url 
				WHERE is_process=0
				LIMIT 1;";
		$rows = $this->fetch($sql);
		$this->View->assign("list",$rs);
		$this->View->assign("start",$start);
		$this->View->assign("total_per_page",$total_per_page);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $rows['total'], "?s=web_category"));
		return $this->View->toString(APPLICATION."/admin/web_category/web_list.html");
	}
}
?>