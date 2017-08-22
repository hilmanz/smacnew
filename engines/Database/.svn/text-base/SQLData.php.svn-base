<?php
class SQLData{
	var $schema;
	var $conn;
	var $rs;
	var $msg;
	var $lastInsertId;
	var $autoconnect=true;
	var $_force_utf8 = true;
	var $cache;
	var $use_cache = true;
	var $last_queries;
	function SQLData(){
		global $CONFIG;
		$this->msg="";
		$this->host = $CONFIG['DATABASE'][0]['HOST'];
		$this->username = $CONFIG['DATABASE'][0]['USERNAME'];
		$this->password = $CONFIG['DATABASE'][0]['PASSWORD'];
		$this->database = $CONFIG['DATABASE'][0]['DATABASE'];
		if($CONFIG['DB_CACHE']){
			$this->initMemcache();
		}
	}
	function getConnection(){
		return $this->conn;
	}
	function force_utf8($f){
		$this->_force_utf8 = $f;
	}
	/**
	 * @param $ttl time to expired, default to 60 seconds / 1 minutes
	 */
	function set_cache($sql,$rs,$ttl=57){
		$this->cache->set(md5($sql),serialize($rs),MEMCACHE_COMPRESSED,$ttl);
	}
	function initMemcache(){
		$this->cache = new Memcache();
		$this->cache->addServer('127.0.0.1',11211);
	}
	function get_cache($sql){
		if(!is_object($this->cache)){
			$this->initMemcache();
		}
		try{
			return $this->cache->get(md5($sql));
		}catch(Exception $e){}
	}
	function open($db=0){
		
		global $CONFIG;
		
        $this->host = $CONFIG['DATABASE'][$db]['HOST'];
		$this->username = $CONFIG['DATABASE'][$db]['USERNAME'];
		$this->password = $CONFIG['DATABASE'][$db]['PASSWORD'];
		$this->database = $CONFIG['DATABASE'][$db]['DATABASE'];
		
		if($this->conn==NULL){
			
			$this->conn = mysql_connect($this->host,$this->username,$this->password);
			$this->addMessage("Open Connection -->".$this->conn);
		}else{
			$this->addMessage("Connection already opened : ".$this->conn."<br/>");
		}	
		//print $this->database;
	}
	function addMessage($msg){
		$this->msg.=$msg."<br/>";
	}
	function close(){
		if($this->conn!=NULL){
			if(@mysql_close($this->conn)){
				$this->addMessage("Connection closed --> ".$this->conn);
				$this->conn=NULL;
			}
		}else{
			$this->addMessage("Connection already closed --> ".$this->conn);
		}
	}
	function setSchema($schema){
		$this->schema = $schema;
	}
	function fetch($str,$flag=0){
		global $CONFIG;
		if($this->use_cache&&$CONFIG['DB_CACHE']){
			$from_cache = $this->get_cache($str);
			if(strlen($from_cache)>0){
				$this->last_queries[] = "CACHED : {$str}";
				return unserialize($from_cache);
			}
		}
		$sql = $this->query($str);
		
		if($flag){
			$n=0;
			while($fetch = mysql_fetch_array($sql,MYSQL_ASSOC)){
				$rs[$n] = $fetch;
				$n++;
			}
		}else{
			$rs = mysql_fetch_array($sql,MYSQL_ASSOC);
		}
		
		mysql_free_result($sql);
		
		//cache the result for sort time.
		if($this->use_cache&&$CONFIG['DB_CACHE']){
			$this->set_cache($str, $rs);
		}
		return $rs;
	}
	function reset(){
		$this->rs = NULL;
	}
	function query($sql,$flag=0){
		global $CONFIG;
		//print $sql;
		//if($this->conn==NULL){$this->open();}
		if(@eregi("INSERT|UPDATE|DELETE",strtoupper($sql))&&$this->use_cache&&$CONFIG['DB_CACHE']){
			$this->clear_cache();
		}
		$this->addMessage("do query using ".$this->conn.": <br/>");
		if($this->_force_utf8){
			@mysql_set_charset("utf8",$this->conn);
		}else{
			@mysql_set_charset("latin1",$this->conn);
		}
		$rs = mysql_db_query($this->database,$sql);	
		if($flag){
			$this->lastInsertId = mysql_insert_id();
		}
		$this->addMessage($rs);
		$this->addMessage(mysql_error()."<br/>");
		
		$this->last_queries[] = $sql;
		return $rs;
	}
	function query2($sql,$flag=0){
		$rs = mysql_query($sql);
		if($flag){
			$this->lastInsertId = mysql_insert_id();
		}
		return $rs;
	}
	function getMessage(){
		$msg=mysql_error();
		$msg.="<br/>";
		$msg.=$this->msg;
		return $msg;
	}
	function getConsoleMessage(){
		$msg=mysql_error();
		$msg.="\n";
		$msg.=str_replace("<br/>","\n",$this->msg);
		return $msg;
	}
	function clear_cache(){
		global $CONFIG;
		if($CONFIG['DB_CACHE']){
			if(!is_object($this->cache)){
				$this->initMemcache();
			}
			$this->cache->flush();
			$this->last_queries[] = "CACHE CLEARED";
		}
	}
	function getLastInsertId(){
		return $this->lastInsertId;
	}
}
?>