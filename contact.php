<?php
	if (file_exists('include/maintenance')){
		include("include/maintenance.php");
		exit(1);
	}

	include("include/reqauth.php");
?>

<html>
<head>
<?php include("include/style.php"); ?>
<meta charset="utf-8">
</head>
<body>
<?php
	include ("include/header.php");
?>

<div class="headerOverlay">
<h1>Contato</h1><br>
<span>Dúvidas, sugestões, reclamações e solicitações</span>
</div>

<div style="padding: 10pt;">

<form method="POST" action="commentController.php">
<p>Título: <input type="text" size="50" name="title"></p>
<p>Mensagem:<br>
<textarea style="width: 100%; height: 300px; resize: vertical" name="comment"></textarea>
</p>

<input type="submit" value="Enviar">

</form>
</div>


<?php
	include ("include/footer.php");
?>

</body>
</html>
