<?php
include_once "common.api.php";
function getApiKey($user_id){
	$app = new Application();
	$app->open(0);
	$user_id = intval($user_id);
	$sql = "SELECT id,agency_id,account_id,email,secret
			FROM 
			smac_web.smac_user 
			WHERE id={$user_id} 
			LIMIT 1";
	$rs = $app->fetch($sql);	
	$app->close();
	if($rs){
		$strcon = "SMAC-SERVICE-API".$rs['id'].$rs['agency_id'].$rs['account_id'].$rs['email'].$rs['secret']."-Version-0.1";
		$api_key = sha1($strcon);
	}
	return $api_key;
}
function createRequestToken($user_id){
	return data_encode(array("user_id"=>$user_id,"request_time"=>date("Y-m-d H:i:s")));
}
$user_id = 1;
$data = array('method'=>'authenticate',
			  'api_key'=>getApiKey($user_id),
			  'request_token'=>createRequestToken($user_id));

$response = curlGet("http://localhost/smac_trunk/web/api/index.php",$data);
//$response = curlGet("http://localhost/smac_trunk/web/service/index.php",$data);
$rs = json_decode($response,true);
print $response.PHP_EOL;
?>