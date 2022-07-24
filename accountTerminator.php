<?php
	session_start();
	include("include/databaseConnection.php");
	$database = DatabaseConnection::getDBObject();
	
	$queryN = "SELECT * FROM taskhistory WHERE idcrowdsourcer=" . $_SESSION['useridnum'] . ";";
	$resultN = mysqli_query($database, $querN);
	
	while ($rowN = mysqli_fetch_array($resultN)){
		$queryX = "DELETE FROM goldtaskhistory WHERE idnumref=" . $rowN['idnum'] . ";";
		mysqli_query($database, $queryX);
	}
	
	$query0 = "DELETE FROM taskhistory WHERE idcrowdsourcer=" . $_SESSION['useridnum'] . ";";
	$query = "UPDATE users SET status=2 WHERE idnum=" . $_SESSION['useridnum'] . ";";

	mysqli_query($database, $query0);
	mysqli_query($database, $query);
	
	session_destroy();	
	header('Location: index.php');	
?>