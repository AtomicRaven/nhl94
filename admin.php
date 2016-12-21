<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

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
					<form method="post" action="duplicateTable.php" enctype="multipart/form-data">
						<ul style="list-style-type:disc">
						
							<li>
								<input type="file" name="csv">
								<input type="submit" style="margin-top: 10px;" value="Import Table"/>
								
							</li>					
							<li>
								<a href="registerAdmin.php">Register Fake User</a>
							</li>
							<li>
								<a href="links.php" target="_blank">Links</a>
							</li>                        
						</ul>
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
				header('Location: index.php');
		}	
?>