<?php
class ka extends App{
	function home(){
		return $this->View->toString(APPLICATION.'/ka.html');
	}
}
?>