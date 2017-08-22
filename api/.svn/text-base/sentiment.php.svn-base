<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);

$conn = open_db(0);
$sql = "SELECT sentiment_positive,sentiment_negative,sentiment_text FROM smac_report.dashboard_summary WHERE campaign_id='$campaign_id' && client_id='$client_id' LIMIT 1;";
$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);
mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';
$xml .= '	<positive>'.$f['sentiment_positive'].'</positive>';
$xml .= '	<negative>'.$f['sentiment_negative'].'</negative>';
$xml .= '	<result>'.$f['sentiment_text'].'</result>';

if( intval($f['sentiment_positive']) > intval($f['sentiment_negative']) ){
	$cond = 'good';
	$status = '+';
	$color = '#00ffff';
}else if( intval($f['sentiment_positive']) < intval($f['sentiment_negative']) ){
	$cond = 'bad';
	$status = '-';
	$color = '#ff0000';
}else{
	$cond = 'neutral';
	$status = 'n';
	$color = '#0000ff';
}

$xml .= '	<status>'.$status.'</status>';
$xml .= '	<text>and a &lt;font color="'.$color.'"&gt;'.$cond.'&lt;/font&gt; sentiment rating:</text>';
$xml .= '</rows>';

echo $xml;
exit;