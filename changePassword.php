<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
        require_once("./reg/include/membersite_config.php");

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

if(isset($_POST['submitted']))
{
   if($fgmembersite->ChangePassword())
   {
        $fgmembersite->RedirectToURL("./reg/changed-pwd.html");
   }
}

if ($LOGGED_IN == true) {
?><!DOCTYPE HTML>
<html>
<head>
<title>Change Password</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>

</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">				

					<h1>Change Password</h1>

					<h2 style="color:red;"><?php echo $fgmembersite->GetErrorMessage(); ?></h2>
					<div id="msg" style="color:red;"></div><br/>

					<form id='changepwd' name='changepwd' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>

							<input type='hidden' name='submitted' id='submitted' value='1'/>
							<input type='hidden' class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />
							<label>Old Password</label><br>
							<input type="text" id="oldpwd" name="oldpwd" maxlength="50" value=""><br>
							<label>New Password</label><br>
							<input type="text" id="newpwd" name="newpwd" maxlength="50" value=""><br>
					
							<button id="submitBtn" type="button" onclick="SubmitChangePWDForm()" style="margin-top: 10px;">SUBMIT</button>
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
				header('Location: ../index.php');
		}	
?>