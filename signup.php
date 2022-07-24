<?php
	session_start();

	if (file_exists('include/maintenance')){
		include("include/maintenance.php");
		exit(1);
	}
?>

<html>
<head>
<title>ACROSS - Increva-se</title>

<?php include("include/style.php"); ?>
<meta charset="utf-8">

<?php
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$isFEICable = ($hostname == "fei.static.gvt.net.br" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1");
	if (!$isFEICable){
		echo "<script src=\"js/facebooksignup.js\"></script>";
	}
?>


<script>
	var Across = {
		showText: function(newText){
			document.querySelector('.modal:not(.guest) IFRAME').src = newText;
			document.querySelector('.modal:not(.guest)').style.display = "inline";
			document.querySelector('.modal:not(.guest)').style.opacity = 1;
			
		},
		
		hideText: function(){
			document.querySelector('.modal:not(.guest)').style.display = "none";
			document.querySelector('.modal:not(.guest)').style.opacity = 0;		
		},
		
		beforeSigningUp: function(){
			if (document.querySelectorAll('DIV[name=abacorpo]')[0].textContent == "Padrão"){
				if (document.getElementsByName('password')[0].value == ""){
					alert('As senhas não podem ficar em branco')
					return false

				}
				if (document.getElementsByName('username')[0].value == ""){
					alert('Nome de usuário não pode ficar em branco')
					return false
				}
				
				if (document.getElementsByName('password')[0].length < 8){
					alert('As senhas devem ter no mínimo 8 caracteres')
				}
				

			}
	
			if (!document.getElementsByName('tos')[0].checked){
				alert('Você deve concordar com os Termos de Serviço e Política de Privacidade!')
				return false
			}
			return true			
		},
		
		changeFormAction: function(mode){
			document.querySelector('FORM[name=signupForm]').action = "signupvalidate.php?mode=" + mode
			
			if (mode == "face"){
				document.querySelector('INPUT[type=submit]').style.display = "none"
			} else {
				document.querySelector('INPUT[type=submit]').style.display = "inline"
			}
			
		},
		
		tooglePassword: function(){
			selectIt = document.querySelector('.signupform TR:nth-child(2) TD:nth-child(2) INPUT')
			toogler = []
			toogler['text'] = "password"
			toogler['password'] = "text"
			
			selectIt.type = toogler[selectIt.type]
		},
		
		tooglePasswordM: function(){
			selectIt = document.querySelector('.signupform TR:nth-child(2) TD:nth-child(2) INPUT')
			selectIt2 = document.querySelector('.spypass')
			
			toogler = []
			toogler['text'] = "password"
			toogler['password'] = "text"
			
			selectIt.type = toogler[selectIt.type]
			
			if (selectIt.type == "text"){
				selectIt2.className = "spypass sel"
			} else {
				selectIt2.className = "spypass"
			}
		},

	
		
	};

 
</script>

</head>
<body>

<?php
	include("include/header.php");
	
	if (isset($_GET["error"]) && $_GET["error"] == "exuser"){
		echo "<div class=\"warning\">Usuário já existe. <a href=\"login.php\">Fazer login?</a>.</div>";
	}
	
?>


<div class="headerOverlay">
<?php
	if (!isset($_SESSION['userprivilege'])){
		echo "<h1>Teste agora</h1>";
	} else {
		echo "<h1>Mude agora</h1>";
	}
	
	echo "<br><span>O processo demora menos de um minuto</span>";
?>
</div>



<div class="centered">
<div class="centered0 centered2">
<div style="display: inline-block; vertical-align: top">
<?php
	
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$isFEICable = ($hostname == "fei.static.gvt.net.br" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1");
	$isFEI = ($isFEICable || $hostname == "200-232-90-170.customer.tdatabrasil.net.br");

	echo "<p>Tipo de cadastro (<a onclick=\"Across.showText('usertype.php?mode=embedded');\">Ajuda</a>):</p>";
	echo "<nav class=\"adminTabs\">";

	$abas = array();
	$acao = array();
	$abOrder = array();	
	
	if (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 0){
		array_push($abas, "Padrão");
		array_push($acao, "perm");
		array_push($abOrder, 0);
	}
	
	if (!$isFEICable && (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 3)){
		$abOrderN = 1;
		
		if (isset($_SESSION['userprivilege']) && $_SESSION['userprivilege'] == 0) $abOrderN--;

		array_push($abas, "Facebook");
		array_push($acao, "face");
		array_push($abOrder, $abOrderN);
	}
	
	if (!isset($_SESSION['labMode']) || $_SESSION['labMode'] != 1 && (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 2)){
		$abOrderN = 2;
		
		if (isset($_SESSION['userprivilege']) && $_SESSION['userprivilege'] == 0) $abOrderN--;
		if ($isFEICable || (isset($_SESSION['userprivilege']) && $_SESSION['userprivilege'] == 3)) $abOrderN--;	
		
		array_push($abas, "Temporário");
		array_push($acao, "temp");
		array_push($abOrder, $abOrderN);
	} 

	if ($isFEICable && isset($_SESSION['labMode']) && $_SESSION['labMode'] == 1 && (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 4)){
		$abOrderN = 1;
		array_push($abas, "Laboratório");
		array_push($acao, "lab");
		array_push($abOrder, $abOrderN);
	}
	
	for ($i = 0; $i < count($abas); $i++){
		$isActive = "";
		if ($i == 0) $isActive = "active";
		
		echo "<a class=\"" . $isActive . "\" name=\"abacabeca\" onclick=\"setTab('abacabeca', 'abacorpo'," . $abOrder[$i] . "); Across.changeFormAction('" . $acao[$i] . "')\">";
		echo $abas[$i];
		echo "</a>";
		
	}
	echo "</nav>";


	echo "<form method=\"POST\" action=\"signupvalidate.php?mode=" . $acao[0] . "\" onSubmit=\"return Across.beforeSigningUp()\" name=\"signupForm\">";

	if (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 0){
		echo "<div name=\"abacorpo\">";
		echo "<table class=\"signupform\">";		
		echo "<tr><td>Usuário</td><td colspan=\"2\"><input type=\"text\" name=\"username\"></td></tr>";
		echo "<tr><td>Senha</td><td><input type=\"password\" name=\"password\"></td><td onmousedown=\"Across.tooglePassword()\" onmouseup=\"Across.tooglePassword()\"></td></tr>";
		echo "</table>";
		
		if (Utilities::isMobile()){
			echo "<div class=\"spypass\" onclick=\"Across.tooglePasswordM()\">Visualizar a senha</div>";
		}
		
		echo "</div>";
	}

	if (!$isFEICable && (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 3)){
		echo "<div name=\"abacorpo\" style=\"display: none\">";
	
		echo "<fb:login-button scope=\"public_profile,email\" onlogin=\"firstTime = false; checkLoginState();\">";
		echo "</fb:login-button>";
	
		echo "<div id=\"status\"></div>";
		echo "</div>";
	}

	if ((!$isFEICable || (!isset($_SESSION['labMode']) || $_SESSION['labMode'] != 1)) && (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 2)){
		echo "<div name=\"abacorpo\" style=\"display: none\">";
		echo "Como usuário temporário, Basta aceitar os termos e começar a utilizar o sistema!";
		echo "</div>";
	} else if (!isset($_SESSION['userprivilege']) || $_SESSION['userprivilege'] != 4){
		echo "<div name=\"abacorpo\" style=\"display: none\">";
		echo "Como usuário do Centro Universitário da FEI, basta aceitar os termos e começar a utilizar o sistema!";
		echo "</div>";
	}
?>
</div>


<div style="display: inline-block; max-width: 400px; vertical-align: top">
<p><input type="checkbox" name="tos"> Eu concordo com os <a href="javascript:void(0);" onclick="Across.showText('terms.php?mode=embedded');">Termos de serviço</a> e <a href="javascript:void(0);" onclick="Across.showText('privacy.php?mode=embedded');">Política de Privacidade</a>.</p>
<input type="submit" value="Prosseguir">
</div>

</form>

</div>
</div>


<?php
	include("include/footer.php");
?>

<div class="modal" style="display: none; opacity: 0" onclick="Across.hideText();">
<table cellspacing="0" cellpadding="0">
<tr height=40><td>Clique aqui para fechar</td></tr>
<tr><td><iframe src="terms.php?mode=embedded"></iframe></td></tr>
</table>
</div>

<script src="js/tab.js"></script>
<script>
	if (localStorage.username){
		tabs = document.querySelectorAll('A[name=abacabeca]')
		
		for (i = 0; i < tabs.length; i++){
			if (tabs[i].textContent == "Temporário"){
				tabs[i].parentNode.removeChild(tabs[i])
			}
		}
		
		
	}
</script>

<?php
	include("include/labmodebox.php");
?>


</body>
</html>