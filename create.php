<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	


		if ($ADMIN_LOGGED_IN == true) {

			// Get Data to populate select boxes from DB			
			$teams = GetTeams();
			$users = GetUsers();
			$seriesType = GetSeriesTypes();

			///////////////////////////////////////////////////////////////////////////
			//Team Select Boxes
			$homeTeamSelectBox = "<select id='homeTeam' name='homeTeam' onchange='UpdateSeriesName()'>";
			$awayTeamSelectBox = "<select id='awayTeam' name='awayTeam' onchange='UpdateSeriesName()'>";

			$homeTeamSelectBox .= "<option value='0'>Select Home Team</option>";
			$awayTeamSelectBox .= "<option value='0'>Select Away Team</option>";

			while($row = mysqli_fetch_array($teams)){
				$homeTeamSelectBox .= "<option value='" . $row['TeamID'] . "'>" . $row['Name'] . "</option>";
				$awayTeamSelectBox .= "<option value='" . $row['TeamID'] . "'>" . $row['Name'] . "</option>";
			}	

			$homeTeamSelectBox .= "</select>";
			$awayTeamSelectBox .= "</select>";

			///////////////////////////////////////////////////////////////////////////
			//User Select Boxes
			$homeUserSelectBox = "<select id='homeUser' name='homeUser' >";
			$awayUserSelectBox = "<select id='awayUser' name='awayUser' >";

			$homeUserSelectBox .= "<option value='0'>Select Home User</option>";
			$awayUserSelectBox .= "<option value='0'>Select Away User</option>";								
			
			while($row = mysqli_fetch_array($users)){
				$homeUserSelectBox .= "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>";
				$awayUserSelectBox .= "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>";
			}	
			
			$homeUserSelectBox .= "</select>";
			$awayUserSelectBox .= "</select>";

			///////////////////////////////////////////////////////////////////////////
			//Series Type Select Box
			$seriesTypeSelectBox = "<select id='seriesType' name='seriesType'>";

			//$seriesTypeSelectBox .= "<option value='0'>Select Home Team</option>";

			while($row = mysqli_fetch_array($seriesType)){
				$seriesTypeSelectBox .= "<option value='" . $row['SeriesID'] . "'>" . $row['Name'] . " | " . $row["Description"] . "</option>";
			}	

			$seriesTypeSelectBox .= "</select>";

			
			
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
								<tr class="normal">
									<td><label>home</label></td>
								</tr>			
								<tr class="normal">
									<td>
										<?= $homeUserSelectBox?> as <?= $homeTeamSelectBox ?>			
									</td>
								</tr>
								<tr>
									<td><label>visitor</label></td>
								</tr>	
								<tr>
									<td>
										<?= $awayUserSelectBox?> as <?= $awayTeamSelectBox ?>
									</td>
								</tr>
							</table>
							<table class="tight">
								<tr class="normal">
									<td><label>series type</label></td>
								</tr>
								<tr class="normal">
									<td>
										<?= $seriesTypeSelectBox?>
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