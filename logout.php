<?php

		session_start();
		include_once './_INCLUDES/00_SETUP.php';

		$_SESSION = [];
		$LOGGED_IN = false;

		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}

		if (session_status() === PHP_SESSION_ACTIVE) {
			@session_destroy();
		}

		setcookie("loginCredentials", "", time() - 3600);
		setcookie("loginCredentials", "", time() - 3600, "/");

?><!DOCTYPE HTML>
<html>
<head>
<title>Logout</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
				
					<h1>Session Ended</h1>
					<p class="message">You have successfully logged out.</p>
					
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>

</body>
</html>
