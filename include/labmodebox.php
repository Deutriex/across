<?php
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$isFEICable = ($hostname == "fei.static.gvt.net.br" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1");
	
	echo "<script src=\"js/ajax.js\"></script>";
	echo "<script src=\"js/labmodeController.js\"></script>";
	

	if ($isFEICable && (!isset($_SESSION['labMode']) || $_SESSION['labMode'] != 1)){
		echo "<div class=\"labmodeContainer\">";
		echo "<div id=\"labmodeStatus\"></div>";
		echo "<p>Você está em um laboratório reservado para o uso do Serviço? Insira o Código de Laboratório para configurar o serviço para uso em laboratórios reservados:</p>";
		echo "<p><input type=\"text\" id=\"labmodeCode\"><a href=\"#\" onclick=\"LabMode.validate()\" class=\"btn\">Prosseguir</a><a href=\"#\" onclick=\"document.querySelector('.labmodeContainer').style.display = 'none'; localStorage.noLabMode = true\" class=\"btn\">Não mostrar novamente</a></p>";
		echo "</div>";
		
	}
	
	echo "<script>LabMode.hidebox()</script>";

?>