<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		if ($ADMIN_LOGGED_IN == true) {		

			$seriesid = $_GET['series_id'];
			$series = GetSeriesById($seriesid);
			$gamesplayed = GetGamesBySeriesId($seriesid);

			$hometeam = GetTeamById($series["HomeTeamId"]);
			$awayteam = GetTeamById($series["AwayTeamId"]);

			$homeUserAlias = getUserAlias($series["H_User_ID"]);
			$awayUserAlias = getUserAlias($series["A_User_ID"]);

			$submitBtn ="<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
			$submitBtn .="<input type='hidden' name='userid' value='" . $_SESSION['userId'] . "' />"; 
			$submitBtn .="<input type='hidden' name='seriesid' value='" . $seriesid ."' />";
			$submitBtn .= "Choose file: <input type='file' name='uploadfile' />";			
			$submitBtn .= '<input type="submit" name="submit" value="Upload" />';
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Update Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h2>Update Series</h2>
					<form method="post" action="processUpdate.php" enctype="multipart/form-data">	
					
					<table class="standard">
						<tr class="heading rowSpacer">
							<td class="seriesNum mainTD">1.</td>
							<td class="seriesName mainTD"><?=$series["Name"]?></td>
							<td class="seriesDate mainTD">Created <?=$series["DateCreated"]?></td>
						</tr>
						<tr class="heading">
							<td>&nbsp;</td>
							<td class="seriesInfo mainTD" colspan="2"><b><?=$hometeam["Name"]?> ( <?= $homeUserAlias ?> )</b> vs <?=$awayteam["Name"]?> ( <?= $awayUserAlias ?> ), starting in <?=$hometeam["ABV"]?> (3-3-1)</td>
						</tr>						

						<?php 
						$i=1;
						while($row = mysqli_fetch_array($gamesplayed, MYSQL_ASSOC)){
						?>
						<tr>
							<td>&nbsp;</td>
							<td>Gm <?=$i?>. <b><?=$hometeam["ABV"]?> <?=$row["H_Score"]?></b> / <?=$awayteam["ABV"]?> <?=$row["A_Score"]?></td>
							<td><button class="square" id="submit">Game Stats</button></td>
						</tr>
						<?php 
							$i++;
						 } 
						 
						for ($x=$i; $x <= 7; $x++) {
							
							$team1 =  $hometeam["ABV"];
							$team2 = $awayteam["ABV"];
							
							switch ($x) {
								case 1:
								case 2:
								case 3:
								case 7:					
									$team1 =  $hometeam["ABV"];
									$team2 = $awayteam["ABV"];				
									break;
								case 4:
								case 5:
								case 6:
									$team2 =  $hometeam["ABV"];
									$team1 = $awayteam["ABV"];				
									break;
									
							}
						?>
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm <?=$x?>. <?=$team1?> at <?=$team2?></td>
							<td><?= $submitBtn?></td>
						</tr>						
						<?php							
						 } 
						 ?>								
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