<?php
	include("include/reqauth.php");
	include("include/databaseConnection.php");
	$database = DatabaseConnection::getDBObject();
	
	$mysqldate = date('Y-m-d H:i:s');	
	
	$query = "INSERT INTO commenthistory (iduser, date, title, comment, isread) VALUES (" . $_SESSION["useridnum"] . ", '" . $mysqldate . "', '" . $_POST['title'] . "', '" . $_POST['comment'] . "', 0);";
	$result = mysqli_query($database, $query);
	echo $query;
	header('Location: index.php?id=commentSent');
?>