<?php
class marketPage extends App{
	function home(){
		return $this->View->toString("smac/marketPage.html");
	}
}
?>