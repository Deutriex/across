var Mobile = {
	tooglePradoMenu: function(){
		if (document.querySelector('.pradomenu').style.display == "none"){
			document.querySelector('.pradomenu').style.display = "inline"
			document.querySelector('.pradobtn').className = "pradobtn pradoactive"
		} else {
			document.querySelector('.pradomenu').style.display = "none"
			document.querySelector('.pradobtn').className = "pradobtn"
		}
	}
}