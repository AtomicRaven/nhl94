<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
        require_once("./reg/include/membersite_config.php");

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

//if ($LOGGED_IN == true) {
if (true) {
?><!DOCTYPE HTML>
<html>
<head>
<title>Useful Stuff</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>

</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">				

					<h1>Links</h1>
                    <ul style="list-style-type:disc">						
						<li>
                            <a href="http://forum.nhl94.com/index.php?/topic/17950-draft-thread/#comment-166284" target="_blank">Et tu, Brute? Draft Forum</a>                        
						</li>					
						<li>
                            <a href="https://docs.google.com/spreadsheets/d/1agkB8JJX74P_umcYpvWYAVzgAunG0cQIWQDv__3ood8/pubhtml" target="_blank">Et tu, Brute? Draft Sheet</a>                        
						</li>
                        <li>
                            <a href="https://docs.google.com/spreadsheets/d/1h7B96W0vYYefgAlfhUTQz2x5LgVfYX-KgBQ0_8sxoEo/edit#gid=0" target="_blank">GDL 16 Draft Sheet</a>
                        </li>
                        <li>
                            <a href="https://docs.google.com/spreadsheets/d/1UcRu8RP7YzvoZmu35CzrqoGztCYQMD7jwgU1PFLSjAY/pubhtml/sheet?headers=false&gid=0" target="_blank">Player Appendix</a>
                        </li>
                    </ul>
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