<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	


		if ($ADMIN_LOGGED_IN == true) {

			$teams = GetTeams();
			$users = GetUsers();

			$homeTeamSelectBox = "<select id='HomeTeam' name='HomeTeam' onchange='UpdateSeriesName()'>";
			$awayTeamSelectBox = "<select id='AwayTeam' name='AwayTeam' onchange='UpdateSeriesName()'>";

			$homeTeamSelectBox .= "<option value='0'>Select Home Team</option>";
			$awayTeamSelectBox .= "<option value='0'>Select Away Team</option>";

			$homeUserSelectBox = "<select id='HomeUser' name='HomeUser' >";
			$awayUserSelectBox = "<select id='AwayUser' name='AwayUser' >";

			$homeUserSelectBox .= "<option value='0'>Select Home User</option>";
			$awayUserSelectBox .= "<option value='0'>Select Away User</option>";
									
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

<script>	

	function UpdateSeriesName(){

		var homeSelect = document.getElementById("HomeTeam");
		var awaySelect = document.getElementById("AwayTeam");

		var homeTeam = "";
		var awayTeam = ""; 

		if(homeSelect.options[homeSelect.selectedIndex].value != 0){

			homeTeam = homeSelect.options[homeSelect.selectedIndex].text; 
		}

		if(awaySelect.options[awaySelect.selectedIndex].value != 0){

			awayTeam = awaySelect.options[awaySelect.selectedIndex].text; 
		}

		$("#series_name").val(homeTeam + " vs " + awayTeam);
	}

	function SubmitForm(){

		var homeSelect = document.getElementById("HomeTeam");
		var awaySelect = document.getElementById("AwayTeam");

		var homeUserSelect = document.getElementById("HomeUser");
		var awayUserSelect = document.getElementById("AwayUser");

		var submit = true;
		var msgBox = $("#msg");
		var msgHtml = "Please Select the following: ";

		if(homeSelect.options[homeSelect.selectedIndex].value == 0){

			msgHtml += "Home Team, ";
			submit = false;
		}

		if(awaySelect.options[awaySelect.selectedIndex].value == 0){
			
			msgHtml += "Away Team, ";
			submit = false;
		}

		if(homeUserSelect.options[homeUserSelect.selectedIndex].value == 0){
			
			msgHtml += "Home User, ";
			submit = false;
		}

		if(awayUserSelect.options[awayUserSelect.selectedIndex].value == 0){
			
			msgHtml += "Away User, ";
			submit = false;
		}

		if(submit){

			document.seriesForm.submit();

		} else{

			msgBox.html(msgHtml.substring(0, msgHtml.length - 2) + "</br></br>");
		}


	}


</script>
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
									<td><label>series name</label></td>
								</tr>
								<tr class="normal">
									<td>
										<input type="text" id="series_name" name="series_name" style="min-width: 450px;"><br>
							
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