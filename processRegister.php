<?php
		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';
		
		// custom code

        $fullname = '';
		$username='';
        $email = '';
		$password = '';      

        if (isset($_POST["fullname"]) && !empty($_POST["fullname"])) {
				$username =  $_POST["fullname"];
		}

		if (isset($_POST["username"]) && !empty($_POST["username"])) {
				$username =  $_POST["username"];
		}

        if (isset($_POST["email"]) && !empty($_POST["email"])) {
				$username =  $_POST["email"];
		}
		
		if (isset($_POST["password"]) && !empty($_POST["password"])) {
				$password =  $_POST["password"];
		}

		$user = ChkPass($username, $password); 

		$_SESSION['Admin'] = false;	
		
?>