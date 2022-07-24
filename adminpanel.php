<?php
	session_start();
	include("include/reqadminauth.php");
?>

<html>
<head>
<meta charset="utf-8">
<title>ACROSS - Painel administrativo</title>
<?php include("include/style.php"); ?>
<script src="js/ajax.js"></script>
<script src="js/sorttable.js"></script>
<script>
	String.prototype.fullReplace = function(toBeReplaced, replaced){
		return this.split(toBeReplaced).join(replaced)
	}

	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}
	
	var ACROSS = new (function(){
		var targetContainer = null
		var testQuestions = 0
		
		this.getTestQuestions = function(){
			return testQuestions
		}
		
		this.incrementTestQuestions = function(){
			testQuestions++
		}

		this.decrementTestQuestions = function(){
			testQuestions--
		}

		this.subtractTestQuestions = function(op){
			testQuestions -= Math.min(op, testQuestions)
		}

		this.getTargetContainer = function(){
			return targetContainer;
		}
		
		this.setTargetContainer = function(getObj){
			targetContainer = getObj
		}

		this.setFlagActionLabel = function(){
			if (event.target.textContent == "Não"){
				event.target.innerHTML = "Atribuir"
			} else if (event.target.textContent == "Sim"){
				event.target.innerHTML = "Remover"
			}
		}
		
		this.clearFlagActionLabel = function(){
			if (event.target.textContent == "Atribuir"){
				event.target.textContent = "Não"
			} else if (event.target.textContent = "Remover"){
				event.target.textContent = "Sim"
			}
		}
		
		this.runFlagAction = function(crowdsourcerID, taskid){
			var APC = new AjaxPoweredContainer(event.target)
			
			if (event.target.innerHTML == "Atribuir"){
				APC.getDados("setFlag", crowdsourcerID + "|" + taskid)
			}
			
			if (event.target.innerHTML == "Remover"){
				APC.getDados("clearFlag", crowdsourcerID + "|" + taskid)				
			}			
		}
		
		this.loadUserContributions = function(taskid){
			contribAPC = new AjaxPoweredContainer(document.querySelector("#crowdcontributions"));	
			contribAPC.getDados("loadTaskCrowd", taskid)
			document.querySelector('#edittask').style.display = "none"			
			document.querySelector('#partresults').style.display = "none"
			document.querySelector('#crowdcontributions').style.display = "inline-block"
			document.querySelector('#crowdcontribsingle').style.display = "none"
			}
		
		this.loadSingleUserContributions = function(crowdid, taskid){
			contrib2APC = new AjaxPoweredContainer(document.querySelector("#crowdcontribsingle"));	
			contrib2APC.getDados("loadUserContributions", crowdid + "|" + taskid)
			document.querySelector('#edittask').style.display = "none"			
			document.querySelector('#partresults').style.display = "none"
			document.querySelector('#crowdcontributions').style.display = "inline-block"
			document.querySelector('#crowdcontribsingle').style.display = "inline-block"
		}


		this.loadSingleUserGoldContributions = function(crowdid, taskid){
			contrib2APC = new AjaxPoweredContainer(document.querySelector("#crowdcontribsingle"));	
			contrib2APC.getDados("loadUserGoldContributions", crowdid + "|" + taskid)
			document.querySelector('#edittask').style.display = "none"			
			document.querySelector('#partresults').style.display = "none"
			document.querySelector('#crowdcontributions').style.display = "inline-block"
			document.querySelector('#crowdcontribsingle').style.display = "inline-block"
		}

		
		this.getDataType = function(addItNow){
			if (typeof(addItNow) != "string"){
				getSource = undefined
				
				if (addItNow == undefined){
					getSource = event.target || ACROSS.getTargetContainer()
				} else if (typeof(addItNow) == "object") {
					getSource = addItNow
				}
			
				while (getSource.getAttribute('name') != "abacorpo"){
					getSource = getSource.parentNode
				}
				
				addPrefix = []
				addPrefix[false] = "#edittask"
				addPrefix[true] = "#createTaskGUI"
				addItNow = addPrefix[(getSource.id == "createTaskGUI")]
			}
			
			
			getChildren = document.querySelector(addItNow + ' #dataimgbuttonparent').children
			for (i = 0; i < getChildren.length; i++){
				if (getChildren[i].className.indexOf('diSelect') != -1)
					return i
			}
			return -1
		}
		
		this.getUpperFileSizeBound = function(mediaTypeID){
			if (mediaTypeID == 0) return 500000
			if (mediaTypeID == 1) return 5000000
			if (mediaTypeID == 2) return 50000000
		}
		
		this.loadFiles = function(){
			getSource = event.target
			
			while (getSource.getAttribute('name') != "abacorpo"){
				getSource = getSource.parentNode
			}

		
			addPrefix = []
			addPrefix[false] = "#edittask"
			addPrefix[true] = "#createTaskGUI"
			addItNow = addPrefix[(getSource.id == "createTaskGUI")]
			
			var formdata = new FormData();	
			
			file = document.querySelector(addItNow + " #dataInput").files;
			rejectedFiles = []
			
			for (ixx = 0, jxx = 0; ixx < file.length; ixx++){
				if (file[ixx].size <= ACROSS.getUpperFileSizeBound(ACROSS.getDataType(addItNow))){
					formdata.append("file" + jxx, file[ixx]);
					jxx++
				} else {
					rejectedFiles.push(file[ixx])
				}
			}			
			
			
			document.cookie = "uploadedFiles="+jxx
			ACROSS.setTargetContainer(addItNow)
			
			getObj = document.querySelector(addItNow + " #rejectedFiles")
			if (rejectedFiles.length > 0){
				getObj.style.display = "block"
				
				if (rejectedFiles.length == file.length){
					getObj.textContent = "Nenhum arquivo pode ser aceito por ser maior que o limite máximo de tamanho"
				} else {
					getObj.textContent = rejectedFiles.length + " arquivo(s) não pôde ser aceito(s) por ser maior que o limite máximo de tamanho"					
				}				
			} else {
				getObj.style.display = "none"
			}
			
	
			if (rejectedFiles.length < file.length){
				var dataInputAjax = new AjaxPoweredContainer(document.querySelector(addItNow + " #createAjax"));
				dataInputAjax.getDados("uploadData", undefined, formdata, ACROSS.insertUploadedFile, ACROSS.loadFileProgress)
			}
		}
		
		this.loadFileProgress = function(event){
			document.querySelector(ACROSS.getTargetContainer() + " #uploadInProgressBox").style.display = "block"
			//document.querySelector("#loaded_n_total").innerHTML = "Enviados "+event.loaded+" bytes de um total de " + event.total;
			var percent = (event.loaded / event.total) * 100;
			document.querySelector(ACROSS.getTargetContainer() + " #progressBar").value = Math.round(percent);
			document.querySelector(ACROSS.getTargetContainer() + " #status").innerHTML = Math.round(percent)+"% Enviado... por favor espere!";
		}
		
		this.insertUploadedFile = function(){
			document.querySelector(ACROSS.getTargetContainer() + " #uploadInProgressBox").style.display = "none"
			filesName = document.querySelector(ACROSS.getTargetContainer() + " #createAjax").textContent.split("|")
			insertHere = document.querySelector(ACROSS.getTargetContainer() + " #dataInputTable")
			
			for (i = 0; i < filesName.length; i++){
				if (filesName[i] != ""){
					nIndex = insertHere.rows.length
					rowRef = insertHere.insertRow()
					cell0Ref = rowRef.insertCell()
					cell0Ref.textContent = nIndex
					cell1Ref = rowRef.insertCell()
					
					if (document.querySelector(ACROSS.getTargetContainer() + ' #dataimgbuttonparent').children[0].className.indexOf('diSelect') != -1){
						cell1Ref.style.backgroundImage = "url('test_uploads/" + filesName[i] + "')"
						cell1Ref.style.backgroundRepeat = "no-repeat"
						cell1Ref.style.backgroundPosition = "center center"
						cell1Ref.style.backgroundSize = "contain"
						cell1Ref.style.width = "120px"
						cell1Ref.style.height = "90px"
					}
					
					if (document.querySelector(ACROSS.getTargetContainer() + ' #dataimgbuttonparent').children[1].className.indexOf('diSelect') != -1){
						gHTML = "<audio controls><source src=\"test_uploads/"  + filesName[i] + "\" type=\"audio/mpeg\"></audio>"
						cell1Ref.innerHTML = gHTML
					}

					if (document.querySelector(ACROSS.getTargetContainer() + ' #dataimgbuttonparent').children[2].className.indexOf('diSelect') != -1){
						gHTML = "<video width=\"320\" height=\"240\" controls> <source src=\"test_uploads/"  + filesName[i] + "\" type=\"video/mp4\"></video>"
						cell1Ref.innerHTML = gHTML
					}

					cell2Ref = rowRef.insertCell()
					cell2Ref.innerHTML = "<input type=\"checkbox\" onclick=\"ACROSS.toogleNewTQ(" + nIndex + ")\">"
					cell2Ref.textAlign = "center"
					cell2Ref.align = "center"
					
					if (document.querySelector(ACROSS.getTargetContainer() + " #creativeMode").checked){
						cell2Ref.style.display = "none"
					} else {
						cell2Ref.style.display = "table-cell"
					}
					
					cell3Ref = rowRef.insertCell()
					cell3Ref.innerHTML = "<textarea disabled style=\"height: 100%; width: 100%\"></textarea>"
					
					if (document.querySelector(ACROSS.getTargetContainer() + " #creativeMode").checked){
						cell3Ref.style.display = "none"
					} else {
						cell3Ref.style.display = "table-cell"
					}

					
					cell4Ref = rowRef.insertCell()
					cell4Ref.innerHTML = "<textarea disabled style=\"height: 100%; width: 100%\"></textarea>"
					
					if (document.querySelector(ACROSS.getTargetContainer() + " #creativeMode").checked){
						cell4Ref.style.display = "none"
					} else {
						cell4Ref.style.display = "table-cell"
					}
					
					cell5Ref = rowRef.insertCell()
					cell5Ref.style.display = "none"
					cell5Ref.innerHTML = "<input type=\"checkbox\">"
					
					
				}
			}
			
			document.querySelector(ACROSS.getTargetContainer() + ' #dataInputTable').style.display = "block"
			document.querySelector(ACROSS.getTargetContainer() + " #deleteMode").style.display = "inline"	
			
			ACROSS.updateBounds(ACROSS.getTargetContainer())
			ACROSS.checkSubmitEligibility(ACROSS.getTargetContainer())			
		
		}
		
		this.selectDataType = function(){
			for (i = 0; i < document.querySelector('#dataimgbuttonparent').children.length; i++){
				refTo = document.querySelector('#dataimgbuttonparent').children[i]
				refTo.className = refTo.className.split(' diSelect').join('')
			}
			
			if (event.target.className.indexOf('diSelect') == -1){
				event.target.className += " diSelect"
			}

			ACROSS.deleteSelectedMedia(true)
			ACROSS.setAcceptableFileFormats(event.target)
		}
		
		this.getMediaSrc = function(currentRow){
			getBaseName = ""

			if (currentRow.cells[1].children.length > 0){
				getBaseName = currentRow.cells[1].children[0].children[0].src
			} else {
				getBaseName = currentRow.cells[1].style.backgroundImage.split('url(').join('').split(')').join('')
			}
			
			getBaseName = getBaseName.split('/')[getBaseName.split('/').length-1]
			
			return getBaseName
		}
		
		this.enableMediaDeletion = function(){
			getSource = event.target
			
			while (getSource.getAttribute('name') != "abacorpo"){
				getSource = getSource.parentNode
			}
			
			addPrefix = []
			addPrefix[false] = "#edittask"
			addPrefix[true] = "#createTaskGUI"
			addItNow = addPrefix[(getSource.id == "createTaskGUI")]
			
			
			for (i = 0; i < document.querySelector(addItNow + ' #dataInputTable').rows.length; i++){
				getCells = document.querySelector(addItNow + ' #dataInputTable').rows[i].cells
				getCells[getCells.length-1].style.display = "table-cell"
			}
			document.querySelector(addItNow + " #deleteMode").style.display = "none"
			document.querySelector(addItNow + " #doDelete").style.display = "inline"
			document.querySelector(addItNow + " #cancelDelete").style.display = "inline"			
			
		}
		
		this.disableMediaDeletion = function(){
			getSource = event.target
			
			while (getSource.getAttribute('name') != "abacorpo"){
				getSource = getSource.parentNode
			}
			
			addPrefix = []
			addPrefix[false] = "#edittask"
			addPrefix[true] = "#createTaskGUI"
			addItNow = addPrefix[(getSource.id == "createTaskGUI")]
		
			ditRowsLength = document.querySelector(addItNow + ' #dataInputTable').rows.length
			
			for (i = 0; i < ditRowsLength; i++){
				document.querySelector(addItNow + ' #dataInputTable').rows[i].cells[5].style.display = "none"
			}
			
			if (ditRowsLength > 1){
				document.querySelector(addItNow + " #deleteMode").style.display = "inline"
				document.querySelector(addItNow + " #dataInputTable").style.display = "block"
			} else {
				document.querySelector(addItNow + " #deleteMode").style.display = "none"
				document.querySelector(addItNow + " #dataInputTable").style.display = "none"
			}
			
			document.querySelector(addItNow + " #doDelete").style.display = "none"
			document.querySelector(addItNow + " #cancelDelete").style.display = "none"			
		}
		
		this.toogleNewTQ = function(){
			getElem = event.target
			
			while (getElem.nodeName != "TR"){
				getElem = getElem.parentNode
			}
			
			dynObj = getElem.cells
			dynObj[3].children[0].disabled = !dynObj[3].children[0].disabled
			dynObj[4].children[0].disabled = !dynObj[4].children[0].disabled
	
			while (getElem.getAttribute('name') != "abacorpo"){
				getElem = getElem.parentNode
			}
			
			addPrefix = []
			addPrefix[false] = "#edittask"
			addPrefix[true] = "#createTaskGUI"
			addItNow = addPrefix[(getElem.id == "createTaskGUI")]
			
			if (dynObj[3].children[0].disabled){
				ACROSS.decrementTestQuestions()
			} else {
				ACROSS.incrementTestQuestions()
			}
			
			ACROSS.updateBounds(addItNow)			
			ACROSS.checkSubmitEligibility(addItNow)			
		}
		
		this.deleteSelectedMedia = function(everything){
			deletedTQ = 0
			addItNow = ""
			if (!everything){
				getSource = event.target
				
				while (getSource.getAttribute('name') != "abacorpo"){
					getSource = getSource.parentNode
				}
				
				addPrefix = []
				addPrefix[false] = "#edittask"
				addPrefix[true] = "#createTaskGUI"
				addItNow = addPrefix[(getSource.id == "createTaskGUI")]
			} else {
				addItNow = "#createTaskGUI"
			}
			
			deletedBoxMsg = document.querySelector(addItNow + ' #deletedMedia')
			
			if (document.querySelector(addItNow + ' #dataInputTable').rows.length > 1){
				deletedBoxMsg.style.display = "block"
			} else {
				deletedBoxMsg.style.display = "none"
			}


			deletedMedia = []
			for (i = 1; i < document.querySelector(addItNow + ' #dataInputTable').rows.length; i++){
				currentRow = document.querySelector(addItNow + ' #dataInputTable').rows[i]
				currentRow.cells[0].textContent = i
				currentRow.cells[2].children[0].onclick = ACROSS.toogleNewTQ
				
				getBaseName = ""
				
				if (currentRow.cells[1].children.length > 0){
					getBaseName = currentRow.cells[1].children[0].children[0].src
				} else {
					getBaseName = currentRow.cells[1].style.backgroundImage
				}

				getBaseName = getBaseName.split("/")[getBaseName.split("/").length-1]
				
				if (everything == true || currentRow.cells[5].children[0].checked){
					deletedMedia.push(getBaseName)
					
					if (currentRow.cells[2].children[0].checked){
						deletedTQ++
					}
					
					document.querySelector(addItNow + ' #dataInputTable').deleteRow(i)
					i--
				}
			}
			
			ACROSS.subtractTestQuestions(deletedTQ)
			ACROSS.updateBounds()			
			deletedBoxMsg = document.querySelector(addItNow + " #deletedMedia")
			var deleteAPC = new AjaxPoweredContainer(deletedBoxMsg)
			deleteAPC.getDados("removeTempFiles", deletedMedia.join("|"))
			
			
			ACROSS.disableMediaDeletion()
		}
		
		this.toogleCreativeMode = function(){
			selectElem = document.querySelectorAll(".tqSensitive")
			for (i = 0; i < selectElem.length; i++){
				if (selectElem[i].style.display != "none"){
					selectElem[i].style.display = "none"
				} else {
					if (selectElem[i].nodeName == "TR"){
						selectElem[i].style.display = "table-row"
					}
					
					if (selectElem[i].nodeName == "TD"){
						selectElem[i].style.display = "table-cell"
					}
					
					if (selectElem[i].nodeName == "SPAN"){
						selectElem[i].style.display = "inline"
					}

					if (selectElem[i].nodeName == "DIV"){
						selectElem[i].style.display = "block"
					}
					
					if (selectElem[i].nodeName == "TEXTAREA"){
						selectElem[i].style.display = "block"
					}
					

				}
			}
			
			for (i = 0; i < document.querySelector('#dataInputTable').rows.length; i++){
				badassArray = []
				badassArray[false] = "table-cell"
				badassArray[true] = "none"

				
				currentRow = document.querySelector('#dataInputTable').rows[i]
				currentRow.cells[2].style.display = badassArray[document.querySelector('#creativeMode').checked]
				currentRow.cells[3].style.display = badassArray[document.querySelector('#creativeMode').checked]
				currentRow.cells[4].style.display = badassArray[document.querySelector('#creativeMode').checked]
			}
		}
		
		this.editTask = function(idx){
			editAPC = new AjaxPoweredContainer(document.querySelector("#edittask"));
			editAPC.getDados("editTaskGUI", idx)
			document.querySelector('#edittask').style.display = "inline-box"
		}
		
		this.releaseTask = function(idx){
			releaseAPC = new AjaxPoweredContainer({innerHTML: ""})
			releaseAPC.getDados("releaseTask", idx, undefined, MTGUIAjax.getDados("manageTask"))
		}

		this.pauseTask = function(idx){
			pauseAPC = new AjaxPoweredContainer({innerHTML: ""})
			pauseAPC.getDados("pauseTask", idx, undefined, MTGUIAjax.getDados("manageTask"))
		}

		this.stopTask = function(idx){
			stopAPC = new AjaxPoweredContainer({innerHTML: ""})
			stopAPC.getDados("stopTask", idx, undefined, MTGUIAjax.getDados("manageTask"))
		}
		
		this.checkSubmitEligibility = function(addItNow){
			if (!addItNow){
				getSource = event.target
			
				while (getSource.getAttribute('name') != "abacorpo"){
					getSource = getSource.parentNode
				}
				
				addPrefix = []
				addPrefix[false] = "#edittask"
				addPrefix[true] = "#createTaskGUI"
				addItNow = addPrefix[(getSource.id == "createTaskGUI")]
			}
			
			getLimitingFactor = document.querySelector(addItNow + ' INPUT[name="maxtasks"]')
			getLimitingFactor2 = document.querySelector(addItNow + ' #dataInputTable')
			getLimitingFactor3 = document.querySelector(addItNow + ' #title')
			getLimitingFactor4 = document.querySelector(addItNow + ' #desc')
			
			if (getLimitingFactor3.value != "" && getLimitingFactor4.value != ""){
				document.querySelectorAll(addItNow + ' .subcontainer')[1].style.display = "inline-block"
			}
			
			if (getLimitingFactor2.rows.length >= 3){
				document.querySelectorAll(addItNow + ' .subcontainer')[2].style.display = "inline-block"
			}
			
			
			submitBtn = document.querySelector(addItNow + ' INPUT[type="submit"]')
			
			if (ACROSS.getTestQuestions() < 1 || getLimitingFactor2.rows.length < 3 || getLimitingFactor3.value == "" || getLimitingFactor4.value == ""){
				submitBtn.setAttribute('disabled', 'disabled') 
			} else {
				submitBtn.removeAttribute('disabled') 
			}
		}
		
		this.updateValue = function(){
			getSrc = event.target
			getSrcValue = getSrc.value
			getID = getSrc.getAttribute('name')
			getLBound = getSrc.getAttribute('min')
			getUBound = getSrc.getAttribute('max')
			
			if (isNaN(getSrcValue)){
				getSrcValue = getLBound
				if (getID.indexOf('accuracy') != -1){
					getSrcValue = 70
					alert(getSrcValue)
				}
			}
			
			while (getSrc.tagName != "TD"){
				getSrc = getSrc.parentNode
			}
			
			getSrc.children[0].value = Math.min(Math.max(getLBound, getSrcValue), getUBound)
			getSrc.children[1].value = Math.min(Math.max(getLBound, getSrcValue), getUBound)
		}
		
		this.updateBounds = function(addItNow){
			if (!addItNow){
				addItNow = ACROSS.getTargetContainer()
				if (!addItNow) return;
			}
			
			getLimitingFactor0V = document.querySelector(addItNow + " #dataInputTable").rows.length - 1
			
			getLimitingFactor = document.querySelector(addItNow + " [name=\"maxtasks\"]")
			getLimitingFactorN = document.querySelector(addItNow + " [name=\"maxtasksN\"]")
			getLimitingFactorV = getLimitingFactor.getAttribute('value') 
			
			newLimitU = Math.min(ACROSS.getTestQuestions(), 1000)
			newLimitL = Math.min(newLimitU, 1)
			newValue = Math.min(Math.max(getLimitingFactorV, newLimitL), newLimitU)
			getLimitingFactor.setAttribute('max', newLimitU)
			getLimitingFactor.setAttribute('min', newLimitL)
			getLimitingFactor.setAttribute('value', newValue)
			
			getLimitingFactorN.setAttribute('max', newLimitU)
			getLimitingFactorN.setAttribute('min', newLimitL)
			getLimitingFactorN.setAttribute('value', newValue)
			
			getLimitingFactor2 = document.querySelector(addItNow + " [name=\"quantity\"]")
			getLimitingFactor2N = document.querySelector(addItNow + " [name=\"quantityN\"]")
			getLimitingFactor2V = getLimitingFactor2.getAttribute('value') 
			
			newLimit2U = Math.min(getLimitingFactor0V, 10)
			newLimit2L = Math.min(newLimit2U, 2)
			newValue2 = Math.min(Math.max(getLimitingFactor2V, newLimit2L), newLimit2U)

			getLimitingFactor2.setAttribute('max', newLimit2U)
			getLimitingFactor2.setAttribute('min', newLimit2L)
			getLimitingFactor2.setAttribute('value', newValue2)
			
			getLimitingFactor2N.setAttribute('max', newLimit2U)
			getLimitingFactor2N.setAttribute('min', newLimit2L)
			getLimitingFactor2N.setAttribute('value', newValue2)
			
		}
		
		this.updateRatioRestrictions = function(){
			getSource = event.target
			getSrcName = getSource.getAttribute('name').split('N').join('')
			
			xarray = []
			xarray['quantity'] = "maxtasks"
			xarray['maxtasks'] = "quantity"
			
			while (getSource.getAttribute('name') != "abacorpo"){
				getSource = getSource.parentNode
			}
			
			addPrefix = []
			addPrefix[false] = "#edittask"
			addPrefix[true] = "#createTaskGUI"
			addItNow = addPrefix[(getSource.id == "createTaskGUI")]
			
			sourceRestrictions = document.querySelector(addItNow + " [name=\"" + getSrcName + "\"]")
			sourceRestrictionsN = document.querySelector(addItNow + " [name=\"" + getSrcName + "N\"]")

			targetRestrictions = document.querySelector(addItNow + " [name=\"" + xarray[getSrcName] + "\"]")
			targetRestrictionsN = document.querySelector(addItNow + " [name=\"" + xarray[getSrcName] + "N\"]")			
			targetRestrictionsV = sourceRestrictions.value
			
			sourceAR = null
			sourceARN = null
			targetAR = null
			targetARN = null

			if (getSrcName.indexOf("maxtasks") != -1){
				targetAR = sourceRestrictions
				targetARN = sourceRestrictionsN
				sourceAR = targetRestrictions
				sourceARN = targetRestrictionsN
			} else {
				targetAR = targetRestrictions
				targetARN = targetRestrictionsN
				sourceAR = sourceRestrictions
				sourceARN = sourceRestrictionsN
			}
			
			loadRest = []
			loadRest['maxtasks'] = 1
			loadRest['quantity'] = 2

			rLen = document.querySelector(addItNow + ' #dataInputTable').rows.length - 1
			newLimit = Math.floor(rLen/Math.max(loadRest[getSrcName], sourceRestrictions.value))
			
			targetRestrictions.setAttribute('max', newLimit)
			targetRestrictionsN.setAttribute('max', newLimit)
			targetRestrictions.value = Math.min(targetRestrictionsV, newLimit)
			targetRestrictionsN.value = Math.min(targetRestrictionsV, newLimit)

			newLimit2U = targetAR.getAttribute('max')
			newLimit2U = Math.min(ACROSS.getTestQuestions(), newLimit2U)
			newLimit2V = targetAR.value

			targetAR.setAttribute('max', newLimit2U)
			targetARN.setAttribute('max', newLimit2U)
			targetAR.value = Math.min(newLimit2V, newLimit2U)
			targetARN.value =  Math.min(newLimit2V, newLimit2U)
			
			newLimit3L = Math.min(rLen, 2)
			
			sourceAR.setAttribute('min', newLimit3L)
			sourceARN.setAttribute('min', newLimit3L)
			sourceAR.value = Math.max(sourceAR.value, newLimit3L)
			sourceARN.value = Math.max(sourceAR.value, newLimit3L)
		}
		
		this.createTask = function(){
			getSource = event.target
		
			while (getSource.getAttribute('name') != "abacorpo"){
				getSource = getSource.parentNode
			}
			
			addPrefix = []
			addPrefix[false] = "#edittask"
			addPrefix[true] = "#createTaskGUI"
			
			addPrefix2 = []
			addPrefix2[false] = "edit"
			addPrefix2[true] = "create"
	
			addItNow = addPrefix[(getSource.id == "createTaskGUI")]
			
			
			getData  = encodeURIComponent(document.querySelector(addItNow + " #desc").value)
			if (document.querySelector(addItNow + " #creativeMode").checked){
				getData += "&data2=" + encodeURIComponent(document.querySelector(addItNow + " #desc2").value)
			}
			
			getData += "&title=" + encodeURIComponent(document.querySelector(addItNow + " #title").value)
			if (document.querySelector(addItNow + " #creativeMode").checked){
				getData += "&title2=" + encodeURIComponent(document.querySelector(addItNow + " #title2").value)
			}
			
			getData += "&tip=" + encodeURIComponent(document.querySelector(addItNow + " #tip").value)
			if (document.querySelector(addItNow + " #creativeMode").checked){
				getData += "&tip2=" + encodeURIComponent(document.querySelector(addItNow + " #tip2").value)
			}
			
			
			regular = []
			gold = []
			
			getData += "&datatype=" + ACROSS.getDataType()
			getData += "&quantity=" +  document.querySelector(addItNow + " [name=\"quantity\"]").value
			getData += "&maxtasks=" +  document.querySelector(addItNow + " [name=\"maxtasks\"]").value
			getData += "&mintrust=" +  document.querySelector(addItNow + " [name=\"accuracy\"]").value
			getData += "&bestanswers=" +  document.querySelector(addItNow + " [name=\"bestanswers\"]").value
			
			for (i = 1; i < document.querySelector(addItNow + ' #dataInputTable').rows.length; i++){
				currentRow = document.querySelector(addItNow + ' #dataInputTable').rows
				if (!(currentRow[i].cells[2].children[0].checked)){
					regular.push(ACROSS.getMediaSrc(currentRow[i]))
				} else {
					dataTriplet = []
					getCLen = currentRow[i].cells.length
					dataTriplet.push(ACROSS.getMediaSrc(currentRow[i]))
					dataTriplet.push(encodeURIComponent(currentRow[i].cells[getCLen-3].children[0].value))
					dataTriplet.push(encodeURIComponent(currentRow[i].cells[getCLen-2].children[0].value))
					gold.push(dataTriplet)
				}
			}
			
			getPOSTData = ""
			
			getPOSTData = regular.join("|")
			
			for (i = 0; i < gold.length; i++){
				gold[i] = gold[i].join(":")
			}
			
			getPOSTData += "*" + gold.join("|")
			
			taskCreatedBox = document.querySelector(addItNow + " #taskCreated")
			taskCreatedBox.style.display = "block"
			submitAPC = new AjaxPoweredContainer(taskCreatedBox)
			
			submitAPC.getDados(addPrefix2[(getSource.id == "createTaskGUI")] + "Task", getData, 'data=' + encodeURIComponent(getPOSTData), undefined, MTGUIAjax.getDados("manageTask"))
		}
		
		this.setAcceptableFileFormats = function(addItNow){
			if (!(addItNow instanceof String)){
				getSource = undefined
				
				if (addItNow == undefined){
					getSource = event.target
				} else if (typeof(addItNow) == "object") {
					getSource = addItNow
				}
			
				while (getSource.getAttribute('name') != "abacorpo"){
					getSource = getSource.parentNode
				}
				
				addPrefix = []
				addPrefix[false] = "#edittask"
				addPrefix[true] = "#createTaskGUI"
				addItNow = addPrefix[(getSource.id == "createTaskGUI")]
			}
			
			acceptFormats = []
			acceptFormats.push("image/gif, image/jpeg, image/png")
			acceptFormats.push("audio/aiff, audio/x-aiff, audio/mpeg3, audio/x-mpeg-3, audio/mp4, audio/ogg, audio/x-ms-wma")
			acceptFormats.push("video/x-ms-wmv, video/mp4, video/x-flv, video/x-f4v, video/quicktime, video/x-msvideo, video/3gpp")
			
			document.querySelector(addItNow + " INPUT[type=\"file\"]").setAttribute("accept", acceptFormats[ACROSS.getDataType(addItNow)])
			mxfisz = document.querySelector(addItNow + " #maxfilesize")
			mxfisz.textContent = "(Máximo " + ACROSS.getSizeHumanReadable(ACROSS.getUpperFileSizeBound(ACROSS.getDataType(addItNow))) + " cada arquivo)"
			mxfisz.style.display = "inline"
		}
		
		
		this.getSizeHumanReadable = function(fsize){
			var valueOfCalcuStd = []
			valueOfCalcuStd['SI'] = 1000
			valueOfCalcuStd['IEC'] = 1024

			byteLevelNames = []
			byteLevelNames['IEC'] = ["Bytes","KiB","MiB","GiB","TiB","PiB","EiB","ZiB","YiB"]
			byteLevelNames['SI'] = ["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"]
			byteLevelNames['SIext'] = ["Bytes","kilobytes","megabytes","gigabytes","terabytes","petabytes","exabytes","zettabytes","yottabytes"]
			byteLevelNames['IECext'] = ["Bytes","kibibytes","megabibytes","gigabytes","terabibytes","petabibytes","exabibytes","zettabibytes","yottabibytes"]

			byteLevel = 0
			
			meth = 'SI'

			for (c=0;c<1;c++){
				if (fsize >= valueOfCalcuStd[meth]){
					fsize = fsize/valueOfCalcuStd[meth]
					byteLevel++
					c = -1
				} else break
			}

			if (byteLevel > 0){
				fsize = parseFloat(fsize)
				fsize = new String(fsize)
				fsize = fsize.substring(0, fsize.indexOf('.')+3)
				fsize = fsize.fullReplace('.',',')
			}

			if (fsize < 2 && meth.indexOf('ext') != -1) byteLevelNames[meth][byteLevel].deleteLastChar()

			return fsize + " " + byteLevelNames[meth][byteLevel]
		}
		
		this.deleteTask = function(idtask){
			deleteAPC = new AjaxPoweredContainer({innerHTML: ""})
			deleteAPC.getDados("deleteTask", idtask, undefined, MTGUIAjax.getDados("manageTask"))
		}
		
	
		this.loadPartResults = function(idTask){
			partAPC = new AjaxPoweredContainer(document.querySelector("#partresults"))
			partAPC.getDados('loadTaskPartRes', idTask)
			document.querySelector('#partresults').style.display = "inline-block"
			document.querySelector('#crowdcontributions').style.display = "none"
			document.querySelector('#edittask').style.display = "none"
			document.querySelector('#crowdcontribsingle').style.display = "none"
		}

		this.showInstructions = function(msg){
			selectIt = document.querySelector(".modal")
			selectIt.children[0].textContent = msg
			selectIt.style.display = "inline"
		}
		
		
		this.hideInstructions = function(){
			selectIt = document.querySelector(".modal")
			selectIt.style.display = "none"
		}
		
		this.updateTask = function(){
		}
		
		
	})()
	
</script>

</head>
<body>
<?php
	include("include/header.php");
?>

<script src="js/tab.js"></script>

<?php
	echo "<div class=\"headerOverlay\">";
	echo "<h1>Painel administrativo</h1>";
	echo "<nav class=\"adminTabs\">";
	$abas = array("Criar tarefa", "Gerenciar tarefa", "Ler comentários");
	for ($i = 0; $i < count($abas); $i++){
		$isActive = "";
		if ($i == 0) $isActive = "active";
		
		echo "<a class=\"" . $isActive . "\" name=\"abacabeca\" onclick=\"setTab('abacabeca', 'abacorpo'," . $i . ")\">";
		echo $abas[$i];
		echo "</a>";
		
	}
	echo "</nav>";
	echo "</div>";
	
	echo "<div id=\"deleteOutput\"></div>";
	
	echo "<div name=\"abacorpo\" class=\"admincnt\" style=\"display: block;\" id=\"createTaskGUI\">";
	echo "</div>";

	echo "<div name=\"abacorpo\" class=\"admincnt\" style=\"display: none;\">";
	echo "<div id=\"monitorTaskGUI\" class=\"admincnt\" style=\"display: inline-block;\"></div>";
	echo "<div id=\"crowdcontributions\" style=\"display: inline-block;\"></div>";
	echo "<div id=\"crowdcontribsingle\" style=\"display: inline-block;\"></div>";
	echo "<div id=\"partresults\" style=\"display: inline-block;\"></div>";
	echo "<div id=\"edittask\" style=\"display: inline-block;\"></div>";
	echo "</div>";	

	echo "<div name=\"abacorpo\" style=\"display: none;\" class=\"admincnt\">";
	include("adminpanelComments.php");
	echo "</div>";
	
?>

<?php
	include("include/footer.php");
?>

<script>
	var CTGUIAjax = new AjaxPoweredContainer(document.querySelector("#createTaskGUI"))
	CTGUIAjax.getDados("createTaskGUI")

	var MTGUIAjax = new AjaxPoweredContainer(document.querySelector("#monitorTaskGUI"))
	MTGUIAjax.getDados("manageTask")
	

	window.onbeforeunload = function(){
		if (document.querySelector('#createTaskGUI #dataInputTable').rows.length > 1){
	    	return "Há dados carregados não salvos. Ao sair desta página, estes dados serão perdidos. Tem certeza que quer sair da página?";
		}
	}
	
	
</script>

<div class="modal" style="display: none" onclick="ACROSS.hideInstructions();">
<div></div>
</div>


</body>
</html>
