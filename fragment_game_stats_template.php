<?php
		include_once './_INCLUDES/dbconnect.php';

		$stripe[0] = '';
		$stripe[1] = ' stripe'; // odd		

		$gameid = $_GET["gameId"];
		$gameNum = $_GET["gameNum"];


		//Get data from DB
		$gStats = GetGameById($gameid);
		$schedule = GetScheduleByGameId($gameid);
		$pStats = GetPlayerStatsByGameId($gameid);

		$homeTeamAbv = GetTeamABVById($schedule["HomeTeamID"]);
		$awayTeamAbv = GetTeamABVById($schedule["AwayTeamID"]);

		$homeTeamName = GetTeamNameById($schedule["HomeTeamID"]);
		$awayTeamName = GetTeamNameById($schedule["AwayTeamID"]);

		$dateSubmitted = new DateTime($schedule["ConfirmTime"]);
		$formattedDate = date_format($dateSubmitted, 'M d, Y @ h:i A');

		///Add up everything

		//Goals per game
		$homeGoals = $gStats["GHP1"] + $gStats["GHP2"] + $gStats["GHP3"]; 
		$awayGoals = $gStats["GAP1"] + $gStats["GAP2"] + $gStats["GAP3"];

		//Shots per game
		$homeShots = $gStats["SHP1"] + $gStats["SHP2"] + $gStats["SHP3"];
		$awayShots = $gStats["SAP1"] + $gStats["SAP2"] + $gStats["SAP3"];

		//Shot Percentage per game
		$homeShotPerCent = FormatPercent($homeGoals,$homeShots);
		$awayShotPerCent = FormatPercent($awayGoals,$awayShots);

		//FaceOffs
		$totalFO = $gStats["FOH"] + $gStats["FOA"]; 
		$FOHP = FormatPercent($gStats["FOH"], $totalFO);
		$FOAP = FormatPercent($gStats["FOA"], $totalFO);

		//Passing
		$pHome = FormatPercent($gStats["PCH"], $gStats["PH"]);
		$pAway = FormatPercent($gStats["PCA"], $gStats["PA"]);

		//AttackZone
		$hZone = FormatZoneTime($gStats["AZH"]);
		$aZone =  FormatZoneTime($gStats["AZA"]);

		//
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

							<h3>Game <?=$gameNum?> Stats</h3>
							<h4>submitted <?=$formattedDate?></h4>

							<table class="standard">
								<tr class="heading">
									<td class="">&nbsp;</td>
									<td class="c"><?=$homeTeamAbv?></td>
									<td class="c"><?=$awayTeamAbv?></td>
								</tr>	
								<tr class="tight"><!-- GOALS -->
									<td class="heading">Goals</td><td class="c"><?=$homeGoals?></td><td class="c"><?=$awayGoals?></td>
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
									<td class="heading"># Penalties</td><td class="c"><?=$gStats["PPA"]?></td><td class="c"><?=$gStats["PPH"]?></td>
								</tr>							
								<tr class="tight"><!-- Breakways -->
									<td class="heading">Breakways</td><td class="c"><?=$gStats["BAH"]?></td><td class="c"><?=$gStats["BAA"]?></td>
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
						$player = GetPlayerFromID($row["PlayerID"]);

						if($row["Pos"] != "G"){
		?>						

								<tr class="tight<?php print $stripe[$j & 1]; ?>">
									<td class=""><?php print $j; ?></td>
									<td class=""><?=$player["First"] . " " . $player["Last"]?></td>
									<td class=""><?=GetTeamABVById($row["TeamID"])?></td>
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
						mysqli_data_seek($pStats, 0);
					
		?>							
								<!-- end of loop -->	

							</table>					


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
					while($row = mysqli_fetch_array($pStats))
					{
						$points = $row["G"] + $row["A"];
						$player = GetPlayerFromID($row["PlayerID"]);
						

						if($row["Pos"] == "G"){
								if($row["SOG"]!= 0)
									$savePct = FormatPercent($row["G"], $row["SOG"]);
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
