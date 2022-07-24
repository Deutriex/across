<?php
	include("DatabaseConnection.php");
	include("accessControl.php");
	$teste = AccessControl::getInstance();

	$getFileExtension = explode(".", $_SERVER['REQUEST_URI']);
	$getFileExtension = $getFileExtension[count($getFileExtension)-1];
	$specialExtensions = array("js", "jpg", "css", "jpeg", "png", "bmp", "gif", "woff", "ico");


	if ($teste->isAlternet($_SERVER['HTTP_HOST'])){

		echo include("absentAlternet.php");

	} else if (in_array($getFileExtension, $specialExtensions) !== false) {

	} else {
		if ($_SERVER['SERVER_NAME'] != $_SERVER['REQUEST_URI']){
			$teste->getBlockRedirectCode();
		}
	}
?>