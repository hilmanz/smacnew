<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person  = $_REQUEST['person'];
$conn = open_db(0);
$sql = "SELECT campaign_id,person,impression,rt_impression,pic,total_mentions,total_rt,lead_mentions 
		FROM campaign_people_imp_rekap WHERE campaign_id=".intval($campaign_id)." AND person='".mysql_escape_string($person)."'";
$q = mysql_query($sql,$conn);
$p = mysql_fetch_assoc($q);

mysql_free_result($q);
mysql_close($conn);

header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="UTF-8"?>';
$xml .= '<info>';
$xml .= '	<image>'.$p['pic'].'</image>';
$xml .= '	<username>101Jakfm</username>';
$xml .= '	<averagefollowers>-</averagefollowers>';
$xml .= '	<mentioned>22</mentioned>';
$xml .= '	<retweetvolume>2943</retweetvolume>';
$xml .= '	<retweetprobability>0.1</retweetprobability>';
$xml .= '	<impressions>579279</impressions>';
$xml .= '	<sentiment>-</sentiment>';
$xml .= '	<impactscore>-</impactscore>';
$xml .= '	</info>';
echo $xml;
exit;