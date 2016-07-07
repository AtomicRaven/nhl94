<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';
		include_once './_INCLUDES/data.php';		


		if ($ADMIN_LOGGED_IN == true) {

			$result = GetTeams();

			$selectBox = "<select id='HomeTeam'>";
			$selectBox2 = "<select id='AwayTeam'>";
									
			while($row = mysqli_fetch_array($result)){
				$selectBox2 = $selectBox .= "<option value='" . $row['Team_ID'] . "'>" . $row['Name'] . "</option>";
			}	

			$selectBox .= "</select>";
			
			
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
									<td>&nbsp;</td>
									<td><label>visitor</label></td>
								</tr>			
								<tr class="normal">
									<td>
										<?= $selectBox ?>			
									</td>
									<td>&nbsp;vs&nbsp;</td>
									<td>
										<?= $selectBox2 ?>
									</td>
								</tr>	
							</table>

							<table class="tight">
								<tr class="normal">
									<td><label>series name</label></td>
								</tr>
								<tr class="normal">
									<td>
										<input type="text" name="series_name" style="min-width: 250px;" value=""><br>
							
										<button id="submit" style="margin-top: 10px;">SUBMIT</button>
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