<?php

		session_start();
    // error_reporting( E_ALL );
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		$seriesid = $_GET["seriesId"];
		$series = GetSeriesById($seriesid);
		$gamesplayed = GetGamesBySeriesId($seriesid);
		$leagueid= $series["LeagueID"];		
		$leagueName = GetLeagueTableABV($leagueid);

		$sPlayerStats = GetPlayerStatsBySeriesId($seriesid, 'P', $leagueid);
		$sGoalieStats = GetPlayerStatsBySeriesId($seriesid, 'G', $leagueid);

		$homeUserAlias = GetUserAlias($series["HomeUserID"]);
		$awayUserAlias = GetUserAlias($series["AwayUserID"]);
		$gamesNeededToWin = NeededWins($seriesid);

		if($series["SeriesWonBy"] != 0){

			$totalGames = $gamesNeededToWin + $series["LoserNumGames"];
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
		$BAHG = 0;
		$BAAG = 0;		

		//One Timers
		$OneTHG = 0;
		$OneTH = 0;
		$OneTAG = 0;
		$OneTA = 0;

		//Checks
		$BCH = 0;
		$BCA = 0;
    
    $ShootPer = '';

		while($row = mysqli_fetch_array($gamesplayed)){
				
				if($series["HomeUserID"] == $row["WinnerUserID"]){
					$sHomeWins++;
				}else if ($series["AwayUserID"] == $row["WinnerUserID"]){
					$sAwayWins++;
				}
								//ADd up stats for each game
				
				if($row["GameID"] != NULL){
					
					$gStats = GetGameById($row["GameID"]);

					//Total FaceOffs
					$sFO += $gStats["FOH"] + $gStats["FOA"]; 					

					if($series["HomeUserID"] == $row["HomeUserID"]){
						
						$sHomeGoals += $row["HomeScore"];
						$sAwayGoals += $row["AwayScore"];

						$sHomeShooting += $gStats["GHP1"] + $gStats["GHP2"] + $gStats["GHP3"]; 
						$sAwayShooting += $gStats["GAP1"] + $gStats["GAP2"] + $gStats["GAP3"];

						//Shots per game
						$sHomeShots += $gStats["SHP1"] + $gStats["SHP2"] + $gStats["SHP3"];
						$sAwayShots += $gStats["SAP1"] + $gStats["SAP2"] + $gStats["SAP3"];

						//Faceoffs
						$sFOH += $gStats["FOH"];
						$sFOA += $gStats["FOA"];

						//One Timers
						$OneTHG += $gStats["1THG"];
						$OneTH += $gStats["1TH"];
						$OneTAG += $gStats["1TAG"];
						$OneTA += $gStats["1TA"];

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
						$PPH += $gStats["PIMH"];
						$PPA += $gStats["PIMA"];			

						//Breakaways
						$BAH += $gStats["BAH"];
						$BAA += $gStats["BAA"];

						//Breakaway Goals
						$BAHG += $gStats["BAHG"];
						$BAAG += $gStats["BAAG"];

						//Checks
						$BCH += $gStats["BCH"];
						$BCA += $gStats["BCA"];

					}else{

						$sAwayGoals += $row["HomeScore"];
						$sHomeGoals += $row["AwayScore"];

						$sAwayShooting += $gStats["GHP1"] + $gStats["GHP2"] + $gStats["GHP3"]; 
						$sHomeShooting += $gStats["GAP1"] + $gStats["GAP2"] + $gStats["GAP3"];

						//Shots per game
						$sAwayShots += $gStats["SHP1"] + $gStats["SHP2"] + $gStats["SHP3"];
						$sHomeShots += $gStats["SAP1"] + $gStats["SAP2"] + $gStats["SAP3"];
						 
						//FaceOffs
						$sFOA += $gStats["FOH"];
						$sFOH += $gStats["FOA"];		

						//Passing
						$sPA += $gStats["PH"];
						$sPCA += $gStats["PCH"];
						$sPH += $gStats["PA"];
						$sPCH += $gStats["PCA"];

						//AttackZone
						$saZone += strtotime($gStats["AZH"]);
						$shZone += strtotime($gStats["AZA"]);

						//Penalties Shots
						$PSAG += $gStats["PSHG"];
						$PSA += $gStats["PSH"];
						$PSHG += $gStats["PSAG"];
						$PSH += $gStats["PSA"];

						//Penalites
						$PPA += $gStats["PIMH"];
						$PPH += $gStats["PIMA"];

						//Breakaways
						$BAA += $gStats["BAH"];
						$BAH += $gStats["BAA"];

						//Breakaway Goals
						$BAAG += $gStats["BAHG"];
						$BAHG += $gStats["BAAG"];

						//One Timers
						$OneTAG+= $gStats["1THG"];
						$OneTA += $gStats["1TH"];
						$OneTHG += $gStats["1TAG"];
						$OneTH += $gStats["1TA"];

						//Checks
						$BCA += $gStats["BCH"];
						$BCH += $gStats["BCA"];
						

					}				

				}

				
		}

		//Add onetimers to complete passes (genesis bug)
		$sPCH = $sPCH + $OneTH;
		$sPCA = $sPCA + $OneTA;

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

		//Divide Penaltie minutes by 2
		//if(!BlitzChk($leagueid)){
			//$PPH = ($PPH /2) + $PSA;
			//$PPA = ($PPA /2) + $PSH;
		//}

		mysqli_data_seek($gamesplayed, 0);

?><!DOCTYPE HTML>
<html>
<head>
<title>Series Results</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
<meta property="og:description" content="<?= $awayUserAlias ?> vs <?= $homeUserAlias ?>.  <?= strip_tags($gamesCompleteText)?>">
</head>
<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
	
					<h2>Results for a Specific Series</h2>
					<h2>Bin: <?=$leagueName?></h2>					
					
					<table class="standard">
						<tr class="heading">
							<td class="c brt"><span class="note">series</span><br /><!-- Rob: series_id --><?= $seriesid ?></td>
							<td class="" colspan="5" style="padding-top: .7em"><b class="billboard"><?= $awayUserAlias ?> vs <?= $homeUserAlias ?></b></td>
						</tr>			
						<tr class="heading">
							<td class="c brt" style="padding: 2px 0 0 0;">
							<a class="<?=$stanleyClass?>"><br/></a>							
							</td>
							<td class="" colspan="5"><?= $gamesCompleteText?><br /> 
								<span class="note"><?= $lastEntryTime?></span></td>
						</tr>							
						<tr class="heading">
							<td class="c"><span class="note">game</span><br />#</td>
							<td class="c">HOME</td>
							<td class="">&nbsp;</td>
							<td class="c">AWAY</div></td>
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
						$homeClass = "c";
						$awayClass = "c";
						$HOT = "";
						$AOT = "";

						if($row["HomeUserID"] == $row["WinnerUserID"]){
							$homeClass .= " winner";
							if($row["OT"] == 1){
								$HOT = " (OT)";
							} 
						} else {
							$awayClass .= " winner";
							if($row["OT"] == 1){
								$AOT = " (OT)";
							} 
						}
?>				
						<tr class="tight<?php print $stripe[$i & 1]; ?>" >

							<td class="c m"><?php print $i; ?></td>
							<td class="<?=$homeClass?>"><?= GetTeamABVById($row["HomeTeamID"], $leagueid) ?><?=$HOT?><div class='logo small <?= GetTeamABVById($row["HomeTeamID"], $leagueid) ?>'></div></td>
							<td class="<?=$homeClass?> m"><b class="billboard"><?=$row["HomeScore"]?></b></td>
							<td class="<?=$awayClass?>"><?= GetTeamABVById($row["AwayTeamID"], $leagueid) ?><?=$AOT?><div class='logo small <?= GetTeamABVById($row["AwayTeamID"], $leagueid) ?>'></div></td>
							<td class="<?=$awayClass?> m"><b class="billboard"><?=$row["AwayScore"]?></b></td>
							<td class="c m"><button type="button" class="square details" onclick="showGameDetails(this, 'detail_<?php print $i; ?>', <?=$gameid?>, <?=$i?>, <?=$leagueid?>)">+ Details</button></td>
						</tr>	
						<tr class="tight detail_row" id="detail_<?php print $i; ?>" style="display: none" data-game-id="<?=$gameid?>" data-league-id="<?=$leagueid?>">
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

		
					<!-- rob: start of series stats table -->	
					<h2>Series Stats</h2>
					<table class="standard">
						<tr class="heading">
							<td class="">&nbsp;</td>
							<td class="c"><?= $homeUserAlias?></td>
							<td class="c"><?= $awayUserAlias?></td>
						</tr>	
						<tr class="tight"><!-- RECORD -->
							<td class="heading">Record</td><td class="c"><?=$sHomeWins?> wins</td><td class="c"><?=$sAwayWins?> wins</td>
						</tr>							
						<tr class="tight stripe"><!-- GOALS -->
							<td class="heading">Goals</td><td class="c"><?=$sHomeGoals?></td><td class="c"><?=$sAwayGoals?></td>
						</tr>							
						<!--<tr class="tight stripe"><!-- ASSISTS
							<td class="heading">Assists</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight"><!-- POINTS
							<td class="heading">Points</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- Points Per Game
							<td class="heading">PPG</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>-->
						<tr class="tight"><!-- Shooting % -->
							<td class="heading">Shooting %</td><td class="c"><?=$sHomeShotPerCent?></td><td class="c"><?=$sAwayShotPerCent?></td>
						</tr>
						<tr class="tight stripe"><!-- Shots on Goal -->
							<td class="heading">Shots on Goal</td><td class="c"><?=$sHomeShots?></td><td class="c"><?=$sAwayShots?></td>
						</tr>														
						<tr class="tight"><!-- Faceoffs -->
							<td class="heading">Faceoffs</td><td class="c"><?=$sFOHP?></td><td class="c"><?=$sFOAP?></td>
						</tr>							
						<tr class="tight stripe"><!-- Att Zone -->
							<td class="heading">Attack Zone</td><td class="c"><?=$shZoneP?></td><td class="c"><?=$saZoneP?></td>
						</tr>							
						<tr class="tight"><!-- Passing -->
							<td class="heading">Passing</td><td class="c"><?=$spHome?><br/><span class="note"><?=$sPCH?> / <?=$sPH?></span></td>
							<td class="c"><?=$spAway?><br/><span class="note"><?=$sPCA?> / <?=$sPA?></span></td>
						</tr>							
						<tr class="tight stripe"><!-- Penalty Shots -->
							<td class="heading">Pen. Shots</td><td class="c"><?= $PSHG . "/" . $PSH?></td><td class="c"><?= $PSAG . "/" . $PSA?></td>
						</tr>							
						<tr class="tight"><!-- PIM 
																	 This should be number of penalties a team takes.  
																	 Note: Each Penalty shot (for opposition) should be added here, which
																	 will give total penatlies	
																									-->
							<td class="heading"># Penalties</td><td class="c"><?=$PPH?><br/><span class="note">(<?=$PSA?> PS)</span></td>
							<td class="c"><?=$PPA?><br/><span class="note">(<?=$PSH?> PS)</span></td>
						</tr>							
						<tr class="tight stripe"><!-- Breakways -->
							<td class="heading">Breakways</td><td class="c"><?=$BAHG?>/<?=$BAH?></td><td class="c"><?=$BAAG?>/<?=$BAA?></td>
						</tr>							
						<tr class="tight"><!-- One Timers -->
							<td class="heading">One Timers</td><td class="c"><?=$OneTHG . "/" . $OneTH?></td><td class="c"><?=$OneTAG . "/" . $OneTA?></td>
						</tr>	
						<tr class="tight stripe"><!-- Checks -->
							<td class="heading">Checks</td><td class="c"><?=$BCH?></td><td class="c"><?=$BCA?></td>
						</tr>							

					</table>		


					<!-- rob: start of series stats table -->	
					<h3>Series Points Leaderboard</h3>
					<table class="standard smallify">
						<tr class="heading">
							<td class="heading">#</td>
							<td class="heading">Name</td>
							<td class="heading">Tm</td>
							<td class="heading">P</td>
							<td class="heading c" style="font-size: 72%;">G<br/>P</td>
							<td class="heading c" style="font-size: 72%;">P<br/>t<br/>s</td>
							<td class="heading">PPG</td>							
							<td class="heading">G</td>
							<td class="heading">A</td>
              <td class="heading c" style="font-size: 72%;">S<br/>h<br/>%</td>
							<td class="heading c" style="font-size: 72%;">S<br/>O<br/>G</td>
							<!-- <td class="heading">TOI/G</td> -->
							<td class="heading c" style="font-size: 72%;">C<br/>h<br/>k</td>
						</tr>	
						<!-- start loop for all players with points -->
		<?php 
					$j = 1;
					while(($row = mysqli_fetch_array($sPlayerStats)))
					{						
						$player = GetPlayerFromID($row["PlayerID"], $leagueid);						
						$pen = $row["tPIM"];
						
						$timePerGame = round($row["tTOI"]/ $row["GP"]);
						$TOIG = secondsToTime($timePerGame);						
						$avgPts = number_format($row["tPoints"] / $row["GP"], 2);

						if($pen > 0 )
							$pen = $pen / 2;			

            $ShootPer = '-';
            $ShootPerRaw = 0;
            if ( $row["tSOG"] > 0 ) {
              $ShootPerRaw = ( $row["tG"] / $row["tSOG"]) * 100;  
              $ShootPer = number_format($ShootPerRaw, 0, '.', '');
              $ShootPer .= '%';
            }
            
						if($row["Pos"] != "G"){		?>							

								<tr class="tight<?php print $stripe[$j & 1]; ?>">
									<td class=""><?php print $j; ?></td>
									<td class="" style="font-size: 80%;"><?=$player["First"] . " " . $player["Last"]?></td>
									<td class=""><?=GetTeamABVById($row["TeamID"], $leagueid)?></td>
									<td class=""><?=$row["Pos"]?></td>
									<td class="c"><?=$row["GP"]?></td>
									<td class="c"><?=$row["tPoints"]?></td>
									<td class=""><?=$avgPts?></td>
									<td class=""><?=$row["tG"]?></td>
									<td class=""><?=$row["tA"]?></td>
                  <td class="c"><?= $ShootPer ?></td>
									<td class="c"><?=$row["tSOG"]?></td>
									<!-- <td class=""><?=$TOIG?></td>		-->							
									<td class="c"><?= $pen*2 ?></td>
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
						$player = GetPlayerFromID($row["PlayerID"], $leagueid);															

						if($row["Pos"] == "G"){

								if($row["SOG"]!= 0){									

									$savePct = 100 - GetPercent($row["tG"], $row["tSOG"]);
									$savePct = $row["tG"] . "/" . $row["tSOG"] . " (" . $savePct . "%)";
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

				</div>			

		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>