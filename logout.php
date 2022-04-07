<?php

		include_once './_INCLUDES/00_SETUP.php';

		session_start();
		// make user name = '' to end sessions
		$_SESSION['username'] = '';
		$LOGGED_IN = false;
		session_destroy();
		setcookie("loginCredentials", "", time() - 3600); // "Expires" 1 hour ago

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