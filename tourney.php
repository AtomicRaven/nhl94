<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';		

		$tourneyid = -1;
		$msg = 'Select Home and Away and upload a game:';

		if(isset($_GET['tId'])){
			$tourneyid = $_GET['tId'];
		}

		if(isset($_GET['err'])){
			$msg = GetUploadMsg($_GET['err']);
			if($_GET['err'] >0)
				$msg = "<div style='color:red;display:inline;'>" . $msg . "</div>";
		}	

		$tourney = GetTourneyById($tourneyid);		
		$leagueid = $tourney["LeagueID"];
		$tourneyusers = GetTourneyUsers($tourneyid, true);

		$homeUserSelectBox = CreateSelectBox("homeUser", "Home", GetTourneyUsers($tourneyid, false), "id_user", "username", "", null);
		$awayUserSelectBox = CreateSelectBox("awayUser", "Away", GetTourneyUsers($tourneyid, false), "id_user", "username", "", null);

		if ($LOGGED_IN == true) {
			$hiddenInputs ="<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
			$hiddenInputs .="<input type='hidden' name='userid' value='" . $_SESSION['userId'] . "' />"; 
			$hiddenInputs .="<input type='hidden' id='seriesName' name='seriesName' value='". $tourney["ABV"] ."' />";
			$hiddenInputs .="<input type='hidden' id='seriesType' name='seriesType' value='1'/>";
			$hiddenInputs .="<input type='hidden' id='leagueType' name='leagueType' value='". $leagueid ."'/>";
			$hiddenInputs .="<input type='hidden' id='numGames' name='numGames' value='1'/>";
			$hiddenInputs .="<input type='hidden' id='tId' name='tId' value='".$tourneyid ."'/>";
		}
		
?><!DOCTYPE HTML>
<html>
<head>
<title><?=$tourney["Name"]?></title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h1><?=$tourney["Name"]?></h1><br />

					<?php
						if ($LOGGED_IN == true) {
					?>

					<h2>Upload a League Game</h2>

					<div style=""><?= $msg ?></div><br/>

					<div id="msg" style="color:red;"></div>
					<form name="seriesForm" method="post" action="processCreateTourney.php" enctype="multipart/form-data">
						<?=$hiddenInputs?>

						
						<table class="uploadThingy">
							<tr class="">
								<td class="">
										 <?= $awayUserSelectBox?> 
								</td>
								<td class="">@</td>
								<td class="">
										<?= $homeUserSelectBox?>
								</td>		
							</tr>
							<tr>		
								<td colspan="3">
									<input type="file" name="uploadfile"><input type="button" onclick="SubmitForm()" value="Upload"><input type="hidden" name="scheduleid" value="1">
								</td>										
							</tr>							
						</table>
						<div id="fileInput1" style="display:none;"></div>	
					</form>

					<?php
						}
					?>


					<p><br /></p>  	
					<h2>Standings</h2> 
					
					<table class="lg_standings">
						<tr class="">
							<td class="c"><br /></td>
							<td class="">#</td>
							<?php if ($tourney["StaticTeam"] == 1){ ?>
							<td class="">Tm</td>
							<?php } ?>
							<td class="">Coach</td>
							<td class="">GP</td>
							<td class="">W</td>
							<td class="">L</td>
							<td class="c">%</td>
							<?php if ($tourney["StaticTeam"] != 1){ ?>
							<!-- <td class="">Lvl</td> -->
							<?php } ?>
						</tr>
						<?php

							$gamesplayed = null;
							while($row = mysqli_fetch_array($tourneyusers)){								
							
								$gamesplayed = GetTourneyGames($tourneyid, $row["UserID"]);

								$Wins = 0;
								$Losses = 0;

								while($row2 = mysqli_fetch_array($gamesplayed)){

									if($row2["WinnerUserID"] == $row["UserID"]){
										$Wins++;
									}else{
										$Losses++;
									}
								}

								$GP = $Wins + $Losses;

								$sortedLeaders[] = array(
									"UserName"=>$row["username"],
									"Wins"=>$Wins,
									"Losses"=>$Losses,
									"ABV"=>$row["ABV"],
									"Games"=>$gamesplayed,
									//"gFor"=>$gFor,
									//"gAgainst"=>$gAgainst,
									//"gTotal"=>$gTotal,
									"GP"=>$GP,
									"PCT"=>GetAvg($Wins, $GP),
									"Level"=> $row["Level"]
									//"GFA"=>GetAvg($gFor, $GP),
									//"GAA"=>GetAvg($gAgainst, $GP)
								);							

								usort($sortedLeaders, 'SortByWins');
											
							
							}
							$j=0;
							mysqli_data_seek($gamesplayed, 0);
							foreach($sortedLeaders as $user){
								$j++;
						?>
						<tr class="tight">
							<td class="c"><button type="button" class='square detailsButton' style='width: 22px; text-align: center;'>+</button></td>
							<td class="c"><?=$j?></td>
							<?php if ($tourney["StaticTeam"] == 1){ ?>
							<td class=""><?=$user["ABV"]?></td>
							<?php } ?>
							<!--<td class=""><a href="javascript:location='./leagueStats.php';">(<?=$user["UserName"]?>)</a></td>-->
							<td class=""><?=$user["UserName"]?> <!-- (<?=$user["Level"]?>) --></td>
							<td class="c"><?=$user["GP"]?></td>
							<td class="c"><?=$user["Wins"]?></td>
							<td class="c"><?=$user["Losses"]?></td>
							<td class="c"><?=$user["PCT"]?></td>
							<?php if ($tourney["StaticTeam"] != 1){ ?>
							<!-- <td class=""><?=$user["Level"]?></td> -->
							<?php } ?>
						</tr>												

						<tr class="show_game_histories"><td><br/></td><td colspan="7">
							<table class="newTable">
								<thead>
									<tr class="headerLg">
										<th>Gm</th>
										<th>Away</th>	
										<th>@</th>
										<th>Home</th>
										<th>Score</th>
										<th>Timestamp</th>
										<th><br /></th>
									</tr>
								</thead>
								<tbody>
							
							<?php

								$i=0;
								$wins = '';
								
								foreach($user["Games"] as $row2) {

									$i++;				

									if(($_SESSION['userId'] == $row2["HomeUserID"] || $_SESSION['userId'] == $row2["AwayUserID"]) || $_SESSION['Admin'])
										$showDeleteBtn = true;
									else
										$showDeleteBtn = false;

									$homeTeam = GetTeamABVById($row2["HomeTeamID"], $leagueid);
									$awayTeam = GetTeamABVById($row2["AwayTeamID"], $leagueid);

									$homeUser = GetUserAlias($row2["HomeUserID"]);
									$awayUser = GetUserAlias($row2["AwayUserID"]);

									$homeScore = $row2["HomeScore"];
									$awayScore = $row2["AwayScore"];

									if( $awayScore < $homeScore ) {
										$winnerScore = $homeScore;
										$loserScore = $awayScore;
										$winnerTeam = $homeTeam;
										$loserTeam = $awayTeam;
										$winnerUser = $homeUser;
										$loserTeam = $awayUser;
										$awayTitle = $awayTeam . " (" . $awayUser . ")";
										$homeTitle = "<em>". $homeTeam . " (" . $homeUser . ")</em>";
									}
									else {
										$winnerScore = $awayScore;
										$loserScore = $homeScore;
										$winnerTeam = $awayTeam;
										$loserTeam = $homeTeam;
										$winnerUser = $awayUser;
										$loserTeam = $homeUser;
										$awayTitle = "<em>". $awayTeam . " (" . $awayUser . ")</em>";
										$homeTitle = $homeTeam . " (" . $homeUser . ")";
									}							

									//Overtime
									$ot = "";
									if($row2["OT"] == 1)
										$ot = "(OT)";

									$dateSubmitted = new DateTime($row2["ConfirmTime"]);
									$formattedDate = date_format($dateSubmitted, 'M d, Y @ h:i A');
									

							?>
								<!-- start: repeat this part (1 row for each game) -->
								<tr class="resultsLg">
									<td class="c"><?=$i?></td>
									<td><?=$awayTitle?></td>	
									<td class="c">@</th>
									<td><?=$homeTitle?></td>
									<td class="c"><?=$winnerScore . "-" . $loserScore?><em> <?=$winnerTeam?></em> <?=$ot?></td>
									<td class="c"><?=$formattedDate?></td>
									<td class="c">
									<button type="button" class="square gameResultsButton" data-game-results=<?php print $row2['SeriesID']; ?>>Details</button>
									<?php
										if($showDeleteBtn){
									?>
									<button type="button" class="square" onclick="DeleteGame('<?=$row2['GameID']?>','<?=$row2['SeriesID']?>', 'tourney', '<?=$tourneyid?>')">x</button> 
									<?php
										}
									?>
									</td>
								</tr>											
							<!-- end: repeat this part -->		
								
								
							<?php
								}
								if ($i == 0 ) {
								?>
								<tr class="resultsLg">
									<td class="c">No games yet...</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<?php 
								}
								?>
							
								</tbody>
							</table>
						</td></tr>					
						<?php
							}
						?>						
					</table>			
					
			<p><br /></p>  	
			<h2>Submit Series Result (Google Forms)</h2> 					
			<iframe frameborder="0" height="800" marginheight="0" marginwidth="0" src="https://docs.google.com/forms/d/e/1FAIpQLSdE7cfAb9fiMOwy77OaBnVWzSlB1HOS8IO6Qd5tyZ4uNRU5Hw/viewform?embedded=true" width="700">Loading...</iframe>
			<h3>Standings: <a href="https://tinyurl.com/y87a9scl" target="_blank">https://tinyurl.com/y87a9scl</a></h3>
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
<script>

		$( ".detailsButton" ).click(function() {
				var symbol = $(this).text();
				if (symbol == '+') {
					$(this).text('-');
					$(this).parents(1).next().addClass('showme');
				}
				else {
					$(this).text('+');
					$(this).parents(1).next().removeClass('showme');
				}
		});		

		$( ".gameResultsButton" ).click(function() {
				var rId = $(this).attr('data-game-results'),
							win = window.open( './resultsSeries.php?seriesId=' + rId, '_blank');
				win.focus();								
				return;
		});		

		
		
</script>
</body>
</html>