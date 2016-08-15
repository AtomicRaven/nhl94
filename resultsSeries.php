<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		$seriesid = $_GET["seriesId"];
		$series = GetSeriesById($seriesid);
		$gamesplayed = GetGamesBySeriesId($seriesid);

		$sPlayerStats = GetPlayerStatsBySeriesId($seriesid, 'P');
		$sGoalieStats = GetPlayerStatsBySeriesId($seriesid, 'G');

		$homeUserAlias = GetUserAlias($series["HomeUserID"]);
		$awayUserAlias = GetUserAlias($series["AwayUserID"]);

		if($series["SeriesWonBy"] != 0){

			$totalGames = 4 + $series["LoserNumGames"];
			$lastEntryTime = "series updated " .HumanTiming($series["DateCompleted"]) . " ago";
			$gamesCompleteText = GetUserAlias($series["SeriesWonBy"]) . " wins <nobr>in ".$totalGames."</nobr>" ;
			$stanleyClass = "stanley";		

		}else{
			
			while($row = mysqli_fetch_array($gamesplayed)){

				$gamesCompleteText = "In progress <nobr>(" .$row["TotalGames"]. " gms)</nobr>";	
				$lastEntryTime = "series updated " . HumanTiming($row["LastEntryDate"]) . " ago";
				break;
			}

			mysqli_data_seek($gamesplayed, 0);
		}

		// add up series stats

		$sHomeWins = 0;
		$sAwayWins= 0;
		$sHomeGoals = 0;
		$sAwayGoals = 0;
		$sHomeShooting = 0;
		$sAwayShooting = 0;
		
		//Shots per game
		$sHomeShots = 0;
		$sAwayShots = 0;

		//FaceOffs
		$sFO = 0;
		$sFOH = 0;
		$sFOA = 0;

		//Passing
		$sPH = 0;
		$sPCH = 0;
		$sPA = 0;
		$sPCA = 0;

		//AttackZone
		$shZone = 0;
		$saZone = 0;

		//Penalties Shots
		$PSHG = 0;
		$PSH = 0;
		$PSAG = 0;
		$PSA = 0;

		//Penalites
		$PPA = 0;
		$PPH = 0;

		//Breakaways
		$BAH = 0;
		$BAA = 0;

		//One Timers
		$OneTHG = 0;
		$OneTH = 0;
		$OneTAG = 0;
		$OneTA = 0;

		//Checks
		$BCH = 0;
		$BCA = 0;

		while($row = mysqli_fetch_array($gamesplayed)){
				
				if($series["HomeUserID"] == $row["WinnerUserID"]){
					$sHomeWins++;
				}else if ($series["AwayUserID"] == $row["WinnerUserID"]){
					$sAwayWins++;
				}
				
				$sHomeGoals += $row["HomeScore"];
				$sAwayGoals += $row["AwayScore"];

				//ADd up stats for each game
				
				if($row["GameID"] != NULL){
					$gStats = GetGameById($row["GameID"]);

					$sHomeShooting += $gStats["GHP1"] + $gStats["GHP2"] + $gStats["GHP3"]; 
					$sAwayShooting += $gStats["GAP1"] + $gStats["GAP2"] + $gStats["GAP3"];

					//Shots per game
					$sHomeShots += $gStats["SHP1"] + $gStats["SHP2"] + $gStats["SHP3"];
					$sAwayShots += $gStats["SAP1"] + $gStats["SAP2"] + $gStats["SAP3"];

					//FaceOffs
					$sFO += $gStats["FOH"] + $gStats["FOA"]; 
					$sFOH += $gStats["FOH"];
					$sFOA += $gStats["FOA"];
					
					//Passing
					$sPH += $gStats["PH"];
					$sPCH += $gStats["PCH"];
					$sPA += $gStats["PA"];
					$sPCA += $gStats["PCA"];

					//AttackZone
					$shZone += strtotime($gStats["AZH"]);
					$saZone += strtotime($gStats["AZA"]);

					//Penalties Shots
					$PSHG += $gStats["PSHG"];
					$PSH += $gStats["PSH"];
					$PSAG += $gStats["PSAG"];
					$PSA += $gStats["PSA"];

					//Penalites
					$PPA += $gStats["PPA"];
					$PPH += $gStats["PPH"];

					//Breakaways
					$BAH += $gStats["BAH"];
					$BAA += $gStats["BAA"];

					//One Timers
					$OneTHG += $gStats["1THG"];
					$OneTH += $gStats["1TH"];
					$OneTAG += $gStats["1TAG"];
					$OneTA += $gStats["1TA"];

					//Checks
					$BCH += $gStats["BCH"];
					$BCA += $gStats["BCA"];
				}

				
		}

		//Shot Percentage per series
		$sHomeShotPerCent = GetPercent($sHomeGoals,$sHomeShots) ."%";
		$sAwayShotPerCent = GetPercent($sAwayGoals,$sAwayShots). "%";

		//FaceOffs per series
		$sFOHP = GetPercent($sFOH, $sFO). "%";
		$sFOAP = GetPercent($sFOA, $sFO). "%";

		//Passing
		$spHome = GetPercent($sPCH, $sPH). "%";
		$spAway = GetPercent($sPCA, $sPA). "%";

		//AttackZone
		$shZoneP = FormatZoneTime(date("Y-m-d H:i:s", $shZone));
		$saZoneP =  FormatZoneTime(date("Y-m-d H:i:s", $saZone));

		mysqli_data_seek($gamesplayed, 0);

?><!DOCTYPE HTML>
<html>
<head>
<title>Series Results</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
<script>

	

</script>	
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					<h1>Results</h1>	
					<h2>Results for a Specific Series</h2>
				<div class="half-left">

					
					
					<table class="standard">
						<tr class="heading">
							<td class="c brt"><span class="note">series</span><br /><!-- Rob: series_id --><?= $seriesid ?></td>
							<td class="" colspan="5"><?= $awayUserAlias ?> @ <?= $homeUserAlias ?></td>
						</tr>			
						<tr class="heading">
							<td class="c brt" style="padding: 2px 0 0 0;">
							<a class="<?=$stanley?>"><br/></a>							
							</td>
							<td class="" colspan="5"><?= $gamesCompleteText?><br /> 
								<span class="note"><?= $lastEntryTime?></span></td>
						</tr>							
						<tr class="heading">
							<td class="c"><span class="note">game</span><br />#</td>
							<td class="c">HOME</td>
							<td class="">&nbsp;</td>
							<td class="c">AWAY</td>
							<td class="">&nbsp;</td>
							<td class="c"><div id="allGames" onclick="showAllGames(this)">+ All</div></td>
						</tr>	
						<!-- loop starts here -->
<?php 
			$i=1;
			while($row = mysqli_fetch_array($gamesplayed))
			{
				if($row["WinnerUserID"] != 0)
				{
						$gameid = $row["GameID"]; 
?>				
						<tr class="tight<?php print $stripe[$i & 1]; ?>" >
							<td class="c"><?php print $i; ?></td>
							<td class="c winner"><?= GetTeamABVById($row["HomeTeamID"]) ?></td>
							<td class="c winner"><?=$row["HomeScore"]?></td>
							<td class="c"><?= GetTeamABVById($row["AwayTeamID"]) ?></td>
							<td class="c"><?=$row["AwayScore"]?></td>
							<td class="c"><button type="button" class="square details" onclick="showGameDetails(this, 'detail_<?php print $i; ?>', <?=$gameid?>, <?=$i?>)">+ Details</button></td>
						</tr>	
						<tr class="tight detail_row" id="detail_<?php print $i; ?>" style="display: none" data-game-id="<?=$gameid?>">
							<td colspan="6" id="fetch_detail_<?php print $i; ?>">

							</td>

						</tr>		
<?php 
				}
			$i++;
			}
?>								
						<!-- loop ends -->	
					</table>

			</div>
			<div class="half-right">			
					<!-- rob: start of series stats table -->	
					<h2>Series Stats</h2>
					<table class="standard">
						<tr class="heading">
							<td class="">&nbsp;</td>
							<td class="c"><?= $homeUserAlias?></td>
							<td class="c"><?= $awayUserAlias?></td>
						</tr>	
						<tr class="tight stripe"><!-- RECORD -->
							<td class="heading">Record</td><td class="c"><?=$sHomeWins?> wins</td><td class="c"><?=$sAwayWins?> wins</td>
						</tr>							
						<tr class="tight"><!-- GOALS -->
							<td class="heading">Goals</td><td class="c"><?=$sHomeGoals?></td><td class="c"><?=$sAwayGoals?></td>
						</tr>							
						<!--<tr class="tight stripe"><!-- ASSISTS
							<td class="heading">Assists</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight"><!-- POINTS
							<td class="heading">Points</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>-->							
						<tr class="tight stripe"><!-- Points Per Game
							<td class="heading">PPG</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>-->
						<tr class="tight"><!-- Shooting % -->
							<td class="heading">Shooting %</td><td class="c"><?=$sHomeShotPerCent?></td><td class="c"><?=$sAwayShotPerCent?></td>
						</tr>							
						<tr class="tight stripe"><!-- Faceoffs -->
							<td class="heading">Faceoffs</td><td class="c"><?=$sFOHP?></td><td class="c"><?=$sFOAP?></td>
						</tr>							
						<tr class="tight"><!-- Att Zone -->
							<td class="heading">Attack Zone</td><td class="c"><?=$shZoneP?></td><td class="c"><?=$saZoneP?></td>
						</tr>							
						<tr class="tight stripe"><!-- Passing -->
							<td class="heading">Passing</td><td class="c"><?=$spHome?></td><td class="c"><?=$spAway?></td>
						</tr>							
						<tr class="tight"><!-- Penalty Shots -->
							<td class="heading">Pen. Shots</td><td class="c"><?= $PSHG . "/" . $PSH?></td><td class="c"><?= $PSAG . "/" . $PSA?></td>
						</tr>							
						<tr class="tight stripe"><!-- PIM 
																	 This should be number of penalties a team takes.  
																	 Note: Each Penalty shot (for opposition) should be added here, which
																	 will give total penatlies	
																									-->
							<td class="heading"># Penalties</td><td class="c"><?=$PPH?></td><td class="c"><?=$PPA?></td>
						</tr>							
						<tr class="tight"><!-- Breakways -->
							<td class="heading">Breakways</td><td class="c"><?=$BAH?></td><td class="c"><?=$BAA?></td>
						</tr>							
						<tr class="tight stripe"><!-- One Timers -->
							<td class="heading">One Timers</td><td class="c"><?=$OneTHG . "/" . $OneTH?></td><td class="c"><?=$OneTAG . "/" . $OneTA?></td>
						</tr>	
						<tr class="tight stripe"><!-- Checks -->
							<td class="heading">Checks</td><td class="c"><?=$BCH?></td><td class="c"><?=$BCA?></td>
						</tr>							

					</table>		


					<!-- rob: start of series stats table -->	
					<h3>Series Points Leaderboard</h3>
					<table class="standard">
						<tr class="heading">
							<td class="heading">#</td>
							<td class="heading">Name</td>
							<td class="heading">Team</td>
							<td class="heading">Pos</td>
							<td class="heading">Pts</td>
							<td class="heading">G</td>
							<td class="heading">A</td>
							<td class="heading">SOG</td>
							<td class="heading">PIM</td>
						</tr>	
						<!-- start loop for all players with points -->
		<?php 
					$j = 1;
					while(($row = mysqli_fetch_array($sPlayerStats)))
					{						
						$player = GetPlayerFromID($row["PlayerID"]);						

						if($row["Pos"] != "G"){		?>						

								<tr class="tight<?php print $stripe[$j & 1]; ?>">
									<td class=""><?php print $j; ?></td>
									<td class=""><?=$player["First"] . " " . $player["Last"]?></td>
									<td class=""><?=GetTeamABVById($row["TeamID"])?></td>
									<td class=""><?=$row["Pos"]?></td>
									<td class=""><?=$row["tPoints"]?></td>
									<td class=""><?=$row["tG"]?></td>
									<td class=""><?=$row["tA"]?></td>
									<td class=""><?=$row["tSOG"]?></td>
									<td class=""><?=$row["tPIM"]?></td>
								</tr>	
		<?php 
					$j++;
						}
					}						
					
		?>						
						<!-- end of loop -->	

					</table>					


					<!-- rob: start of series goalies table -->	
					<h3>Series Goalies Leaderboard</h3>
					
					<!-- start loop for all goalies with time on ice, sorted by save % -->
					<table class="standard">
						<tr class="heading">
							<td class="heading">#</td>
							<td class="heading">Name</td>
							<td class="heading">Team</td>
							<td class="heading">Save %</td>
						</tr>	

<?php 
					$j = 1;
					while($row = mysqli_fetch_array($sGoalieStats))
					{
						$player = GetPlayerFromID($row["PlayerID"]);					

						if($row["Pos"] == "G"){

								if($row["SOG"]!= 0)
									$savePct = FormatPercent($row["tG"], $row["tSOG"]);
								else 
									$savePct = "0 SOG";
		?>								
								<tr class="tight<?php print $stripe[$j & 1]; ?>">
									<td class=""><?php print $j; ?></td>
									<td class=""><?=$player["First"] . " " . $player["Last"]?></td>
									<td class=""><?=GetTeamABVById($row["TeamID"])?></td>
									<td class=""> <?=$savePct?><!-- 17 goals on 72 shots) --></td>
								</tr>	
		<?php 
						}
					$j++;
					}
		?>						
						<!-- end of loop -->	

					</table>					

				</div>			

				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>