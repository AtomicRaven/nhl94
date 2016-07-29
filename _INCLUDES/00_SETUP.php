<?php
		
		if(isset($_SESSION['loggedin'])){

			if (isset($_SESSION['Admin'])) {		

					if ($_SESSION['Admin'] == true) {

						$ADMIN_LOGGED_IN = true;

					}
					else {
						$ADMIN_LOGGED_IN = false;				
					}
			}
			else {
				$ADMIN_LOGGED_IN = false;
			}

			$LOGGED_IN = true;
		}else{

			$LOGGED_IN = false;
		}
	
?>