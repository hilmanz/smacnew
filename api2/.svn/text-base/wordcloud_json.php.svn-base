<?php
/**
 * Twitter Search RSS feeder
 * run per minutes
 */
include_once "config.php";
include_once "common.php";
$last_id = intval($_REQUEST['last_id']);

//temporary solution, until gnip feed re-activated
if($last_id==0){
	//$last_id = 1;
}
//-->

$campaign_id = intval($_REQUEST['campaign_id']);
$client_id = intval($_REQUEST['client_id']);
$entry = $arr['feed']['_c']['entry'];
$limit = 100;

$conn = open_db(0);
$sql="SELECT keyword,occurance as amount FROM smac_report.campaign_words WHERE campaign_id='".$campaign_id."' 
	ORDER BY occurance DESC LIMIT 50;";

$q = mysql_query($sql,$conn);
$num = mysql_num_rows($q);
//echo 'sql: '.$sql.'<br />';
//echo $num;
//exit;
$words = array();
if($num>0){
	while($r=mysql_fetch_assoc($q)){
		//$words[ $r['keyword'] ] = $r['amount'];
		$words[] = $r;
	}
}

mysql_free_result($q);
close_db($conn);

/*
//$words = array("kemang"=>154300,"makan"=>12450,"gw"=>563000,"jakarta"=>34500);
$n_total = 0;





$str = "<?xml version=\"1.0\"?>\n";
$str.="<rows>\n";
$str.="<text>several of favorite word</text>\n";
foreach($words as $word=>$tt){
	if($n_total==0){$n_total = $tt;}
	$n = ceil($tt/$n_total*100);
	//$n=$n/10;
	for($i=0;$i<$n;$i++){
		$str.="<row>";
		$str.="<word>".htmlspecialchars($word)."</word>";
		$str.="</row>";
	}
}
$str.="</rows>\n";


header("Content-type: text/xml");
print $str;
*/
?>
