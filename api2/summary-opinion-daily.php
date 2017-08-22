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
$from_date = ($_REQUEST['from']);
$to_date = ($_REQUEST['to']);


$conn = open_db(0);
//total impressions
//total impressions
if($lang=='all'){
	$sql = "SELECT SUM( impressions ) AS impression,SUM(rt_impression) as rt_imp
			FROM smac_market.campaign_daily_stats
			WHERE campaign_id =".$campaign_id."
			AND lang = 'all'
			AND geo='{$geo}'
			AND published_date >= '".$from_date."'
			AND published_date <= '".$to_date."'
			GROUP BY campaign_id LIMIT 1";
}else{
	$sql = "SELECT SUM( impressions ) AS impression,SUM(rt_impression) as rt_imp
			FROM smac_market.campaign_daily_stats
			WHERE campaign_id =".$campaign_id."
			AND lang = '".$lang."'
			 AND geo='{$geo}'
			AND published_date >= '".$from_date."'
			AND published_date <= '".$to_date."'
			GROUP BY campaign_id LIMIT 1";
}
$q = mysql_query($sql,$conn);
$campaign=mysql_fetch_assoc($q);
mysql_free_result($q);
$total_impression = $campaign['impression']+$campaign['rt_imp'];
$campaign=null;

//people summary
if($lang=='all'){
	$sql = "SELECT * FROM (SELECT campaign_id,author_id,author_name,author_avatar,SUM(total_mentions) as total_mentions,SUM(imp) as impression,
				SUM(rt_imp) as rt_impression,SUM(rt_mention) as rt_mention
				FROM smac_market.campaign_people_daily_stats WHERE campaign_id=".$campaign_id." 
				AND geo='{$geo}'
				AND published_date >='".$from_date."' AND published_date<='".$to_date."'
				GROUP BY author_id) a ORDER BY impression DESC LIMIT 5";
}else{
	$sql = "SELECT * FROM (SELECT a.campaign_id,a.author_id,author_name,author_avatar,SUM(total_mentions) as total_mentions,SUM(imp) as impression,
				SUM(rt_imp) as rt_impression,SUM(rt_mention) as rt_mention
				FROM smac_market.campaign_people_daily_stats a
				INNER JOIN smac_market.campaign_people_lang b
				ON a.author_id = b.author_id
				WHERE a.campaign_id=".$campaign_id." AND b.campaign_id=".$campaign_id." 
				AND a.geo='{$geo}'
				AND a.published_date >='".$from_date."' AND a.published_date<='".$to_date."'
				GROUP BY author_id) c ORDER BY c.impression DESC LIMIT 5";
	
}

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

?>