<?php
	include("include/databaseConnection.php");
	include("include/reqauth.php");
	include("include/usuario.php");
	include("include/tarefa.php");
	
	if (isset($_SESSION["isCorrect"]) && $_SESSION["isCorrect"] == 0){
		$_SESSION["isCorrect"] = 1;
		header('Location: job.php?id=' . $_GET['id']);
		exit(1);
	}
	
	$database = DatabaseConnection::getDBObject();
	
	$getTaskInfoPage = "SELECT * FROM task WHERE id=" . $_GET['id'] . ";";
	$queryExec = mysqli_query($database, $getTaskInfoPage);
	$result = mysqli_fetch_array($queryExec);
	$getQuestionsPage = $result['questionspage'];
	$getMinAccuracy = $result['minaccuracy'];
	
	$unitidarray = explode(", ", $_SESSION['unitidarray']);
		
	$tqFound = 0;
	for ($i = 0; $i < $getQuestionsPage; $i++){
		if (!isset($_POST["inputdata"][$i]) || !($_POST["inputdata"][$i]) || $_POST["inputdata"][$i] == ""){
			$_POST["inputdata"][$i] = "false";
		}
		
		if ($i != $_SESSION['tqPos']){
			$query = "INSERT INTO taskhistory (idunit, idtask, gold, idcrowdsourcer, data) VALUES (" . $unitidarray[$i-$tqFound] . ", " . $_GET["id"] . ", 0, " . $_SESSION["useridnum"] . ", '" . $_POST["inputdata"][$i] . "');";
			mysqli_query($database, $query);
		} else {
			$tqFound = 1;
		}
	}
	
	$query = "INSERT INTO taskhistory (idunit, idtask, gold, idcrowdsourcer, data) VALUES (" . $_SESSION["tqId"] . ", " . $_GET["id"] . ", 1, " . $_SESSION["useridnum"] . ", '" . $_POST["inputdata"][ $_SESSION['tqPos']] . "');";
	mysqli_query($database, $query);
	
	$query = "SELECT max(idnum) as lasttransactid FROM taskhistory;";
	$result2 = mysqli_query($database, $query);
	$row2 = mysqli_fetch_array($result2);
	
	$isCorrect = 0;
	
	$_SESSION["crowdAnswer"] = $_POST["inputdata"][$_SESSION['tqPos']];
	
	$queryAw = "SELECT * FROM goldunitanswers WHERE idref=" . $_SESSION['tqId'] . ";";
	$resAw = mysqli_query($database, $queryAw);

	while ($rowAw = mysqli_fetch_array($resAw)){	
		if ($_SESSION["crowdAnswer"] == $rowAw['answer']){
			$isCorrect = 1;
		}
	}
	
	$_SESSION["isCorrect"] = $isCorrect;

	$query2 = "INSERT INTO goldtaskhistory (idnumref, correct) VALUES (" . $row2['lasttransactid'] . ", " .  $isCorrect. ");";
	mysqli_query($database, $query2);
	header('Location: job.php?id=' . $_GET['id']);	
	
?>

