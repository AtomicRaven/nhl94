<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
		include_once './_INCLUDES/dbconnect.php';	

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

	$leagueTypeSelectBox = CreateSelectBox("leagueType", "Select Bin", GetLeagueTypes(), "LeagueID", "Name", null, null);

	$msg = "";
	if (isset($_GET["msg"])){
		$msg = $_GET["msg"];
	}

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
					<div style="color:red;"><?=$msg?></div>
					<form method="post" action="duplicateTable.php" enctype="multipart/form-data">
						<ul style="list-style-type:disc">
						
							<li>
								<input type="file" name="csv">
								<input type="submit" style="margin-top: 10px;" value="Import Table"/>
								<select name="blitz">
									<option value="0">Normal</option>
									<option value="1">Blitz</option>
								</select>
								
							</li>								
							<li>
								<a href="registerAdmin.php">Register Fake User</a>
							</li>
							<li>
								<a href="links.php">Links</a>
							</li>                        
						</ul>
					</form>
					
					Delete League Table: <?=$leagueTypeSelectBox?> <button id="submitBtn" onclick="DeleteTable(this)" style="margin-top: 10px;">Go</button> 						
					
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