<div class="modal guest" style="display: none; opacity: 0" onclick="Guest.hideLogoutWarning()">";
<div class="small">
<h1>Atenção</h1>
<hr>

<div style="width: 100%; height: calc(100% - 50px); overflow-y: auto"><?php
	if ($_SESSION["userprivilege"] == 2){
		echo "<p>Se você sair sem criar uma conta em defintivo, você poderá perder o acesso a sua conta a qualquer momento, principalmente se você limpar os cookies ou usar programas de limpeza como o CCleaner. Você é encorajado a <a href=\"signup.php\" onclick=\"Guest.cancelEvent()\">cadastrar-se em definitivo</a> para evitar isso.</p>";
		
		echo "<p><b class=\"danger\">Importante:</b> Copie o código abaixo para poder recuperar a sessão. Um novo código será gerado a cada vez que sair como sair como usuário temporário. Se você não copiar e/ou esquecer o código de recuperação, não será possível recuperar a sua conta. Não fornecemos o código por quaisquer outros meios a não ser através desta caixa de diálogo. Convidamos a você a <a href=\"signup.php\" onclick=\"Guest.cancelEvent()\">migrar para uma conta definitiva</a> para evitar isso.</p>";
		
		echo "<p id=\"retrievalCodeAjaxContainer\" style=\"user-select: all; -moz-user-select: all; -webkit-user-select: all; -ms-user-select: all\" onclick=\"Guest.cancelEvent()\"></p> <small style=\"display: inline-block\"><a onclick=\"Guest.copyToken()\">(Copiar na área de transferência)</a></small> <input style=\"opacity: 0; position: absolute; left: 0px\" id=\"tokenCopyText\" value=\"\">";
	}
	
	if ($_SESSION["userprivilege"] == 3){
		echo "<p>A autenticação por Facebook possui limitações técnicas e não é possível desuantenticar-se por completo a não ser que prefira desautenticar-se do Facebook também</p>";
	}

	if ($_SESSION["userprivilege"] == 4){
		echo "<p>Se você sair sem criar uma conta em defintivo, você não será capaz de recuperar o acesso ao que você fez até agora mais tarde. Recomendamos que você <a href=\"signup.php\" onclick=\"Guest.cancelEvent()\">cadastre-se em defintivo</a> para evitar isso.</p>";
	}

	if ($_SESSION["userprivilege"] == 2 || $_SESSION["userprivilege"] == 4){
		echo "<p>";
		echo "<a href=\"signup.php\" class=\"btn\" onclick=\"Guest.cancelEvent()\">Cadastrar-se em definitivo</a><br><br>";
		echo "<a href=\"auth.php?action=logout\" onclick=\"Guest.cancelEvent()\"><small>Sair mesmo assim</small></a>";
		echo "</p>";
	} else if ($_SESSION["userprivilege"] == 3){
		echo "<a href=\"#\" class=\"btn\" onclick=\"Guest.logoutFacebook(); Guest.cancelEvent()\">Sair</a><br><br>";
		echo "<p><input type=\"checkbox\" id=\"logoutFacebook\" onclick=\"Guest.cancelEvent()\"> <b>Sair</b> do Facebook também. Você será desconectado do Facebook ao marcar esta caixa.</p>";
	}


?>


</div>


</div>
</div>