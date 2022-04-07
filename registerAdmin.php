<?php
		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/config.php';		
		

		
		if ($LOGGED_IN == true && $_SESSION['Admin'] == true && ($_SESSION['username'] == 'Atomic' || $_SESSION['username'] == 'aqua')){
			
			$msg = "";
			if (isset($_GET["msg"])){
				$msg = $_GET["msg"];
			}

?><!DOCTYPE HTML>
<html>
<head>
<title>Register a Fake User</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">				

					<h1>Register a Fake User</h1>

					<h2 style="color:red;"><?=$msg?></h2>
					<div id="msg" style="color:red;"></div><br/>
					<div id="msg2" style="color:red;"></div>

					<form id='register' name='register' action='processRegisterAdmin.php' method='post' accept-charset='UTF-8'>

							<label>username</label><br>
							<input type="text" id="username" name="username" maxlength="15" value=""><br>
							<label>email</label><br>
							<input type="text" id="email" name="email" maxlength="50" value=""><br>
							<label>password</label><br>
							<input type="password" id="password" name="password" maxlength="50" value=""><br>
							<!--<label>confirm password</label><br>
							<input type="password" id="confirmPassword" name="confirmPassword" maxlength="50" value=""><br>-->
					
							<button id="submitBtn" type="submit" style="margin-top: 10px;">SUBMIT</button>
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