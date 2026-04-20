<?PHP
    require_once("../_INCLUDES/config.php");
    require_once("include/membersite_config.php"); 

$emailsent = false;
if(isset($_POST['submitted']))
{
   if($fgmembersite->EmailResetPasswordLink())
   {
        $fgmembersite->RedirectToURL("reset-pwd-link-sent.html");
        exit;
   }
}

?>
<!DOCTYPE HTML>
<html>
<head>
      <title>Reset Password Request</title>
            <meta name="description" content=""/>
      <meta name="keywords" content="" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width">
      <link rel="STYLESHEET" type="text/css" href="../css/default.css">
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
<div id="page">	
			
				<div id="main">				
                    <!-- Form Code Start -->
                    <div id='fg_membersite'>
                    <form id='resetreq' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
                    <fieldset >
                    <h1>Reset Password</h1>

                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
                    <div class='container'>
                        <label for='username' >Your Email:</label><br/>
                        <input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
                        <span id='resetreq_email_errorloc' class='error'></span>
                    </div>
                    <div class='short_explanation'>A link to reset your password will be sent to the email address. You should see it in about 10 minutes.</div>
                    <div class='container'>
                        <button type='submit' name='Submit' value='Submit' >Submit</button>
                    </div>

                    </fieldset>
                    </form>
                    <!-- client-side Form Validations:
                    Uses the excellent form validation script from JavaScript-coder.com-->

                    <script type='text/javascript'>
                    // <![CDATA[

                        var frmvalidator  = new Validator("resetreq");
                        frmvalidator.EnableOnPageErrorDisplay();
                        frmvalidator.EnableMsgsTogether();

                        frmvalidator.addValidation("email","req","Please provide the email address used to sign-up");
                        frmvalidator.addValidation("email","email","Please provide the email address used to sign-up");

                    // ]]>
                    </script>

                    </div>
                </div>
            </div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>