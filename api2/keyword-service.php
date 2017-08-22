<?php
include_once "config.php";
include_once "common.php";

$conn = open_db(0);

$res = 0;
if($_POST['act'] == 'edit'){
	$id = $_POST['id'];
	$value = $_POST['value'];
	$campaign_id = intval($_POST['campaign_id']);
	
	if(is_numeric($id)&&is_numeric($value)){
		$qry = "UPDATE smac_report.campaign_sentiment_setup 
				SET weight='".$value."' WHERE keyword_id=".mysql_escape_string($id)." AND campaign_id=".$campaign_id.";";
		if(mysql_query($qry,$conn)){
			$res = 2; //biar ngga reload di applikasinya
			$qry = "UPDATE smac_report.job_campaign_words
				SET update_sentiment=1,is_sentiment_manual=1 WHERE campaign_id=".$campaign_id.";";
			mysql_query($qry,$conn);
		}else{
			$res = 0;
		}
	}else{
		$res = 0;
	}
}elseif($_POST['act'] == 'add'){
	$value = intval($_POST['value']);
	$word = mysql_escape_string($_POST['word']);
	$category = mysql_escape_string($_POST['category']);
	$campaign_id = intval($_POST['campaign_id']);
	
	$qry = "SELECT count(*) total FROM ".$SCHEMA_WEB.".tbl_campaign_sentiment WHERE keyword='$word' AND campaign_id='$campaign_id'";
	$q = mysql_query($qry,$conn);
	$res = mysql_fetch_assoc($q);
	
	if(intval($res['total']) <= 0){
		$qry = "INSERT INTO ".$SCHEMA_WEB.".tbl_campaign_sentiment (keyword,category,score,campaign_id) VALUES ('$word','$category','$value','$campaign_id');";
		if(mysql_query($qry,$conn)){
			$res = 1;
		}else{
			$res = 0;
		}
	}else{
		$res = 0;
	}
}elseif($_POST['act'] == 'delete'){
	$id = intval($_POST['id']);
	$campaign_id = intval($_POST['campaign_id']);
	
	$qry = "DELETE FROM ".$SCHEMA_WEB.".tbl_campaign_sentiment WHERE id='$id' AND campaign_id='$campaign_id';";
	if(mysql_query($qry)){
		$res = 1; //biar ngga reload di applikasinya
	}else{
		$res = 0;
	}
}
mysql_free_result($q);
close_db($conn);

header("Content-type: text/xml");
$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= "<row><status>".$res."</status></row>";
echo $xml;
exit;