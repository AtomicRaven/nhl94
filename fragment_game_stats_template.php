<?php
		include_once './_INCLUDES/dbconnect.php';

		$stripe[0] = '';
		$stripe[1] = ' stripe'; // odd

		$gameid = $_GET["gameId"];
		$gameNum = $_GET["gameNum"];
		$leagueid = $_GET["leagueId"];


		//Get data from DB
		$gStats = GetGameById($gameid);
		$schedule = GetScheduleByGameId($gameid);
		$pStats = GetPlayerStatsByGameId($gameid, 'DESC');
		$goalieStats = GetPlayerStatsByGameId($gameid, 'ASC');

		$scoreStats = GetScoringSummary($gameid);
		$penaltyStats = GetPenaltySummary($gameid);

		$homeTeamAbv = GetTeamABVById($schedule["HomeTeamID"], $leagueid);
		$awayTeamAbv = GetTeamABVById($schedule["AwayTeamID"], $leagueid);

		//$homeTeamName = GetTeamNameById($schedule["HomeTeamID"], $leagueid);
		//$awayTeamName = GetTeamNameById($schedule["AwayTeamID"], $leagueid);

		$dateSubmitted = new DateTime($schedule["ConfirmTime"]);
		$formattedDate = date_format($dateSubmitted, 'M d, Y @ h:i A');

		///Add up everything

		//Goals per game
		$homeGoals = $schedule["HomeScore"];
		$awayGoals = $schedule["AwayScore"];

		//Shots per game
		$homeShots = $gStats["SHP1"] + $gStats["SHP2"] + $gStats["SHP3"] + $gStats["SHOT"];
		$awayShots = $gStats["SAP1"] + $gStats["SAP2"] + $gStats["SAP3"] + $gStats["SAOT"];

		//Penalites per game
		$homePen = $gStats["PIMH"];
		$awayPen = $gStats["PIMA"];

		//Shot Percentage per game
		$homeShotPerCent = FormatPercent($homeGoals,$homeShots);
		$awayShotPerCent = FormatPercent($awayGoals,$awayShots);

		//FaceOffs
		$totalFO = $gStats["FOH"] + $gStats["FOA"];
		$FOHP = FormatPercent($gStats["FOH"], $totalFO);
		$FOAP = FormatPercent($gStats["FOA"], $totalFO);

		//Passing
		$pHome = FormatPercent($gStats["PCH"] + $gStats["1TH"], $gStats["PH"]);
		$pAway = FormatPercent($gStats["PCA"] + $gStats["1TA"], $gStats["PA"]);

		//AttackZone
		$hZone = FormatZoneTime($gStats["AZH"]);
		$aZone =  FormatZoneTime($gStats["AZA"]);

		$GHOT = "";
		$GAOT = "";

		if($gStats["GHOT"] == 1){
			$GHOT = " (OT)";
		}

		if($gStats["GAOT"] == 1){
			$GAOT = " (OT)";
		}

?>


<!-- This is what we can pull from game stats table
14			Shots	11
50.0%		Shooting Pct.	45.5%
0/0			Power Play	2/3
0			SH Goals	0
0/0			Breakaways	0/4
3/5			One-Timers	5/7
0/0			Penalty Shots	0/0
13/20(65%)	Faceoffs	7/20 (35%)
44			Checks	32
6			PIM	0
05:28		Attack Zone	04:25
28/50 (56%)	Passing 35/62 (56%)

-->

					<!-- rob: start of series stats table -->
					<div class="gamestats">

							<h3 data-id="<?=$gameid?>">Game <?=$gameNum?> Stats</h3>
							<h4>submitted <?=$formattedDate?></h4>

							<table class="standard">
								<tr class="heading">
									<td class="">&nbsp;</td>
									<td class="c"><?=$homeTeamAbv?></td>
									<td class="c"><?=$awayTeamAbv?></td>
								</tr>
								<tr class="tight"><!-- GOALS -->
									<td class="heading">Goals</td><td class="c"><?=$homeGoals?><?=$GHOT?></td><td class="c"><?=$awayGoals?><?=$GAOT?></td>
								</tr>
								<!--<tr class="tight stripe"><!-- ASSISTS - This doesn't exist
									<td class="heading">Assists</td><td class="c">xxx</td><td class="c">xxx</td>
								</tr>
								What are Points?
								<tr class="tight"><!-- POINTS
									<td class="heading">Points</td><td class="c">xxx</td><td class="c">xxx</td>
								</tr>
								<tr class="tight stripe"><!-- Points Per Game
									<td class="heading">PPG</td><td class="c">xxx</td><td class="c">xxx</td>
								</tr>-->
								<tr class="tight"><!-- Shooting % -->
									<td class="heading">Shooting %</td><td class="c"><?=$homeShotPerCent?></td><td class="c"><?=$awayShotPerCent?></td>
								</tr>
								<tr class="tight stripe"><!-- Faceoffs -->
									<td class="heading">Faceoffs</td><td class="c"><?=$FOHP?></td><td class="c"><?=$FOAP?></td>
								</tr>
								<tr class="tight"><!-- Att Zone -->
									<td class="heading">Attack Zone</td><td class="c"><?=$hZone?></td><td class="c"><?=$aZone?></td>
								</tr>
								<tr class="tight stripe"><!-- Passing -->
									<td class="heading">Passing</td><td class="c"><?=$pHome?></td><td class="c"><?=$pAway?></td>
								</tr>
								<tr class="tight"><!-- Penalty Shots -->
									<td class="heading">Pen. Shots</td><td class="c"><?=$gStats["PSHG"]?>/<?=$gStats["PSH"]?></td><td class="c"><?=$gStats["PSAG"]?>/<?=$gStats["PSA"]?></td>
								</tr>
								<tr class="tight stripe"><!-- PIM
																			 This should be number of penalties a team takes.
																			 Note: Each Penalty shot (for opposition) should be added here, which
																			 will give total penatlies
																											-->
									<td class="heading"># Penalties</td><td class="c"><?=$homePen?></td><td class="c"><?=$awayPen?></td>
								</tr>
								<tr class="tight"><!-- Breakways -->
									<td class="heading">Breakways</td><td class="c"><?=$gStats["BAHG"]?>/<?=$gStats["BAH"]?></td><td class="c"><?=$gStats["BAAG"]?>/<?=$gStats["BAA"]?></td>
								</tr>
								<tr class="tight stripe"><!-- One Timers -->
									<td class="heading">One Timers</td><td class="c"><?=$gStats["1THG"]?>/<?=$gStats["1TH"]?></td><td class="c"><?=$gStats["1TAG"]?>/<?=$gStats["1TA"]?></td>
								</tr>
								<tr class="tight"><!-- Checks -->
									<td class="heading">Checks</td><td class="c"><?=$gStats["BCH"]?></td><td class="c"><?=$gStats["BCA"]?></td>
								</tr>

							</table>


							<!-- rob: start of series stats table -->
							<h3>Game <?=$gameNum?> Points Leaderboard</h3>
							<table class="standard">
								<tr class="heading">
									<td class="heading">#</td>
									<td class="heading">Name</td>
									<td class="heading">Team</td>
									<td class="heading">Pts</td>
									<td class="heading">G</td>
									<td class="heading">A</td>
									<td class="heading">SOH</td>
									<td class="heading">PIM</td>
								</tr>
								<!-- start loop for all players with points -->
		<?php
					$j = 1;
					while($row = mysqli_fetch_array($pStats))
					{
						$points = $row["G"] + $row["A"];
						$player = GetPlayerFromID($row["PlayerID"], $leagueid);

						if($row["Pos"] != "G"){
		?>

								<tr class="tight<?php print $stripe[$j & 1]; ?>">
									<td class=""><?php print $j; ?></td>
									<td class=""><?=$player["First"] . " " . $player["Last"]?></td>
									<td class=""><?=GetTeamABVById($row["TeamID"], $leagueid)?></td>
									<td class=""><?=$points?></td>
									<td class=""><?=$row["G"]?></td>
									<td class=""><?=$row["A"]?></td>
									<td class=""><?=$row["SOG"]?></td>
									<td class=""><?=$row["PIM"]?></td>
								</tr>
		<?php
					$j++;
						}
					}

		?>
								<!-- end of loop -->

							</table>
							
							<!--
									$homeShots = $gStats["SHP1"] + $gStats["SHP2"] + $gStats["SHP3"];
									$awayShots = $gStats["SAP1"] + $gStats["SAP2"] + $gStats["SAP3"];
							-->
							
							<!-- Period stats Table -->
							<h3>Game <?=$gameNum?> Period Stats (Goals-Shots)</h3>
							<table class="standard">
								<tr class="heading">
									<td class="heading">Team</td>
									<td class="heading">1st</td>
									<td class="heading">2nd</td>
									<td class="heading">3rd</td>
									<td class="heading">OT</td>
									<td class="heading">Total</td>
								</tr>								
								<tr class="tight stripe">
									<td class="logoleft"><div class='logo small <?=$homeTeamAbv?>'></div></td>
									<td class="m"><?= $gStats["GHP1"]?> - <?= $gStats["SHP1"]?></td>
									<td class="m"><?= $gStats["GHP2"]?> - <?= $gStats["SHP2"]?></td>
									<td class="m"><?= $gStats["GHP3"]?> - <?= $gStats["SHP3"]?></td>
									<td class="m"><?= $gStats["GHOT"]?> - <?= $gStats["SHOT"]?></td>
									<td class="m"><?=$homeGoals?> - <?=$homeShots?></td>
								</tr>
								<tr class="tight">
									<td class="logoleft"><div class='logo small <?= $awayTeamAbv ?>'></div></td>
									<td class="m"><?= $gStats["GAP1"]?> - <?= $gStats["SAP1"]?></td>
									<td class="m"><?= $gStats["GAP2"]?> - <?= $gStats["SAP2"]?></td>
									<td class="m"><?= $gStats["GAP3"]?> - <?= $gStats["SAP3"]?></td>
									<td class="m"><?= $gStats["GAOT"]?> - <?= $gStats["SAOT"]?></td>
									<td class="m"><?=$awayGoals?> - <?=$awayShots?></td>
								</tr>
								<tr class="tight stripe" >
									<td colspan="5" class="heading">Peak Crowd Level</td> 
									<td  class="heading"><?= $gStats["Crowd"]?>db</td>
								</tr>
							<table>


							<!-- rob: start of series goalies table -->
							<h3>Game <?=$gameNum?> Goalies Leaderboard</h3>

							<!-- start loop for all goalies with time on ice, sorted by save % -->
							<table class="standard" style="margin-bottom: 1em;">
								<tr class="heading">
									<td class="heading">#</td>
									<td class="heading">Name</td>
									<td class="heading">Team</td>
									<td class="heading">Save %</td>
								</tr>

		<?php
					$j = 1;
					while($row = mysqli_fetch_array($goalieStats))
					{
						$points = $row["G"] + $row["A"];
						$player = GetPlayerFromID($row["PlayerID"], $leagueid);


						if($row["Pos"] == "G"){
								if($row["SOG"]!= 0){
									$savePct = 100 - GetPercent($row["G"], $row["SOG"]);
									$savePct = $row["G"] . "/" . $row["SOG"] . " (" . $savePct . "%)";
								}
								else
									$savePct = "0 SOG";
		?>
								<tr class="tight<?php print $stripe[$j & 1]; ?>">
									<td class=""><?php print $j; ?></td>
									<td class=""><?=$player["First"] . " " . $player["Last"]?></td>
									<td class=""><?=GetTeamABVById($row["TeamID"], $leagueid)?></td>
									<td class=""> <?=$savePct?><!-- 17 goals on 72 shots) --></td>
								</tr>
		<?php
						$j++;
						}
					
					}
		?>
								<!-- end of loop -->

							</table>

							<!-- start of Scoring Sum table -->
							<h3>Game <?=$gameNum?> Scoring Summary</h3>
							<table class="standard">
								<tr class="heading">
									<td class="heading">Per</td>
									<td class="heading">Time</td>
									<td class="heading">Team</td>
									<td class="heading">Goal Scorer</td>
									<td class="heading">Assists</td>
									<td class="heading">Type</td>
								</tr>
								<!-- start loop for all players with points -->
		<?php
					$j = 1;
					while($row = mysqli_fetch_array($scoreStats))
					{
						$scorer = GetPlayerFromID($row["G"], $leagueid);
						$assists = "";

						if( $row["A1"] !=0){

							$assist1 = GetPlayerFromID($row["A1"], $leagueid);
							$assists = $assist1["JNo"] . "-" . $assist1["First"] . " " . $assist1["Last"];							

							$assist2 = GetPlayerFromID($row["A2"], $leagueid);
							$assists .= ", " . $assist2["JNo"] . "-" . $assist2["First"] . " " . $assist2["Last"];

						}

						$goalScorer = $scorer["JNo"] . "-" . $scorer["First"] . " " . $scorer["Last"];					

		?>
								<tr class="tight<?php print $stripe[$j & 1]; ?>">
									<td class=""><?=$row["Period"]?></td>
									<td class=""><?=$row["Time"]?></td>
									<td class=""><?=GetTeamABVById($row["TeamID"], $leagueid)?></td>
									<td class=""><?=$goalScorer?></td>
									<td class=""><?=$assists?></td>
									<td class=""><?=$row["Type"]?></td>
								</tr>
		<?php
					$j++;
					}

		?>
					<!-- end of loop -->

					</table>

							<!-- start of Penalty Sum table -->
							<h3>Game <?=$gameNum?> Penalty Summary</h3>
							<table class="standard">
								<tr class="heading">
									<td class="heading">Per</td>
									<td class="heading">Time</td>
									<td class="heading">Team</td>
									<td class="heading">Player</td>
									<td class="heading">Penalty</td>
									<td class="heading">Min</td>
								</tr>
								<!-- start loop for all players with points -->
		<?php
					$j = 1;
					while($row = mysqli_fetch_array($penaltyStats))
					{
						$player = GetPlayerFromID($row["PlayerID"], $leagueid);						
						$penalized = $player["JNo"] . "-" . $player["First"] . " " . $player["Last"];					

		?>
								<tr class="tight<?php print $stripe[$j & 1]; ?>">
									<td class=""><?=$row["Period"]?></td>
									<td class=""><?=$row["Time"]?></td>
									<td class=""><?=GetTeamABVById($row["TeamID"], $leagueid)?></td>
									<td class=""><?=$penalized?></td>
									<td class=""><?=$row["Type"]?></td>
									<td class="">2:00</td>
								</tr>
		<?php
					$j++;
					}

		?>
					<!-- end of loop -->

					</table>
		

					</div>
