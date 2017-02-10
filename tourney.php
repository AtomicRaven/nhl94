<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		if ($LOGGED_IN == true) {

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

		$hiddenInputs ="<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
		$hiddenInputs .="<input type='hidden' name='userid' value='" . $_SESSION['userId'] . "' />"; 
		$hiddenInputs .="<input type='hidden' id='seriesName' name='seriesName' value='". $tourney["ABV"] ."' />";
		$hiddenInputs .="<input type='hidden' id='seriesType' name='seriesType' value='1'/>";
		$hiddenInputs .="<input type='hidden' id='leagueType' name='leagueType' value='". $leagueid ."'/>";
		$hiddenInputs .="<input type='hidden' id='numGames' name='numGames' value='1'/>";
		$hiddenInputs .="<input type='hidden' id='tId' name='tId' value='".$tourneyid ."'/>";
		
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

					<h2>A. Upload a League Game</h2>

					<div style=""><?= $msg ?></div><br/>

					<div id="msg" style="color:red;"></div>
					<form name="seriesForm" method="post" action="processCreateTourney.php" enctype="multipart/form-data">
						<?=$hiddenInputs?>
						<table class="">
							<tr class="">
								<td class="">
										<?= $homeUserSelectBox?> 
								</td>
								<td class="">vs</td>
								<td class="">
										<?= $awayUserSelectBox?> 
								</td>					
								<td>
									<button type="button" class='square' id='submit1' onclick="UploadFile('1', true)">Upload File</button><br/>														
								</td>										
							</tr>							
						</table>
						<div colspan="4" id="fileInput1" style="display:none;"></div>	
					</form>



					<p><br /></p>  	
					<h2>B. Standings</h2> 
					
					<table class="lg_standings">
						<tr class="">
							<td class="">Rank</td>
							<td class="">Team</td>
							<td class="">Coach</td>
							<td class="">GP</td>
							<td class="">Wins</td>
							<td class="">Losses</td>
							<td class="c">%</td>
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
									"PCT"=>GetAvg($Wins, $GP)
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
						<tr class="">
							<td class="c"><?=$j?></td>
							<td class=""><?=$user["ABV"]?></td>
							<!--<td class=""><a href="javascript:location='./leagueStats.php';">(<?=$user["UserName"]?>)</a></td>-->
							<td class="">(<?=$user["UserName"]?>)</td>
							<td class="c"><?=$user["GP"]?></td>
							<td class="c"><?=$user["Wins"]?></td>
							<td class="c"><?=$user["Losses"]?></td>
							<td class="c"><?=$user["PCT"]?></td>
						</tr>												

						<tr class="show_game_histories" id="tempHandler"><td><br/></td><td colspan="4">
							<ol>
							<?php

								$i=0;

								foreach($user["Games"] as $row2){

									$i++;								

									$homeTeam = GetTeamABVById($row2["HomeTeamID"]);
									$awayTeam = GetTeamABVById($row2["AwayTeamID"]);

									$homeUser = GetUserAlias($row2["HomeUserID"]);
									$awayUser = GetUserAlias($row2["AwayUserID"]);

									$homeScore = $row2["HomeScore"];
									$awayScore = $row2["AwayScore"];

									$score = $i . ". ";									
										
									$score .= $homeTeam;
									//$score .= "(" . $homeUser . ") @ ";
									$score .= " @ ";
									$score .= $awayTeam;
									//$score .=  "(" . $awayUser . ")";
									$score .= " [ " .$homeScore . " to " . $awayScore . " ]";

									

									if(($_SESSION['userId'] == $row2["HomeUserID"] || $_SESSION['userId'] == $row2["AwayUserID"]) || $_SESSION['Admin'])
										$showDeleteBtn = true;
									else
										$showDeleteBtn = false;
									
							?>
								<li><?php
									if($showDeleteBtn){
									?>
										<button type="button" class="square" onclick="DeleteGame('<?=$row2['GameID']?>','<?=$row2['SeriesID']?>', 'tourney', '<?=$tourneyid?>')">x</button> 
									<?php
										}
									?>
									<?=$score?>&nbsp;
									<button type="button" class="square" onclick="location.href='resultsSeries.php?seriesId=<?=$row2['SeriesID']?>'">Go</button>
								</li>
							<?php
								}
							?>
							</ol>	
						</td></tr>					
						<?php
							}
						?>						
					  	<tr>
							<td></br /></td>
						  <td colspan="4" style="padding-top: 20px;">
								<button type="button" class="square" id="" onclick="javascript:showGameHistories();">Show Game Histories</button>
							</td>
						</tr>
					</table>			

<script>

	function showGameHistories() {
			$('.show_game_histories').show();
	}

</script>					
					
		
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