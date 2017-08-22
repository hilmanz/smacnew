<?php
class kol extends App{
	function home(){
		return $this->View->toString(APPLICATION.'/kol.html');
	}
}
?>