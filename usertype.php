<html>
<head>


<meta charset="utf-8">
<?php include("include/style.php"); ?>
<link rel="stylesheet" media="all" type="text/css" href="style/userprivileges.css">

<title>ACROSS - Tipos de usuário</title>
</head>
<body>

<?php
	if (!isset($_GET['mode']) || $_GET['mode'] != "embedded"){
		include("include/header.php");
	}
?>

<article>
<div style="display: inline-block; vertical-align: top; width: 700px">
<h1>Lista de privilégios por tipo de usuário</h1>
<hr>

<?php
	$tempTypes = 1;
	$permTypes = 2;
	
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$isFEICable = ($hostname == "fei.static.gvt.net.br" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1");
	$isLab = ($isFEICable && isset($_SESSION['labMode']) && $_SESSION['labMode'] == 1);
	
	if ($isFEICable){
		$permTypes--;
	}
	
		
	echo "<table class=\"userprivileges\" cellspacing=0 cellpadding=0>";
	echo "<tr><th>Categoria</th><th colspan=\"" . $permTypes . "\">Definitivo</th><th colspan=\"" . $tempTypes . "\">Temporário</th></tr>";
	
	echo "<tr><th>Privilégios</th>";
	echo "<th class=\"default\"><a>Padrão</a></th>";
	
	if (!$isFEICable){
		echo "<th class=\"face\"><a>Facebook</a></th>";
	}

	if (!$isLab){
		echo "<th class=\"guest\"><a>Temporário</a></th>";
	} else {
		echo "<th class=\"lab\"><a>Lab</a></th>";
	}
	
	echo "</tr>";
	echo "<tr><th>Autenticação</th>";
	echo "<td>Login e senha</td>";

	if (!$isFEICable){
		echo "<td>Facebook</td>";
	}
	
	if (!$isLab){
		echo "<td>Cookie e Código de Recuperação</td>";
	} else {
		echo "<td>IP, Código de Laborátório e Cookie</td>";
	}
	
	echo "</tr>";
	echo "<tr><th>Acessibilidade</th>";
	echo "<td>Qualquer computador</td>";
	
	if (!$isFEICable){
		echo "<td>Qualquer computador <small>(exceto os da rede cabeada da FEI)</small></td>";
	}
	
	if (!$isLab){
		echo "<td>Seu computador</td>";
	} else {
		echo "<td>Computadores do laborário da CGI reservado na FEI</td>";
	}
	
	echo "</tr>";
	echo "<tr><th title=\"É a capacidade de conseguir recuperar o usuário após o log out\">Recuperabilidade</th>";
	echo "<td><img src=\"images/check.png\"></td>";

	if (!$isFEICable){
		echo "<td><img src=\"images/check.png\"></td>";
	}
	
	if (!$isLab){
		echo "<td>Mediante Código de Recuperação</td>";
	} else {
		echo "<td><img src=\"images/cross.png\"></td>";
	}
	
	echo "</tr>";
	echo "<tr><th>Mudar para</th>";
	
	
	if (!$isFEICable){
		echo "<td class=\"face\"><a>Facebook</a></td>";
	} else {
		echo "<td><img src=\"images/cross.png\"></td>";
	}

	if (!$isFEICable){
		echo "<td class=\"default\"><a>Padrão</a></td>";
	}
	
	if (!$isLab){
		echo "<td><b class=\"default\"><a>Padrão</a></b>";
		if (!$isFEICable){
			echo " ou <b class=\"face\"><a>Facebook</a></b> <small>(não disponível na FEI)</small>";
		}
		echo "</td>";
	} else {
		echo "<td class=\"default\"><a>Padrão</a></td>";
	}
	
	echo "</tr>";
	echo "<tr><th>Associação</th>";
	
	if (!$isFEICable){
		echo "<td class=\"face\"><a>Facebook</a></td>";
	} else {
		echo "<td><img src=\"images/cross.png\"></td>";
	}
	

	if (!$isFEICable){
		echo "<td class=\"default\"><a>Padrão</a></td>";
	}


	if (!$isLab){
		echo "<td><img src=\"images/cross.png\"></td>";
	} else {
		echo "<td><img src=\"images/cross.png\"></td></tr>";
	}
	
	echo "</table>";
?>


</div>

<div style="display: inline-block; vertical-align: top"><p><h1>Ajuda</h1></p>
<p><b>Categoria: </b> É o grupo de usuários, onde a acessibilidade é um fator determinante.</p>
<p><b>Acessibilidade: </b> Em que locais um tipo de usuário está disponível.</p>
<p><b>Recuperabilidade: </b> É a capacidade de autenticar-se novamente depois de sair do sistema.</p>
<p><b>Mudar para: </b> Este tipo de operação permite mudar para outro método de autenticação, removendo o anterior.</p>
<p><b>Associação: </b> Este tipo de operação permite adicionar outro método de autenticação, mantendo o anterior.</p>


</div>
</article>

<?php 
	if (!isset($_GET['mode']) || $_GET['mode'] != "embedded"){
		include("include/footer.php");
	}
?>

</body>
</html>