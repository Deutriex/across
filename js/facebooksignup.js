  function statusChangeCallback(response) {
    if (response.status === 'connected') {
	  
	  if (!firstTime && document.getElementsByName('tos')[0].checked){
		  isAuth = true
	  }	
	  
	  if (!document.getElementsByName('tos')[0].checked){
		  alert('Você deve concordar com os Termos de Serviço e Política de Privacidade!')
	  }
	  	  
	  if (isAuth){
		  var uid = response.authResponse.userID;
		  var accessToken = response.authResponse.accessToken;
		  testAPI(uid);
	  }
	  
    } else if (response.status === 'not_authorized') {
      document.getElementById('status').innerHTML = 'Please log into this app.';
    } else {
      document.getElementById('status').innerHTML = 'Please log into Facebook.';
    }
  }

  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '510297445806331',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
  });


  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  function testAPI(uid) {
    FB.api('/me', function(response) {

	Across.changeFormAction('face')
	document.querySelector('INPUT[name=username]').maxlength = 250
	document.querySelector('INPUT[name=username]').value = response.name

      //document.getElementById('status').innerHTML = 'Thanks for logging in, ' + response.name + '!';
      //document.getElementById('status').innerHTML += '<br><a href="#" onclick="FB.logout()">Desautenticar</a>'	  
	  
		/* make the API call */
		FB.api("/" + uid + "/picture",
			function (response) {
			  if (response && !response.error) {
			  	localStorage.FBuserProfile = response.data.url
				document.querySelector('FORM[name=signupForm]').submit()
				
			  }
			}
		)

	  
   	}
	
	
	);
	

  }