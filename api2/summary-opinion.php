<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$lang = mysql_escape_string($_REQUEST['lang']);
$geo = mysql_escape_string($_REQUEST['geo']);

if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

$conn = open_db(0);
//total impressions
$sql = "SELECT SUM(imp) as total_impression
FROM smac_author.campaign_geo_author_{$campaign_id} 
WHERE campaign_id={$campaign_id} AND geo='{$geo}' LIMIT 1; ";
$campaign = fetch($sql,$conn);
$total_impression = $campaign['total_impression'];
$campaign=null;

//people summary
$sql = "SELECT author_name,author_id,author_avatar,SUM(imp) AS impression,0 as share,
SUM(total_mentions) AS mentions,SUM(rt_imp) AS rt_impression,SUM(rt_mention) AS rt_mention
FROM smac_author.campaign_geo_author_{$campaign_id} 
WHERE campaign_id={$campaign_id} AND geo='{$geo}' GROUP BY author_id 
ORDER BY impression DESC LIMIT 5;";


$q = mysql_query($sql,$conn);
//$f = mysql_fetch_assoc($q);

$num = mysql_num_rows($q);

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';

if($num > 0){
	while($r=mysql_fetch_array($q)){
		if($r['rt_impression']==null){
			$r['rt_impression'] = 0;
		}
		$r['share'] = ceil((($r['impression']+$r['rt_impression'])/$total_impression)*100);
		$xml .= '<user image="'.$r['author_avatar'].'" name="'.$r['author_id'].'" followers="'.smac_number($r['impression']+$r['rt_impression']).'" rt="'.smac_number($r['rt_mention']).'" share="'.$r['share'].'"/>';
	}
}
$xml .= '</rows>';

mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
echo $xml;
exit;