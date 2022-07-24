<?php
	session_start();
	include("include/databaseConnection.php");
	$database = DatabaseConnection::getDBObject();

	$queryN = "SELECT * FROM taskhistory WHERE idcrowdsourcer=" . $_SESSION['useridnum'] . " AND idtask=" . $_GET['taskid'] . ";";
	$resultN = mysqli_query($database, $querN);
	
	while ($rowN = mysqli_fetch_array($resultN)){
		$queryX = "DELETE FROM goldtaskhistory WHERE idnumref=" . $rowN['idnum'] . ";";
		mysqli_query($database, $queryX);
	}

	$query0 = "DELETE FROM taskhistory WHERE idcrowdsourcer=" . $_SESSION['useridnum'] . " AND idtask=" . $_GET['taskid'] . ";";
	$query = "INSERT INTO taskoptout (userid, taskid) VALUES (" . $_SESSION['useridnum'] . ", " . $_GET['taskid'] . ");";

	mysqli_query($database, $query0);
	mysqli_query($database, $query);
	
	header('Location: panel.php?msg=optout');
?>