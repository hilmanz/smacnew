<?php
/**
 * 
 * @author duf
 * @todo
 * need to build paypal integration
 */
class TopicGroupHelper extends Application{
	function get_types(){
		$this->open(0);
		$sql = "SELECT * FROM smac_web.tbl_topic_group_type ORDER BY id ASC";
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function create_group($account_id,$group_name,$group_type){
		$account_id = mysql_escape_string($account_id);
		$group_name = mysql_escape_string(cleanXSS($group_name));
		$group_type = intval($group_type);
		$this->open(0);
		$sql = "INSERT INTO smac_web.tbl_topic_group(client_id,group_name,group_type)
				VALUES(".$account_id.",'".$group_name."',".$group_type.")";
		$rs = $this->query($sql,1);
		$group_id = mysql_insert_id();
		$this->close();
		return $group_id;
	}
	function get_groups($account_id){
		$account_id = mysql_escape_string($account_id);
		$this->open(0);
		$sql = "SELECT * FROM smac_web.tbl_topic_group WHERE client_id=".$account_id." AND group_name <> '' 
				ORDER BY group_name ASC LIMIT 1000";
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
}
?>