<?php
	include("include/databaseConnection.php");
	include("include/reqauth.php");
	include("include/usuario.php");
	include("include/tarefa.php");
?>

<html>
<head>
<title>ACROSS - Tarefas disponíveis</title>
<meta charset="utf-8">
<?php include("include/style.php"); ?>

<script>
	var Job = {
		showInstructions: function(idx){
			if (typeof event.stopPropagation != "undefined") {
			  event.stopPropagation();
			}
			if (typeof event.cancelBubble  != "undefined") {
			  event.cancelBubble = true;
			}

			document.querySelector('.modal:not(.guest) IFRAME').src = "instructions.php?id=" + idx
			document.querySelector('.modal:not(.guest)').style.display = "inline";
			document.querySelector('.modal:not(.guest)').style.opacity = 1;
			event.stopPropagation()
		},
		
		hideInstructions: function(){
			document.querySelector('.modal:not(.guest)').style.display = "none";
			document.querySelector('.modal:not(.guest)').style.opacity = 0;		
		},

		toogleInst: function(){
			document.querySelector('#qint').style.display = (document.querySelector('#qint').style.display == "block") ? "none" : "block"
			event.target.src = (event.target.src.indexOf('up.png') != -1) ? "images/down.png" : "images/up.png"
		}
	};
</script>

</head>
<body>

<?php
	include("include/header.php");
?>

<article>
<?php
	if (isset($_GET['msg'])){
		if ($_GET['msg'] == "optout"){
			echo "<div class=\"completenote\">Você removeu suas contribuições com sucesso.</div>";
		}
	}
?>

<div class="headerOverlay">
<h1>Trabalhos disponíveis</h1><br>
<span>Clique no <b>nome da tarefa</b> para iniciar um novo trabalho ou retomar um trabalho já feito.</span>
<!-- <li>Clique em <b>instruções</b> para ler as instruções.</li> -->
</div>

<?php
	unset($_SESSION['tqId']);
	unset($_SESSION['tqPos']);
	unset($_SESSION['tqMedia']);
	unset($_SESSION['tqAnswer']);
	unset($_SESSION['tqReason']);
	unset($_SESSION["isCorrect"]);


	$database = DatabaseConnection::getDBObject();

	if (!mysqli_connect_errno($database)){
		$query = "SELECT * from task WHERE status=1;";
		$result = mysqli_query($database, $query);
		$userObj = new Usuario($_SESSION["useridnum"], $_SESSION["userprivilege"]);
		
		$gHTML = "";
		$numberOfTasks = 0;
		$firstTime = true;
		
		while ($row = mysqli_fetch_array($result)){
			$taskObj = new Tarefa($row["id"], $_SESSION['useridnum']);
			$remainingTasks = $taskObj->getRemainingTasks();
			
			if ($remainingTasks > 0 && $taskObj->canWork()){
				
				if ($firstTime){
					
					$gHTML .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"joblist\">";
					$gHTML .= "<tr><th>#</th><th>Título</th><th>Descrição</th><th>Qtd.</th></tr>";
					$firstTime = false;
				}
				
				$inst = $row["instructions"];
				if (strlen($inst) > 50){
					$inst = substr($inst, 0, 50) . "...";
				}
								
				$gHTML .= "<tr onclick=\"location.href = 'job.php?id=" . $row["id"] . "';\"><td>" . $row["id"] . "</td><td>" . $row["title"] . "</td>";
				$gHTML .= "<td style=\"cursor: pointer\" onclick=\"Job.showInstructions(" . $row["id"] . "); return false\">" . $inst ."</td>";
				$gHTML .= "<td>" . $remainingTasks . "</td></tr>";
				$numberOfTasks++;
			}
		}
		
		if ($numberOfTasks > 0){
			$gHTML .= "</table>";
		} else {
			$gHTML .= "<div class=\"warning\">";
			$gHTML .= "Não há trabalhos disponíveis. Tente novamente mais tarde";
			$gHTML .= "</div>";
		}
		
		echo $gHTML;
	}
?>

</article>

<div class="modal" style="display: none; opacity: 0" onclick="Job.hideInstructions()">
<table cellspacing="0" cellpadding="0">
<tr height=40><td><a href="#" class="btn">Fechar</a></td></tr>
<tr><td><iframe src="terms.php?mode=embedded"></iframe></td></tr>
</table>
</div>


<?php
	include("include/footer.php");
?>

</body>
</html>