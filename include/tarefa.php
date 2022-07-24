<?php
	include("databaseConnection.php");
	include("unidade.php");
	class Tarefa {
		private static $database = NULL;
		private $id;
		private $title;
		private $precisaoMinima;
		private $unidades;
		private $unidadesPorPagina;
		private $questoesPagina;
		private $currentUser;
		private $maxTasks;
		private $tipo;
				
		public function __construct($id, $iduser = NULL){
			if (!isset($iduser) || $iduser == NULL){
				$iduser = $_SESSION["useridnum"];
			}
			
			if (self::$database == NULL) self::$database = DatabaseConnection::getDBObject();			
			$this->id = $id;

			$query = "SELECT * FROM task WHERE id=" . $this->id . ";";

			$result = mysqli_query(self::$database, $query);
			$row = mysqli_fetch_array($result);
			$this->currentUser = new Usuario($iduser);
			$this->title = $row['title'];
			$this->questoesPagina = $row['questionspage'];
			$this->instructions = $row['instructions'];
			$this->tipo = $row['type'];
			
			$this->precisaoMinima = $row['minaccuracy']/100;
			
			$query3 = "SELECT COUNT(*) as totaltq FROM goldunit WHERE taskid=" . $this->id . ";";
			$result3 = mysqli_query(self::$database, $query3);
			$row3 = mysqli_fetch_array($result3);
			
			$this->maxTasks = min($row['taskubound'], $row3['totaltq']);
		}
		
		public function getMaximumAssignments(){
			return $this->maxTasks;
		}
		
		public function getTitulo(){
			return $this->title;
		}
			
		private function insertTestQuestion($deltaInt){
			$getGoldUnits = "SELECT * FROM goldunit WHERE taskid=" . $this->id . " ORDER BY RAND();";
			$filterGoldUnits = "SELECT * FROM taskhistory WHERE idtask=" . $this->id . " AND idcrowdsourcer=" . $this->currentUser->getId() . " AND gold=1;";
			$result2 = mysqli_query(self::$database, $getGoldUnits);

			$rowg = mysqli_fetch_array($result2);
			
			$result3 = mysqli_query(self::$database, $filterGoldUnits);

			while ($rowg2 = mysqli_fetch_array($result3)){
				if ($rowg2['idunit'] == $rowg['unitid']){
					$rowg = mysqli_fetch_array($result2);
				}
			}
			
			$goldUnit = new Unidade($rowg['unitid'], true);
			$_SESSION['tqId'] = $rowg['unitid'];
			$_SESSION['tqPos'] = $deltaInt;
			$goldUnit->render();
		}
		

		public function getPrecisaoMinima(){
			return $this->precisaoMinima;
		}
		
		public function getUnidades(){
			return $this->unidades;
		}
		
		public function getUnidadesPorPagina(){
			return $this->unidadesPorPagina;
		}
		
		public function optedOut(){
			$query = "SELECT * FROM taskoptout WHERE userid=" . $_SESSION['useridnum'] . " AND taskid=" . $this->id . ";";
			$result = mysqli_query(self::$database, $query);
				
			while ($row = mysqli_fetch_array($result)){
				return true;
			}
			return false;
		}

		
		public function canWork(){
			if ($this->currentUser->getTrustScore($this->id) < $this->precisaoMinima) return false;
			if ($this->currentUser->hasFlagOn($this->id) != 0) return false;
			if (isset($_SESSION['useridnum']) && $this->optedOut()) return false;
			
			return true;
		}
		
		public function getRemainingTasks(){
		
			return max($this->getMaximumAssignments() - $this->currentUser->getSubmissions($this->id) , 0);
		}
		
		
		public function render(){
			echo "<article class=\"job\">";
			echo "<p><h1>" . $this->title . "</h1></p>";
			
			if ($this->canWork() || (isset($_SESSION["isCorrect"]) && $_SESSION["isCorrect"] == 0)){

				if ($this->getRemainingTasks() == 0 && $_SESSION["isCorrect"] == 1) {
					echo "<div class=\"completenote\">";
					include("include/jobcomplete.php");
					echo "</div>";
				
				} else if (!isset($_SESSION["isCorrect"]) || $_SESSION["isCorrect"] == 1){
					$effectiveLimit = $this->questoesPagina-1;
					
					if ($this->tipo == 0) $effectiveLimit++;
					
					$getUnits = "SELECT * FROM taskunit WHERE taskid=" . $this->id . " ORDER BY RAND() LIMIT " . $effectiveLimit . ";";
					$result1 = mysqli_query(self::$database, $getUnits);
		
					$deltaInt = mt_rand(0, $this->questoesPagina-1);
		
					echo "<p class=\"centered\">";
					$i = 0;
					
					$unitIdArray = array();
					
					while ($rown = mysqli_fetch_array($result1)){
						$unitObj = new Unidade($rown['unitid'], false); 
			
						array_push($unitIdArray, $rown['unitid']);
			
						if ($this->tipo != 0){
							if ($i == $deltaInt) $this->insertTestQuestion($deltaInt);
						}
						
						$unitObj->render();
	
						if ($this->tipo != 0){
							if ($deltaInt == $this->questoesPagina-1 && $i == $this->questoesPagina-2) $this->insertTestQuestion($deltaInt);
						}
						
						$i++;
					}
					
					$_SESSION['unitidarray'] = implode(", ", $unitIdArray);
					
					echo "</p>";
				} else if ($_SESSION["isCorrect"] == 0) {
					$unitObj = new Unidade($_SESSION['tqId'], true); 
					$unitObj->render(true);
				}
			} else if ($this->optedOut()){

				echo "<div class=\"warning\">";
				include("include/joboptedout.php");
				echo "</div>";
				
			} else if ($this->currentUser->hasFlagOn($_GET["id"]) != 0){
				
				echo "<div class=\"expelnote flag\">";
				include("include/jobflagged.php");
				echo "</div>";
					
			} else if ($this->currentUser->getTrustScore($_GET["id"]) < $this->precisaoMinima) {
				echo "<div class=\"expelnote\">";
				include("include/jobfailure.php");
				echo "</div>";
			}
			
			echo "</article>";
			
			if (!$this->optedOut()){	
				$this->renderHUD();
			}
			
			if ($this->currentUser->getSubmissions($_GET["id"]) == 0 && !$this->optedOut()){
				echo "<div class=\"modal inst\" style=\"display: inline; opacity: 1\" onclick=\"Job.hideInstructions()\">";
			} else {
				echo "<div class=\"modal inst\" style=\"display: none; opacity: 0\" onclick=\"Job.hideInstructions()\">";
			}
			
			echo "<div>";
			echo "<h1>" . $this->title. "</h1><br>";
			echo "<h2>Instruções</h2> <a class=\"btn\" onclick=\"Job.hideInstructions()\">Clique aqui para fechar</a>";
			echo "<hr>";
	
			echo "<nav class=\"adminTabs\">";
			$abas = array("Instruções da tarefa", "Instruções gerais");
			for ($i = 0; $i < count($abas); $i++){
				$isActive = "";
				if ($i == 0) $isActive = "active";
				
				echo "<a class=\"" . $isActive . "\" name=\"abacabeca\" onclick=\"setTab('abacabeca', 'abacorpo'," . $i . "); Job.cancelEvent()\">";
				echo $abas[$i];
				echo "</a>";
				
			}
			echo "</nav>";

	
			
			echo "<div style=\"width: 100%; height: calc(100% - 80px); overflow-y: auto; display: block\" name=\"abacorpo\">";

			echo "<iframe src=\"instructions.php?id=" . $_GET["id"] . "\"></iframe>";
			
		
			echo "</div>";
			
			echo "<div style=\"width: 100%; height: calc(100% - 80px); overflow-y: auto; display: none\" name=\"abacorpo\">";
			if ($this->currentUser->getSubmissions($_GET["id"]) == 0){
				echo "<p><h2>Aviso de primeira submissão</h2></p>";
				echo "<p><b>Aviso:</b> Detectamos que esta é a sua primeira submissão. Você deve respoder a primeira questão de teste correntamente para continuar trabalhando na tarefa. Esta tela não irá aparecer novamente após enviar a sua primeira página ao menos que clique em <a class=\"btn\">Instruções</a> novamente.</P>";
			}
			
			echo "<h2>O seu poder de controle sobre os dados submetidos</h2>";
			
			echo "<p>Os dados submetidos serão armazenados em um banco de dados em um servidor remoto a fim de complementar o TCC denominado \"ACROSS: indexando dados não estruturados não textuais usando Crowdsourcing\" que está em desenvolvimento no Campus de São Bernardo do Campo Centro Universitário da FEI. Se por um acaso qualquer coisa na tarefa causar constrangimento ou não concordar com o uso de dados descritos na <a href=\"privacy.php\" target=\"_blank\" onclick=\"Job.cancelEvent()\">Política de Privacidade</a>, clique no botão <a class=\"btn\">Desistir</a> e marque a opção <b class=\"danger\">Sair em definitivo</b>";
			
			if ($this->currentUser->getSubmissions($_GET["id"]) == 0){
				echo " Esta opção só está disponível quando você enviar sua primeira página de contribuições";
			}
			
			echo ".</p>";
			echo "</div>";


			
			echo "</div>";
			echo "</div>";
			
			
			echo "<div class=\"modal giveup\" style=\"display: none; opacity: 0\" onclick=\"Job.hideGiveUp()\">";
			echo "<div class=\"small\">";
			echo "<h1>Você quer sair?</h1>";
			echo "<hr>";
			echo "<p>Você pode sair a qualquer momento por qualquer motivo. Seus dados enviados estarão salvos e poderá continuar a tarefa no futuro.</p>";
			echo "<p class=\"centered\">";
			echo "<a class=\"btn\" onclick=\"Job.hideGiveUp()\">Não</a><a class=\"btn\" onclick=\"location.href = 'panel.php';\" id=\"critical\">Sim</a>";
			echo "</p>";
			
			if ($this->currentUser->getSubmissions($_GET["id"]) > 0){
				echo "<p><input type=\"checkbox\" onclick=\"Job.cancelEvent(); Job.optOutWarning()\" id=\"criticalbox\"><b class=\"danger\">Sair em definitivo</b>. Exclui todos os seus dados coletados na tarefa de forma irreversível. Você não poderá fazer esta tarefa novamente.</p>";
			}
			
			echo "</div>";
			echo "</div>";

		}
		
		public function renderHUD(){
			echo "<div class=\"hud\">";
			echo "<table style=\"width: 100%\" cellspacing=\"0\" cellpadding=\"0\">";
			echo "<tr>";
			echo "<td style=\"width: 25px\"><img src=\"images/logo.png\" style=\"width: 24px; height: 24px\"></td>";
			
			echo "<td>";

			if (!$this->optedOut()){
				if ($this->tipo != 0 && $this->currentUser->getSubmissions($_GET["id"]) > 0){	
					if ($this->currentUser->getTrustScore($_GET["id"]) >= $this->precisaoMinima){
						echo "<span>";
					} else {
						echo "<span class=\"red\">";
					}
		
					echo "Credibilidade: " . floor(1000*$this->currentUser->getTrustScore($_GET["id"]))/10 . "%";
					echo "</span>";
				}
								
				echo "<span>Envios: " . $this->currentUser->getSubmissions($_GET["id"]) . "</span>";
			}
			
			echo "</td>";

			
			echo "<td style=\"text-align: right;\">";
			
			if ($this->canWork() && $this->getRemainingTasks() > 0){
				echo "<span>";
				echo "<a href=\"#\" class=\"btn\" onclick=\"Job.showInstructions()\">";
				echo "Instruções";
				echo "</a>";
			}

			echo "<a href=\"#\" class=\"btn\" onclick=\"Job.showGiveUp()\">";
			echo "Desistir";
			echo "</a>";

			
			if ($this->canWork() && $this->getRemainingTasks() > 0){
				echo "<a href=\"#\" class=\"btn\" onclick=\"Job.beforeSubmit();\">";
				echo "Enviar";
				echo "</a>";
				echo "</span>";
			}
			
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";

		}
		
		
	}
?>