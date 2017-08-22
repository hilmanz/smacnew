<?php
include_once "common.php";
include_once $APP_PATH."smac_api/App.php";
$app = new SmacAPI($req);
print $app->run();
?>