<?php
	date_default_timezone_set('America/Sao_Paulo');
	$filename = $_SERVER["SCRIPT_FILENAME"];
	
 	echo "<br><span><em>Última atualização: ";
	echo date ("d/m/Y", filemtime($filename)) . " às " .  date ("H:i:s", filemtime($filename));
	echo " (Horário de Brasília)</em></span>";
?>