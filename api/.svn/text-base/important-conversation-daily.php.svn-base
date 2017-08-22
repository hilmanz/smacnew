<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$limit = intval($_REQUEST['limit']);
$lang = mysql_escape_string($_REQUEST['lang']);
$from_date = mysql_escape_string($_REQUEST['from']);
$to_date = mysql_escape_string($_REQUEST['to']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

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


//total impressions
$sql = "SELECT SUM(total_impression_twitter) AS true_reach
FROM smac_report.campaign_rule_volume_history 
WHERE campaign_id={$campaign_id} AND dtreport BETWEEN '{$from_date}' AND '{$to_date}';";

$q = mysql_query($sql,$conn);
$campaign=mysql_fetch_assoc($q);
mysql_free_result($q);
$total_impression = $campaign['true_reach'];
$campaign=null;

/*
$sql = "SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, 
		published_date,followers as imp,generator,content
FROM (
	SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
	FROM smac_feeds.campaign_feeds_{$campaign_id} a
		LEFT JOIN smac_rt.rt_content_{$campaign_id} b
			ON a.feed_id = b.feed_id
		INNER JOIN smac_report.campaign_feed_lang c
			ON a.ref_campaign_feeds_id = c.campaign_feed_id
	WHERE a.is_active = 1 AND published_date BETWEEN '{$from_date}' AND '{$to_date}'
	GROUP BY b.feed_id
) a
ORDER BY imp DESC LIMIT {$limit};
";
*/

/*
$sql = "SHOW TABLES FROM smac_rt like 'rt_content_{$campaign_id}'";
$rs = mysql_query($sql, $conn);
$is_rt_exists = false;
if($row = mysql_fetch_array($rs)) {
    $is_rt_exists = true;
}
mysql_free_result($rs);	

if ($is_rt_exists) {
	$sql = 
		"SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, 
		       published_date,followers as imp,generator,content
		FROM (
		   SELECT a.*,COUNT(b.feed_id) as rt_total,SUM(rt_author_followers) as rt_imp 
		   FROM smac_feeds.campaign_feeds_{$campaign_id}  a
		       LEFT JOIN smac_rt.rt_content_{$campaign_id}  b
		           ON a.feed_id = b.feed_id
		   WHERE a.is_active = 1 AND published_date BETWEEN '{$from_date}' AND '{$to_date}'
		   GROUP BY a.feed_id
		) a
		ORDER BY imp DESC LIMIT {$limit};
		";
} else {
	$sql = 
		"SELECT feed_id,rt_total,rt_imp,author_id,author_name,author_avatar as avatar_pic, 
		       published_date,followers as imp,generator,content
		FROM (
		   SELECT a.*,0 as rt_total,0 as rt_imp 
		   FROM smac_feeds.campaign_feeds_{$campaign_id}  a
		   WHERE a.is_active = 1 AND published_date BETWEEN '{$from_date}' AND '{$to_date}'
		) a
		ORDER BY imp DESC LIMIT {$limit};
		";

}
*/

$sql = "SELECT feed_id,local_rt_count as rt_total,
			local_rt_impresion as rt_imp,
			author_id,
			author_name,
			author_avatar AS avatar_pic, 
			published_date,
			followers AS imp,generator,content
		FROM smac_feeds.campaign_feeds_{$campaign_id}
		WHERE is_active = 1 AND published_date BETWEEN '{$from_date}' AND '{$to_date}'
		ORDER BY followers desc
		LIMIT 10";


mysql_set_charset("utf8",$conn);
$q = mysql_query($sql,$conn);


$num = mysql_num_rows($q);

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';

if($num > 0){
	$rs = array();
	while($fetch=mysql_fetch_array($q)){
		$rs[] = $fetch;
	}
	foreach($rs as $r){
			$share = round((($r['imp']+$r['rt_imp'])/$total_impression)*100,4);
			$xml.="<row>\n";
			$xml.="<id>".$r['feed_id']."</id>\n";
			$xml.="<published>".$r['published_date']."</published>\n";
			$xml.="<author_id>".$r['author_id']."</author_id>\n";
			$xml.="<name>".htmlspecialchars($r['author_name'])."</name>\n";
			$xml.="<txt><![CDATA[".utf8tohtml($r['content'])."]]></txt>\n";
			$xml.="<device>".get_device($r['generator'],$devices)."</device>\n";
			$xml.="<keyword></keyword>\n";
			$xml.="<lat></lat>\n";
			$xml.="<lon></lon>\n";
			
			
			$xml.="<profile_image_url>".$r['avatar_pic']."</profile_image_url>\n";
			$xml.="<insert_date></insert_date>\n";
			$xml.="<imp>".smac_number($r['imp']+$r['rt_imp'])."</imp>\n";
			$xml.="<rt>".smac_number($r['rt_total'])."</rt>\n";
			$xml.="<rt_imp>".smac_number($r['rt_imp'])."</rt_imp>\n";
			$xml.="<sentiment>0</sentiment>\n";
			$xml.="<share>".$share."</share>\n";
			$xml.="</row>";
			mysql_free_result($qq);
	}
}
$xml .= '</rows>';

mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml"); 
echo $xml;
exit;
?>