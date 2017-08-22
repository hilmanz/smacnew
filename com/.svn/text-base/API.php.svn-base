<?php
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $ENGINE_PATH."Utility/Debugger.php";
include_once $APP_PATH."smac_api/helper/AuthHelper.php";
/**
 * 
 * API base class
 * @author duf
 *
 */
class API extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $debug;
	var $Auth;
	function __construct($req){
		parent::__construct($req);
		$this->debug = new Debugger();
	}
	function mainLayout($val=null){
		if($val==null){
			return $this->_mainLayout;
		}else{
			$this->_mainLayout = $val;
		}
	}
	function beforeFilter(){}
	/**
	 * 
	 * @todo tolong di tweak lagi expired_timenya.
	 */
	function main(){
		global $CONFIG;
		if($this->is_authorized()){
			return $this->run();
		}else{
			return $this->toJson(401,'unauthorized access',null);
		}
	}
	function is_authorized(){
		return true;
	}
	function admin(){
		
	}
	function toJson($status,$msg,$data){
		
		return json_encode(array("status"=>$status,"message"=>$msg,"data"=>$data));
	}
	function param($name){
		return $this->Request->getParam($name);
	}
	function assign($name,$val){
		$this->View->assign($name,$val);
	}
	function post($name){
		return $this->Request->getPost($name);
	}
	function out($tpl){
		return $this->View->toString($tpl);
	}
	function __toString(){
		return $this->out($this->_mainLayout);
	}
	function getList($sql,$start,$total,$base_url){
		//paging
		$paging = new Paginate();
		$sql1 = $sql." LIMIT ".$start.",".$total;
		$sql2 = eregi_replace("SELECT (.*) FROM","SELECT COUNT(*) as total FROM",$sql);
		$sql2 = eregi_replace("ORDER BY(.*)","",$sql2);
		$this->open();
		$list = $this->fetch($sql,1);
		$rs = $this->fetch($sql2);
		$this->close();
		$this->assign("list",$list);
		$this->assign("pages",$paging->generate($start, $total, $rs['total']));
		
	}
	
	function object2array($object) {
		if (is_object($object)) {
			foreach ($object as $key => $value) {
				$array[$key] = $value;
			}
		}
		else {
			$array = $object;
		}
		return $array;
	}
	function toSqlArray($txt){
		$str = "";
		$arr = explode(",",$txt);
		foreach($arr as $n=>$a){
			if($n>0){
				$str.=",";
			}
			$a = trim($a);
			$str.="'{$a}'";
		}
		return $str;
	}
	public function call(){
		if($_REQUEST['action']!=""){
			$action = cleanXSS($_REQUEST['action']);
			if(method_exists($this, $action)){
				return $this->$action();
			}else{
				return $this->toJson(404, "Method Not Found", array());
			}
		}
	}
	function isOwner($campaign_id){
		$campaign_id = intval($campaign_id);
		$user_id = $this->Auth->getUserId($_REQUEST['access_token']);
		$sql = "SELECT a.id as campaign_id,b.id as user_id 
				FROM smac_web.tbl_campaign a INNER JOIN smac_web.smac_user b
				ON a.client_id = b.account_id
				WHERE a.id = {$campaign_id} AND b.id = {$user_id} LIMIT 1";
		$rs = $this->fetch($sql);
		if($rs['user_id']==$user_id && $rs['campaign_id']==$campaign_id){
			return true;
		}
	}
	function getCurrentUser(){
		$user_id = $this->Auth->getUserId($_REQUEST['access_token']);
		return $user_id;
	}
	function isGroupOwner($group_id){
		$group_id = intval($group_id);
		$owner_id = $this->getOwnerId();
		if($group_id>0&&$owner_id>0){
			$sql = "SELECT id,client_id FROM smac_web.tbl_topic_group WHERE id={$group_id} LIMIT 1";
			$rs = $this->fetch($sql);
			if($rs['client_id']==$owner_id && $rs['id']==$group_id){
				return true;
			}
		}
	}
	/**
	 * get owner id
	 * returns int
	 */
	function getOwnerId(){
		$user_id = $this->Auth->getUserId($_REQUEST['access_token']);
		$sql = "SELECT account_id
			FROM 
			smac_web.smac_user 
			WHERE id={$user_id} 
			LIMIT 1";
		$rs = $this->fetch($sql);
		return $rs['account_id'];
	}
	function loadModel($module,$modelName){
		global $APP_PATH;
		//print getcwd();
		$modelName = cleanXSS($modelName);
		$target = $APP_PATH."{$module}/models/{$modelName}.php";
		if(file_exists($target)){
			include_once $target;
			if(class_exists($modelName)){
				$this->$modelName = new $modelName();
			}
		}
	}
}
?>