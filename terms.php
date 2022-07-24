<?php
	session_start();
?>


<html>
<head>
<title>ACROSS - Termos de serviço</title>
<?php include("include/style.php"); ?>
<meta charset="utf-8">

</head>
<body>

<?php
	if (!isset($_GET['mode']) || $_GET['mode'] != "embedded"){
		include("include/header.php");

		echo "<div class=\"headerOverlay\">";
		echo "<h1>Termos de serviço</h1>";
		include("include/lastmodified.php");
		echo "</div>";

		echo "<article class=\"just\">";
	} else {
		echo "<article class=\"justemb\">";
	}

?>

<div class="warning">
<p><b>Importante:</b> Você deve aceitar os termos de serivço intergralmente antes de começar a utilizar o serviço como Administrador da Tarefa ou como Contribuinte (<i>Crowdsourcer</i>). O uso do serviço implicará na aceitação do mesmo. Se você não concorda com uma mais cláusulas aqui expostas, não utilize o serviço ou exclua sua conta através da opção painel do usuário.</p>
</div>

<h2>Termos utilizados</h2>
<ul>
<li>"O Serviço" se refere-se ao sistema ACROSS, disponível no endereço across-the-crowd.com</li>
<li>"Cadastro definitivo" (Padrão e Facebook) é todo aquele que pode ser acessado por vários computadores.</li>
<li>"Cadastro temporário" (Temporário e Lab) é todo aquele que pode ser acessado apenas em um computador e é sensível a presença de arquivos temporários.</li>
<li>Para mais informações sobre os privilégios e tipos de usuários disponíveis para você, <a href="usertype.php" target="_blank">clique aqui</a>.</li>
<li>"Administradores da Tarefa" são pessoas responsáveis por criar tarefas e gerir a qualidade dos dados coletados nas mesmas.</li>
<li>"Contribuintes" são os voluntários que enviará os dados referentes às contribuições das tarefas criadas pelos Administradores da Tarefa.</li>
<li>"TCC" é o Trabalho de Conclusão de Curso denominado "ACROSS: indexando dados não estruturados não textuais com Crowdsourcing" atualmente em desenvolvimento no Campus de São Bernardo do Campo do Centro Universitário da FEI.</li>
</ul>

<h2>Disposições gerais</h2>

<p>O Serviço é oferecido para todos que possuirem acesso à Internet, tiver uma idade mínima de 18 (dezoito) anos e ser capaz de ler e escrever textos em Língua Portuguesa. Todos os cadastros (definitivos e temporários) realizados no Serviço lhe permite utilizar o sistema como Contribuinte.</p>

<p>Nenhum dos tipos de usuários pode criar contas com identidade falsa ou com intuito de arruinar a honra e reputação de nenhuma pessoa, muito menos para fomentar atos ilícitos perante o Código Penal Brasileiro ou de distribuir <i>malwares</i> de qualquer natureza.</p>

<p>Se uma conta for suspensa por quaisquer motivos, a criação de outra a fim de evitar sanções é terminalmente vetada. Para garantir a integridade do sistema, os representantes deste sistema verificam as contas regularmente a fim de assegurar que os Termos de Uso estão sendo cumpridos sem exceção. </p>

<p>Este termo de serviço está divido em duas partes, uma destinada para os Contribuintes e outra destinada aos Administradores da Tarefa.</p>

<p>Os termos de serviço pode ser alterado a qualquer instante. Qualquer alteração significativa nos Termos de Serviços e/ou na Poliítica de Privacidade que venha afetar os usuários existentes será comunicada no Serviço em forma de uma faixa que pode ser fechada. Entretantos alterações menores podem não ser comunicados, você é encorajado a ler os Termos de Serviços e a Poliítica de Privacidade com regularidade.</p>

<h2>Para os contribuintes</h2>

<p>Os contribuintes podem se cadastrar de quatro formas, categorizadas de duas formas: De forma temporária (Temporário e Lab) e de forma definitiva (Padrão e Facebook). Os detalhes dos privilégios que cada tipo de usuário disponível para você podem ser conferidos <a href="usertype.php" target="_blank">aqui</a>. A formas definitivas estão disponíveis para qualquer pessoa que entrará no Serviço. Entretanto a autenticação via Facebook não está disponível na rede cabeada da FEI, pois a rede social é bloqueada neste ambiente. Este método de autenticação pode não funcionar caso tente acessar o serviço em um lugar onde o Facebook esteja bloqueado. Existem dois tipos de cadastros temporários disponíveis. A primeira (Temporário) está disponível para todos, exceto nos laboratórios da FEI reservados para o uso do Serviço. A segunda (Lab) está apenas para alunos do Centro Universitário da FEI em laboratórios separados para o uso do Serviço a fim de alçancar público suficiente para que o TCC seja concluído com sucesso e para cumprir com os aspectos éticos vigentes.</p>

<p>Caso opite por cadastrar como Temporário, você terá todos os privilégios de um usuário que cadastrou definitivamente através das duas formas citadas anteriormentes. Entretanto, ao sair do sistema com um cadastro temporário, o Serviço irá encorajar você a cadastrar de forma definitiva. Se você sair do sistema sem cadastrar defitivamente, você não será capaz de recuperar o cadastro temporário no futuro caso não se lembre do Código de Recuperação e/ou limpe os arquivos temporários do navegador por questões éticas e de segurança, tendo que criar um novo cadastro temporário ou um cadastro defitivo (Padrão e Facebook) para voltar a utilizar o Serviço. Não são aceitos solicitações de recuperação de Código de Recuperação, por motivos de segurança.</p>

<p>Um cadastro definitivo não pode ser revertido em um cadastro temporário. Um computador não pode possuir dois ou mais contas temporárias. Neste caso, os usuários envolvidos devem necessariamente possuir contas definitivas no sistema por motivos de segurança.</p>

<p>Um usuário cadastrado como Lab possui privilégios semelhates a um usuário Temporário, porém não poderá recuperar a sua conta após sair do sistema. Entrentanto, o usuário Lab pode mudar sua conta para usuário Padrão. Note que a opção Facebook não está disponível pelos motivos explicados anteriormente.</p>

<p>Uma pessoa só pode ter um cadastro defitivo. Se por um acaso o cadastro defitivo for banido por quaisquer razões relevantes ou optar por excluir sua conta por não concordar com os uso dos dados conforme destrinos na <a href="privacy.php">Política de Privacidade</a> por considerar que as tarefas em gerais são contrangedoras de qualquer forma, o Contribuinte não poderá voltar a criar nenhum cadastro de nenhum das duas categorias em qualquer computador a fim de evitar abusos e manter a integridade do Serviço.</p>

<p>Como contribuinte, você concorda que o uso de quaisquer técnicas de Inteligência Artifical, bem como o uso de macros de automação de mouse e teclado para completar tarefas no Serviço são terminalmente vetados. Se for detectado o uso destas técnicas proibidas, o infrator terá a sua conta suspensa sem direito de recuperação.</p>

<p>Você também concorda que o seu trabalho pode ser rejeitado pelos Administradores da Tarefa caso suas contribuições não atinjam o nível de expectativa dos mesmos. Caso considere uma ação de um Administrador da Tarefa injusto, sinta-se livre para entrar em contato conosco com as provas cabíveis. Se jugarmos a sua reclamação procedente, podemos desfazer as ações do Administrador da Tarefa para você. Rejeições reincidentes podem resultar na suspensão da sua conta.</p>

<p>Você também está ciente que boa parte das tarefas possuem um mecanismo de qualidade o qual é usado para medir a precisão. Caso sua precisão caia para abaixo do limiar mínimo, você não poderá continuar na tarefa a não ser que as questões de testes sejam revisas pelo Administrador da Tarefa ou pela nossa equipe e julgamos que o gabarito estava incorreto. Como consequência você deve responder necessariamente a primeira questão de qualquer tarefa . Rejeições automáticas reincidentes também podem resultar na suspensão de sua conta.</p>

<p>Como contribuinte, você concorda que você não deve inserir informações pessoais a não quer que a tarefa requira os dados, bem como compromete enviar qualquer programa malicioso ou dados contendo pornografia infantil, tráfico de drogas, vendas de armas, ameaças de morte ou o envio de mensagens não solicitadas (SPAM) para nenhuma pessoa em uma tarefa.</p>

<p>Como contribuinte, Você compromte a não criar capturas de telas baseadas na interface do site, muito menos compartilhar as respostas das questões de testes na Internet. Se você o fizer e nós percebemos, sua conta poderá ser suspensa de forma permanente.</p>

<h2>Para os Administradores da Tarefa</h2>

<p>Como administrador, você se compromente a criar uma tarefa que não invova pornografia infantil, tráfico de drogas, vendas de armas, ameaças de morte, tarefas que entram em conflito com os Termos de Serviço de serviços de terceiros ou crie uma tarefa que incentive o envio de mensagens não solicitadas (SPAM) para nenhum contibuiente em uma tarefa. Caso não obedeça a esta cláusula, reservamos o direito de suspender sua conta, impossibilitando o uso do Serviço para publicação de tarefas futuras.</p>

<p>Você não deve criar uma tarefa impossível de propósito, nem agir de má-fe, com intuito de prejudicar os contribuintes da sua tarefa.</p>
</article>

<?php
	if (!isset($_GET['mode']) || $_GET['mode'] != "embedded"){
		include("include/footer.php");
	}
?>


</body>
</html>