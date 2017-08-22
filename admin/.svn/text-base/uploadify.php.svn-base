<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
include_once "common.php";
include_once $ENGINE_PATH."Utility/Debugger.php";

$logger = new Debugger();
$logger->setAppName('uploadify');
$logger->setDirectory('../logs/');
// Define a destination
$user_folder = sha1("cmp#".intval($_POST['topic_id']));
$targetFolder = $report_upload_dir."{$user_folder}"; // Relative to the root
$logger->info($targetFolder);
$logger->info(json_encode($_POST));
$verifyToken = md5('jonnysayshello' . $_POST['timestamp']);
$logger->info($_POST['token']."<->".$verifyToken);
$logger->info(json_encode($_FILES));
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$logger->info("PREPARING..");
	
	$tempFile = $_FILES['Filedata']['tmp_name'];
	//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	if(!is_dir($targetFolder)){
		$logger->info("folder doesn't exists");
		if(mkdir($targetFolder)){
			$logger->info("{$targetFolder} created");
		}else{
			$logger->info("{$targetFolder} failed to create");
		}
		$logger->info("---");
	}
	$targetPath = $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	$logger->info("attempt :  {$tempFile}->{$targetFile}");
	// Validate the file type
	$fileTypes = array('pdf','doc','csv','xls','xlsx'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$logger->info(json_encode($fileTypes));
	$logger->info("fileparts -->".json_encode($fileParts));
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		$logger->info("{$tempFile}->{$targetFile}");
		echo '1';
		$logger->info("uploaded");
	} else {
		$logger->info("failed");
		echo 'Invalid file type.';
	}
}
?>