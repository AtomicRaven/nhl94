<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		if ($ADMIN_LOGGED_IN == true) {		

			$creatednew = true;
			$seriesid = 0;

			if(isset($_GET['series_id'])){

				$seriesid = $_GET['series_id'];
				$creatednew = false;
			}

			if(!$creatednew){

				$series = GetSeriesById($seriesid);
				$gamesplayed = GetGamesBySeriesId($seriesid);

				$hometeam = GetTeamById($series["HomeTeamId"]);
				$awayteam = GetTeamById($series["AwayTeamId"]);

				$homeUserAlias = getUserAlias($series["H_User_ID"]);
				$awayUserAlias = getUserAlias($series["A_User_ID"]);

				$submitBtn ="<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
				$submitBtn .="<input type='hidden' name='userid' value='" . $_SESSION['userId'] . "' />"; 
				$submitBtn .="<input type='hidden' name='seriesid' value='" . $seriesid ."' />";						

			}else{
				
				// User wants to update an existing series so gab all the series games and show in drop down box
				$allseries = GetSeries();
				$seriesSelectBox = "<select id='Series' name='Series' onchange='LoadSeries(this.value)'>";
									
				while($row = mysqli_fetch_array($allseries)){
					$seriesSelectBox .= "<option value='" . $row['ID'] . "'>" . $row['Name'] . "  |  " . $row['DateCreated']. "</option>";					
				}	

				$seriesSelectBox .= "</select>";
				
				echo $seriesSelectBox;
			}

			$fileInput = "Choose file: <input type='file' name='uploadfile' />";			
			$fileInput .= "<input type='submit' name='submit' value='Upload' />";
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Update Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>

			<script>
			var fileInputBox = "<?= $fileInput ?>"

			function UploadFile(gameNum){

				var fileInputDiv = $("#fileInput" + gameNum);

				fileInputDiv.html(fileInputBox);
				fileInputDiv.show();					

			}

			function LoadSeries(seriesId){

				document.location.href = "update.php?series_id=" + seriesId;
				
			}
			</script>

</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h2>Update Series</h2> 
					<?php
					if(!$creatednew){
					?>
					<form method="post" action="processUpdate.php" enctype="multipart/form-data">	
					<?= $submitBtn?>
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
							<td><button class="square" id="submit<?=$i?>">Game Stats</button></td>
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
							<td><button type="button" class='square' id='submit<?=$x?>' onclick="UploadFile('<?= $x?>')">Upload File</button></td>
						</tr>		
						<tr >
						<td colspan="3" id="fileInput<?= $x?>" style="display:none;">
							
						</td>						
						</tr>				
						<?php							
						 } 
						 ?>								
					</table>
					</form>	
					<?php						 
					 }else{
						 echo $seriesSelectBox;
					 }
					  ?>
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