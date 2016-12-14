<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
        require_once("./reg/include/membersite_config.php");

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

if ($LOGGED_IN == true && $_SESSION['username']="Atomic") {

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

					<h1>Admin</h1>
                    <ul style="list-style-type:disc">
					
						<li>
                            <a href="duplicateTable.php" target="_blank">Import GDL</a>                        
						</li>					
						<li>
                            <a href="deleteTable.php" target="_blank">Delete GDL Table</a>                        
						</li>					
                        <li>
                            <a href="links.php" target="_blank">Links</a>
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
				header('Location: index.php');
		}	
?>