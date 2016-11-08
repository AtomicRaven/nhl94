<?php
		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/config.php';
		require_once("./reg/include/membersite_config.php");
		include_once './_INCLUDES/00_SETUP.php';

		if(isset($_POST['submitted']))
		{
			if($fgmembersite->RegisterUser())
			{
					$fgmembersite->RedirectToURL("reg/thank-you.html");
			}
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

					<h2 style="color:red;"><?php echo $fgmembersite->GetErrorMessage(); ?></h2>
					<div id="msg" style="color:red;"></div><br/>
					<div id="msg2" style="color:red;"></div>

					<form id='register' name='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>

							<input type='hidden' name='submitted' id='submitted' value='1'/>
							<input type='hidden' class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />
							<label>name</label><br>
							<input type="text" id="name" name="name" maxlength="50" value=""><br>
							<label>username</label><br>
							<input type="text" id="username" name="username" maxlength="15" value=""><br>
							<label>email</label><br>
							<input type="text" id="email" name="email" maxlength="50" value=""><br>
							<label>password</label><br>
							<input type="password" id="password" name="password" maxlength="50" value=""><br>
							<label>confirm password</label><br>
							<input type="password" id="confirmPassword" name="confirmPassword" maxlength="50" value=""><br>
					
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