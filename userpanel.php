<?php
	include("include/reqauth.php");
?>

<html>
<head>
<title>ACROSS - Painel do usuário </title>
<script src="js/ajax.js"></script>

<script>
	var ACROSS = new (function(){
		this.optOutWarning = function(){
			if (event.target.checked){
				if (document.querySelector('#pwmagic').innerText != "Pneumoultramicroscopicossilicovulcanoconiose"){
					event.target.checked = false
					alert('Digite sua senha atual antes de prosseguir.')
					document.querySelector('#pwbox').focus()
					return false
				}
				
				document.querySelector('#critical').className = "btn danger"
				document.querySelector('#critical').href = "accountTerminator.php"
	
				delta = confirm("Ao clicar em 'Sim' com esta opção ativa, todos os seus dados serão excluídos e você não poderá voltar a utilizar o serviço ACROSS criando cadastros futuros. Não haverá quaisquer caixas de diálogo de confirmações a não ser essa. Caso clicou acidentalmente nesta opção clique em Cancelar.")
				if (!delta){
					event.target.checked = false
					document.querySelector('#critical').className = "btn"
					document.querySelector('#critical').href = "#"
				}
			} else {
				document.querySelector('#critical').className = "btn"
				document.querySelector('#critical').href = "#"
			}
		}
		
		this.dismissPassword = function(){
			if (document.querySelector('#pwmagic').innerText == "Pneumoultramicroscopicossilicovulcanoconiose"){
				document.querySelector('#pwconfirmation').style.display = "none"
			} else {
				alert('Senha incorreta.')
				document.querySelector('#pwbox').focus()
			}
		}
		
		this.validatePassword = function(){
			pwAPC = new AjaxPoweredContainer(document.querySelector('#pwmagic'))
			pwAPC.getDados("changePassword", document.querySelector('#pwbox').value, undefined, ACROSS.dismissPassword)
		}
		
		this.changePassword = function(){
			if (document.querySelector('#pwmagic').innerText == "Pneumoultramicroscopicossilicovulcanoconiose"){
				if (document.querySelector('#newpw').value == document.querySelector('#newpw2').value){
					newPWAPC = new AjaxPoweredContainer(document.querySelector('#newpkok'))
					newPWAPC.getDados('newPassword', document.querySelector('#newpw').value, undefined, function(){document.querySelector('#newpwok').style.display = "inline"})
				} else {
					alert('As senhas são se coincidem.')
				}
			} else {
				alert('Digite sua senha atual antes de prosseguir.')
				document.querySelector('#pwbox').focus()
			}
		}
		
	})()

</script>

<?php include("include/style.php"); ?>
<meta charset="utf-8">

</head>
<body>

<?php
	echo "<div style=\"display: none\" id=\"pwmagic\">";
	
	if ($_SESSION["userprivilege"] < 2){
		echo "Pneumoultramicroscopicossilicovulcanoconiose";
	}
	
	echo "</div>";
	
	include("include/header.php");
?>

<div class="headerOverlay">
<h1>Painel do usuário</h1><br>
<span>Altere as opções disponíveis abaixo</span>
</div>

<article class="just">


<?php
	if ($_SESSION["userprivilege"] < 2){
		echo "<div class=\"warning\" id=\"pwconfirmation\">";
		echo "Digite a senha para poder efetuar as opções abaixo: <input type=\"password\" id=\"pwbox\">";
		echo "<input type=\"button\" value=\"Validar\" onclick=\"ACROSS.validatePassword()\">";
		echo "</div>";
	
		echo "<div style=\"display: inline-block; width: auto; vertical-align: top\">";
		echo "<h1>Mudar a senha</h1>";
	
		echo "<div id=\"newpkok\" style=\"display: none\"></div>";
	
		echo "<table>";
		echo "<tr><td>Nova senha:</td><td><input type=\"password\" id=\"newpw\"></td></tr>";
		echo "<tr><td>Confirme:</td><td><input type=\"password\"  id=\"newpw2\"></td></tr>";
		echo "</table>";
	
		echo "<p><a class=\"btn\" onclick=\"ACROSS.changePassword()\">Executar</a></p>";
		echo "</div>";
	}


	if ($_SESSION["userprivilege"] != 1){
		echo "<div style=\"display: inline-block; width: 500px; vertical-align: top\">";
		echo "<h1>Encerrar minha conta</h1><br>";
		echo "Você pode optar por encerrar a sua conta a qualquer momento. Todos os dados enviados por você, exceto o utilizado para fazer autenticação no servidor serão excluídos. Entretanto você não poderá se cadastrar de forma temporária ou definitiva em qualquer computador no futuro. Este processo é irreverrsível.";
		echo "<p><input type=\"checkbox\" onclick=\"ACROSS.optOutWarning()\"> <b class=\"danger\">Encerrar minha conta definitivamente</b></p>";
		echo "<p><a class=\"btn\" id=\"critical\" href=\"#\">Encerrar...</a></p>";
		echo "</div>";
	}
?>

</article>

<?php
	include("include/footer.php");
?>


</body>
</html>