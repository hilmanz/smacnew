<?php
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$exclude = intval($_REQUEST['exclude']);
$lang = mysql_escape_string($_REQUEST['lang']);
if($lang==null||strlen($lang)>3){
	$lang = 'all';
}

$conn = open_db(0);

//ambassador

if($exclude==1){
	$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
		FROM smac_supporter.campaign_ambas_{$campaign_id}
		WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
			ORDER BY sentiment DESC LIMIT 6";
	
}else if($exclude==2){
	
	$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
		FROM smac_supporter.campaign_ambas_{$campaign_id}
		WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
			ORDER BY sentiment DESC LIMIT 6";

}else{
	$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
		FROM smac_supporter.campaign_ambas_{$campaign_id}
		 ORDER BY sentiment DESC LIMIT 6";
	
}

$q = mysql_query($sql,$conn);
//print mysql_error(0);
while($f = mysql_fetch_assoc($q)){
	$ambas[] = $f;
}

//trolls

if($exclude==1){
	$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
		FROM smac_supporter.campaign_trolls_{$campaign_id}
		WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=1)
		ORDER BY sentiment ASC LIMIT 6";
}else if($exclude==2){
	$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
		FROM smac_supporter.campaign_trolls_{$campaign_id}
		WHERE author_id NOT IN (SELECT author_id FROM smac_report.kol_group WHERE account_type=2)
		ORDER BY sentiment ASC LIMIT 6";
}else{
	$sql = "SELECT author_id as author,author_avatar as pic,sentiment as total,pii_score 
		FROM smac_supporter.campaign_trolls_{$campaign_id}
		ORDER BY sentiment ASC LIMIT 6";	
}
	
$q = mysql_query($sql,$conn);
while($f = mysql_fetch_assoc($q)){
	$trolls[] = $f;
}

$num = mysql_num_rows($q);

header("Content-type: text/xml"); 
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rows>';
$xml .= 		'<category name="ambassador">';
				foreach($ambas as $a):
$xml .=					'<row>';
$xml .=						'<img>'.$a['pic'].'</img>';
$xml .=						'<name>'.$a['author'].'</name>';
$xml .=						'<rt>'.smac_number($a['total']).'</rt>';
$xml .=						'<impressi>'.number_format($a['pii_score']).'</impressi>';
$xml .=						'<positive>+'.smac_number($a['total']).'</positive>';
$xml .=					'</row>';
				endforeach;
$xml .= 		'</category>';
$xml .= 		'<category name="troll">';
				foreach($trolls as $t):
$xml .=					'<row>';
$xml .=						'<img>'.$t['pic'].'</img>';
$xml .=						'<name>'.$t['author'].'</name>';
$xml .=						'<rt>'.smac_number(abs($t['total'])).'</rt>';
$xml .=						'<impressi>'.number_format($t['pii_score']).'</impressi>';
$xml .=						'<positive>-'.smac_number(abs($t['total'])).'</positive>';
$xml .=					'</row>';
				endforeach;
$xml .= 		'</category>';
$xml .= '</rows>';

echo $xml;
mysql_close($conn);
exit;
?>