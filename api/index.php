<?php
include_once "common.api.php";
include_once $APP_PATH."smac_api/App.php";
$app = new SmacAPI($req);
//we need client to authenticate and recieve the access token before they could make an API call.
$app->requireAuth(true);
//fire up the engine !
print $app->run();
?>