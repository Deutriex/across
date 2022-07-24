<?php
	include("databaseConnection.php");
	
	$query = "SELECT * FROM task WHERE id=" . $_GET['data'] .";";
	$result = mysqli_query($database, $query);
	$row = mysqli_fetch_array($result);
	
	echo "<b>Tarefa: " . $row['title'] . "</b>";
	
	$query2 = "SELECT count(DISTINCT idcrowdsourcer) as totalpeople FROM taskhistory WHERE idtask=" . $_GET['data'] . ";";
	$result2 = mysqli_query($database, $query2);
	$row2 = mysqli_fetch_array($result2);
	$noPeople = $row2['totalpeople'];
	echo "<p>" . $noPeople . " pessoa(s) fizeram esta tarefa</p>";
	
	$query3 = "SELECT DISTINCT idcrowdsourcer FROM taskhistory WHERE idtask=" . $_GET['data'] . ";";
	$result3 = mysqli_query($database, $query3);
	
	echo "<table class=\"joblist taskcrowd\" cellpadding=0 cellspacing=0>";
	echo "<tr>";
	echo "<th onclick=\"sortTable('.taskcrowd', 0)\">ID</th>";
	echo "<th onclick=\"sortTable('.taskcrowd', 1)\">Usuário</th>";
	echo "<th onclick=\"sortTable('.taskcrowd', 2)\">Submissões</th>";
	echo "<th onclick=\"sortTable('.taskcrowd', 3)\">Credibilidade (%)</th>";
	echo "<th onclick=\"sortTable('.taskcrowd', 4)\">Bandeira?</th>";
	echo "</tr>";
	for ($i = 0; $row3 = mysqli_fetch_array($result3); $i++){
		$flagLabel = array();
		array_push($flagLabel, "Não");
		array_push($flagLabel, "Sim");
		
		$userObj = new Usuario($row3['idcrowdsourcer']);
		echo "<tr><td>" . $row3['idcrowdsourcer'] . "</td><td>" . $userObj->getUsername() . "</td><td onclick=\"ACROSS.loadSingleUserContributions(" . $row3['idcrowdsourcer'] . ", " . $_GET['data'] . ")\">" .$userObj->getSubmissions($_GET['data']) . "</td><td onclick=\"ACROSS.loadSingleUserGoldContributions(" . $row3['idcrowdsourcer'] . ", " . $_GET['data'] . ")\">" . $userObj->getTrustScore($_GET['data'], true) . "</td><td onmouseover=\"ACROSS.setFlagActionLabel()\" onmouseout=\"ACROSS.clearFlagActionLabel()\" onclick=\"ACROSS.runFlagAction(" . $row3['idcrowdsourcer'] . ", " . $_GET['data'] . ")\" style=\"cursor: pointer\">" . $flagLabel[$userObj->hasFlagOn($_GET['data'])] . "</td></tr>";
	}
	echo "</table>";
?>