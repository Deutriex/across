<?php
	
	if (!isset($_SESSION)){
		session_start();
	}
	
	include("include/databaseConnection.php");
	include("include/tarefa.php");
	include("include/usuario.php");
	$database = DatabaseConnection::getDBObject();
	
	function cmp($a, $b){
		$delta = strcmp($a[0], $b[0]);
		if ($delta == 0) return 0;
		if ($a[1] < $b[1]) return 1;
		else if ($a[1] > $b[1]) return -1;
		else if ($a[1] == $b[1]){
			return $delta;
		}

	}
		
	$query = "";
	
	if ($_GET['action'] == "setFlag"){
		$getData = explode("|", $_GET['data']);
		$query = "INSERT INTO flaghistory VALUES (" . $getData[0] . ", " . $getData[1] . ");";
		echo "Sim";
		mysqli_query($database, $query);
	}
	
	if ($_GET['action'] == "clearFlag"){
		$getData = explode("|", $_GET['data']);
		$query = "DELETE FROM flaghistory WHERE idcrowdsourcer=" . $getData[0] . " AND idtask=" . $getData[1] . ";";
		echo "Não";
		mysqli_query($database, $query);
	}
	
	if ($_GET['action'] == "loadTaskCrowd"){
		include("include/ajaxLoadTaskCrowd.php");
	}
	
	if ($_GET['action'] == "loadUserContributions"){
		$getData = explode("|", $_GET['data']);
		$queryT = new Tarefa($getData[1], $getData[0]);
		$queryU = new Usuario($getData[0]);
		
		echo "<p>Contribuições de <b>" . $queryU->getUsername() . "</b> na tarefa <b>" . $queryT->getTitulo() . "</b></p>";
		
		$query = "SELECT * FROM taskhistory WHERE idcrowdsourcer=" . $getData[0] . " AND idtask=" . $getData[1] . " AND gold=0 ORDER BY idunit;";
		$result = mysqli_query($database, $query);
		
		echo "<table class=\"joblist\" cellpadding=0 cellspacing=0>";
		echo "<tr><th>ID</th><th>Mídia</th><th>Resposta</th></tr>";
		while ($row = mysqli_fetch_array($result)){
			$query2 = "SELECT * FROM taskunit WHERE unitid=" . $row['idunit'] . ";";
			$result2 = mysqli_query($database, $query2);
			$row2 = mysqli_fetch_array($result2);
			echo "<tr><td>" . $row['idunit'] . "</td><td style=\"width: 120px; height: 90px; background-image: url('media/image/" . $row2['mediasrc'] . "'); background-size: contain; background-repeat: no-repeat; background-position: center center\">&nbsp;</td><td>" . $row['data'] . "</td></tr>";
		}
		echo "</table>";
	}
	
	if ($_GET['action'] == "loadUserGoldContributions"){
		$getData = explode("|", $_GET['data']);
		$queryT = new Tarefa($getData[1]);
		$queryU = new Usuario($getData[0]);
		
		echo "<p>Resposta às questões de teste de <b>" . $queryU->getUsername() . "</b> na tarefa <b>" . $queryT->getTitulo() . "</b></p>";


		$query = "SELECT * FROM taskhistory WHERE idcrowdsourcer=" . $getData[0] . " AND idtask=" . $getData[1] . " AND gold=1 ORDER BY idunit;";
		$result = mysqli_query($database, $query);
		
		echo "<table class=\"joblist\" cellpadding=0 cellspacing=0>";
		echo "<tr><th>ID</th><th>Mídia</th><th>Resposta</th><th>Gabarito</th><th>Correto</th><th>Ação</th></tr>";
		while ($row = mysqli_fetch_array($result)){
			$query2 = "SELECT * FROM goldunit WHERE unitid=" . $row['idunit'] . ";";
			$result2 = mysqli_query($database, $query2);
			$row2 = mysqli_fetch_array($result2);
			echo "<tr><td>" . $row['idunit'] . "</td><td style=\"width: 120px; height: 90px; background-image: url('media/image/" . $row2['mediasrc'] . "'); background-size: contain; background-repeat: no-repeat; background-position: center center\">&nbsp;</td><td>" . $row['data'] . "</td>";
			
			echo "<td>";
			
			$queryAw = "SELECT * FROM goldunitanswers WHERE idref=" . $row['idunit'] . ";";
			$resAw = mysqli_query($database, $queryAw);
			$arrayAw = array();
		
			while ($rowAw = mysqli_fetch_array($resAw)){	
				array_push($arrayAw, $rowAw['answer']);
			}
			
			echo implode(" ou ", $arrayAw);
			
			
			echo "</td>";

			echo "<td>";
			
			$queryAs = "SELECT * FROM goldtaskhistory WHERE idnumref=" . $row['idnum'] . ";";
			$resultAs = mysqli_query($database, $queryAs);
			$rowAs = mysqli_fetch_array($resultAs);
			
			if ($rowAs['correct'] == 1) echo "<b>Sim</b>";
			else echo "<b class=\"danger\">Não</b>";
			
			
			echo "</td>";

			echo "<td>";

			if ($rowAs['correct'] != 1) echo "<a class=\"btn\" href=\"#\">Perdoar</a>";
			else echo "N/A";
			
			echo "</td>";

		
			
			echo "</tr>";
		}
		echo "</table>";
	}

	
	if ($_GET['action'] == "loadTaskPartRes"){
		include("include/ajaxLoadTaskPartRes.php");		
	}
	
	if ($_GET['action'] == "uploadData"){
		for ($i = 0; $i < $_COOKIE['uploadedFiles']; $i++){
			$getExt = $_FILES["file" . $i]["name"];
			$getExt = explode(".", $getExt);
			$getExt = $getExt[count($getExt) - 1];
			
			$fileName = md5_file($_FILES["file" . $i]["tmp_name"]) . "." . $getExt; //$_FILES["file" . $i]["name"]; // The file name
			$fileTmpLoc = $_FILES["file" . $i]["tmp_name"]; // File in the PHP tmp folder
			$fileType = $_FILES["file" . $i]["type"]; // The type of file it is
			$fileSize = $_FILES["file" . $i]["size"]; // File size in bytes
			$fileErrorMsg = $_FILES["file" . $i]["error"]; // 0 for false... and 1 for true
			if (!$fileTmpLoc) { // if file not chosen
				//echo "ERROR: Please browse for a file before clicking the upload button.";
				exit();
			}
			if (move_uploaded_file($fileTmpLoc, "test_uploads/$fileName")){
				echo $fileName . "|";
			} else {
				echo "";
			}
		}
	}
	
	if (strpos($_GET['action'], "TaskGUI") !== false){
		$getTitle = "";
		$getType = 1;
		$getDataType = 0;
		$getInstructions = "";
		$getQuestionsPage = 0;
		$getTaskUbound = 0;
		$getCrowdsourcer = -1;
		$getData = "";
		$defaultV = "none";
		$taskObj = null;
		$questionsPageL = 0;
		$questionsPageU = 0;
		$maxtasksL = 0;
		$maxtasksU = 0;
		$getAccuracy = 70;
		$bestAnswers = 1;
		$tipText = "";
		$defaultS = 'disabled';
				
		if (strpos($_GET['action'], "edit") !== false){
			$database = DatabaseConnection::getDBObject();
			$query = "SELECT * FROM task WHERE id=" . $_GET['data'] . ";";
			$result = mysqli_query($database, $query);
			$row = mysqli_fetch_array($result);
			$getTitle = $row['title'];
			$getType = $row['type'];
			$getDataType = $row['datatype'];
			$getInstructions = $row['instructions'];
			$getQuestionsPage = $row['questionspage'];
			$getTaskUbound = $row['taskubound'];
			$defaultV = "inline-block";
			$taskObj = new Tarefa($_GET['data']);
			$maxtasksL = 2;
			$maxtasksU = $taskObj->getMaximumAssignments();
			$questionsPageL = 2;
			$questionsPageU = 10;
			$getAccuracy = $row['minaccuracy'];
			$bestAnswers = $row['bestanswers'];
			$tipText = $row['tip'];
			$defaultS = '';
		}
		
		
		echo "<div class=\"completenote\" style=\"display: none\" id=\"taskCreated\"></div>";
		
		echo "<form method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return false\">";
		echo "<div style=\"display: inline-block; padding-left: 10pt\" class=\"subcontainer admincnt\">";
		echo "<p><b>1 - Dados básicos</b></p>";
		echo "<table>";
		echo "<tr><td colspan=2>";
		
		echo "<table>";
		echo "<tr>";
		echo "<td rowspan=2 style=\"vertical-align: top\">Título:</td>";
		
		if (strpos($_GET['action'], "create") !== false){
			echo "<td class=\"tqSensitive\" style=\"display: none\">Criação</td>";
		}
		
		echo "<td>";
		echo "<input id=\"title\" type=\"text\" maxlength=\"50\" size=\"50\" placeholder=\"Dê um título com um máximo de 50 caracteres\" value=\"" . $getTitle . "\" required onchange=\"ACROSS.checkSubmitEligibility()\">";
		echo "</td>";
		echo "</tr>";

		if (strpos($_GET['action'], "create") !== false){
			echo "<tr class=\"tqSensitive\" style=\"display: none\">";
			echo "<td>Revisão</td>";
			echo" <td>";
			echo "<input id=\"title2\" type=\"text\" maxlength=\"50\" size=\"50\" placeholder=\"Dê um título com um máximo de 50 caracteres\">";
			echo "</td>";
			echo "</tr>";
		}
		
		echo "</table>";
	
		
		echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>Esta tarefa é de criação/revisão de conteúdo?</td>";
		echo "<td>";
	
		$specState = "onclick=\"ACROSS.toogleCreativeMode()\"";
		
		if (strpos($_GET['action'], "edit") !== false){
			$specState = "disabled";
		}
		
		if ($getType != 1){
			$specState .= " checked";
		}
		
		echo "<input type=\"checkbox\" id=\"creativeMode\" " . $specState. ">";
		
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Dado não estruturado a ser analisado</td>";
		echo "<td rowspan=2 style=\"width: 100px; font-size: 9pt; text-align: justify\">";
		if (strpos($_GET['action'], "create") !== false){
			echo "<div class=\"warning\">Ao mudar o tipo de dado, os dados carregados serão perdidos!</div>";
		}
		echo "</td></tr>";
		echo "<tr><td colspan=2 id=\"dataimgbuttonparent\">";
		
		$specState = "";
		
		if (strpos($_GET['action'], "create") !== false){
			$specState = "onclick=\"ACROSS.selectDataType()\"";
		}
		
		echo "<div class=\"dataimgbutton image diSelect\" " . $specState . ">Imagem</div>";
		echo "<div class=\"dataimgbutton sound\" " . $specState . ">Som</div>";
		echo "<div class=\"dataimgbutton video\" " . $specState . ">Vídeo</div>";
		
		echo "</td>";
		echo "</tr>";

		
		echo "<tr><td colspan=\"2\">Descreva o que deve ser feito:</td></tr>";
		echo "<tr style=\"display: none\" class=\"tqSensitive\"><td colspan=\"2\">";
		echo "<nav class=\"adminTabs\">";
		$abas = array("Criação", "Revisão");
		
		for ($i = 0; $i < count($abas); $i++){
			$isActive = "";
			if ($i == 0) $isActive = "active";
		
			echo "<a class=\"" . $isActive . "\" name=\"abahdesc\" onclick=\"setTab('abahdesc', 'ababdesc'," . $i . ")\">";
			echo $abas[$i];
			echo "</a>";
		}
		
		
		echo "</nav>";
		
		echo "</td></tr>";
		echo "<tr><td colspan=\"2\">";
		echo "<div name=\"ababdesc\" style=\"display: block\">";
		echo "<textarea style=\"width: 480px; height: 180px\" id=\"desc\" required onchange=\"ACROSS.checkSubmitEligibility()\">" . $getInstructions . "</textarea><br><br>";
		echo "Dica: <input type=\"text\" maxlength=80 placeholder=\"É o texto que aparece em cada questão de teste\" id=\"tip\" size=\"55\" value=\"" . $tipText . "\">";
		echo "</div>";
		
		if (strpos($_GET['action'], "create") !== false){
			echo "<div name=\"ababdesc\" style=\"display: none\">";
			echo "<textarea style=\"width: 480px; height: 180px\" id=\"desc2\" onchange=\"ACROSS.checkSubmitEligibility()\"></textarea><br><br>";
			echo "Dica: <input type=\"text\" maxlength=80 placeholder=\"É o texto que aparece em cada questão de teste\" id=\"tip2\" size=\"55\">";
			echo "</div>";
		}

		echo "</tr>";	
		echo "</table>";
		echo "</div>";
		
		echo "<div style=\"display: " . $defaultV . "; padding-left: 10pt;\" class=\"subcontainer admincnt\">";
		echo "<p><b>2 - Gerenciar mídia <span class=\"tqSensitive\" style=\"display: none\">(Tarefa de criação)</span></b></p>";
		
		echo "<div class=\"completenote infobox\" style=\"display: none\" id=\"deletedMedia\" onclick=\"this.style.display = 'none'\"></div>";
		echo "<div class=\"expelnote infobox\" style=\"display: none\" id=\"rejectedFiles\" onclick=\"this.style.display = 'none'\"></div>";
		
		
		if (strpos($_GET['action'], "create") !== false){
			echo "<div class=\"warning tqSensitive\" style=\"display: none; width: 300px; text-align: justify\">";
			echo "As unidades da tarefa de revisão dependem de unidades respondidas da tarefa correspondente de criação e só estará disponível após enviar o formulário.";
			echo "</div>";
		}
		
		echo "<table cellspacing=0 cellpadding=0 width=100%>";
		echo "<tr>";
		echo "<td>";
		
		echo "<table style=\"width: 100%\">";
		echo "<tr>";
		echo "<td><input type=\"file\" multiple id=\"dataInput\"><span id=\"maxfilesize\"></span></td>";
		echo "<td style=\"align: right; text-align: right\" align=\"right\"><input type=\"button\" value=\"Carregar\" onclick=\"ACROSS.loadFiles()\"></td>";
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr>";		
		echo "<td align=\"right\">";
		
		$specState = "style=\"display: none\"";
		
		if (strpos($_GET['action'], "edit") !== false){
			$specState = "";
		}
		
		
		echo "<input type=\"button\" value=\"Excluir mídia(s)...\" onclick=\"ACROSS.enableMediaDeletion()\" " . $specState . " id=\"deleteMode\">";
		echo "<input type=\"button\" value=\"Confirmar\" onclick=\"ACROSS.deleteSelectedMedia()\" style=\"display: none\" id=\"cancelDelete\">";
		echo "<input type=\"button\" value=\"Cancelar\"  onclick=\"ACROSS.disableMediaDeletion()\" style=\"display: none\" id=\"doDelete\">";
		
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		
		echo "<div id=\"uploadInProgressBox\" style=\"display: none\">";
		echo "<progress id=\"progressBar\" value=\"0\" max=\"100\" style=\"width:300px;\"></progress>";
		echo "<h3 id=\"status\"></h3>";
		echo "</div>";
	
	
		$specState = "style=\"display: none\"";	
		
		if (strpos($_GET['action'], "edit") !== false){
			$specState = "";
		}
		
		
		echo "<div style=\"width: 100%; overflow-y: auto; height: calc(100% - 140px)\">";
		echo "<table class=\"joblist\" cellspacing=0 cellpadding=0 id=\"dataInputTable\" " . $specState . ">";
		echo "<tr><th>ID</th><th>Mídia</th>";
		
		if ($getType == 2){
			echo "<th>Contribuição</th>";
			echo "<th>Crowdsourcer</th>";
		}
		
		
		
		echo "<th title=\"questão de teste\">QT?</th><th>Resposta</th><th>Razão</th><th style=\"display: none\">Excluir?</th></tr>";

		if (strpos($_GET['action'], "edit") !== false){
			$queryG = "SELECT * FROM goldunit WHERE taskid=" . $_GET['data'] . ";";
			$resultG = mysqli_query($database, $queryG);
			
			while ($rowG = mysqli_fetch_array($resultG)){
				echo "<tr>";
				echo "<td>" . $rowG['unitid'] . "</td>";
				
				$mediaSrc = "";
				
				if ($getType != 2){
					$mediaSrc = $rowG['mediasrc'];
				} else {
					$query3 = "SELECT * FROM taskhistory WHERE idnum=" . $rowG['mediasrc'] . ";";
					$result3 = mysqli_query($database, $query3);
					$row3 = mysqli_fetch_array($result3);
					$getData = $row3['data'];
					$getCrowdsourcer = $row3['idcrowdsourcer'];
					
					$query4 = "SELECT * FROM taskunit WHERE unitid=" . $row3['idunit'] . ";";
					$result4 = mysqli_query($database, $query4);
					$row4 = mysqli_fetch_array($result4);
					
					$mediaSrc = $row4['mediasrc'];
				}
				
				
				echo "<td style=\"width: 120px; height: 90px; background-image: url('media/image/" . $mediaSrc . "'); background-position: center center; background-size: contain; background-repeat: no-repeat\"></td>";
				
				if ($getType == 2){
					echo "<td>" . $getData . "</td>";
					echo "<td>" . $getCrowdsourcer . "</td>";
				}
				
				echo "<td>";
				echo "<input type=\"checkbox\" disabled checked>";
				echo "</td>";
				
				echo "<td>";
				
				$queryAw = "SELECT * FROM goldunitanswers WHERE idref=" . $rowG['unitid'] . ";";
				$resAw = mysqli_query($database, $queryAw);
				
				if ($getType != 2){
					while ($rowAw = mysqli_fetch_array($resAw)){
						echo "<input type=\"text\" value=\"" . $rowAw['answer'] . "\"><br>";
					}
				} else {
					$rowAw = mysqli_fetch_array($resAw);
					$outputThis = array();
					$outputThis["true"] = "checked";
					$outputThis["false"] = "";
					
					echo "<input type=\"checkbox\" " . $outputThis[$rowAw['answer']] . ">";
				}
				
				echo "</td>";
				
				echo "<td><textarea>" . $rowG['reason'] . "</textarea></td>";
				echo "<td style=\"display: none\"><input type=\"checkbox\"></td>";
				echo "</tr>";

			}
			
			$query2 = "SELECT * FROM taskunit WHERE taskid=" . $_GET['data'] . ";";
			$result2 = mysqli_query($database, $query2);
			
			while ($row2 = mysqli_fetch_array($result2)){
				echo "<tr>";
				echo "<td>" . $row2['unitid'] . "</td>";

				$mediaSrc = "";

				if ($getType != 2){
					$mediaSrc = $row2['mediasrc'];
				} else {
					$query3 = "SELECT * FROM taskhistory WHERE idnum=" . $row2['mediasrc'] . ";";
					$result3 = mysqli_query($database, $query3);
					$row3 = mysqli_fetch_array($result3);
					$getData = $row3['data'];
					$getCrowdsourcer = $row3['idcrowdsourcer'];
					
					$query4 = "SELECT * FROM taskunit WHERE unitid=" . $row3['idunit'] . ";";
					$result4 = mysqli_query($database, $query4);
					$row4 = mysqli_fetch_array($result4);
					
					$mediaSrc = $row4['mediasrc'];
				}

				
				
				echo "<td style=\"width: 120px; height: 90px; background-image: url('media/image/" . $mediaSrc . "'); background-position: center center; background-size: contain; background-repeat: no-repeat\"></td>";
				
				
				
				if ($getType == 2){
					echo "<td>" . $getData . "</td>";
					echo "<td>" . $getCrowdsourcer . "</td>";
				}
				
				
				echo "<td>";
				echo "<input type=\"checkbox\" disabled>";
				echo "</td>";
				echo "<td>N/A</td>";
				echo "<td>N/A</td>";
				echo "<td style=\"display: none\"><input type=\"checkbox\"></td>";
				echo "</tr>";
			}
		}
		
		
		echo "</table>";
		echo "</div>";
		
		echo "<div id=\"createAjax\" style=\"display: none\"></div>";
		echo "</div>";
	
		
		echo "<script>";
		echo "var dataInputAjax = new AjaxPoweredContainer(document.querySelector(\"#createAjax\"));";
		echo "</script>";
		
		echo "<div style=\"display: " . $defaultV . "\" class=\"subcontainer admincnt\">";
		echo "<p><b>3 - Controle de qualidade e formato das tarefas</b></p>";
		

		
		echo "<table>";
		echo "<tr>";
		echo "<td>Questões por página</td>";
		echo "<td>";
		echo "<input type=\"range\" name=\"quantity\" min=\"" . $questionsPageL . "\" max=\"" . $questionsPageU . "\" onchange=\"ACROSS.updateValue(); ACROSS.updateRatioRestrictions()\" value=\"" . $getQuestionsPage . "\" size=\"4\">&nbsp;";
		echo "<input type=\"number\" name=\"quantityN\" min=\"" . $questionsPageL . "\" max=\"" . $questionsPageU . "\" value=\"" . $getQuestionsPage . "\" onchange=\"ACROSS.updateValue(); ACROSS.updateRatioRestrictions()\">";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>Número máximo de tarefas</td>";
		echo "<td>";
		echo "<input type=\"range\" name=\"maxtasks\" min=\"" . $maxtasksL . "\" max=\"" . $maxtasksU . "\" onchange=\"ACROSS.checkSubmitEligibility(); ACROSS.updateValue(); ACROSS.updateRatioRestrictions()\" value=\"" . $getTaskUbound . "\" size=\"4\">&nbsp;";
		echo "<input type=\"number\" name=\"maxtasksN\" min=\"" . $maxtasksL . "\" max=\"" . $maxtasksU . "\" value=\"" . $getTaskUbound . "\" onchange=\"ACROSS.checkSubmitEligibility(); ACROSS.updateValue(); ACROSS.updateRatioRestrictions()\">";

		echo "</td></tr>";


		if ($getType != 0){
			echo "<tr style=\"display: table-row\"><td>Credibilidade mínima (%) ";
			
			if (strpos($_GET['action'], "create") !== false){
				echo "<span class=\"tqSensitive\" style=\"display: none\">(Tarefa de revisão)</span>";
			}
			
			echo "</td><td>";
			echo "<input type=\"range\" name=\"accuracy\" min=\"50\" max=\"90\" value=\"" . $getAccuracy . "\" size=\"4\" onchange=\"ACROSS.updateValue()\" step=\"5\">&nbsp;";
			echo "<input type=\"number\" name=\"accuracyN\" min=\"50\" max=\"90\" value=\"" . $getAccuracy . "\" onchange=\"ACROSS.updateValue()\" step=\"5\">";
					
			echo "</td></tr>";
		}
		
		if ($getType != 2){
			echo "<tr style=\"display: table-row\"><td>Melhores respostas ";
			
			if (strpos($_GET['action'], "create") !== false){
				echo "<span class=\"tqSensitive\" style=\"display: none\">(Tarefa de criação)</span>";
			}
			
			echo "</td><td>";
			echo "<input type=\"range\" name=\"bestanswers\" min=\"1\" max=\"10\" value=\"" . $bestAnswers . "\" size=\"4\" onchange=\"ACROSS.updateValue()\">&nbsp;";
			echo "<input type=\"number\" name=\"bestanswersN\" min=\"1\" max=\"10\" value=\"" . $bestAnswers . "\" onchange=\"ACROSS.updateValue()\">";

			echo "</td></tr>";
		}
		echo "</table>";
		
		echo "<p><b>4 - Finalizar</b></p>";
		
		echo "<input type=\"submit\" value=\"Finalizar\" " . $defaultS ." onclick=\"ACROSS.createTask()\">";
		echo "</div>";
		echo "</form>";

	}
	
	function getMediaType($ext){
		if ($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif") return "image";
		if ($ext == "mp3" || $ext == "wav" || $ext == "ogg") return "sound";
		if ($ext == "webm" || $ext == "mp4" || $ext == "flv" || $ext == "wmv" || $ext == "av2" || $ext == "mp2") return "video";
		return NULL;
	}
	
	if ($_GET['action'] == "createTask"){
		$getTaskId = "SELECT max(id) as maxid FROM task;";
		$result0 = mysqli_query($database, $getTaskId);
		$row0 = mysqli_fetch_array($result0);
		$taskId = $row0['maxid']+1;
		$taskId2 = -1;
		
		$badassArray = array();
		$badassArray[false] = 0;
		$badassArray[true] = 1;

		$badassArray2 = array();
		$badassArray2[false] = $_GET['mintrust'];
		$badassArray2[true] = NULL;
		
		$TQOrder = "(id, title, type, datatype, instructions, tip, minaccuracy, bestanswers, questionspage, taskubound, status)";
		$taskQuery = "INSERT INTO task " . $TQOrder . " VALUES (" . $taskId . ", '" . addslashes($_GET['title']) . "', " . $badassArray[isset($_GET['title2'])] . ", " . $_GET['datatype'] . ", '" . addslashes($_GET['data']) . "', '" . $_GET['tip'] . "', " . $badassArray2[isset($_GET['title2'])] . ", " . $_GET['bestanswers'] . ", " . $_GET['quantity'] . ", " . $_GET['maxtasks'] . ", 1);";
		$taskQuery2 = "";
		$taskLink = "";
		
		if (isset($_GET['title2'])){
			$taskId2 = $taskId+1;
			$taskQuery2 = "INSERT INTO task " . $TQOrder . " VALUES (" . $taskId2 . ", '" . addslashes($_GET['title2']) . "', 2, " . $_GET['datatype'] . ", '" . addslashes($_GET['data2']) . "', " . $_GET['tip2'] . ", " . $_GET['mintrust'] . ", NULL, " . $_GET['quantity'] . ", " . $_GET['maxtasks'] . ", 0);";
			$taskLink = "INSERT INTO tasklink VALUES (" . $taskId . ", " . $taskId2 . ")";
		}
		
		
		$splitData = explode("*", $_POST['data']);
		$splitData[0] = explode("|", $splitData[0]);
		$splitData[1] = explode("|", $splitData[1]);
		
		
		$NQArray = array();
		for ($i = 0; $i < count($splitData[0]); $i++){
			$filename = $splitData[0][$i];
			$filename = explode("\"", $filename);
			$filename = $filename[0];
			
			$filetype = explode(".", $filename);
			$filetype = $filetype[count($filetype)-1];
						
			rename("test_uploads/" . $filename, "media/" . getMediaType($filetype) . "/" . $filename);
			array_push($NQArray, "INSERT INTO taskunit (taskid, mediasrc) VALUES (" . $taskId . ", '" . $filename . "');");
		}
		
		$TQArray = array();
		for ($i = 0; $i < count($splitData[1]); $i++){
			$dataBit = explode(":", $splitData[1][$i]);
			
			$filename = $dataBit[0];
			$filename = explode("\"", $filename);
			$filename = $filename[0];

			$filetype = explode(".", $filename);
			$filetype = $filetype[count($filetype)-1];
			
						
			rename("test_uploads/" . $filename, "media/" . getMediaType($filetype) . "/" . $filename);
			array_push($TQArray, "INSERT INTO goldunit (taskid, mediasrc, answer, reason) VALUES (" . $taskId . ", '" . $filename . "', '" . $dataBit[1] . "', '" . $dataBit[2] . "');");
		}
		
		mysqli_query($database, $taskQuery);
		if ($taskQuery2 != ""){
			mysqli_query($database, $taskQuery2);
		}
	
		if ($taskLink != ""){
			mysqli_query($database, $taskLink);
		}

		for ($i = 0; $i < count($NQArray); $i++){
			mysqli_query($database, $NQArray[$i]);
		}

		for ($i = 0; $i < count($TQArray); $i++){
			mysqli_query($database, $TQArray[$i]);
		}

		echo "Tarefa criada com sucesso!";	
	}
	
	if ($_GET['action'] == "editTask"){
		$taskId = -1;
		
		$badassArray2 = array();
		$badassArray2[false] = $_GET['mintrust'];
		$badassArray2[true] = NULL;
		
		$TQOrder = "(title, type, datatype, instructions, tip, minaccuracy, bestanswers, questionspage, taskubound, status)";
		$taskQuery = "UPDATE task SET title='" . addslashes($_GET['title']) . "', instructions='" . addslashes($_GET['data']) . "', tip='" . $_GET['tip'] . "', minaccuracy=" . $badassArray2[isset($_GET['title2'])] . ", bestanswers=" . $_GET['bestanswers'] . ", questionspage=" . $_GET['quantity'] . ", taskubound=" . $_GET['maxtasks'] . " WHERE id=" . $taskId . ";";
	
	
		
		$splitData = explode("*", $_POST['data']);
		$splitData[0] = explode("|", $splitData[0]);
		$splitData[1] = explode("|", $splitData[1]);
		
		
		$NQArray = array();
		/*
		for ($i = 0; $i < count($splitData[0]); $i++){
			$filename = $splitData[0][$i];
			$filename = explode("\"", $filename);
			$filename = $filename[0];
			
			$filetype = explode(".", $filename);
			$filetype = $filetype[count($filetype)-1];
						
			array_push($NQArray, "UPDATE taskunit SET mediasrc='" . $filename . "' WHERE taskid=" . $taskId . ";");
		}
		*/
		
		$TQArray = array();
		/*
		for ($i = 0; $i < count($splitData[1]); $i++){
			$dataBit = explode(":", $splitData[1][$i]);
			
			$filename = $dataBit[0];
			$filename = explode("\"", $filename);
			$filename = $filename[0];

			$filetype = explode(".", $filename);
			$filetype = $filetype[count($filetype)-1];
			
			array_push($TQArray, "UPDATE goldunit SET mediasrc='" . $filename . "', answer='" . $dataBit[1] . "', reason='" . $dataBit[2] . "' WHERE taskid=" . $taskId . ";");
		}
		*/
		mysqli_query($database, $taskQuery);
		echo $taskQuery;
	
		for ($i = 0; $i < count($NQArray); $i++){
			mysqli_query($database, $NQArray[$i]);
		}

		for ($i = 0; $i < count($TQArray); $i++){
			mysqli_query($database, $TQArray[$i]);
		}


		echo "Tarefa atualizada com sucesso!";	
	}
	
	if ($_GET['action'] == "removeTempFiles"){
		if (isset($_GET['data'])){
			$getData = explode("|", $_GET['data']);
			
			for ($i = 0; $i < count($getData); $i++){
				if (trim($getData[$i]) != ""){
					$getData[$i] = implode("", explode(")", $getData[$i]));
					$deleteThis = "test_uploads/" . $getData[$i];
					
					try {
						unlink($deleteThis);
					} catch (Exception $exception){
					}
				}
			}
			
			$deletedMessage = count($getData) . " arquivos excluídos com sucesso.";
			if (count($getData) == 1){
				$deletedMessage = implode(" ", explode("s ", $deletedMessage));
			}
			
			echo $deletedMessage;
		}		
	}
	
	if ($_GET['action'] == "deleteTask"){
		$deleteTaskDef = "DELETE FROM task WHERE id=" . $_GET['data'] . ";";
		$deleteTaskDef2 = "DELETE FROM taskhistory WHERE idtask=" .$_GET['data'] .  ";";
		$deleteTaskDef3 = "DELETE FROM goldunit WHERE taskid=" .$_GET['data'] .  ";";
		$deleteTaskDef4 = "DELETE FROM taskunit WHERE taskid=" .$_GET['data'] .  ";";
		$deleteTaskDef5 = "DELETE FROM tasklink WHERE id=" .$_GET['data'] .  ";";
		
		mysqli_query($database, $deleteTaskDef);
		mysqli_query($database, $deleteTaskDef2);
		mysqli_query($database, $deleteTaskDef3);
		mysqli_query($database, $deleteTaskDef4);
		mysqli_query($database, $deleteTaskDef5);
	}
	
	if ($_GET['action'] == "manageTask"){
		$query = "SELECT * FROM task;";
		$result = mysqli_query($database, $query);

		echo "<div style=\"display: inline-block; vertical-align: top; padding-left: 10pt\">";
		echo "<p>Clique na tarefa para obter mais informações</p>";

		echo "<table cellspacing=\"0\" cellpadding=\"0\" class=\"joblist\">";
		echo "<tr><th>ID</th><th>Título</th><th>Descrição</th><th>Status</th><th>Ação</th></tr>";
		while ($row = mysqli_fetch_array($result)){

			$inst = substr($row["instructions"], 0, 30);
			if (strlen($inst) != strlen($row["instructions"])){
				$inst .= "...";
			}

			$statusText = array("Pausado", "Em execução", "Finalizado");
			echo "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td><td onclick=\"ACROSS.showInstructions('" . addslashes($row["instructions"]) . "')\">" . $inst ."</td>";
			echo "<td onmouseover=\"\" onmouseout=\"\">" . $statusText[$row["status"]] . "</td><td>";
			echo "<div class=\"icobg\" style=\"background-image: url(images/users.png)\" onclick=\"ACROSS.loadUserContributions(" . $row['id'] . ")\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
			if ($row["status"] != 0){
				echo "<div class=\"icobg\" style=\"background-image: url(images/check.png)\" onclick=\"ACROSS.loadPartResults(" . $row['id'] . ")\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
				if ($row["status"] == 1){
					echo "<div class=\"icobg\" style=\"background-image: url(images/pause.png)\" onclick=\"ACROSS.pauseTask(" . $row['id'] . ")\" title=\"Pausar tarefa. Removendo-a da lista de tarefas disponíveis. Use em casos de manutenção.\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
				}
				
			} else {
				echo "<div class=\"icobg\" style=\"background-image: url(images/play.png)\" onclick=\"ACROSS.releaseTask(" . $row['id'] . ")\" title=\"Executar tarefa, tornando-a disponível aos contribuintes\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
			}
			
			if ($row["status"] != 2){
				echo "<div class=\"icobg\" style=\"background-image: url(images/edit.png)\" onclick=\"ACROSS.editTask(" . $row['id'] . ")\" title=\"Editar tarefa\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
				
				echo "<div class=\"icobg\" style=\"background-image: url(images/stop.png)\" onclick=\"ACROSS.stopTask(" . $row['id'] . ")\" title=\"Finalizar tarefa\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
				
				echo "<div class=\"icobg\" style=\"background-image: url(images/excluir.png)\" onclick=\"ACROSS.deleteTask(" . $row['id'] . ")\" title=\"Excluir tarefa\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";


			}

			echo "</td></tr>";
			
		}
		echo "</table>";
		echo "</div>";
	}
	
	if ($_GET['action'] == "releaseTask"){
		$query = "UPDATE task SET status=1 WHERE id=" . $_GET['data'] . ";";
		mysqli_query($database, $query);
	}
	
	if ($_GET['action'] == "pauseTask"){
		$query = "UPDATE task SET status=0 WHERE id=" . $_GET['data'] . ";";
		mysqli_query($database, $query);
	}
	
	if ($_GET['action'] == "stopTask"){
		$query = "UPDATE task SET status=2 WHERE id=" . $_GET['data'] . ";";
		mysqli_query($database, $query);
	}
	
	if ($_GET['action'] == "downloadXML"){
		echo "<?xml ver=\"1.0\">";
		echo "<acrossxml>";
		echo "</acrossxml>";

		header("Content-type: application/xml");
		header("Content-Disposition: attachment; filename=\"result.xml\"");
	}
	
	if ($_GET['action'] == "changePassword"){
		$query = "SELECT password FROM users WHERE idnum=" . $_SESSION['useridnum'] . ";";
		$result = mysqli_query($database, $query);
		$row = mysqli_fetch_array($result);
		if (md5($_GET['data']) == $row['password']){
			echo "Pneumoultramicroscopicossilicovulcanoconiose";
		}
	}
	
	if ($_GET['action'] == "newPassword"){
		$query = "UPDATE users SET password='" . md5($_GET['data']) . "' WHERE idnum=" . $_SESSION['useridnum'] . ";";
		mysqli_query($database, $query);
		echo "Nova senha definida com sucesso!";
	}
	
	if ($_GET['action'] == "getRetrievalCode"){
		$randomString = '';
		$query0 = "SELECT * FROM userretrieval WHERE userid=" . $_SESSION['useridnum'] . ";";
		$result0 = mysqli_query($database, $query0);
		
		while ($row0 = mysqli_fetch_array($result0)){
			$randomString = $row0['code'];
		}
		
		if ($randomString == ''){
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < 32; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
	
			$query = "INSERT INTO userretrieval VALUES (" . $_SESSION['useridnum'] . ", '" . $randomString . "')";
			mysqli_query($database, $query);
		}

	    echo $randomString;
	}
	
	if ($_GET['action'] == "setLabMode"){
		if (hash('sha256', $_GET['data']) == "6757f4abae0cd685ffbad350a300195c7c72ab651b0302524fc78c2acf8a24a2"){
			$_SESSION['labMode'] = 1;
			
			echo "Modo Laboratório definido com êxito. <a href=\"#\" onclick=\"location.reload();\">Atualize a página</a>";
		} else {
			echo "Palavra-chave inválida";
		}
	}
	
?>