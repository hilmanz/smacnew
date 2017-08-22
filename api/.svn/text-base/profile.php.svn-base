<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$id = intval($_REQUEST['id']);

$conn = open_db(0);
$sql = "SELECT * FROM smac_data.feeds a INNER JOIN 
smac_report.campaign_history b 
ON a.id = b.feed_id
WHERE b.campaign_id = '$campaign_id' AND a.id=$id;";
$q = mysql_query($sql,$conn);

$num = mysql_num_rows($q);

$xml = '<?xml version="1.0" encoding="UTF-8"?>';

if($num > 0){
	$f = mysql_fetch_assoc($q);
	
	$xml .= '<info>';
	$xml .= '	<image>'. $f['author_avatar'] .'</image>';
	$xml .= '	<name>'. $f['author_name'] .'</name>';
	$xml .= '	<username>'. $f['author_id'] .'</username>';
	$xml .= '	<city>'. $f['location'] .'</city>';
	$xml .= '	<bio>'. $f['author_name'] .' bio currently not availble</bio>';
	$xml .= '	<friendlist>'.number_format($f['followers']).'</friendlist>';
	$xml .= '	<mentions>'. number_format($f['total_mentions']).'</mentions>';
	$xml .= '	<keyword>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '		<word></word>';
	$xml .= '	</keyword>';
	$xml .= '</info>';
}else{
	$xml .= '<info>Not Availble</info>';
}

mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
echo $xml;
exit;