<?php
		
		$LOGGED_IN = false;
		
		if (isset($_SESSION['username'])) {		
				if ($_SESSION['username'] != '' ) {
					$LOGGED_IN = true;
				}
		}
	
?>