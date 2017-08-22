<?php
class coba extends API{
	function execute(){
		if($this->param('foo')){
			return $this->toJson('1','hooray',null);
		}else{
			return $this->toJson('0','no request',null);
		}
	}
}
?>