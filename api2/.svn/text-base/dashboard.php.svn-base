<?php
/**
*API to provide Dashboard Statistics
*@author Hapsoro Renaldy <hapsoro.renaldy at kana.co.id>
*/
include_once "config.php";
include_once "common.php";

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);

if($campaign_id==0){
	header("Content-type: text/xml");
	print "<status>0</status>";
	die();
}
$conn = open_db(0);

//1.get campaign details
$sql = "SELECT * FROM smac.tbl_campaign WHERE client_id = ".$client_id." AND id=".$campaign_id." LIMIT 1";
$q = mysql_query($sql,$conn);
if($q){
$campaign = mysql_fetch_assoc($q);
mysql_free_result($q);
}
if(is_array($campaign)){
	$campaign_start = $campaign['campaign_start'];
	$campaign_end = $campaign['campaign_end'];
	$channels = sizeof(unserialize($campaign['channels']));


	//total mentions
	$sql = "SELECT COUNT(id) as total FROM smac_data.standard_feeds a
	WHERE retrieve_date BETWEEN '".$campaign_start."' AND '".$campaign_end."'
	AND
	a.tag_id IN (SELECT keyword_id FROM smac.tbl_campaign_keyword WHERE campaign_id=".$campaign_id.")";
	$q = mysql_query($sql,$conn);
	$r = mysql_fetch_assoc($q);
	mysql_free_result($q);
	$total_mentions = $r['total'];

	//total people
	$sql = "SELECT COUNT(*) as total FROM (SELECT author_id FROM smac_data.standard_feeds a
			WHERE retrieve_date BETWEEN '".$campaign_start."' AND '".$campaign_end."'
			AND
			a.tag_id IN (SELECT keyword_id FROM smac.tbl_campaign_keyword WHERE campaign_id=".$campaign_id.")
			GROUP BY author_id) cc";
	
	$q = mysql_query($sql,$conn);
	$r = mysql_fetch_assoc($q);
	mysql_free_result($q);
	$people = $r['total'];
	$str_people = "";
	if($people>1000){
		if($people>1000000){
			$str_people = round($people/1000000,2)."M";
		}else{
			$str_people = round($people/1000)."K";
		}
	}
}

$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$str.="<rows>\n";
$n=0;



	$str.="<row>\n";
	$str.="<total_mentions>".number_format($total_mentions)." Mentions</total_mentions>\n";
	$str.="<categories>0</categories>\n";
	$str.="<channels>".$channels."</channels>\n";
	$str.="<people>".$str_people."</people>\n";
	$str.="<influencers>0</influencers>\n";
	$str.="<cities>0</cities>\n";
	$str.="<words>\n";
	$str.="<word total='10'>makan</word>\n";
	$str.="<word total='56'>jakarta</word>\n";
	$str.="<word total='120'>macet</word>\n";
	$str.="<word total='24'>kemang</word>\n";
	$str.="</words>\n";
	$str.="</row>";
$str.="</rows>";

close_db($conn);
header("Content-type: text/xml");
print $str;
?>
