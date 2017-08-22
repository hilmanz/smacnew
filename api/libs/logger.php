<?php
function log_load_average($load_size=0){
	global $DATABASE,$SCHEMA;
	$today = date("Y-m-d");
	$sql0 = "INSERT IGNORE INTO ".$SCHEMA.".payload_average(the_date,load_size,counter)
		VALUES('".$today."',0,0)";
	
	$sql1 = "UPDATE ".$SCHEMA.".payload_average SET counter = counter+1, load_size = load_size + ".$load_size."
		WHERE the_date = '".$today."'";

	$conn = open_db(0);
	print "Insert new average load data if not exist\n";
	$q = mysql_query($sql0,$conn);
	print "Update average load\n";
	$q2 = mysql_query($sql1,$conn);
	close_db($conn);
}
function get_average_load($the_date){
	global $DATABASE,$SCHEMA;
	
	$sql1 = "SELECT * FROM ".$SCHEMA.".payload_average
		WHERE the_date = '".$the_date."' LIMIT 1";

	$conn = open_db(0);
	
	$q = mysql_query($sql1,$conn);
	print mysql_error();
	$f = mysql_fetch_assoc($q);

	mysql_free_result($q);
	close_db($conn);
	$avg_load = (intval($f['load_size'])/intval($f['counter']));
	return $avg_load;
}
