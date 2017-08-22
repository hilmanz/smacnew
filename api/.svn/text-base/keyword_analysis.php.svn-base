<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);


$conn = open_db(0);

//1. retrieve top 10 keywords
$sql = "SELECT * FROM smac_report.campaign_sentiment
WHERE campaign_id=".$campaign_id."
ORDER BY occurance  DESC LIMIT 10";

$q = mysql_query($sql,$conn);
$keywords = array();
while($f=mysql_fetch_assoc($q)){
	$keywords[] = $f;
}
$f = null;
mysql_free_result($q);

mysql_close($conn);
/*
echo mysql_error();exit;
$q2 = mysql_query($sql2,$conn);

$r2=mysql_fetch_assoc($q2);
print_($r2);exit;
*/
header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';

$xml .= '<info>
				<score>'.number_format($score).'</score>
		';
$xml.='</info>';

echo $xml;
exit;