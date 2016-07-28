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

		if ($user !== FALSE) {
				
				$currentUser = $user["Alias"];
				$_SESSION['username'] = $currentUser;
				$_SESSION['userId'] = $user["ID"];

				if ( $currentUser == 'AquaLizard' || $currentUser == 'AtomicRaven' ) {
					
					header('Location: manage.php');
				}
				else {
					header('Location: index.php?m=1');
				}
		}
		else {
			header('Location: index.php?m=1');
		}
		
		
?>