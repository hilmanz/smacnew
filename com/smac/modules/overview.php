<?php
global $APP_PATH;
class overview extends App{
	function home(){
		$this->assign("username",$this->user->first_name." ".$this->user->last_name);
		$params = array("page"=>"campaign","act"=>"add","subdomain"=>$_GET['subdomain']);
		$this->assign("urlnewcampaign","index.php?".$this->Request->encrypt_params($params));
		$this->assign("show_tips",$this->show_tips());
		return $this->View->toString(APPLICATION.'/overview.html');
	}
	function show_tips(){
		$sql = "SELECT show_tips FROM smac_web.smac_user WHERE id={$this->user->id} LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		return $rs['show_tips'];
	}
}
?>