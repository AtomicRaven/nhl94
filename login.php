<?php
		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		
		// custom code
		
		if (isset($_GET["m"])) {
				$message = '<p class="message">Wrong username/password combo, son:</p>';
		}
		else {
				$message = '<p class="message">Enter credentials below:</p>';
		}
		
		if ($LOGGED_IN == false) {
	
?><!DOCTYPE HTML>
<html>
<head>
<title>NHL94Rocks Game Saver</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
				

					<h1>Login</h1>
					<h2><?php print $message; ?></h2>
					<form id="loginForm" name="loginForm" method="post" action="processLogin.php">
							<label>username</label><br>
							<input type="text" name="username" value=""><br>
							<label>password</label><br>
							<input type="password" name="password" value=""><br>
					
							<button id="submit">SUBMIT</button><br/><br/>
							<a href='register.php' class="link">Register</a><br/>
							<a href='reg/reset-pwd-req.php' class="link"'>Forgot Password?</a>
					</form>
					
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>

</body>
</html>
<?php
		}
		else {
				header('Location: manage.php');
		}	
?>	