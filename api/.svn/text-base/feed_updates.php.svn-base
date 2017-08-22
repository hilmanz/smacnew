<?php
/**
 * Twitter Search RSS feeder
 * run per minutes
 */
include_once "/home/kana/kanadigital/preview/flex/config.php";
include_once "/home/kana/kanadigital/preview/flex/common.php";
//print $url."\n";
$n_loop = $LOOPS;
for($i=0;$i<$n_loop;$i++){
	//print "#feed ".($i+1)."\n";
	$rs = get_url(urlencode($url));

	$response = $rs[0];
	$info = $rs[1];
	var_dump($info);
	if($info['http_code']==200){
		//print $response;
		$arr = xml2ary($response);
		$entry = $arr['feed']['_c']['entry'];
		$conn = open_db(0);
		$sql = "INSERT INTO ".$SCHEMA.".twitter_bulk(raw_txt,retrieve_date,n_status)
				VALUES('".mysql_escape_string($response)."',NOW(),0)";
		mysql_query($sql,$conn);
		close_db($conn);
		print "ok\n";
	}else{
	 print "failed\n";
	}
	sleep($LOOP_INTERVAL);
}
print "done\n";
?>
