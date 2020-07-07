<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		if ($LOGGED_IN == true) {	

			$msg = "";
			if(isset($_GET['err'])){
				$msg = GetUploadMsg($_GET['err']);
			}	

			$creatednew = true;
			$seriesid = 0;

			if(isset($_GET['seriesId'])){

				$seriesid = $_GET['seriesId'];
			}


			$series = GetSeriesById($seriesid);
			$gamesplayed = GetGamesBySeriesId($seriesid);
			$numGames = mysqli_num_rows($gamesplayed);
			$gamesNeededToWin = NeededWins($seriesid);
			$leagueid= $series["LeagueID"];	

			//echo ("TotalGames: " . $numGames . "<br/> Winner must have " . $gamesNeededToWin . " to win series.");


			if($numGames == 1){

				$titleText = "Add Game";

			}else{
				
				$titleText = "Update Series";

			}

			$homeUserAlias = GetUserAlias($series["HomeUserID"]);
			$awayUserAlias = GetUserAlias($series["AwayUserID"]);

			$submitBtn ="<input type='hidden' name='MAX_FILE_SIZE' value='4194304' />";
			$submitBtn .="<input type='hidden' name='userid' value='" . $_SESSION['userId'] . "' />"; 
			$submitBtn .="<input type='hidden' name='seriesid' value='" . $seriesid ."' />";
			$submitBtn .="<input type='hidden' name='leagueid' value='" . $series["LeagueID"] ."' />";

			$fileInput = "Choose file: <input type='file' name='uploadfile' multiple/>";			
			$fileInput .= "<input type='submit' name='submit' value='Upload' />";
			
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
					<div style="color:red;"><?= $msg ?></div>

					<h2><?= $titleText ?></h2> 
										
					<form method="post" action="processUpdate.php" id="frmGameSubmit" enctype="multipart/form-data">	
					<?= $submitBtn?>					

					<!-- Matt this is new Table -->
					<table id="tblBulkUpload" class="standard">					
						<tr class="heading rowSpacer">
							<td><button id="btnUploadBulk" type="button" class='square blue' onclick="UploadBulk();">Upload Bulk</button><br/><br/></td>
						</tr>
						<tr>
							<td id="bulkUpload" style="padding-bottom: 30px;display:none;">
								Choose files: <input type="file" id='uploadfile' name='uploadfile[]' multiple><input type='button' name='btnSubmit' onclick='UploadFiles()' value='Upload' />								
							</td>
						</tr>
					</table>

					<!-- Matt this END of new Table -->

					<table id="tblStandardUpload" class="standard">
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
							<td><button type="button" class="square" onclick="DeleteGame('<?=$row['GameID']?>','<?=$seriesid?>', 'update')">X</button></td>
							<td>Gm <?=$i?>. <?= GetTeamABVById($row["AwayTeamID"], $leagueid) ?> (<?= GetUserAlias($row["AwayUserID"])?>) <?=$row["AwayScore"]?> | <?= GetTeamABVById($row["HomeTeamID"], $leagueid)?> (<?= GetUserAlias($row["HomeUserID"]) ?>) <?=$row["HomeScore"]?></td>
							<td><button type="button" class="square" onclick="location.href='resultsSeries.php?seriesId=<?= $seriesid?>'">Game Stats</button></td>
						</tr>
						<?php
							}else{
								
								$uploadCount++;
								if($homeWinnerCount < $gamesNeededToWin && $awayWinnerCount < $gamesNeededToWin && $uploadCount <=1){
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

							if($homeWinnerCount > $awayWinnerCount && $homeWinnerCount < $gamesNeededToWin){
								$seriesText .= $homeUserAlias . " leads series " . $homeWinnerCount . " to " . $awayWinnerCount;
							}

							if($awayWinnerCount > $homeWinnerCount && $awayWinnerCount < $gamesNeededToWin) {
								$seriesText .= $awayUserAlias . " leads series " . $awayWinnerCount . " to " . $homeWinnerCount;
							}

							if($awayWinnerCount == $homeWinnerCount && (!$awayWinnerCount == 0 && !$homeWinnerCount == 0)  ){
								$seriesText .= "Series tied " .  $awayWinnerCount . " to " . $homeWinnerCount;
							}

							if($awayWinnerCount == 0 && $homeWinnerCount == 0 ){

								$seriesText .= "Series not yet started.";
							}

							if($homeWinnerCount >= $gamesNeededToWin){

								$seriesText .= $homeUserAlias . " Wins The Stanley " .  $homeWinnerCount . " to " . $awayWinnerCount . "!!"; 
							}

							if($awayWinnerCount >= $gamesNeededToWin){

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