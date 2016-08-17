<?php
    
    require_once("../_INCLUDES/config.php");
    require_once("include/membersite_config.php");   

    if(isset($_GET['code']))
    {
    if($fgmembersite->ConfirmUser())
    {
            $fgmembersite->RedirectToURL("thank-you-regd.html");
    }
    }

?>
<!DOCTYPE HTML>
<html>
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Confirm Registration</title>
      <link rel="STYLESHEET" type="text/css" href="../css/default.css">
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
<div id="page">
		
				
				
				<div id="main">				
<h2>Confirm registration</h2>
<p>
Please enter the confirmation code in the box below
</p>

<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='confirm' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='get' accept-charset='UTF-8'>

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='code' >Confirmation Code: </label><br/>
    <input type='text' name='code' id='code' maxlength="50" /><br/>
    <span id='register_code_errorloc' class='error'></span>
</div>
<div class='container'>
    <button type='submit' name='Submit' value='Submit' style="margin-top: 10px;">SUBMIT</button>
</div>

</form>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("confirm");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("code","req","Please enter the confirmation code");

// ]]>
</script>
</div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->
</div>
</div>
</body>
</html>