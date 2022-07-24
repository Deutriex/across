<?php
		include("include/databaseConnection.php");
		session_start();
		$database = DatabaseConnection::getDBObject();

		if (!mysqli_connect_errno($database)){
			if (isset($_GET['mode'])){
				if ($_GET['mode'] == "temp" || $_GET['mode'] == "face" || $_GET['mode'] == "lab"){
					$user = "";
					$privilege = -1;
					
					if ($_GET['mode'] == "temp"){
						$user = "TMP-" . date("d-m-Y,H:i:s") . "-" . rand();
						$privilege = 2;
					}

					if ($_GET['mode'] == "face"){
						$user = $_POST['username'];
						$privilege = 3;
					}
					
					if ($_GET['mode'] == "lab"){
						$user = "LAB-" . date("d-m-Y,H:i:s") . "-" . rand();
						$privilege = 4;
					}
					
					$query2 = "";
					$result2 = null;
					$row2 = null;
					
					if (!isset($_SESSION['userprivilege'])){
						$query2 = "SELECT max(idnum) as lastestUser FROM users;";
						$result2 = mysqli_query($database, $query2);
						$row2 = mysqli_fetch_array($result2);
					}

					$query = "";
					if (!isset($_SESSION['userprivilege'])){
						$query = "INSERT INTO users (username, password, type, status) VALUES ('" . $user . "', '', " . $privilege . ", 1);";
					} else {
						$query = "UPDATE users SET username='" . $user . "', password='', type=" . $privilege. " WHERE idnum=" . $_SESSION["useridnum"]  . ";";
					}
					
					$result = mysqli_query($database, $query);
				
					$_SERVER['PHP_AUTH_USER'] = $user;
					$_SERVER['PHP_AUTH_PW'] = "";

					$_SESSION["user"] = $user;
					
					if (!isset($_SESSION['userprivilege'])){
						$_SESSION["useridnum"] = $row2['lastestUser']+1;
					}
					
					$_SESSION["privilege"] = "user";
					$_SESSION["userprivilege"] = $privilege;
					header('Location: panel.php');
					exit(1);
				}
			}
		
			$result = mysqli_query($database,"SELECT * FROM users WHERE username = '" . addslashes($_POST["username"]) . "';");
			$thereIsUser = false;

			while ($row = mysqli_fetch_array($result)) {
				$thereIsUser = true;
			}
			
			if ($thereIsUser){
				header("Location: signup.php?error=exuser");
				exit;
			} else {
				
				$query = "";
				
				if (!isset($_SESSION['userprivilege'])){
					$query = "INSERT INTO users (username, password, type, status) VALUES ('" . addslashes($_POST["username"]) . "', '" . md5($_POST["password"]) . "', 0, 1);";
					
				} else {
					$query = "UPDATE users SET username='" . addslashes($_POST["username"]) . "', password='" . md5($_POST["password"]) . "', type=0 WHERE idnum=" . $_SESSION['useridnum'] . ";";
				}
				
				$result = mysqli_query($database, $query);
				
				$_SERVER['PHP_AUTH_USER'] = addslashes($_POST["username"]);
				$_SERVER['PHP_AUTH_PW'] = md5($_POST["password"]);

				$_SESSION["user"] = $_POST["username"];
				if (!isset($_SESSION['userprivilege'])){
					$getIdNum = "SELECT max(idnum) as newId FROM users WHERE username='" . $_SESSION["user"] . "';";
					$resultX = mysqli_query($database, $getIdNum);
					$rowX = mysqli_fetch_array($resultX);
				
					$_SESSION["useridnum"] = $rowX["newId"];
					$_SESSION["userprivilege"] = 0;
					$_SESSION["privilege"] = "user";
				}

				
				header("Location: panel.php");
			}
		}
	
?>