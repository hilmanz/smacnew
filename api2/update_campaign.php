<?php
/**
 * Create Campaign Service
 */
include_once "config.php";
include_once "common.php";
include_once "libs/Campaign.php";
$conn = open_db(0);
$campaign = new Campaign();
header("Content-type: text/xml");
print $campaign->update_campaign();
close_db($conn);
?>
