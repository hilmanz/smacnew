<?php
/**
 * Twitter Search RSS feeder
 * run per minutes
 */
include_once "config.php";
include_once "common.php";
$last_id = intval($_REQUEST['last_id']);
$entry = $arr['feed']['_c']['entry'];

$conn = open_db(0);
if($last_id==NULL){
	$sql = "SELECT * FROM ".$SCHEMA.".feeds WHERE retrieve_date >= CURRENT_DATE ORDER BY id ASC LIMIT 100";
}else{
	$sql = "SELECT * FROM ".$SCHEMA.".feeds WHERE id > ".$last_id." ORDER BY id ASC LIMIT 100";
}
$q = mysql_query($sql,$conn);
$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$str.="<rows>";
while($fetch = mysql_fetch_assoc($q)){
	$str.="<row>";
	$str.="<id>".$fetch['id']."</id>";
	$str.="<published>".$fetch['published']."</published>";
	$str.="<name>".$fetch['author_name']."</name>";
	$str.="<txt>".htmlspecialchars($fetch['content'])."</txt>";
	$str.="<lat>".$fetch['coordinate_x']."</lat>";
	$str.="<lon>".$fetch['coordinate_y']."</lon>";
	$str.="<profile_image_url>".$fetch['author_avatar']."</profile_image_url>";
	$str.="<insert_date>".$fetch['retrieve_date']."</insert_date>";
	$str.="</row>";
}
$str.="</rows>";
mysql_free_result($q);
close_db($conn);
header("Content-type: text/xml");
print $str;
?>
