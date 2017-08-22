<?php
/**
 * 
 * @author duf
 * @todo
 * need to build paypal integration
 */
class KOLHelper extends Application{
	function getPaidKOL($campaign_id){
		$sql = "SELECT author_id FROM smac_web.smac_paid_kol WHERE campaign_id={$campaign_id} LIMIT 100";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
}
?>