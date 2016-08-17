<?PHP
    require_once("../_INCLUDES/config.php");
    require_once("include/membersite_config.php"); 

$success = false;
if($fgmembersite->ResetPassword())
{
    $success=true;
}

?>
<!DOCTYPE HTML>
<html>
<head>
      <title>Reset Password</title>
      <link rel="STYLESHEET" type="text/css" href="../css/default.css">
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
<div id="page">	
			
        <div id="main">				
            <div id='fg_membersite_content'>
            <?php
            if($success){
                ?>
                    <h2>Password Reset Successfully</h2>
                    Your new password was sent to your email address.  You should see it in about 10 minutes.  Go daddy - BAH!  
                <?php
                }else{
                ?>
                    <h2>Error</h2>
                    <span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span>
            <?php
            }
            ?>
            </div>
        </div>
</div>

</body>
</html>