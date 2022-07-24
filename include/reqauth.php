<?php
	ob_start();
	session_regenerate_id();
	ini_set('session.cookie_lifetime', 60 * 60 * 24 * 55);
	session_start();
	
	if (!isset($_SESSION["user"])){
		header('Location: login.php');
		exit(1);
	}
	
	if (file_exists('include/maintenance') && (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 1)){
		header('Location: auth.php?action=logout');
		exit(1);
	}
?>