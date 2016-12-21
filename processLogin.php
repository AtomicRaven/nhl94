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
				
				//echo $user["username"];
				$_SESSION['username'] = $user["username"];
				$_SESSION['email_of_user'] = $user['email'];
				$_SESSION['loggedin'] = true;								
				$_SESSION['userId'] = $user['id_user'];				

				if($user['Role'] == 'admin'){
					$_SESSION['Admin'] = true;
				}

				header('Location: manage.php');
		}
		else {
			//logMsg("Uknown User");
			header('Location: index.php?m=1');
		}
		
		
?>