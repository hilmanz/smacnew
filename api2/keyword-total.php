<?php
include_once "config.php";
include_once "common.php";

$conn = open_db(0);

$campaign_id = intval($_GET['campaign_id']);
$geo = mysql_escape_string($_GET['geo']);

$qry = "SELECT COUNT(*) as total FROM smac_report.campaign_sentiment_setup WHERE campaign_id=".$campaign_id." LIMIT 1";

$q = mysql_query($qry,$conn);

//echo mysql_error();exit;

if(mysql_num_rows($q) > 0){
	$xml = '<?xml version="1.0" encoding="utf-8"?><rows>';
	while($r=mysql_fetch_assoc($q)){
		$xml .= '<keyword total="'.$r['total'].'" />';
	}
	$xml .= '</rows>';
}else{
	$xml = '<?xml version="1.0" encoding="utf-8"?><rows></rows>';
}
mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml");
/*
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';
$xml .= '<keyword word="ayam" category="F" value="55" />';
$xml .= '<keyword word="makan" category="U" value="75" />';
$xml .= '<keyword word="minum" category="F" value="85" />';
$xml .= '<keyword word="lari" category="F" value="25" />';
$xml .= '<keyword word="cepat" category="U" value="15" />';
$xml .= '<keyword word="nonton" category="F" value="65" />';
$xml .= '</rows>';
*/
echo $xml;
exit;