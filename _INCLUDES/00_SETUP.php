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

		if (!isset($_SESSION['Admin'])) {
			$_SESSION['Admin'] = false;
		}

		if (isset($_SESSION['username'])) {		
				if ($_SESSION['username'] != '' ) {
					$LOGGED_IN = true;
				}
		}


		//Set cookie for League
		if(isset($_COOKIE["leagueType"])) {
			$leagueType = (int) $_COOKIE["leagueType"];
		}	

		if (isset($_GET["leagueType"])){
			$leagueType = (int) $_GET["leagueType"];
			setcookie("leagueType",$leagueType,time() + (10 * 365 * 24 * 60 * 60));
		}

		//Set cookie for homeuserid
		if(isset($_COOKIE["homeuserid"])) {
			$homeuserid = (int) $_COOKIE["homeuserid"];
		}	

		if (isset($_GET["homeUser"])){
			$homeuserid = (int) $_GET["homeUser"];
			setcookie("homeuserid",$homeuserid,time() + (10 * 365 * 24 * 60 * 60));
		}

		//Set cookie for awayuserid
		if(isset($_COOKIE["awayuserid"])) {
			$awayuserid = (int) $_COOKIE["awayuserid"];
		}	

		if (isset($_GET["awayUser"])){
			$awayuserid = (int) $_GET["awayUser"];
			setcookie("awayuserid",$awayuserid,time() + (10 * 365 * 24 * 60 * 60));
		}
		
		//echo "homeuserid:" . $homeuserid ."</br>";
		//echo "awayuserid:" . $awayuserid;
		
	
?>
