<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$limit = intval($_REQUEST['limit']);

$from_date = strtotime(mysql_escape_string($_REQUEST['from']));
$to_date = strtotime(mysql_escape_string($_REQUEST['to']));


if($limit == 0){
	$limit = 1000;
}

$conn = open_db(0);

//get devices
$sql = "SELECT * FROM ".$SCHEMA.".device_lookup";
$q = mysql_query($sql,$conn);
$devices = array();
while($f=mysql_fetch_assoc($q)){
	$devices[] = $f;
}
$f=null;
mysql_free_result($f);

//check campaign language setting. is it restrict to specific language ?
$sql = "SELECT lang FROM smac_web.tbl_campaign WHERE id = {$campaign_id} LIMIT 1";
$campaign = fetch($sql,$conn);

/**
 * Before the lang detection is resolve force to use 'all' to have more relevant to current 
 * June 4, 2012
 */

//if($campaign['lang']=="all"){
if (0 < 1) {
	$sql = "SELECT feed_id, link,author_name, author_uri, comments, title, summary, published, source_service,generator
	FROM smac_report.campaign_web_feeds 
	WHERE campaign_id={$campaign_id} AND published_ts BETWEEN $from_date AND $to_date
	ORDER BY rank ASC LIMIT ".$limit;
}else{
	$sql = "SELECT feed_id, link,author_name, author_uri, comments, title, summary, published, source_service,generator
	FROM smac_report.campaign_web_feeds  a
	INNER JOIN smac_report.campaign_web_language b
	ON a.feed_id = b.fid
	WHERE a.campaign_id={$campaign_id} BETWEEN $from_date AND $to_date 
	AND b.lang='{$campaign['lang']}'
	ORDER BY rank ASC LIMIT ".$limit;
}

//print $sql;


$q = mysql_query($sql,$conn);
//print mysql_error();
//$f = mysql_fetch_assoc($q);

//echo mysql_error();exit;

$num = mysql_num_rows($q);



if($num > 0){
	$rs = array();
	while($r=mysql_fetch_assoc($q)){
		if($r['author_name']=="unknown"){
			$r['author_name'] = $r['author_uri'];
		}
		$rs[] = array('id'=>$r['feed_id'],
					  'url'=>$r['author_uri'],
					'link'=>$r['link'],
		'name'=>clean_ascii($r['author_name']),
		'title'=>clean_ascii($r['title']),
		'txt'=>clean_ascii($r['summary']),
		'device'=>get_device($r['generator'],$devices),
		'reach'=>number_format($r['comments']),
		'device'=>intval($rs_i['comments'])
		);
	}
}


mysql_free_result($q);
close_db($conn);

header("Content-type: application/json"); 
//echo $xml;
echo json_encode($rs);
exit;