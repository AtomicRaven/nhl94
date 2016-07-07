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

		$user = chkpass($username, $password); 
		if ($user !== FALSE) {
				
				$currentUser = $user["Alias"];

				if ( $currentUser == 'AquaLizard' || $currentUser == 'AtomicRaven' ) {
					$_SESSION['username'] = $currentUser;
					header('Location: manage.php');
				}
				else {
					header('Location: index.php?m=1');
				}
		}
		else {
			header('Location: index.php?m=1');
		}
		
		/*********************************************************************/
		function chkpass($userid, $pwd){

			// Retrieve password

			$conn = $GLOBALS['$conn'];

			$uq = "SELECT * FROM Users WHERE Alias = '$userid' LIMIT 1";

			$ur = mysqli_query($conn, $uq);

			if(mysqli_num_rows($ur)){
				$urow = mysqli_fetch_array($ur, MYSQL_ASSOC);
				if($pwd == $urow['Password'])
					return $urow;
				else
					return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE
			}

			return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE
			
		}  // end of function
		
?>