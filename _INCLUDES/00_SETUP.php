<?php
		
		include_once './_INCLUDES/dbconnect.php';

		// common code
		$stripe[0] = '';
		$stripe[1] = ' stripe'; // odd


		// logged in stuff
		$LOGGED_IN = false;
		$leagueType = 1;
		$homeuserid = 0;
        $awayuserid = 0;
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

		//Set cookie for homeuserid
		if(isset($_COOKIE["homeuserid"])) {
			$homeuserid = $_COOKIE["homeuserid"];
		}	

		if (isset($_GET["homeUser"])){
			$homeuserid = $_GET["homeUser"];
			setcookie("homeuserid",$homeuserid,time() + (10 * 365 * 24 * 60 * 60));
		}

		//Set cookie for awayuserid
		if(isset($_COOKIE["awayuserid"])) {
			$awayuserid = $_COOKIE["awayuserid"];
		}	

		if (isset($_GET["awayUser"])){
			$awayuserid = $_GET["awayUser"];
			setcookie("awayuserid",$awayuserid,time() + (10 * 365 * 24 * 60 * 60));
		}
		
		//echo "homeuserid:" . $homeuserid ."</br>";
		//echo "awayuserid:" . $awayuserid;
		
	
?>