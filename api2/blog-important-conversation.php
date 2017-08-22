<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$limit = intval($_REQUEST['limit']);
$geo = mysql_escape_string($_REQUEST['geo']);
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



$sql = "SELECT feed_id, link,author_name, author_uri, comments, title, summary, published, source_service,generator
FROM smac_report.campaign_web_feeds a INNER JOIN smac_report.campaign_web_country b
ON a.id = b.fid 
WHERE a.campaign_id={$campaign_id} AND b.geo='{$geo}'
ORDER BY rank ASC LIMIT ".$limit;


/*
$sql = "SELECT * 
FROM (
SELECT a.feed_id, a.author, a.content, a.avatar_pic, COUNT( a.feed_id ) AS total,b.score as sentiment
FROM smac_report.campaign_rt_content a
LET JOIN smac_report.campaign_rt_impact b
ON a.id = b.feed_id
WHERE a.campaign_id =".$campaign_id." 
AND b.campaign_id=".$campaign_id."
GROUP BY feed_id
)a
ORDER BY a.total DESC LIMIT 10";
*/
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