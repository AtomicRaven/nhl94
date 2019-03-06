<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	


		if ($LOGGED_IN == true) {

            $tournamentid = $_GET['tournamentId'];
            $tournament = GetTournament($tournamentid);

			// Get Data to populate select boxes from DB	
			//$leagueTypeSelectBox = CreateSelectBox("leagueType", null, GetLeagueTypes(), "LeagueID", "Name", null, null);
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Create Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>

</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h2><?= $tournament["Name"] ?></h2>
					<div id="msg" style="color:red;"></div>
								
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