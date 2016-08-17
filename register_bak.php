<?PHP
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

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Register</title>       
    <link rel="stylesheet" type="text/css" href="./css/default.css" />

</head>
<body onload="RegisterInit()">
<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">				
                    <!-- Form Code Start -->
                    <div id='fg_membersite'>
                    <form id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
                    <fieldset >
                    <legend>Register</legend>

                    <input type='hidden' name='submitted' id='submitted' value='1'/>

                    <div class='short_explanation'>* required fields</div>
                    <input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

                    <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
                    <div class='container'>
                        <label for='name' >Your Full Name*: </label><br/>
                        <input type='text' name='name' id='name' value='<?php echo $fgmembersite->SafeDisplay('name') ?>' maxlength="50" /><br/>
                        <span id='register_name_errorloc' class='error'></span>
                    </div>
                    <div class='container'>
                        <label for='email' >Email Address*:</label><br/>
                        <input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
                        <span id='register_email_errorloc' class='error'></span>
                    </div>
                    <div class='container'>
                        <label for='username' >UserName*:</label><br/>
                        <input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
                        <span id='register_username_errorloc' class='error'></span>
                    </div>
                    <div class='container' style='height:80px;'>
                        <label for='password' >Password*:</label><br/>
                        <div class='pwdwidgetdiv' id='thepwddiv' ></div>
                        <noscript>
                        <input type='password' name='password' id='password' maxlength="50" />
                        </noscript>    
                        <div id='register_password_errorloc' class='error' style='clear:both'></div>
                    </div>

                    <div class='container'>
                        <button type='submit' name='Submit' style="margin-top: 10px;">SUBMIT</button>
                    </div>

                    </fieldset>
                    </form>                   
                    <!--
                    Form Code End (see html-form-guide.com for more info.)
                    -->
                    </div>	
                
    </div><!-- end: #page -->	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    <script src="./js/default.js"></script>
    <script type='text/javascript' src='./reg/scripts/gen_validatorv31.js'></script>
    <script src="./reg/scripts/pwdwidget.js" type="text/javascript"></script>
</body>
</html>