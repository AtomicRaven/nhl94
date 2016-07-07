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

		if ( $password == '111') {
				if ( $username == 'rob' || $username == 'matt' ) {
					$_SESSION['username'] = $username;
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