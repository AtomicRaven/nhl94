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
<title>Register</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
				

					<h1>Register</h1>
					<h2><?php print $message; ?></h2>
					<div id="msg" style="color:red;"></div><br/>
					<div id="msg2" style="color:red;"></div>
					<form id="registerForm" name="registerForm" method="post" action="processRegister.php">
							<label>full name</label><br>
							<input type="text" id="fullname" name="fullname" value=""><br>
							<label>username</label><br>
							<input type="text" id="username" name="username" value=""><br>
							<label>email</label><br>
							<input type="text" id="email" name="email" value=""><br>
							<label>password</label><br>
							<input type="password" id="password" name="password" value=""><br>
							<label>confirm password</label><br>
							<input type="password" id="confirmPassword" name="confirmPassword" value=""><br>
					
							<button id="submitBtn" type="button" onclick="SubmitRegisterForm()" style="margin-top: 10px;">SUBMIT</button>
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