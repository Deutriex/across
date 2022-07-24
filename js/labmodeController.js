var LabMode = {
	validate: function(){
		labmodeAPC = new AjaxPoweredContainer(document.querySelector('#labmodeStatus'))
		labmodeAPC.getDados('setLabMode', document.querySelector('#labmodeCode').value)
	},

	hidebox: function(){
		if (localStorage.noLabMode == "true"){
			document.querySelector('.labmodeContainer').style.display = "none"
		}
	}
}