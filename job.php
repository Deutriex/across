<?php
	if (!isset($_GET["id"])){
		header('Location: panel.php');
	}

	include("include/databaseConnection.php");
	include("include/reqauth.php");
	include("include/usuario.php");
	include("include/tarefa.php");
	
?>


<html>
<head>

<title>ACROSS - Trabalhando na tarefa</title>
<?php
	include('include/style.php');
	include('include/jobstyle.php');
 ?>


<meta charset="utf-8">

<script src="js/tab.js"></script>

<script>
	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}
	
	var Job = {
		showInstructions: function(){
			document.querySelector('.modal:not(.guest):not(.giveup)').style.display = "inline";
			document.querySelector('.modal:not(.guest):not(.giveup)').style.opacity = 1;
		},
		
		hideInstructions: function(){
			document.querySelector('.modal:not(.guest):not(.giveup)').style.display = "none";
			document.querySelector('.modal:not(.guest):not(.giveup)').style.opacity = 0;		
		},
		
		showGiveUp: function(){
			if (document.querySelector('#criticalbox')){
				document.querySelector('#criticalbox').checked = false
			}
			document.querySelector('#critical').className = "btn"
			document.querySelector('#critical').href = "panel.php"
			document.querySelector('.modal.giveup').style.display = "inline";
			document.querySelector('.modal.giveup').style.opacity = 1;	
		},
		
		hideGiveUp: function(){
			document.querySelector('.modal.giveup').style.display = "none";
			document.querySelector('.modal.giveup').style.opacity = 0;	
		},
		
		updateCheckValue: function(){
			event.target.value = event.target.checked
		},
		
		cancelEvent: function(){
			if (typeof event.stopPropagation != "undefined") {
			  event.stopPropagation();
			}
			if (typeof event.cancelBubble  != "undefined") {
			  event.cancelBubble = true;
			}
		},
		
		optOutWarning: function(){
			if (event.target.checked){
				document.querySelector('#critical').className = "btn danger"
				document.querySelector('#critical').href = "taskoptout.php?taskid=" + getUrlVars()["id"]

				delta = confirm("Ao clicar em 'Sim' com esta opção ativa, todos os seus dados serão excluídos e você não poderá voltar a participar da tarefa no futuro. Não haverá quaisquer caixas de diálogo de confirmação a não ser essa. Caso clicou acidentalmente nesta opção clique em Cancelar.")
				if (!delta){
					event.target.checked = false
					document.querySelector('#critical').className = "btn"
					document.querySelector('#critical').href = "panel.php"
				}
			} else {
				document.querySelector('#critical').className = "btn"
				document.querySelector('#critical').href = "panel.php"
			}
		},
		
		beforeSubmit: function(){	
			selectIt = document.querySelectorAll('TEXTAREA')
			
			for (i = 0; i < selectIt.length; i++){
				if (selectIt[i].value == ""){
					alert('Não é possível enviar sua contribuição\n\n\Um ou mais campos estão vazios.')
					Job.cancelEvent()
					return false
				}
			}
			
		
			document.forms.jobform.submit()
		}
	};
</script>


</head>
<body>

<form action="jobvalidate.php?id=<?php echo $_GET['id']; ?>" method="POST" name="jobform">
<?php
		$database = DatabaseConnection::getDBObject();

		$currentTask = new Tarefa($_GET["id"]);
		$currentTask->render();
	
?>

</form>

</body>
</html>