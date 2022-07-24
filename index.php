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
<title>ACROSS - A inteligência de muitos para muitos</title>

<script>
	var ACROSS = {
		onScroll: function(){
			getFactor = document.body.scrollTop/document.documentElement.clientHeight
			getFactorRound = Math.round(getFactor)

			if (getFactor >= 0.205){
				document.querySelector('NAV').style.position = "fixed"
				document.querySelector('NAV').style.top = "90px"
			} else {
				document.querySelector('NAV').style.position = "absolute"
				document.querySelector('NAV').style.top = "20.5%"
			}


			if (document.body.scrollTop >= document.documentElement.clientHeight){
				document.querySelector('.top').style.opacity = 1


				document.querySelector('.progress').style.position = "fixed"
				document.querySelector('.progress').style.top = "90px"
				document.querySelector('.progress').style.width = 100*(document.body.scrollTop/document.documentElement.clientHeight)/4 + "%"
				document.querySelector('.progress').style.display = "inline"

			} else {
				document.querySelector('.top').style.opacity = 0

				document.querySelector('.progress').style.position = "absolute"
				document.querySelector('.progress').style.top = "20.5%"
				document.querySelector('.progress').style.display = "none"
			}


			if (Math.abs((getFactorRound-getFactor)*document.documentElement.clientHeight) <= 90){
				document.body.scrollTop = getFactorRound*document.documentElement.clientHeight
			}

			document.body.scrollLeft = 0

			for (i = 1; i <= 4; i++){
				if (document.body.scrollTop >= document.documentElement.clientHeight*i){
					document.querySelector('.stepslides > DIV:nth-child(' + i + ') > DIV').style.left = "5%"
					document.querySelector('.stepslides > DIV:nth-child(' + i + ') > DIV').style.opacity = 1
					document.querySelector('.stepslides > DIV:nth-child(' + i + ') > SPAN').style.right = "3%"
					document.querySelector('.stepslides > DIV:nth-child(' + i + ') > SPAN').style.opacity = 1

				} else {
					document.querySelector('.stepslides > DIV:nth-child(' + i + ') > DIV').style.left = "-50%"
					document.querySelector('.stepslides > DIV:nth-child(' + i + ') > DIV').style.opacity = 0
					document.querySelector('.stepslides > DIV:nth-child(' + i + ') > SPAN').style.right = "-50%"
					document.querySelector('.stepslides > DIV:nth-child(' + i + ') > SPAN').style.opacity = 0
				}
			}
			
		}
	}
</script>

<meta charset="utf-8">
<?php include("include/style.php"); ?>

<link rel="stylesheet" type="text/css" media="all" href="style/index.css">

</head>
<body>
<a class="btn top" style="opacity: 0" onclick="document.body.scrollTop = 0">▲ Topo</a>

<?php
	include("include/header.php");
?>

<div class="headerOverlay">
<h1>ACROSS</h1><br>
<span>A inteligência de muitos para muitos</span>
</div>

<div class="progress" style="width: 0%; position: absolute; top: 20.5%; height: 35px !important; z-index: 1002 !important; display: none"></div>

<nav style="position: absolute;	top: 20.5%;">
<a onclick="document.body.scrollTop = document.documentElement.clientHeight">1</a>
<a onclick="document.body.scrollTop = document.documentElement.clientHeight*2">2</a>
<a onclick="document.body.scrollTop = document.documentElement.clientHeight*3">3</a>
<a onclick="document.body.scrollTop = document.documentElement.clientHeight*4">4</a>
</nav>



<article>

<div style="width: 100%; height: 100%">
<h1 style="position: absolute; left: 3%; top: 95px; text-align: center; width: 94%">Melhore a inteligência do seu mecanismo de busca com a nossa plataforma em quatro passos:</h1>

<div style="background: transparent url(images/icon_m10-green.png) no-repeat center center; width: 15%; height: 15%; background-size: contain; position: absolute; left: 3%; top: 40%">
</div>

<div style="background: transparent url(media/image/54.jpg) no-repeat center center; width: 20%; height: 20%; left: 26%; top: calc(150px + 28%); position: absolute; background-size: contain">
</div>

<div style="background: transparent url(media/image/1.jpg) no-repeat center center; width: 20%; height: 20%; left: 20%; top: calc(150px + 17%); position: absolute; background-size: contain">
</div>

<div style="background: transparent url(media/image/5.jpg) no-repeat center center; width: 20%; height: 20%; left: 28%; top: calc(150px + 10%); position: absolute; background-size: contain">
</div>

<svg width=325 height=300 style="position: absolute; left: 52.5%; top: 32.5%">
  <polygon points="0,100 200,100 200,0 325,150 200,300 200,200 0,200" style="fill:lime" />
</svg>


<div style="background: transparent url(images/users.png) no-repeat center center; width: 15%; height: 15%; background-size: contain; position: absolute; left: 53%; top: 40%">
</div>

<div style="background: transparent url(media/image/54.jpg) no-repeat center center; width: 20%; height: 20%; left: 76%; top: calc(150px + 28%); position: absolute; background-size: contain">
</div>

<div style="background: transparent url(media/image/1.jpg) no-repeat center center; width: 20%; height: 20%; left: 70%; top: calc(150px + 17%); position: absolute; background-size: contain">
</div>

<div style="background: transparent url(media/image/5.jpg) no-repeat center center; width: 20%; height: 20%; left: 78%; top: calc(150px + 10%); position: absolute; background-size: contain">
</div>

<div style="position: absolute; top: calc(48% + 80px); left: calc(96% - 150px); color: white; font-size: 24pt; text-shadow: 1px 1px #000, -1px -1px #000, 1px -1px #000, -1px 1px #000;">Torre</div>
<div style="position: absolute; top: calc(37% + 80px); left: calc(90% - 150px); color: white; font-size: 24pt; text-shadow: 1px 1px #000, -1px -1px #000, 1px -1px #000, -1px 1px #000;">Girassol</div>
<div style="position: absolute; top: calc(30% + 80px); left: calc(98% - 150px); color: white; font-size: 24pt; text-shadow: 1px 1px #000, -1px -1px #000, 1px -1px #000, -1px 1px #000;">Coelho</div>


<div class="stepbox">
<div onclick="document.body.scrollTop = document.documentElement.clientHeight">1 Cadastre-se neste site</div>
<div onclick="document.body.scrollTop = document.documentElement.clientHeight*2">2 Crie um trabalho com o que vai ser analisado</div>
<div onclick="document.body.scrollTop = document.documentElement.clientHeight*3">3 A multidão fará o trabalho para você</div>
<div onclick="document.body.scrollTop = document.documentElement.clientHeight*4">4 O resultado é obtido</div>
</div>

<div class="uxinfo">Clique nos quadros numerados para mais informações</div>

<div class="stepslides">
<div>
<h1>1 Cadastre-se neste site</h1>

<div style="background-image: url(advertisement/signup.jpg); left: -50%; top: 0px; opacity: 0"></div>
<span style="right: -50%; top: 0px; opacity: 0">
<p>Cadastrar-se no ACROSS é simples como 1, 2, 3: Basta informar o seu ID de usuário e sua senha.</p>
<p>O processo leva menos de um minuto e proporciona acesso imediato ao sistema.</p>
<p><a class="btn">Cadastre-se hoje mesmo!</a></p>

</span>

</div>

<div>
<h1>2 Crie um trabalho com o que vai ser analisado</h1>

<div style="background-image: url(advertisement/create.jpg); left: -50%; top: 0px; opacity: 0"></div>
<span style="right: -50%; top: 0px; opacity: 0">
<p>
<div style="background-image: url(advertisement/Icon_15-128.png)"></div>
<div style="background-image: url(advertisement/Music_13-512.png)"></div>
<div style="background-image: url(advertisement/photo-video-start-icon.png)"></div>
</p>
<p>Imagem, som ou vídeo. O ACROSS é capaz de extrair conhecimento onde outros métodos podem falhar.</p>
<p>Ao se cadastrar, você terá acesso imediato a um painel intuitivo que permitirá que você crie uma tarefa de acordo com suas necessidades.</p>
<p>Escolha o número de tarefas, o número de unidades, questões de teste e limiar mínimo. Tudo sob medida.</p>
<p></p>
</span>

</div>

<div>
<h1>3 A multidão fará o trabalho para você</h1>

<div style="background-image: url(advertisement/crowd.jpg); left: -50%; top: 0px; opacity: 0"></div>
<span style="right: -50%; top: 0px; opacity: 0">
<p>Depois de ter publicado a tarefa, uma multidão de pessoas dedicada irá fazer o trabalho.</p>
<p>O sistema gerenciará a qualidade de forma automática, conforme parâmetros fornecidos por você. Ou seja, se alguém tentar submeter respostas inválidas, não se preocupe. As contribuições não irão afetar o resultado final.</p>
</span>

</div>

<div>
<h1>4 O resultado é obtido</h1>

<div style="background-image: url(advertisement/result.jpg); left: -50%; top: 0px; opacity: 0"></div>
<span style="right: -50%; top: 0px; opacity: 0">
<p>Após certo tempo, você poderá ver o andamento parcial das tarefas através da aba Gerenciar Tarefa.</p>
<p>Depois de finalizado, você poderá exportar o resultado para um arquivo XML a ser interpretado pelo seu mecanismo de busca</p>
</span>

</div>

</div>


<?php
	include("include/labmodebox.php");
?>


</article>


<footer>
<?php 
	include("include/footer.php");
?></footer>

<script>
	window.addEventListener("scroll", ACROSS.onScroll)
	for (i = 1; i <= 4; i++){
		document.querySelector('.stepslides > DIV:nth-child(' + i + ') > DIV').style.top = "180px"
		document.querySelector('.stepslides > DIV:nth-child(' + i + ') > SPAN').style.top = "180px"
	}
</script>


</body>
</html>