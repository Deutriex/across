	var Guest = {
		showLogoutWarning: function(){
			document.querySelector(".modal.guest").style.display = "inline"
			document.querySelector(".modal.guest").style.opacity = 1
		},
		
		hideLogoutWarning: function(){
			document.querySelector(".modal.guest").style.display = "none"
			document.querySelector(".modal.guest").style.opacity = 0
		},
		
		cancelEvent: function(){
			if (typeof event.stopPropagation != "undefined") {
			  event.stopPropagation();
			}
			if (typeof event.cancelBubble  != "undefined") {
			  event.cancelBubble = true;
			}
		},
		
		getRetrievalCode: function(){
			if (document.querySelector('#retrievalCodeAjaxContainer').textContent == ""){
				retrievalAPC = new AjaxPoweredContainer(document.querySelector('#retrievalCodeAjaxContainer'))
				retrievalAPC.getDados("getRetrievalCode")
			}
		},
		
		copyToken: function(){
			document.querySelector('#tokenCopyText').value = document.querySelector('#retrievalCodeAjaxContainer').textContent
			document.querySelector('#tokenCopyText').select()
		    successful = document.execCommand('copy');
			if (successful){
				alert('Código copiado com sucesso')
			} else {
				alert('Um erro ocorreu. Você terá que copiá-lo manualmente')
			}
			Guest.cancelEvent()
		},
		
		logoutFacebook: function(){
			if (document.querySelector('#logoutFacebook').checked){
				FB.logout()
			}
			location.replace('auth.php?action=logout')
		}
	}