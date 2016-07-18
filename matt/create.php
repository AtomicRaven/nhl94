<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	


		if ($ADMIN_LOGGED_IN == true) {

			$teams = GetTeams();
			$users = GetUsers();

			$homeTeamSelectBox = "<select id='HomeTeam' name='HomeTeam'>";
			$awayTeamSelectBox = "<select id='AwayTeam' name='AwayTeam'>";

			$homeUserSelectBox = "<select id='HomeUser' name='HomeUser'>";
			$awayUserSelectBox = "<select id='AwayUser' name='AwayUser'>";
									
			while($row = mysqli_fetch_array($teams)){
				$homeTeamSelectBox .= "<option value='" . $row['Team_ID'] . "'>" . $row['Name'] . "</option>";
				$awayTeamSelectBox .= "<option value='" . $row['Team_ID'] . "'>" . $row['Name'] . "</option>";
			}	

			while($row = mysqli_fetch_array($users)){
				$homeUserSelectBox .= "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>";
				$awayUserSelectBox .= "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>";
			}	

			$homeTeamSelectBox .= "</select>";
			$awayTeamSelectBox .= "</select>";
			$homeUserSelectBox .= "</select>";
			$awayUserSelectBox .= "</select>";
			
			
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
					<form method="post" action="processCreate.php">							
					
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
									<td><label>series name</label></td>
								</tr>
								<tr class="normal">
									<td>
										<input type="text" name="series_name" style="min-width: 250px;" value="Test"><br>
							
										<button id="submit" type="submit" style="margin-top: 10px;">SUBMIT</button>
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