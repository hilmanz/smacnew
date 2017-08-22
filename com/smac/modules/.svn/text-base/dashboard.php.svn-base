<?php
global $APP_PATH;
class dashboard extends App{
	function home(){
		//check if the account has twitter or facebook channel
		$sql = "SELECT twitter_account,fb_account FROM smac_web.tbl_campaign WHERE id={$this->campaign_id} LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		if(strlen($rs['twitter_account'])>0||strlen($rs['fb_account'])>0){
			$this->View->assign("channel_account",1);
		}
		//-->
		return $this->View->toString(APPLICATION.'/dashboard.html');
	
	}

}
?>