<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
include_once "common.php";
// Define a destination
$user_folder = md5($_POST['account_id']."-folder");
$targetFolder = $report_upload_dir."reports/{$user_folder}"; // Relative to the root
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/' . $_POST['filename'])) {
	echo 1;
} else {
	echo 0;
}
?>