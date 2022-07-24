<?php
	session_start();
?>

<html>
<head>
<title>ACROSS - Política de privacidade</title>
<?php include("include/style.php"); ?>
<meta charset="utf-8">
</head>
<body>
<?php
	if (!isset($_GET['mode']) || $_GET['mode'] != "embedded"){
		include("include/header.php");

		echo "<div class=\"headerOverlay\">";
		echo "<h1>Política de privacidade</h1>";
		include("include/lastmodified.php");
		echo "</div>";

		echo "<article class=\"just\">";
	} else {
		echo "<article class=\"justemb\">";
	}
?>


<p>O serviço de Crowdsourcing ACROSS ("O Serviço") usa cookies a fim de assegurar uma melhor experiência de usuário.</p>

<p>Os dados pessoais solicitados na hora de cadastro são utilizados apenas para fins de controle de acesso ao Serviço. Eles jamais serão divulgados ou vendidos para terceiros. A autenticação via Facebook usa o seu nome de perfil e sua imagem de perfil para fins de autenticação e está sob os mesmos procedimentos ditos anteriormente. Se não se sente confortável com isso, não se autentique via Facebook.</p>

<p>A senha é gravada no banco de dados em um formato irreversível. Você pode mudá-la a qualquer momento no painel de usuário. Caso não lembre da sua senha, envie um email para anteu2009 [arroba] yahoo (ponto) com &lt;ponto&gt; br com assunto <i>ACROSS - Recuperação de senha</i> e lhe enviaremos um código para dar acesso à sua conta novamente. Você é encorajado a mudar a sua senha após isso.</p>

<p>Os dados solicitados em qualquer tarefa tem como finalidade melhorar a eficiência dos potenciais mecanismos de buscas futuros. Estes dados são armazeados no servidor onde este site está armazenado e não serão utilizados para outros fins a não ser como servir de fonte para o Trabalho de Conclusão de Curso (TCC) denominado "ACROSS: indexando dados não estruturados não textuais com Crowdsourcing", atualmente em desenvolvimento no Campus de São Bernardo do Campo do Centro Universitário da FEI.</p>

<p>Você pode optar por desistir de qualquer tarefa durante a sua participação ou mesmo desistir de utilizar o Serviço de forma defintiva. Todos os dados relevantes serão eliminados de forma irreversível e não será utilizado por nenhum motivo posteriormente. Entretanto não eliminaremos os dados pessoais relevantes ao controle de acesso a fim de controlar o acesso ao Serviço no futuro.</p>
</div>
</article>


<?php
	if (!isset($_GET['mode']) || $_GET['mode'] != "embedded"){
		include("include/footer.php");
	}
?>

</body>
</html>
