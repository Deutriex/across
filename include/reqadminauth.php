<?php
	if (!isSet($_SESSION["privilege"]) || $_SESSION["privilege"] != "admin"){
		header('HTTP/1.1 401 Unauthorized');
		include("include/401.php");
		exit;
	}
 ?>