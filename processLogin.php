<?php
		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';
		
		// custom code

		$username='';
		$password = '';
		
		
		if (isset($_POST["username"]) && !empty($_POST["username"])) {
				$username =  $_POST["username"];
		}
		
		if (isset($_POST["password"]) && !empty($_POST["password"])) {
				$password =  $_POST["password"];
		}

		$user = ChkPass($username, $password); 

		$_SESSION['Admin'] = false;
		
		if ($user !== FALSE) {
				
				$currentUser = $user["username"];
				
				$_SESSION['username'] = $currentUser;
				$_SESSION['email_of_user'] = $user['email'];
				$_SESSION['loggedin'] = true;					
				$_SESSION['Admin'] = true;
				$_SESSION['userId'] = $user['id_user'];
					header('Location: manage.php');
		}
		else {
			//logMsg("Uknown User");
			header('Location: index.php?m=1');
		}
		
		
?>