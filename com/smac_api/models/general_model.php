<?php
class general_model extends base_model{
	private $request;
	function setRequestHandler($req){
		$this->request = $req;
	}
	function toggle_tips($user_id){
		$sql = "SELECT show_tips FROM smac_web.smac_user WHERE id={$user_id} LIMIT 1";
		$rs = $this->fetch($sql);
		
		if($rs['show_tips']==1){
			$sql = "UPDATE smac_web.smac_user SET show_tips=0 WHERE id={$user_id}";
		}else{
			$sql = "UPDATE smac_web.smac_user SET show_tips=1 WHERE id={$user_id}";
		}
		$q = $this->query($sql);
		if($q){return 1;}
		else{return 0;}
	}
}
?>