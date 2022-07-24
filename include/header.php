<?php
	if (!class_exists("Utilities")) include("utilities.php");


	if (isSet($_SESSION["userprivilege"]) && $_SESSION["userprivilege"] >= 2){

		if ($_SESSION["userprivilege"] == 2){
			echo "<script src=\"js/ajax.js\"></script>";
		}

		echo "<script src=\"js/guest.js\"></script>";
		include("include/modalGuest.php");
	}
	
	if (isset($_SESSION["userprivilege"]) && $_SESSION["userprivilege"] == 3 && strpos($_SERVER["SCRIPT_FILENAME"], "signup.php") == FALSE && strpos($_SERVER["SCRIPT_FILENAME"], "login.php") == FALSE){
		echo "<script src=\"js/facebookheader.js\"></script>";		
	}
	
	if (Utilities::isMobile()) echo "<script src=\"js/mobile.js\"></script>";
?>



<header>

<table cellspacing=0 cellpadding=0>
<tr>
<td class="logo" onclick="location.href = 'index.php';"></td>
<td class="btnContainer">

<?php
	if (!file_exists('include/maintenance') || (isset($_SESSION['userprivilege']) && $_SESSION['userprivilege'] == 1)){
		
		if (!Utilities::isMobile()){
			if (!isSet($_SESSION["privilege"])){

				if (strpos($_SERVER["SCRIPT_FILENAME"], "signup.php") == FALSE) echo "<a href=\"signup.php\" class=\"btn\">Testar agora</a>";
				if (strpos($_SERVER["SCRIPT_FILENAME"], "login.php") == FALSE) echo "<a href=\"login.php\" class=\"btn\">Fazer login</a>";
				
				echo "<script>";
				echo "var isAuth = false;";
				echo "var firstTime = true;";
				echo "</script>";
				
			} else {
				echo "<script>";
				echo "var isAuth = true;";
				echo "var firstTime = true;";
				
				if ($_SESSION["userprivilege"] == 2){
					echo "localStorage.username = \"" . $_SESSION["user"] . "\";";
				}
				
				echo "</script>";

				echo "<div style=\"background-image: url(images/users.png)\" id=\"userPic\"></div>";
				
			
				$isTemp = ($_SESSION["userprivilege"] == 2 || $_SESSION["userprivilege"] == 4);
				

				$dispName = "<a href=\"userpanel.php\" title=\"Acessar o painel de usuário\">" . $_SESSION["user"] . "</a>";
				if ($isTemp) $dispName = "<a href=\"userpanel.php\" title=\"Acessar o painel de usuário\">Convidado</a>";

				if (!$isTemp){
					if ($_SESSION["userprivilege"] == 0) echo "<p class=\"default\">";
					if ($_SESSION["userprivilege"] == 1) echo "<p class=\"admin\">";
					if ($_SESSION["userprivilege"] == 3) echo "<p class=\"face\">";
					echo $dispName;
				} else {
					if ($_SESSION["userprivilege"] == 2) echo "<p class=\"guest\">";
					if ($_SESSION["userprivilege"] == 4) echo "<p class=\"lab\">";
					echo $dispName;
				}
				echo "</p>";
				
				if (strpos($_SERVER["SCRIPT_FILENAME"], "signup.php") == FALSE && $_SESSION["userprivilege"] == 2){
					echo "<a href=\"signup.php\" class=\"btn\" title=\"Inscreva-se em definitivo para continuar fazendo as tarefas disponíveis na sua casa\">Inscreva-se</a>";
				}
			
				if ($_SESSION["privilege"] == "admin"){
					echo "<a href=\"adminpanel.php\" class=\"btn\">Admin</a>";
				}
	
				if ($_SESSION["privilege"] == "user"){
					echo "<a href=\"panel.php\" class=\"btn\">Tarefas</a>";
				}
				
				if ($isTemp || $_SESSION["userprivilege"] == 3){
					$addSteps = "";			
					$wType = "warning";
	
					if ($_SESSION["userprivilege"] == 2) $addSteps = "Guest.getRetrievalCode()";
					if ($_SESSION["userprivilege"] == 3) $wType = "";
					if ($_SESSION["userprivilege"] == 4) $wType = "danger";
					
					echo "<a href=\"#\" onclick=\"Guest.showLogoutWarning(); " . $addSteps . "\" class=\"btn " . $wType . "\">";
				} else {
					echo "<a href=\"auth.php?action=logout\" class=\"btn\">";
				}
				
				echo "Sair</a>";
			}

		} else {
			echo "<div class=\"pradobtn\" onclick=\"Mobile.tooglePradoMenu();\"></div>";
		}
	} else {
		echo "<div>";
		echo "<b>Em manuntenção</b>";
		echo "<p>Não é possível usar o Serviço agora</p>";
		echo "</div>";
	}
	
?>

</td>
</tr>
</table>

<?php
	if (Utilities::isMobile()){
		$isTemp = (isset($_SESSION["privilege"]) && ($_SESSION["userprivilege"] == 2 || $_SESSION["userprivilege"] == 4));
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$isFEICable = ($hostname == "fei.static.gvt.net.br");
		
		echo "<div class=\"pradomenu\" style=\"display: none\">";

		if (isset($_SESSION["privilege"])){
			$dispName = $_SESSION["user"];
			if ($isTemp) $dispName = "Convidado";

			echo "<div onclick=\"location.href = 'userpanel.php';\">" . $dispName . "</div>";
		}
		
		if (isset($_SESSION["privilege"]) && $_SESSION["privilege"] == "admin") echo "<div onclick=\"location.href = 'adminpanel.php';\">Admin</div>";
		if (isset($_SESSION["privilege"]) && $_SESSION["privilege"] == "user") echo "<div onclick=\"location.href = 'panel.php';\">Tarefas</div>";
		

		if (strpos($_SERVER["SCRIPT_FILENAME"], "signup.php") == FALSE && (!isset($_SESSION["privilege"]) || $_SESSION["privilege"] == 2 || $_SESSION["privilege"] == 4)) echo "<div onclick=\"location.href = 'signup.php';\">Testar agora</div>";
		if (strpos($_SERVER["SCRIPT_FILENAME"], "login.php") == FALSE && !isset($_SESSION["privilege"])) echo "<div onclick=\"location.href = 'login.php';\">Fazer login</div>";
		
		
		if (strpos($_SERVER["SCRIPT_FILENAME"], "terms.php") == FALSE) echo "<div onclick=\"location.href = 'terms.php';\">Termos de serviço</div>";
		if (strpos($_SERVER["SCRIPT_FILENAME"], "privacy.php") == FALSE) echo "<div onclick=\"location.href = 'privacy.php';\">Política de privacidade</div>";
		if (strpos($_SERVER["SCRIPT_FILENAME"], "contact.php") == FALSE) echo "<div onclick=\"location.href = 'contact.php';\">Contato</div>";
		
		if (!$isFEICable) echo "<div onclick=\"location.href = 'http://facebook.com/acrosscrowd';\">Página no Facebook</div>";

		if (isset($_SESSION["privilege"])){
			if ($isTemp || $_SESSION["userprivilege"] == 3){
				$addSteps = "";			
				$wType = "warning";

				if ($_SESSION["userprivilege"] == 2) $addSteps = "Guest.getRetrievalCode()";
				if ($_SESSION["userprivilege"] == 3) $wType = "";
				if ($_SESSION["userprivilege"] == 4) $wType = "danger";
				
				echo "<div onclick=\"Guest.showLogoutWarning(); " . $addSteps . "\" class=\"" . $wType . "\">";
			} else {
				echo "<div onclick=\"location.href = 'auth.php?action=logout';\">";
			}
			
			echo "Sair</div>";
		}

		
		echo "</div>";
	}
?>


<script>
	if (localStorage.FBuserProfile && document.querySelector('#userPic')) document.querySelector('#userPic').style.backgroundImage = "url('" + localStorage.FBuserProfile + "')"
</script>

</header>