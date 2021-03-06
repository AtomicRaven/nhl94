<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	


		if ($LOGGED_IN == true) {

			// Get Data to populate select boxes from DB
			
			$homeUserSelectBox = CreateSelectBox("homeUser", "Select Home User", GetUsers(true), "id_user", "username", "UpdateSeriesName()", null);
			$awayUserSelectBox = CreateSelectBox("awayUser", "Select Away User", GetUsers(true), "id_user", "username", "UpdateSeriesName()", null);
			$seriesTypeSelectBox = CreateSelectBox("seriesType", null, GetSeriesTypes(), "SeriesID", "Description", null, null);			
			$leagueTypeSelectBox = CreateSelectBox("leagueType", null, GetLeagueTypes(), "LeagueID", "Name", null, null);
			
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
				
					<h2>Create Series</h2>
					<div id="msg" style="color:red;"></div>
					<form name="seriesForm" method="post" action="processCreate.php">							
					
							<table class="tight">
								<tr>
									<td><label>bin</label></td>
								</tr>
								<tr class="normal">
									<td>
										<?= $leagueTypeSelectBox?> 		
									</td>
								</tr>
								<tr class="normal">
									<td><label>home</label></td>
								</tr>			
								<tr class="normal">
									<td>
										<?= $homeUserSelectBox?> 		
									</td>
								</tr>
								<tr>
									<td><label>visitor</label></td>
								</tr>	
								<tr>
									<td>
										<?= $awayUserSelectBox?>
									</td>
								</tr>
							</table>
							<table class="tight">
								<tr class="normal">
									<!--<td><label>series type</label></td>-->
									<td><label>number of games</label></td>
								</tr>					
									
								<tr class="normal">
									<!--<td>
										<?= $seriesTypeSelectBox?>
									</td>-->
									<input type="hidden" id='seriesType' name='seriesType' value='1'/>
									<td>
										<input type="radio" name="numGames" value="1">1 &nbsp;
										<input type="radio" name="numGames" value="3">3 &nbsp;
										<input type="radio" name="numGames" value="5">5 &nbsp;
										<input type="radio" name="numGames" value="7" checked>7 &nbsp;
									</td>
								</tr>						
							</table>
							<table class="tight">
								<tr class="normal">
									<td><label>series name</label></td>
								</tr>
								<tr class="normal">
									<td>
										<input type="text" id="seriesName" name="seriesName" style="min-width: 450px;"><br>
							
										<button id="submitBtn" type="button" onclick="SubmitForm()" style="margin-top: 10px;">SUBMIT</button>
									</td>
								</tr>						
							</table>
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