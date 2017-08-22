<?php
/**
 * login
 */
include_once "config.php";
include_once "common.php";

if( $_POST['username'] == 'demo' && $_POST['password'] == 'demo' ){
	$xml = '<rows><status>1</status></rows>';
}else{
	$xml = '<rows><status>0</status></rows>';
}

header("Content-type: text/xml");
echo $xml;
exit;