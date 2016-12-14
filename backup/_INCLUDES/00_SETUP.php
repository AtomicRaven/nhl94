<?php
		
		// common code
		$stripe[0] = '';
		$stripe[1] = ' stripe'; // odd


		// logged in stuff
		$LOGGED_IN = false;
		
		if (isset($_SESSION['username'])) {		
				if ($_SESSION['username'] != '' ) {
					$LOGGED_IN = true;
				}
		}
	
?>