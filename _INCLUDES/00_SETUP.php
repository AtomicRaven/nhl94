<?php
		
		if (isset($_SESSION['username'])) {		
				if ($_SESSION['username'] != '' ) {
				$ADMIN_LOGGED_IN = true;
				}
				else {
					$ADMIN_LOGGED_IN = false;		
				}
		}
		else {
			$ADMIN_LOGGED_IN = false;
		}
	
?>