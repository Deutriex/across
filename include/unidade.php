<?php
	include("databaseConnection.php");
	class Unidade {
		private static $idEnumeravel;
		private static $database = NULL;
		private $id;
		private $taskId;
		private $tipo;
		private $tipodado;		
		private $questaoTeste;
		private $resposta;
		private $razao;
		private $mediaSrc;
		private $dadoColetado;
		private $dadocrowdsourcer;
		private $dica;
		
		protected function compFunctionComum($enumObj){
		}
		
		public function getIdEnumeravel(){
			return self::$idEnumeravel;
		}
		
		public function __construct($id, $tq){
			if (self::$database == NULL){
				self::$database = DatabaseConnection::getDBObject();
			}


			//parent::__construct("" . $id);
			if (!self::$idEnumeravel){
				//self::$idEnumeravel = parent::alocaRetornaIndice();
			}
			
			$this->id = $id;
			$this->questaoTeste = $tq;
			$query = "";
			
			if (!$this->questaoTeste){
				$query = "SELECT * FROM taskunit WHERE unitid=". $id . ";";
			} else {
				$query = "SELECT * FROM goldunit WHERE unitid=". $id . ";";
			}
			
			$result = mysqli_query(self::$database, $query);
			$row = mysqli_fetch_array($result);
			
			
			$this->taskId = $row['taskid'];
		
			$query2 = "SELECT * FROM task WHERE id=" . $row['taskid'] . ";";
			$result2 = mysqli_query(self::$database, $query2);
			$row2 = mysqli_fetch_array($result2);
			$this->tipo = $row2['type'];
			$this->tipodado = $row2['datatype'];
			$this->dica = $row2['tip'];

			
			if ($this->tipo != 2){
				$this->mediasrc = $row['mediasrc'];
			} else {
				$query0 = "SELECT * FROM taskhistory WHERE idnum=" .  $row['mediasrc'] . ";";
				$result0 = mysqli_query(self::$database, $query0);
				$row0 = mysqli_fetch_array($result0);
				$this->dadoColetado = $row0['data'];
				$dadocrowdsourcer = $row0['idcrowdsourcer'];
				
				$queryU = "SELECT * FROM taskunit WHERE unitid=" . $row0['idunit'] . ";";
				$resultU = mysqli_query(self::$database, $queryU);
				$rowU = mysqli_fetch_array($resultU);
				$this->mediasrc = $rowU['mediasrc'];
			}
			
			if ($this->questaoTeste){
				$queryAw = "SELECT * FROM goldunitanswers WHERE idref=" . $id . ";";
				$resAw = mysqli_query(self::$database, $queryAw);
				$res = array();
			
				while ($rowAw = mysqli_fetch_array($resAw)){	
					array_push($res, $rowAw['answer']);
				}

				$this->resposta = implode(" ou ", $res);
				$this->razao = $row['reason'];
			}
			
		
		}
		
		public function getQuestaoTeste(){
			return $this->questaoTeste;
		}
		
		public function getResposta(){
			return $this->resposta;
		}
		
		public function render($tqMode = false){
			echo "<table class=\"unittable\">";
			echo "<tr>";
			if ($this->tipodado == 0){
				echo "<td class=\"unitimg\" style=\"background-image: url('media/image/" . $this->mediasrc . "')\"></td>";
			}
			
			if ($this->tipodado == 1){
				echo "<td>";
				echo "<audio controls><source src=\"media/sound/"  . $this->mediasrc . "\" type=\"audio/mpeg\"></audio>";
				echo "</td>";
			}
			
			if ($this->tipodado == 2){
				echo "<td>";
				echo "<video width=\"320\" height=\"240\" controls> <source src=\"media/video/" . $this->mediasrc . "\" type=\"video/mp4\"></video>";
				echo "</td>";
			}

			
			echo "<td valign=\"top\">";
			
			if (!$tqMode){
				if ($this->tipo != 2){
					echo "<h2>" . $this->dica . "</h2>&nbsp;";
					echo "<br>";
					echo "<textarea name=\"inputdata[]\" required=\"required\" ";
					
					$querySp = "SELECT * FROM taskspecialformat WHERE taskid=" . $this->taskId . ";";
					$resSp = mysqli_query(self::$database, $querySp);

					while ($rowSp = mysqli_fetch_array($resSp)){
						$querySpBlur = "SELECT * FROM tasksfunitblur WHERE idref=" . $this->taskId . ";";
						$resSpBlur = mysqli_query(self::$database, $querySpBlur);

						echo "onblur=\"";

						while ($rowSpBlur = mysqli_fetch_array($resSpBlur)){
							
							if ($rowSpBlur['action'] == "lowercase"){
								echo "this.value = this.value.toLowerCase(); ";
							}

							if ($rowSpBlur['action'] == "trim"){
								echo "this.value = this.value.trim(); ";
							}
							
						}
						
						echo "\"";

					
						if ($rowSp['unitformat'] != NULL){
							echo "pattern=\"" . $rowSp['unitformat'] . "\" ";
						}

					}
					
					
					
					echo ">";		
					echo "</textarea>";
				} else {
					echo "<h2>" . $this->dadoColetado . "</h2>";
					echo "<p>";
					echo "<input type=\"checkbox\" name=\"inputdata[]\" value=\"false\" onclick=\"Job.updateCheckValue()\">";
					echo "<b>Clique na caixa caso a contribução esteja de acordo com as instruções.</b>&nbsp;";
					echo "</p>";
				}
			} else {
				if ($this->tipo == 2){
					echo "<h2>" . $this->dadoColetado . "</h2>";
				}
				
				echo "<div style=\"width: 50%; display: inline-block\"><p><b>Sua resposta:</b></p>" . $_SESSION["crowdAnswer"] ."</div>";
				echo "<div style=\"width: 50%; display: inline-block\"><p><b>Resposta correta:</b></p>" . $this->resposta ."</div>";
				echo "<hr>";
				echo "<b>Justificativa:</b> " . $this->razao;
			}
			
			echo "</td></tr>";
			echo "</table>";
			
			
			if ($tqMode){
				echo "<div class=\"note unfortunate\">";
				echo "Algumas de suas respostas não coincidem com nosso modelo de resposta esperado. Por favor revise a sua contribuição acima antes de continuar trabalhando na tarefa.";
				echo "</div>";
			}
			
			
		}
	}
?>