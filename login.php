<html>
<head>
<title>ACROSS - Login</title>

<?php include("include/style.php"); ?>
<meta http-equiv="charset" value="utf-8">

<?php
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$isFEICable = ($hostname == "fei.static.gvt.net.br");
	if (!$isFEICable){
		echo "<script src=\"js/facebooklogin.js\"></script>";
	}
?>

<script>
	var Across = {
		beforeSubmit: function(){
			gText = document.querySelectorAll('A[name=abacabeca] .active').textContent
			
			if (gText == "Temporário"){
				document.querySelector('INPUT[name=username]').value = localStorage.username
				document.querySelector('INPUT[name=password]').value = document.querySelector('INPUT[name=retrieval]').value
			}
			
		},
		
		changeFormAction: function(mode){
			document.querySelector('FORM[name=authform]').action = "auth.php?mode=" + mode
			document.querySelector('INPUT[name=username]').value = ""
			document.querySelector('INPUT[name=password]').value = ""
			
			if (mode == "face"){
				document.querySelector('INPUT[type=submit]').style.display = "none"
			} else {
				document.querySelector('INPUT[type=submit]').style.display = "inline"
			}
		}
	};

 </script>


</head>
<body>

<?php
	include("include/header.php");
	
	if (isset($_GET["error"]) && $_GET["error"] == 401){
		echo "<div class=\"warning\">Usuário e senha não correspondem.</div>";
	}
	
	if (isset($_GET["status"])){
		if ($_GET["status"] == "banned"){
			echo "<div class=\"expelnote\">Este usuário foi banido e não tem permissão de criar novos cadastros em nenhum computador.</div>";
		}

		if ($_GET["status"] == "optedout"){
			echo "<div class=\"expelnote\">Este usuário optou em excluir-se o sistema e remover todos os dados relevantes. Não é possível logar com estas credencias conforme explicado nos <a href=\"terms.php\">Termos de Serviço</a>.</div>";
		}

	}
	
	if (isset($_GET["msg"]) && $_GET['msg'] == "usercreated"){
		echo "<div class=\"completenote\">Usuário criado com sucesso</div>";
	}
?>


<div class="headerOverlay">
<h1>Login</h1><br><span>Seja bem-vindo!</span>
</div>


<article>


Autenticar-se como:
<br>
<?php
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$isFEICable = ($hostname == "fei.static.gvt.net.br");
	
	echo "<nav class=\"adminTabs\">";
	$abas = array("Padrão");
	$acao = array("perm");
	$abOrder = array(0);
	$nextAbOrder = 1;
	
	if (!$isFEICable){
		array_push($abas, "Facebook");
		array_push($acao, "face");
		array_push($abOrder, $nextAbOrder);
		$nextAbOrder++;
	}
	
	array_push($abas, "Temporário");
	array_push($acao, "temp");
	array_push($abOrder, $nextAbOrder);
	
	for ($i = 0; $i < count($abas); $i++){
		$isActive = "";
		if ($i == 0) $isActive = "active";
		
		echo "<a class=\"" . $isActive . "\" name=\"abacabeca\" onclick=\"setTab('abacabeca', 'abacorpo'," . $abOrder[$i] . "); Across.changeFormAction('" . $acao[$i] . "')\">";
		echo $abas[$i];
		echo "</a>";
		
	}
	echo "</nav>";
	
	
?>


<form method="POST" action="auth.php" name="authform" onsubmit="Across.beforeSubmit()">
<div name="abacorpo" style="display: inline">
<table>
<tr><td>Usuário</td><td><input type="text" name="username" maxlength="20"></td></tr>
<tr><td>Senha</td><td><input type="password" name="password" maxlength="55"></td></tr>
</table>
</div>

<?php
	if (!$isFEICable){
		echo "<div name=\"abacorpo\" style=\"display: none\">";
		echo "<fb:login-button scope=\"public_profile,email\" onlogin=\"firstTime = false; checkLoginState();\">";
		echo "</fb:login-button>";
	
		echo "<div id=\"status\"></div>";
		echo "</div>";
	}
?>


<div name="abacorpo" style="display: none">
Código de recuperação:
<p><input type="text" name="retrieval" size="30"></p>

</div>
<input type="submit" value="Autenticar-se" onclick="Across.beforeSubmit()">
</form>


<?php
	include("include/footer.php");
?>


</article>




</body>
</html>