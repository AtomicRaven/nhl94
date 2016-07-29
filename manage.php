<?php

		session_start();
		include_once './_INCLUDES/00_SETUP.php';

		if ($LOGGED_IN == true) {
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Manage Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h2>Manage Series</h2>
					
					<table class="standard">
						<tr class="">
							<td class=""><a href="./create.php" class="square-button">Create</a></td>
							<td class="">Create a new series</td>
						</tr>
						<tr class="">
							<td class=""><a href="./update.php" class="square-button">Update</a></td>
							<td class="">Update existing series</td>
						</tr>
					</table>
					
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