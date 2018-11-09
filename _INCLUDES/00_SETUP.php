<?php
		
		include_once './_INCLUDES/dbconnect.php';

		// common code
		$stripe[0] = '';
		$stripe[1] = ' stripe'; // odd


		// logged in stuff
		$LOGGED_IN = false;
		$leagueType = 1;
		$nameFilter = ["Forward","Goaltender", "Defenseman"];
		

		//Check for previous login

		if(isset($_COOKIE["loginCredentials"])) {			
			$user = unserialize($_COOKIE["loginCredentials"]);
			SetUser($user);
		}		

		if (isset($_SESSION['username'])) {		
				if ($_SESSION['username'] != '' ) {
					$LOGGED_IN = true;
				}
		}

		//Set cookie for League

		if(isset($_COOKIE["leagueType"])) {
			$leagueType = $_COOKIE["leagueType"];
		}	

		if (isset($_GET["leagueType"])){

			$leagueType = $_GET["leagueType"];
			setcookie("leagueType",$leagueType,time() + (10 * 365 * 24 * 60 * 60));

		}		
		
		
	
?>