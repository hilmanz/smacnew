<?php
include_once "common.php";
/*
$data = array('method'=>'sentiment',
			  'lang'=>'ar',
			  'text'=>base64_encode(" نموذج للحرب الاستخباراتية وليس حصرا الحرب الباردة بين قطبي الكون بعد الحرب العالمية "),
			  "campaign_id"=>999);*/
/*$data = array('method'=>'sentiment',
			  'lang'=>'en',
			  'text'=>base64_encode("a happy sunny day in my whole life, its not bad tho"),
			  "campaign_id"=>999);*/

$data = array('method'=>'sentiment',
			  'lang'=>'ar',
			  'text'=>base64_encode("العنف"),
			  "campaign_id"=>999);

/*
$data = array('method'=>'sentiment',
			  'lang'=>'ph',
			  'text'=>base64_encode("kainis nmn..super tgal mgupload ng video oh..ssshhhhh..."),
			  "campaign_id"=>999);
*/
$data = array('method'=>'sentiment',
			  'lang'=>'id',
			  'text'=>base64_encode("nonton festival musik soulnation keren abis ! gak jelek, gak nyesel ! muaah muah gelo bloh"),
			  "campaign_id"=>999);
print "http://localhost/smac_trunk/web/service/index.php?".http_build_query($data).PHP_EOL;
$response = curlGet("http://localhost/smac_trunk/web/service/index.php",$data);

$rs = json_decode($response,true);
print $response.PHP_EOL;
print_r($rs);
?>
