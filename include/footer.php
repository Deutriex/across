<?php
	if (!class_exists("Utilities")) include("utilities.php");

	if (!Utilities::isMobile()){
		echo "<footer>";

		$addClass = "";

		if (strpos($_SERVER["SCRIPT_FILENAME"], "terms.php") !== FALSE){
			$addClass = "btn";
		}

		echo "<a class=\"" . $addClass . "\" href=\"terms.php\">Termos de serviço</a>";


		$addClass = "";

		if (strpos($_SERVER["SCRIPT_FILENAME"], "privacy.php") !== FALSE){
			$addClass = "btn";
		}

		echo "<a class=\"" . $addClass . "\" href=\"privacy.php\">Política de privacidade</a> ";

		$addClass = "";

		if (strpos($_SERVER["SCRIPT_FILENAME"], "help.php") !== FALSE){
			$addClass = "btn";
		}

		echo "<a class=\"" . $addClass . "\" href=\"help.php\">Ajuda</a> ";

		$addClass = "";

		if (strpos($_SERVER["SCRIPT_FILENAME"], "contact.php") !== FALSE){
			$addClass = "btn";
		}

		echo "<a class=\"" . $addClass . "\" href=\"contact.php\">Contato</a> ";
		
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$isFEICable = ($hostname == "fei.static.gvt.net.br");
		
		if (!$isFEICable){
			echo "<a class=\"btn fb\" href=\"https://www.facebook.com/acrosscrowd\"><img src=\"images/fb_icon_325x325.png\" style=\"height: 16px; width: 16px\"> Curta nossa página</a>";
		}
		
		echo "<div class=\"copy\">&copy; Copyright 2015 Iago Brunherotto e Raphael Gasparini. Todos os direitos reservados.</div>";
		echo "</footer>";
	}
?>