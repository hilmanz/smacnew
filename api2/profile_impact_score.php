<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$person = mysql_escape_string($_REQUEST['person']);

$conn = open_db(0);

if(is_new_feeds($campaign_id,$conn)){
	$FEEDS = "smac_feeds.campaign_feeds_{$campaign_id}";
}else{
	$FEEDS = "smac_report.campaign_feeds}";
}


$sql = "SELECT SUM(b.pii) as total 
		FROM {$FEEDS} a
				INNER JOIN smac_report.campaign_feed_sentiment b
					ON a.id = b.feed_id AND b.campaign_id = ".$campaign_id."
		WHERE a.campaign_id=".$campaign_id." AND a.author_id='".($person)."';";
$q = mysql_query($sql,$conn);
$f = mysql_fetch_assoc($q);
mysql_free_result($q);
mysql_close($conn);


$score = $f['total'];
$f = null;

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