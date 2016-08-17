<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		if ($LOGGED_IN == true) {	

			$msg = "";
			if(isset($_GET['err'])){
				switch ($_GET['err']){
					
					case 0:
						$msg = "Game has been uploaded and submitted.";
					break;
					case 1:
						$msg = "Teams in the save state file do not match the game on the schedule.  Please try a different file.";
					break;
					case 2:
						$msg = "Password is incorrect.  Please try again.";
					break;
					case 3:
						$msg = "File is not valid.  Please choose a file that ends in .gs (Genesis) or .zs (SNES).";
					break; 
					case 4:
						$msg = "Error submitting game.  Please contact the administrator.";
					break;			
					case 5:
						$msg = "Game could not be uploaded.  Please contact the administrator.";
					break;
					case 6:
						$msg = "Game has been Deleted.";
					break;
					default:
						$msg = "";
					break;
			
				}
			}	

			$creatednew = true;
			$seriesid = 0;

			if(isset($_GET['seriesId'])){

				$seriesid = $_GET['seriesId'];
			}


			$series = GetSeriesById($seriesid);
			$gamesplayed = GetGamesBySeriesId($seriesid);

			$homeUserAlias = GetUserAlias($series["HomeUserID"]);
			$awayUserAlias = GetUserAlias($series["AwayUserID"]);

			$submitBtn ="<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
			$submitBtn .="<input type='hidden' name='userid' value='" . $_SESSION['userId'] . "' />"; 
			$submitBtn .="<input type='hidden' name='seriesid' value='" . $seriesid ."' />";

			$fileInput = "Choose file: <input type='file' name='uploadfile' />";			
			$fileInput .= "<input type='submit' name='submit' value='Upload' />";
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Update Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>

			<script>			

			function UploadFile(scheduleNum){

				var fileInputBox = "<?= $fileInput ?><input type='hidden' name='scheduleid' value='" + scheduleNum + "' />";
				var fileInputDiv = $("#fileInput" + scheduleNum);

				fileInputDiv.html(fileInputBox);
				fileInputDiv.show();					

			}

			</script>

</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					<div style="color:red;"><?= $msg ?></div>

					<h2>Update Series</h2> 
										
					<form method="post" action="processUpdate.php" enctype="multipart/form-data">	
					<?= $submitBtn?>
					<table class="standard">
						<tr class="heading rowSpacer">
							<td class="seriesNum mainTD"></td>
							<td class="seriesName mainTD"><?=$series["Name"]?>, starting at <?= $homeUserAlias ?>'s Home Arena</td>
							<td class="seriesDate mainTD">Created <?=$series["DateCreated"]?></td>
						</tr>

						<?php 

						$i=1;
						$homeWinnerCount = 0;
						$awayWinnerCount = 0;
						$uploadCount = 0;

						while($row = mysqli_fetch_array($gamesplayed, MYSQL_ASSOC)){						


							$homeUserSelectBox = CreateSelectBox("homeUser", null, GetUsersFromSeries($seriesid), "id_user", "username", null, $row["HomeUserID"]);
							$awayUserSelectBox = CreateSelectBox("awayUser", null, GetUsersFromSeries($seriesid), "id_user", "username", null, $row["AwayUserID"]);							
								
							if($row["WinnerUserID"] != 0){								
							
								if($row["WinnerUserID"] == $series["HomeUserID"]){
									$homeWinnerCount++;
								}

								if($row["WinnerUserID"] == $series["AwayUserID"]){
									$awayWinnerCount++;
								}				

							
						?>
						<tr>
							<td><button type="button" class="square" onclick="DeleteGame('<?=$row['GameID']?>','<?=$seriesid?>')">X</button></td>
							<td>Gm <?=$i?>. <?= GetTeamABVById($row["AwayTeamID"]) ?> (<?= GetUserAlias($row["AwayUserID"])?>) <?=$row["AwayScore"]?> | <?= GetTeamABVById($row["HomeTeamID"])?> (<?= GetUserAlias($row["HomeUserID"]) ?>) <?=$row["HomeScore"]?></td>
							<td><button type="button" class="square" onclick="location.href='resultsSeries.php?seriesId=<?= $seriesid?>'">Game Stats</button></td>
						</tr>
						<?php
							}else{
								
								$uploadCount++;
								if($homeWinnerCount < 4 && $awayWinnerCount < 4 && $uploadCount <=1){
						?>
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm <?=$i?>. <?= $awayUserSelectBox ?>  @  <?= $homeUserSelectBox ?></td>
							<td><button type="button" class='square' id='submit<?= $row["ID"]?>' onclick="UploadFile('<?= $row["ID"]?>')">Upload File</button></td>
						</tr>		
						<tr>
							<td colspan="3" id="fileInput<?= $row["ID"]?>" style="display:none; padding-left: 41px;"></td>						
						</tr>				
						<?php
								//End Winner Count If
								}
							}	
							$i++;					
							// End While
						 }			
						 
						 
						 ?>						 
						 
						<?php
							
							$seriesText = "<h2>";

							if($homeWinnerCount > $awayWinnerCount && $homeWinnerCount < 4){
								$seriesText .= $homeUserAlias . " leads series " . $homeWinnerCount . " to " . $awayWinnerCount;
							}

							if($awayWinnerCount > $homeWinnerCount && $awayWinnerCount < 4) {
								$seriesText .= $awayUserAlias . " leads series " . $awayWinnerCount . " to " . $homeWinnerCount;
							}

							if($awayWinnerCount == $homeWinnerCount && (!$awayWinnerCount == 0 && !$homeWinnerCount == 0)  ){
								$seriesText .= "Series tied " .  $awayWinnerCount . " to " . $homeWinnerCount;
							}

							if($awayWinnerCount == 0 && $homeWinnerCount == 0 ){

								$seriesText .= "Series not yet started.";
							}

							if($homeWinnerCount >= 4){

								$seriesText .= $homeUserAlias . " Wins The Stanley " .  $homeWinnerCount . " to " . $awayWinnerCount . "!!"; 
							}

							if($awayWinnerCount >= 4){

								$seriesText .= $awayUserAlias . " Wins The Stanley " . $awayWinnerCount . " games to " . $homeWinnerCount . "!!";

							}
							
							$seriesText .= "</h2>";

							echo $seriesText;	 
					
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