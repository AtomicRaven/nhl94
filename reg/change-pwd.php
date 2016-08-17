<?PHP

		session_start();
		$ADMIN_PAGE = true;
		include_once '../_INCLUDES/00_SETUP.php';
        require_once("../_INCLUDES/config.php");
        require_once("include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->ChangePassword())
   {
        $fgmembersite->RedirectToURL("changed-pwd.html");
   }
}

if ($LOGGED_IN == true) {
?><!DOCTYPE HTML>
<html>
<head>
      <title>Change Password</title>
      <link rel="STYLESHEET" type="text/css" href="../css/default.css">
      <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css">

      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
      <script src="scripts/pwdwidget.js" type="text/javascript"></script>   
   
</head>
<body>
<div id="page">	
			
        <div id="main">	
        <!-- Form Code Start -->
        <div id='fg_membersite'>
        <form id='changepwd' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
        <fieldset >
        <h1>Change Password</h1>

        <input type='hidden' name='submitted' id='submitted' value='1'/>        

        <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
        <div class='container'>
            <label for='oldpwd' >Old Password:</label><br/>
            <div class='pwdwidgetdiv' id='oldpwddiv' ></div><br/>            
            <input type='password' name='oldpwd' id='oldpwd' maxlength="50" />            
            <span id='changepwd_oldpwd_errorloc' class='error'></span>
        </div>

        <div class='container'>
            <label for='newpwd' >New Password:</label><br/>
            <div class='pwdwidgetdiv' id='newpwddiv' ></div>            
            <input type='password' name='newpwd' id='newpwd' maxlength="50" /><br/>            
            <span id='changepwd_newpwd_errorloc' class='error'></span>
        </div>

        <br/><br/><br/>
        <div class='container'>
            <button type='submit' name='Submit' value='Submit' style="margin-top: 10px;">SUBMIT</button>
        </div>

        </fieldset>
        </form>
        <!-- client-side Form Validations:
        Uses the excellent form validation script from JavaScript-coder.com-->

        <script type='text/javascript'>
        // <![CDATA[
            var pwdwidget = new PasswordWidget('oldpwddiv','oldpwd');
            pwdwidget.enableGenerate = false;
            pwdwidget.enableShowStrength=false;
            pwdwidget.enableShowStrengthStr =false;
            pwdwidget.MakePWDWidget();
            
            var pwdwidget = new PasswordWidget('newpwddiv','newpwd');
            pwdwidget.MakePWDWidget();
            
            
            var frmvalidator  = new Validator("changepwd");
            frmvalidator.EnableOnPageErrorDisplay();
            frmvalidator.EnableMsgsTogether();

            frmvalidator.addValidation("oldpwd","req","Please provide your old password");
            
            frmvalidator.addValidation("newpwd","req","Please provide your new password");

        // ]]>
        </script>

        <p>
        <a href='../index.php'>Home</a>
        </p>

        </div>
        <!--
        Form Code End (see html-form-guide.com for more info.)
        -->
    </div>
</div>
 
</body>
</html>
<?php
		}
		else {
				header('Location: ../index.php');
		}	
?>