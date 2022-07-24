<?php
	session_start();
	include("include/databaseConnection.php");

	if (isSet($_GET["action"]) && $_GET["action"] == "logout"){
		unset($_SERVER['PHP_AUTH_USER']);
		unset($_SERVER['PHP_AUTH_PW']);
		session_destroy();
		header("Location: index.php");
		exit(1);
	} 

	$database = DatabaseConnection::getDBObject();


	if (!mysqli_connect_errno($database)){	
		$result = mysqli_query($database,"SELECT * FROM users WHERE username = '" . addslashes($_POST["username"]) . "';");
		$getPassword = "";
		$getPrivilege = -1;
		$getIdNum = -1;
		$getStatus = -1;

		while ($row = mysqli_fetch_array($result)){
			$getPassword = addslashes($row['password']);
			$getPrivilege = $row['type'];
			$getIdNum = $row['idnum'];
			$getStatus = $row['status'];
		}
		
		if (file_exists('include/maintenance') && $getPrivilege != 1){
			header('Location: login.php?status=maintenance');
			exit(1);
		}
		
		if ($getStatus != 1){
			if ($getStatus == 0) header('Location: login.php?status=banned');
			if ($getStatus == 2) header('Location: login.php?status=optedout');
			exit(1);			
		}
	
		if ($getPrivilege == 2){
			$result2 = mysqli_query($database,"SELECT * FROM userretrieval WHERE userid = " . $getIdNum . ";");
			$retCode = "";
			
			while ($row2 = mysqli_fetch_array($result2)){
				$retCode = $row2['code'];
			}
			
			
			if ($_POST["password"] == $retCode){
				
				$_SESSION["user"] = $_POST["username"];
				$_SESSION["useridnum"] = $getIdNum;
				$_SESSION["userprivilege"] = $getPrivilege;
				$_SESSION["privilege"] = "user";
				
				$queryDEL = "DELETE FROM userretrieval WHERE userid=" . $getIdNum . ";";
				mysqli_query($database, $queryDEL);
				
				
				header("Location: panel.php");
				
			} else {
				header("Location: login.php?error=401");
			}
			
			
			exit(1);			
		}

		if ($getPrivilege < 2 && $getPassword == md5($_POST["password"]) || $getPrivilege == 3){
			$_SERVER['PHP_AUTH_USER'] = addslashes($_POST["username"]);

			if ($getPrivilege != 3){
				$_SERVER['PHP_AUTH_PW'] = md5($_POST["password"]);
			} else {
				$_SERVER['PHP_AUTH_PW'] = '';
			}

			$_SESSION["user"] = $_POST["username"];
			$_SESSION["useridnum"] = $getIdNum;
			$_SESSION["userprivilege"] = $getPrivilege;
			

			if ($getPrivilege != 1){
				$_SESSION["privilege"] = "user";
				header("Location: panel.php");
			} else {
				$_SESSION["privilege"] = "admin";
				header("Location: adminpanel.php");
			}
			
		} else {
			header("Location: login.php?error=401");
		}

	} else {
		echo "Erro ao conectar ao Banco de Dados";
	}
?>

