<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	


		if ($LOGGED_IN == true) {

			// Get Data to populate select boxes from DB			
			$tournamentTypeSelectBox = CreateSelectBox("tournamentType", "-- Select Tournament Type --", GetTournamentTypes(), "ID", "Name", null, null);			
			$leagueTypeSelectBox = CreateSelectBox("leagueType", "-- Select League --", GetLeagueTypes(), "LeagueID", "Name", null, null);
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Create Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h2>Tournament Bracket Generator</h2>
					<div id="msg" style="color:red;"></div>
					<form name="seriesForm" method="post" action="processCreateTournament.php">							
					
							<table class="tight">
								<tr>
									<td><label>Tournament Name: </label></td>
									<td><input type="text" id="tournamentName" name="tournamentName" style="min-width: 250px;"></td>									
								</tr>					
								<tr>
									<td><label>Tournament Type: </label></td>									
									<td><?= $tournamentTypeSelectBox?></td>									
								</tr>	
								<tr>
									<td><label>Number of Participants: </label></td>
									<td>
										<select id="bracketSize" name="bracketSize">
											<option value = -1>-- Please Select --</option>
											<option value="8">8</option>
											<option value="16">16</option>
											<option value="32">32</option>
										</select>
									</td>			
								</tr>					
								<tr>
									<td><label>League:</label></td>
									<td><?= $leagueTypeSelectBox?></td>
								</tr>
								<tr>
									<td><label>Start Date:</label></td>
									<td><input type="text" id="startDate" name="startDate"></td>
								</tr>
							</table>							
							<table class="tight">								
								<tr class="normal">
									<td><button id="submitBtn" type="button" onclick="SubmitTournament()" style="margin-top: 10px;">SUBMIT</button></td>
								</tr>						
							</table>
					</form>					
				</div>	
		
		</div><!-- end: #page -->	
		


  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#startDate" ).datepicker({dateFormat: 'yy-mm-dd'});
  } );
  </script>
<script src="./js/default.js"></script>

</body>
</html>
<?php
		}
		else {
				header('Location: index.php');
		}	
?>	