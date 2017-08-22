<?php
class maintenanceHelper extends Application{
	
	/*
	 *	Get status maintenance
	 *	if status = 1, maintenance mode is on
	 *	if status = 0, maintenance mode is off
	 */
	function getStatus(){
		global $CONFIG;
		if($CONFIG['MAINTENANCE_MODE']==TRUE){
			return true;
		}
		$this->open(0);
		$qry = "SELECT val AS status FROM smac_web.gm_options WHERE name='maintenance_mode';";
		$rs = $this->fetch($qry);
		$this->close();
		
		if( intval($rs['status']) == 0 ){
			return false;
		}else{
			return true;
		}
	}
	
	function enable_maintenance_mode(){
		$this->open(0);
		$qry = "UPDATE smac_web.gm_options SET val='1' WHERE name='maintenance_mode';";
		
		if( $this->fetch($qry) ){
			return true;
		}else{
			return false;
		}
		$this->close();
	}
	
	function disable_maintenance_mode(){
		$this->open(0);
		$qry = "UPDATE smac_web.gm_options SET val='0' WHERE name='maintenance_mode';";
		
		if( $this->fetch($qry) ){
			return true;
		}else{
			return false;
		}
		$this->close();
	}
}
?>