<?php
/**
 * Twitter Search RSS feeder
 * run per minutes
 */
include_once "config.php";
include_once "common.php";

$entry = $arr['feed']['_c']['entry'];
$conn = open_db(0);
$sql = "SELECT * FROM ".$SCHEMA.".twitter_bulk WHERE n_status=0 LIMIT 1";

$q = mysql_query($sql,$conn);
$fetch = mysql_fetch_assoc($q);
mysql_free_result($q);
if(is_array($fetch)){
	$arr = xml2ary($fetch['raw_txt']);
	$entry = $arr['feed']['_c']['entry'];
	
	foreach($entry as $data){
		//var_dump($data);
		
		$content = $data['_c'];
		$author = mysql_escape_string($content['author']['_c']['name']['_v']);
		$title = mysql_escape_string($content['title']['_v']);
		$published = mysql_escape_string($content['published']['_v']);
		$google_location = mysql_escape_string($content['google:location']['_v']);
		$coordinate = retrieve_coordinate($google_location);
		$pic = $content['link'][1]['_a']['href'];
		$ids = explode(":",$content['id']['_v']);
		#var_dump($content['id']);
		$tweet_id = trim($ids[2]);
		#print $tweet_id;
		if(is_array($coordinate)){
			//insert into db
			$sql = "INSERT IGNORE INTO ".$SCHEMA.".twitter_feeds(name,txt,lat,lon,insert_date,published,pic,tweet_id)
					VALUES('".$author."','".$title."','".$coordinate[0]."','".$coordinate[1]."',NOW(),'".$published."',
					'".$pic."',".$tweet_id.")";
			mysql_query($sql,$conn);
		}
		
		//print $content['published']['_v']."&nbsp;".$author."-".$content['title']['_v']."<br/>";
	}
	//flag bulk
	$sql = "UPDATE ".$SCHEMA.".twitter_bulk SET n_status=1 WHERE id=".intval($fetch['id']);
	mysql_query($sql,$conn);
}
close_db($conn);
?>
