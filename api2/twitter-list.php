<?php

include_once "config.php";
include_once "common.php";
$last_id = intval($_REQUEST['last_id']);

//temporary solution, until gnip feed re-activated
if($last_id==0){
	//$last_id = 1;
}
//-->

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$entry = $arr['feed']['_c']['entry'];
$limit = intval($_REQUEST['limit']);
//echo 'limit = '.$limit;exit;
$conn = open_db(0);

//dapetin kampanye
$sql = "SELECT id AS campaign_id,campaign_start,campaign_end,tracking_method FROM smac.tbl_campaign WHERE id='$campaign_id' AND client_id='$client_id';";
//dapetin keyword
$sql2 = "SELECT GROUP_CONCAT(keyword_id) AS keywords FROM smac.tbl_campaign_keyword WHERE campaign_id='$campaign_id';";

$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);

$q2 = mysql_query($sql2,$conn);
$f2 = mysql_fetch_assoc($q2);

mysql_free_result($q);
if($f['tracking_method']=="0"){
	$str = get_standard_twitter(intval($f['campaign_id']),$limit,$f['campaign_start'],$f['campaign_end'],$f2['keywords']);
}else{
	$str = get_power_twitter(intval($f['campaign_id']),$limit,$f['campaign_start'],$f['campaign_end'],$f2['keywords']);
}
close_db($conn);
header("Content-type: text/xml");
print $str;
/*
header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';

$xml .= "<rows>";
$xml .= "	<mentions>1,900</mentions>";
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<profile_image_url>http://a3.twimg.com/profile_images/1402593057/Cindy_20Manda_20Gir_normal.jpg</profile_image_url>";
$xml .= "		<name>cindygirsang</name>";
$xml .= "		<txt>@ezholiceo done eze, accept</txt>";
$xml .= "	</row>";
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<profile_image_url>http://a0.twimg.com/profile_images/1405301380/php6uVMMm_normal</profile_image_url>";
$xml .= "		<name>ersarasee</name>";
$xml .= "		<txt>jjrgktGbsdthnlg.Sktht :'(</txt>";
$xml .= "	</row>";
$xml .= "	<row>";
$xml .= "		<id>8.3182391941792E</id>";
$xml .= "		<profile_image_url>http://a0.twimg.com/profile_images/1396577981/307466942_normal.jpg</profile_image_url>";
$xml .= "		<name>YasmienWA</name>";
$xml .= "		<txt>Panggil @solusisehat bu.. They're the best! ? @fannieelias</txt>";
$xml .= "	</row>";
$xml .= "</rows>";

echo $xml;
*/

exit;