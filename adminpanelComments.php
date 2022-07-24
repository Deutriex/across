<?php
	include("include/databaseConnection.php");
	$database = DatabaseConnection::getDBObject();


	$query = "SELECT * FROM commenthistory ORDER BY isread ASC, date DESC;";
	$result = mysqli_query($database, $query);
	$resCount = 0;
	$gHTML = "";
	
	while ($row = mysqli_fetch_array($result)){
		if ($resCount == 0){
			$gHTML .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"joblist\">";
			$gHTML .= "<tr><th>Data</th><th>Usuário</th><th>Título</th><th>Comentário</th></tr>";	
		}
		
		$query2 = "SELECT username FROM users WHERE idnum='" . $row['idnum'] . "';";
		$result2 = mysqli_query($database, $query2);
		$row2 = mysqli_fetch_array($result2);
		
		$date = new DateTime($row["date"]);
		
		$gHTML .= "<tr><td>" . $date->format('d/m/Y') . " às " . $date->format('H:i:s') . "</td><td>" . $row2["username"] . "</td><td>" . $row["title"] . "</td><td>" . $row["comment"] . "</td></tr>";
		$resCount++;
	}
	
	if ($resCount != 0) $gHTML .= "</table>";
	
	if ($resCount == 0){
		echo "<div class=\"warning\">Não há comentarios feitos.</div>";
	}
	

	echo "<div style=\"padding-left: 10pt\">";
	echo "<p>Estes são os comentários feitos:</p>";
	echo $gHTML;
	echo "</div>";

?>
