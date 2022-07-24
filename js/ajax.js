var AjaxController = new (function(){
	var AjaxQueue = []
	var AjaxHash = {}
	var isBusy = false

	this.getBusyStatus = function(){
		AjaxQueue.length !== 0
	}

	this.setBusyStatus = function(newStatus){
		isBusy = newStatus
	}

	this.enqueueAjax = function(newAjaxRequest){
		AjaxQueue.unshift(newAjaxRequest)
	}

	this.dequeueAjax = function(){
		return AjaxQueue.pop()
	}

	this.firstAjaxRequest = function(){
		return AjaxQueue[AjaxQueue.length-1]
	}

	this.queueIsEmpty = function(){
		return AjaxQueue.length === 0
	}

	this.registerAjaxRequest = function(reqId, reqObj){
		AjaxHash[reqId] = reqObj
	}
	
	this.clearAjaxRequest = function(reqId){
		AjaxHash[reqId] = undefined
	}


})()

function AjaxPoweredContainer(result){
	var thisObject = this
	var requestNumber = 0
	var ajaxPage = "ajax.php"
	var xmlreq
	var async = true
	
	this.getThisObject = function(){
		return this;
	}

	this.setAjaxPage = function(newAjaxPage){
		ajaxPage = newAjaxPage
	}
	
	this.setSyncMode = function(){
		async = false
	}

	function CriaRequest() {
		 try {
			 request = new XMLHttpRequest();		
		 } catch (IEAtual) {
			 try {
				 request = new ActiveXObject("Msxml2.XMLHTTP");	   
			 } catch (IEAntigo){		 
				 try {
					 request = new ActiveXObject("Microsoft.XMLHTTP");		  
				 } catch(falha) {
					 request = null;
				 }
			 }
		}
	 
		 return request;
	}

	this.getRequestCount = function(){
		return requestNumber
	}

	this.getDados = function(getActionOrObject, getData, getPOSTData, afterRecevingAResponse, progressHandler) {
		if (typeof getActionOrObject == "object"){
			getAction = getActionOrObject.action
			getData = getActionOrObject.GETData
			getPOSTData = getActionOrObject.POSTData
			afterRecevingAResponse = getActionOrObject.callback
			progressHandler = getActionOrObject.progressHandler
		} else {
			getAction = getActionOrObject
		}
		
		if ((AjaxController && !AjaxController.getBusyStatus()) || (arguments && new String(arguments.callee.caller).indexOf('xmlreq.onreadystatechange = function(){') != -1)){
			xmlreq = CriaRequest();
			if (!xmlreq){
				alert("O seu navegador não suporta AJAX")
				return false;
			}

			result.innerHTML = "Esperando resposta do servidor...";
	
			composeURL = ajaxPage + "?action=" + getAction
			if (getData) composeURL += "&data=" + getData


			xmlreq.open("POST", composeURL, async);
			if (!(getPOSTData instanceof FormData)){
				xmlreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
			}
			
			if (progressHandler){
				xmlreq.addEventListener("progress", progressHandler, false)
			}

			if (getPOSTData){
				xmlreq.send(getPOSTData)
			} else {
				xmlreq.send(null);
			}
			

			xmlreq.onreadystatechange = function(){
				result.readyState = xmlreq.readyState
				if (xmlreq.readyState == 4) {
					if (xmlreq.status == 200) {
						result.innerHTML = xmlreq.responseText;

						if (afterRecevingAResponse){
							setTimeout(afterRecevingAResponse, 500);
						}
					} else {
						 result.innerHTML = "Erro #" + xmlreq.status;
					}
					
					
					
					if (!AjaxController.queueIsEmpty()){
						var executeAjax = AjaxController.dequeueAjax()
						if (executeAjax.arguments.length == 1) executeAjax.object.getDados(executeAjax.arguments[0])
						if (executeAjax.arguments.length == 2) executeAjax.object.getDados(executeAjax.arguments[0], executeAjax.arguments[1])
						if (executeAjax.arguments.length == 3) executeAjax.object.getDados(executeAjax.arguments[0], executeAjax.arguments[1], executeAjax.arguments[2])
						if (executeAjax.arguments.length == 4) executeAjax.object.getDados(executeAjax.arguments[0], executeAjax.arguments[1], executeAjax.arguments[2], executeAjax.arguments[3])
						
					}

					requestNumber++
				 }
			 }

		} else {
			AjaxObjectDetails = {
				object: thisObject,
				arguments: arguments			
			}

			AjaxController.enqueueAjax(AjaxObjectDetails)
			result.innerHTML = "Há uma ou mais solicitações ao servidor neste momento!";
			alert(requestNumber)
		}
		
		//return xmlreq			
	}
}	