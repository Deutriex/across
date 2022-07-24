<?php
	$query = "SELECT DISTINCT idunit FROM taskhistory WHERE idtask=" . $_GET["data"] . " AND gold=0 ORDER BY idunit;";
	$result = mysqli_query($database, $query);
	
	$getType = 0;
	$getDataType = 0;
	
	$query5 = "SELECT * FROM task WHERE id=" . $_GET["data"] . ";";
	$result5 = mysqli_query($database, $query5);
	$row5 = mysqli_fetch_array($result5);
	
	$getType = $row5['type'];
	$getDataType = $row5['datatype'];

	if ($row5['status'] == 1){
		echo "<div class=\"warning\">";
		echo "A tarefa está em excecução e portanto os resultados são parciais. Quando estiver terminada, aparecerá uma opção para exportar os resultados em um arquivo XML";
		echo "</div>";
	}

	if ($row5['status'] == 2){
		echo "<div>";
		echo "<p><a class=\"btn\" href=\"download.php?data=" . $_GET["data"] . "\">Exportar para arquivo XML</a></p>";
		echo "</div>";
	}
	
	echo "<table class=\"joblist\" cellpadding=0 cellspacing=0>";
	echo "<tr>";
	echo "<th>ID</th>";
	echo "<th>Mídia</th>";
	echo "<th>";
	
	echo "<table style=\"width: 100%\">";
	echo "<tr>";
	
	if ($getType == 2){
		echo "<th style=\"align: center\">Dado</th>";
		echo "<th style=\"align: center\">Crowdsourcer</th>";
	}
	
	echo "<th style=\"align: left\">Resposta</th>";
	echo "<th style=\"text-align: right\">Confiança</th>";
	echo "</tr>";
	echo "</table>";
	
	echo "</th>";
	echo "</tr>";
	
	while ($row = mysqli_fetch_array($result)){
		echo "<tr>";
		echo "<td>" . $row['idunit'] . "</td>";
		$query2 = "SELECT * FROM taskunit WHERE unitid=" . $row['idunit'] . ";";
		$result2 = mysqli_query($database, $query2);
		$row2 = mysqli_fetch_array($result2);
		
		$mediaSrc = "";
		
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
		
		
		echo "<td style=\"width: 120px; height: 90px; background-image: url('media/image/" . $mediaSrc . "'); background-size: contain; background-repeat: no-repeat; background-position: center center\">&nbsp;</td>";
		echo "<td>";
		
		$query3 = "SELECT DISTINCT data FROM taskhistory WHERE idtask=" . $_GET["data"] . " AND gold=0 AND idunit=" . $row['idunit'] .  " ORDER BY data;";
		$result3 = mysqli_query($database, $query3);
		
		$arrTmp = array();
		
		echo "<table cellspacing=0 cellpadding=0 width=100%>";	
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

			if ($getType != 2){
				//array_push("", $arrTmpLet);
				//array_push("", $arrTmpLet);
			}


			
			
		}
		usort($arrTmp, "cmp");
		for ($i = 0; $i < count($arrTmp); $i++){
			$setClass = "";
			if ($i < intval($row5['bestanswers']) && $arrTmp[$i][1] > 0) $setClass = "selected";
			
			echo "<tr>";
			
			if ($getType == 2){
				echo "<td class=\"" . $setClass . "\"></td>";
				echo "<td class=\"" . $setClass . "\"></td>";
			}
			
			echo "<td class=\"" . $setClass . "\" style=\"text-align: right\">" . $arrTmp[$i][0] . "</td>";
			echo "<td class=\"" . $setClass . "\" style=\"text-align: right\">" . $arrTmp[$i][1] . "</td>";
			echo "</tr>";
		}
		
		echo "</table>";
		
		echo "</td>";
		echo "</tr>";
	}

	echo "</table>";
?>