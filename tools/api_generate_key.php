<?php
/**
 * a tool to generate API Key for user id
 */
include_once "common-service.php";
include_once $APP_PATH."smac/helper/ServiceHelper.php";

$user_id = 0;
for($i=0;$i<sizeof($argv);$i++){
	if(eregi("--",$argv[$i])){
		$param = explode("=",trim($argv[$i]));
		
		switch($param[0]){
			case "--user_id":
				$user_id = intval(trim($param[1]));	
			break;
		}
	}
}
if($user_id>0){
	$helper = new ServiceHelper(null);
	$helper->open(0);
	$request_token = $helper->generateRequestToken($user_id);
	$api_key = $helper->generateAPIKey($user_id);
	$helper->close();
	print "APIKEY : {$api_key}".PHP_EOL;
	print "request_token : {$request_token}".PHP_EOL;
}
?>