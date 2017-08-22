<?php
class Campaign{
	function __construct(){
		
	}
	function create_campaign(){
		global $conn,$SCHEMA_WEB;
		$client_id = intval($_POST['client_id']);
		$campaign_name = mysql_escape_string($_POST['campaign_name']);
		$campaign_start = mysql_escape_string($_POST['campaign_start']);
		$campaign_end = mysql_escape_string($_POST['campaign_end']);
		$tracking_method = mysql_escape_string($_POST['tracking_method']);
		$channels = mysql_escape_string(serialize($_POST['channels']));
		
		$keyword = $_POST['keywords'] ? nl2br($_POST['keywords']) : '';
		$keywords = explode("<br />",$keyword);
		$id=$this->add_campaign($client_id,$campaign_name,$campaign_start,
				$campaign_end,$channels,$tracking_method);
		if($id>0){
			if(sizeof($keywords)){
				$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".tbl_keyword (keyword_txt) VALUES ";
				$keys = "";
				$t = 0;
				foreach($keywords as $k => $v){
					$kw = trim($v);
					if( $kw != '' ){
						$qry .= "('".mysql_escape_string( $kw )."'), ";
						if($t>0){
							$keys.=",";
						}							
						$keys.="'".mysql_escape_string( $kw )."'";
						$t++;
					}
				}
				$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
				//print $qry."<br/>";
				if(!mysql_query($qry,$conn)){
					return "<status>0</status>";
				}else{
					$sql = "SELECT keyword_id FROM ".$SCHEMA_WEB.".tbl_keyword WHERE keyword_txt IN (".$keys.")";
					//print $sql."<br/>";
					$rs1 = $this->fetch($sql,1);
					$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".tbl_campaign_keyword (keyword_id,campaign_id) VALUES ";
					
					foreach($rs1 as $kk => $vv){
						$kwds = trim($vv['keyword_id']);
						if( $kwds != '' ){
							$qry .= "('".mysql_escape_string( $kwds )."',".$id."), ";
						
						}
						
					}
					$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
					//print $qry."<br/>";
					mysql_query($qry,$conn);
					$sql = "UPDATE ".$SCHEMA_WEB.".tbl_keyword SET n_status=0 
							WHERE keyword_id IN (SELECT keyword_id FROM ".$SCHEMA_WEB.".tbl_campaign_keyword
										WHERE campaign_id = ".intval($id).")";
						
					mysql_query($sql,$conn);
					return "<status>1</status>";
				}
			}
		}
	}
	function update_campaign(){
		global $conn,$SCHEMA_WEB;
		$client_id = intval($_POST['client_id']);
		$id = intval($_POST['campaign_id']);
		$campaign_name = mysql_escape_string($_POST['campaign_name']);
		$campaign_start = mysql_escape_string($_POST['campaign_start']);
		$campaign_end = mysql_escape_string($_POST['campaign_end']);
		$tracking_method = mysql_escape_string($_POST['tracking_method']);
		$channels = mysql_escape_string(serialize($_POST['channels']));
		
		$keyword = $_POST['keywords'] ? nl2br($_POST['keywords']) : '';
		$keywords = explode("<br />",$keyword);
		$qq=$this->edit_campaign($client_id,$id,$campaign_name,$campaign_start,
				$campaign_end,$channels,$tracking_method);
		if($qq>0){
			$qry = "DELETE FROM ".$SCHEMA_WEB.".tbl_campaign_keyword WHERE campaign_id=".$id.";";
				if( $this->query($qry) ){					
					$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".tbl_keyword (keyword_txt) VALUES ";
					$keys = "";
					$t = 0;
					if($keywords){
						foreach($keywords as $k => $v){
							$kw = trim($v);
							if( $kw != '' ){
								$qry .= "('".mysql_escape_string( $kw )."'), ";
								if($t>0){
									$keys.=",";
								}							
								$keys.="'".mysql_escape_string( $kw )."'";
								$t++;
							}
						}
						$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
						//print $qry."<br/>";
						if(!$this->query($qry)){
							return "<result>0</result>";
						}else{
							$sql = "SELECT keyword_id FROM ".$SCHEMA_WEB.".tbl_keyword WHERE keyword_txt IN (".$keys.")";
							//print $sql."<br/>";
							$rs1 = $this->fetch($sql,1);
							//var_dump($rs1);
							//print "<br/>";
							$qry = "INSERT IGNORE INTO ".$SCHEMA_WEB.".tbl_campaign_keyword (keyword_id,campaign_id) VALUES ";
							foreach($rs1 as $kk => $vv){
								$kwds = trim($vv['keyword_id']);
								if( $kwds != '' ){
									$qry .= "('".mysql_escape_string( $kwds )."',".$id."), ";
						
								}
							
							}
							$qry = substr($qry, 0, strlen($qry) - 2 ) . ";";
							//print $qry."<br/>";
							$this->query($qry);
							$sql = "UPDATE ".$SCHEMA_WEB.".tbl_keyword SET n_status=2 
								WHERE keyword_id NOT IN (SELECT keyword_id FROM smac.tbl_campaign_keyword)";
						
							$this->query($sql);
						
							$sql = "UPDATE ".$SCHEMA_WEB.".tbl_keyword SET n_status=0 
								WHERE keyword_id IN (SELECT keyword_id FROM smac.tbl_campaign_keyword
											WHERE campaign_id = ".intval($id).")";
						
							$this->query($sql);
						}
					}
				return "<result>1</result>";	
			}else{
				return "<result>-1</result>";
			}
			return "<result>1</result>";
		}else{
			return "<result>0</result>";
		}
	}
	function fetch($sql,$f=false){
		global $conn;
		$q = mysql_query($sql,$conn);
		
		if($f){
			while($ff = mysql_fetch_assoc($q)){
				$fetch[] = $ff;
			}
		}else{
			$fetch = mysql_fetch_assoc($q);
		}
		mysql_free_result($q);
		return $fetch;
	}
	function query($sql){
		global $conn;
		$q = mysql_query($sql,$conn);
		return $q;
	}
	function add_campaign($client_id,$campaign_name,$campaign_start,$campaign_end,$channels,$tracking_method){
		global $conn,$SCHEMA_WEB;
		//insert campaign
		$qry = "INSERT INTO ".$SCHEMA_WEB.".tbl_campaign 		
			(client_id,campaign_name,campaign_start,
			campaign_end,campaign_added,channels,tracking_method) 
			VALUES
			(".$client_id.",'".$campaign_name."',
			'".$campaign_start."','".$campaign_end."',NOW(),
			'".$channels."',".$tracking_method.");";
		
		$q = mysql_query($qry,$conn);		
		
		$id = mysql_insert_id();
		return $id;
	}
	function edit_campaign($client_id,$campaign_id,$campaign_name,$campaign_start,$campaign_end,$channels,$tracking_method){
		global $conn,$SCHEMA_WEB;
		//insert campaign
		$qry = "UPDATE ".$SCHEMA_WEB.".tbl_campaign 		
			SET campaign_name='".$campaign_name."',campaign_start='".$campaign_start."',
			campaign_end='".$campaign_end."',channels='".$channels."',tracking_method=".$tracking_method."
			WHERE client_id = ".$client_id." AND id = ".$campaign_id."";
		$q = mysql_query($qry,$conn);	
		
		return $q;
	}
	function get_campaign_detail($format='json'){
		global $conn,$SCHEMA_WEB;
		$campaign_id = intval($_REQUEST['campaign_id']);
		$client_id = intval($_REQUEST['client_id']);
		$sql = "SELECT id as campaign_id,campaign_name,campaign_start,campaign_end,campaign_added,channels,tracking_method 
				FROM ".$SCHEMA_WEB.".tbl_campaign WHERE id=".$campaign_id." AND client_id = ".$client_id." LIMIT 1";
		$rs = $this->fetch($sql);
		$rs['channels'] = unserialize($rs['channels']);
		if($format=='json'){
			return json_encode($rs);
		}else{
			$str = "<result>";
			foreach($rs as $n=>$v){
				$str.="<".$n.">".@htmlspecialchars(stripslashes($v))."</".$n.">";
			}
			$str.="</result>";
			return $str;
		}
	}
	function get_campaign_list($format='json'){
		global $conn,$SCHEMA_WEB;
		
		$client_id = intval($_REQUEST['client_id']);
		$sql = "SELECT id as campaign_id,campaign_name,campaign_start,campaign_end,campaign_added,channels,tracking_method 
				FROM ".$SCHEMA_WEB.".tbl_campaign WHERE client_id = ".$client_id." LIMIT 1000";
		$results = $this->fetch($sql,1);
	
		
		//$rs['channels'] = unserialize($rs['channels']);

		if($format=='json'){
			return json_encode($results);
		}else{
			$str = "<result>";
			foreach($results as $rs){
				$rs['channels'] = "";
				$str .= "<campaign>";
				foreach($rs as $n=>$v){
					$str.="<".$n.">".@htmlspecialchars(stripslashes($v))."</".$n.">";
				}
				$str.="</campaign>";
				
			}
			$str.="</result>";
			return $str;
		}
		
	}
	function toggle_campaign(){
		//do something here.
	}
}

?>
