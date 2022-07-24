<html>
<head>

<script>
	var ACROSS = {
		toogleTQ: function(){
			getTR = event.target
			while (getTR.nodeName != "TR"){
				getTR = getTR.parentNode
			}
			getTR.cells[5].children[0].disabled = !getTR.cells[5].children[0].disabled
			getTR.cells[6].children[0].disabled = !getTR.cells[6].children[0].disabled
			
			if (getTR.cells[5].children[0].disabled){
				document.querySelector("#tableobj").rows[0].cells[0].textContent++
				document.querySelector("#tableobj").rows[1].cells[0].textContent--
			} else {
				document.querySelector("#tableobj").rows[0].cells[0].textContent--
				document.querySelector("#tableobj").rows[1].cells[0].textContent++
			}			
		},
		
		createCode: function(){
			queries = []
			gRow = document.querySelector("#datatable").rows
			for (i = 1; i < gRow.length; i++){
				if (gRow[i].cells[4].children[0].checked){
					queries.push("INSERT INTO goldunit (taskid, mediasrc, answer, reason) VALUES (3, " + gRow[i].cells[0].textContent + ", '" + gRow[i].cells[5].children[0].checked + "', '" + gRow[i].cells[6].children[0].value + "');")
				} else {
					queries.push("INSERT INTO taskunit (taskid, mediasrc) VALUES (3, " + gRow[i].cells[0].textContent + ");")
				}
			}
			
			document.querySelector("#genCode").value = queries.join("\x0D\x0A")
		}
	}
</script>

</head>
<body>

<div style="display: inline-block">
<?php
	include("include/databaseConnection.php");
	$database = DatabaseConnection::getDBObject();
	
	$query = "SELECT * FROM taskhistory WHERE idtask=1 AND gold=0;";
	$result = mysqli_query($database, $query);

	echo "<table id=\"datatable\">";
	echo "<tr><th>ID</th><th>Mídia</th><th>Dado coletado</th><th>Crowdsourcer</th><th>Teste?</th><th>Resposta</th><th>Razão</th></tr>";
	while ($row = mysqli_fetch_array($result)){
		echo "<tr>";
		echo "<td>" . $row['idnum'] . "</td>";
		
		$query2 = "SELECT mediasrc FROM taskunit WHERE unitid=" . $row['idunit'] . ";";
		$result2 = mysqli_query($database, $query2);
		$row2 = mysqli_fetch_array($result2);
		
		echo "<td style=\"background-image: url('media/image/" . $row2['mediasrc'] .  "'); background-repeat: no-repeat; background-position: center center; background-size: contain; width: 120px; height: 90px\">";
		
		echo "</td>";
		
		echo "<td>" . $row['data'] . "</td>";
		echo "<td>" . $row['idcrowdsourcer'] .  "</td>";
		echo "<td><input type=\"checkbox\" onclick=\"ACROSS.toogleTQ()\"></td>";
		echo "<td><input type=\"checkbox\" disabled></td>";
		echo "<td><textarea  disabled></textarea></td>";
			
		echo "</tr>";
	}
	echo "</table>";
?>
</div>

<div style="display: inline-block; vertical-align: top">

<table id="tableobj">
<tr><td><?php 

	$query = "SELECT COUNT(*) as totalq FROM taskhistory WHERE idtask=1 AND gold=0;";
	$result = mysqli_query($database, $query);
	$row = mysqli_fetch_array($result);
	echo $row['totalq'];

?></td><td>Questões normais</td></tr>
<tr><td>0</td><td>Questões de teste</td></tr>
</table>

<p><input type="button" value="Gerar" onclick="ACROSS.createCode()"></p>

<textarea id="genCode"></textarea>
</div>

</body>
</html>