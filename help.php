<?php
	try {
		if (session_status() == PHP_SESSION_NONE) {
	 		session_start();
		}
	} catch (Exception $e){
	}
?>


<html>
<head>
<title>ACROSS - A próxima geração de serviços de Crowdsourcing</title>

<meta charset="utf-8">
<?php include("include/style.php"); ?>

</head>
<body>

<?php
	include("include/header.php");
?>

<div class="headerOverlay">
<h1>Ajuda</h1><br>
<span>Perguntas frequentes sobre o ACROSS</span>
</div>

<article>
<p><h2>O que é o ACROSS?</h2></p>
<p>O ACROSS (Acrônimo de ACROSS CROwdSourcing Service) é uma plataforma onde autores da tarefa recolhem resultados com uso da inteligência humana. A plataforma se baseia no conceito de divisão e conquista, onde uma grande tarefa é dividida em tarefas menores que uma pessoa comum consiga realizar com relativa facilidade</p>

<p><h2>A inscrição é gratuita?</h2></p>
<p>Sim, a inscrição é gratuita para todos que quiserem auxiliar no processo de coleta de dados das tarefas atualmente disponíveis</p>

<p><h2>Que tipo de tarefas estão disponíveis atuais?</h2></p>
<p>Atualmente estão disponíveis duas tarefas de indexação de imagens, que são simples de realizar. Basta estar atento às instruções!</p>

<p><h2>Sou pago para contribuir?</h2></p>
<p>Não, por se tratar de um sistema construído para fins acadêmicos. Entretanto, o sistema será relançado como um produto comercial, dentro de um plano de negócios definido, onde será possível os contribuintes receberem dinheiro conforme a qualidade das contribuições</p>

<p><h2>Fui corrigido injustamente, o que devo fazer?</h2></p>
<p>Envie um email para anteu2009 (arroba) yahoo <ponto> com {ponto} br, entre em <a href="https://www.facebook.com/acrosscrowd">contato</a> com a nossa página no Facebook ou use o <a href="http://192.168.1.3/across/contact.php">formulário de contato</a> com os detalhes pertinentes do erro e nós revisaremos. As respostas são usualmente revistas dentro de 24 horas.</p>

<p><h2>Encontrei um erro no site, o que devo fazer?</h2></p>
<p>Siga os passos do tópico anterior</p>

<p><h2>Fui expulso ou recebi uma bandeira, o que devo fazer?</h2></p>
<p>Se errar questões acima de um limite (não exposto ao contribuinte por questões de controle de qualidade), você não poderá fazer tarefas. Se as questões que levaram você a ser expulso tiverem problemas, siga o passo do item de ser corrigido injustamente.</p>

<p>O mesmo princípio vale para as bandeiras, mas neste caso é aplicado quando se percebe inconsistência nas submissões mesmo se sua credibilidade estimada estiver alta. Neste caso siga o passo do item de ser corrigido injustamente, com argumento do porque você não merece a bandeira.</p>

</article>


<footer>
<?php 
	include("include/footer.php");
?></footer>
</body>
</html>