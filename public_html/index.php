<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH . APPLICATION . '/App.php';
include_once $APP_PATH."Interaction/Interaction.php";
include_once $ENGINE_PATH."Utility/Debugger.php";

$logger = new Debugger();
$logger->setAppName('smacapp');
$logger->setDirectory('../logs/');
$view = new BasicView();
$app = new App($req);
$app->main();

print $app;
die();
?>
