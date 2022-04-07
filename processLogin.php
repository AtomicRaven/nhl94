<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';		
		
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
				
			SetUser($user);
			header('Location: create.php');
		}
		else {
			header('Location: login.php?m=1');
		}
		
		
?>