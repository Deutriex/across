<?php
	header("Content-type: application/xml");
	header("Content-Disposition: attachment; filename=\"result.xml\"");

	include("include/databaseConnection.php");
	include("include/tarefa.php");
	include("include/usuario.php");
	$database = DatabaseConnection::getDBObject();

	function cmp($a, $b){
		$delta = strcmp($a[0], $b[0]);
		if ($delta == 0) return 0;
		if ($a[1] < $b[1]) return 1;
		else if ($a[1] > $b[1]) return -1;
		else if ($a[1] == $b[1]){
			return $delta;
		}

	}

	$query = "SELECT DISTINCT idunit FROM taskhistory WHERE idtask=" . $_GET["data"] . " AND gold=0 ORDER BY idunit;";
	$result = mysqli_query($database, $query);
	
	$getType = 0;
	$getDataType = 0;

	$query5 = "SELECT * FROM task WHERE id=" . $_GET["data"] . ";";
	$result5 = mysqli_query($database, $query5);
	$row5 = mysqli_fetch_array($result5);
	
	$getType = $row5['type'];
	$getDataType = $row5['datatype'];

	echo "<?xml ver=\"1.0\" ?>\n";
	echo "<acrossxml>\n";

	while ($row = mysqli_fetch_array($result)){
		echo "<media ";

		$query2 = "SELECT * FROM taskunit WHERE unitid=" . $row['idunit'] . ";";
		$result2 = mysqli_query($database, $query2);
		$row2 = mysqli_fetch_array($result2);
		
		$mediaSrc = "";

		echo "type=\"";
		
		if ($getDataType == 0) echo "image";
		if ($getDataType == 1) echo "audio";
		if ($getDataType == 2) echo "video";

		echo "\" ";
		
		if ($getType != 2){
			$mediaSrc = $row2['mediasrc'];
		} else {
			$query7 = "SELECT * FROM taskhistory WHERE idnum=" . $row2['mediasrc'] . ";";
			$result7 = mysqli_query($database, $query7);
			$row7 = mysqli_fetch_array($result7);
			
			$query8 = "SELECT * FROM taskunit WHERE unitid=" . $row7['idunit'] . ";";
			$result8 = mysqli_query($database, $query8);
			$row8 = mysqli_fetch_array($result8);
			
			$mediaSrc = $row8['mediasrc'];
		}

		echo "src=\"" . $mediaSrc  . "\" ";
		
		
		$query3 = "SELECT DISTINCT data FROM taskhistory WHERE idtask=" . $_GET["data"] . " AND gold=0 AND idunit=" . $row['idunit'] .  " ORDER BY data;";
		$result3 = mysqli_query($database, $query3);
		
		$arrTmp = array();
		
		while ($row3 = mysqli_fetch_array($result3)){
			$arrTmpLet = array();
			array_push($arrTmpLet, $row3['data']);
			
			if ($getType != 2){
				$query9 = "SELECT * FROM taskhistory;";
			}
			
			
			$query4 = "SELECT idcrowdsourcer FROM taskhistory WHERE idtask=" . $_GET["data"] . " AND gold=0 AND idunit=" . $row['idunit'] . " AND data='" . $row3['data'] . "';";

			$result4 = mysqli_query($database, $query4);
			$totalConfidence = 0;
								
			while ($row4 = mysqli_fetch_array($result4)){
				$getUser = new Usuario($row4['idcrowdsourcer']);			
				$taskObj = new Tarefa($_GET['data'], $row4['idcrowdsourcer']);
				
				$test1 = !$getUser->hasFlagOn($_GET['data']);
				$test2 = ($getUser->getTrustScore($_GET['data']) >= $taskObj->getPrecisaoMinima());
				
				if ($test1 && $test2){
					$totalConfidence +=  $getUser->getTrustScore($_GET['data']);
				}
			}			
			array_push($arrTmpLet, floor($totalConfidence*1000)/1000);
			array_push($arrTmp, $arrTmpLet);


			
			
		}

		echo "tags=\"";

		usort($arrTmp, "cmp");
		$tmpAns = array();
		for ($i = 0; $i < count($arrTmp); $i++){
			$setClass = "";
			if ($i < intval($row5['bestanswers']) && $arrTmp[$i][1] > 0) $setClass = "selected";

			if ($setClass == "selected"){
				array_push($tmpAns, $arrTmp[$i][0]);
			}
		}

		echo implode(", ", $tmpAns);

		echo "\" ";
	

		echo "/>\n";
	}



	echo "</acrossxml>";
?>