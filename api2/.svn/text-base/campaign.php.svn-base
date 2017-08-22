<?php
/**
 * Create Campaign Service
 */
include_once "config.php";
include_once "common.php";
include_once "libs/Campaign.php";
include_once "../engines/Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName('api_campaign');
$logger->setDirectory('../logs/');
$logger->info("foo");

$conn = open_db(0);
$campaign = new Campaign();
header("Content-type: text/xml");
print $campaign->get_campaign_detail('xml');
close_db($conn);
?>