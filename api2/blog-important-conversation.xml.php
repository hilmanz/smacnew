<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$limit = intval($_REQUEST['limit']);

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

$sql = "SELECT a.feed_id, link,author_name, author_uri, b.comments_count AS comments, title, summary, published, source_service,generator
FROM smac_report.campaign_web_history a
INNER JOIN smac_data.feeds_blogsearch_topsy b ON a.feed_id = b.id
WHERE a.campaign_id =".$campaign_id."
ORDER BY comments DESC 
LIMIT ".$limit;



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

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';

if($num > 0){
	while($r=mysql_fetch_array($q)){
		if($r['author_name']=="unknown"){
			$r['author_name'] = $r['author_uri'];
		}
		$xml.="<row>\n";
		$xml.="<id>".$r['feed_id']."</id>\n";
		$xml.="<published></published>\n";
		$xml.="<url><![CDATA[".$r['author_uri']."]]></url>\n";
		$xml.="<link>".$r['link']."</link>\n";
		$xml.="<name><![CDATA[".clean_ascii($r['author_name'])."]]></name>\n";
		$xml.="<title><![CDATA[".clean_ascii($r['title'])."]]></title>\n";
		$xml.="<txt><![CDATA[".clean_ascii($r['summary'])."]]></txt>\n";
		$xml.="<device>".get_device($r['generator'],$devices)."</device>\n";
		$xml.="<keyword></keyword>\n";
		$xml.="<lat></lat>\n";
		$xml.="<lon></lon>\n";
		$xml.="<profile_image_url></profile_image_url>\n";
		$xml.="<insert_date></insert_date>\n";
		$xml.="<reach>".number_format($r['comments'])."</reach>\n";
		$xml.="<sentiment>".intval($rs_i['comments'])."</sentiment>\n";
		$xml.="</row>";
	}
}
$xml .= '</rows>';

mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
echo $xml;
exit;