<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		$showAll = null;

		if(isset($_GET['showAll'])){
			$showAll = true;
		}

		if($showAll)
			$allseries = GetSeriesAndGames(false, 0, null);
		else
			$allseries = GetSeriesAndGames(false, 0, 25);		
		
		$seriesHtml = "";	
		$i = 0;

		while($row = mysqli_fetch_array($allseries)){

			$leagueid = $row["LeagueID"];
			$leagueName = GetLeagueTableABV($leagueid); 
			$gamesCompleteText = "";
			$lastEntryTime = "";
			$homeTeamPlayer = GetUserAlias($row["HomeUserID"]);
			$awayTeamPlayer = GetUserAlias($row["AwayUserID"]);
			$stanleyClass = "";
			$bestofNum = NumGamesPerSeries($row["SeriesID"]);

			if($row["SeriesWonBy"] != 0){				

				$gamesNeededToWin = NeededWins($row["SeriesID"]);
				
				// got rid of player ID
				//$homeTeam .= " (" . GetTeamABVById($row["HomeTeamID"]) . ")";
				//$awayTeam .= " (" . GetTeamABVById($row["AwayTeamID"]) . ")";
				$homeTeam = " " . GetTeamABVById($row["HomeTeamID"], $leagueid);
				$awayTeam = " " . GetTeamABVById($row["AwayTeamID"], $leagueid);

				//$homeTeam .= "<br/><span class='note'>Best of " . $bestofNum . "</span>"; 

				//echo "Num:" . $numGames;

				$totalGames = $gamesNeededToWin + $row["LoserNumGames"];
				$lastEntryTime = "Series Completed " . $row["DateCompleted"];

				if($row["SeriesWonBy"] == $row["HomeUserID"]){
					$loser = GetUserAlias($row["AwayUserID"]);
				}else{
					$loser = GetUserAlias($row["HomeUserID"]);
				}
				
				// ***
				$gamesCompleteText = GetUserAlias($row["SeriesWonBy"]) . " wins in ".$totalGames." vs " . $loser;
				//$gamesCompleteText .= '<br /><span class="note">(' . $homeTeamPlayer . ' v ' . $awayTeamPlayer . ')</span>';
				$stanleyClass = "stanley";

			}else if($row["TotalGames"] == 0){
				$lastEntryTime = "Series Created " . $row["DateCreated"];
				//$gamesCompleteText = "Series not yet started.";

			}else{
				$homeTeam = " " . GetTeamABVById($row["HomeTeamID"], $leagueid);
				$awayTeam = " " . GetTeamABVById($row["AwayTeamID"], $leagueid);
				$gamesCompleteText .= $homeTeamPlayer . ' vs ' . $awayTeamPlayer;
				$gamesCompleteText .= "&nbsp;In progress (" .$row["TotalGames"]. " gms)";					
				$lastEntryTime = "Last Updated " . HumanTiming($row["LastEntryDate"]) . " ago";
			}

			$seriesHtml .= '<tr';

			if($i % 2 == 0){
				$seriesHtml .= " class='stripe'";
			}

			//Format the Date 
			$formattedEntryDate = GetDateFromSQL($row["LastEntryDate"]);
			
			$seriesHtml .= '>';
			$seriesHtml .= '<td class="c">'.$row['SeriesID'].'</td>';
			$seriesHtml .= '<td class="c"><div class="logo small ' . $awayTeam . '"></div>vs<div class="logo small '.$homeTeam.'"></div></td>';
			$seriesHtml .= '<td class="">'.$gamesCompleteText.'<br />'; 
			$seriesHtml .= '<span class="note">Best of ' . $bestofNum . '<br/>Updated ' . $formattedEntryDate. '<br/>Bin: ' . $leagueName . '</span></td>';
			$seriesHtml .= '<td class="c"><button type="button" class="square" onclick="location.href=\'resultsSeries.php?seriesId='. $row['SeriesID'].'\'">Select</button>';

			if($LOGGED_IN == true)
              if($_SESSION['userId'] == $row["HomeUserID"] || $_SESSION['userId'] == $row["AwayUserID"])
					$seriesHtml .= '<br/><button type="button" style="margin-top: 10px;" class="square" onclick="location.href=\'update.php?seriesId='. $row['SeriesID'].'\'">&nbsp;Edit&nbsp;</button></td>';
          
			$seriesHtml .= '</tr>';

			$i++;
			
			//$seriesHtml .= '<td><button type="button" class="square" onclick="DeleteSeries(\'' .$row['SeriesID'].'\',\'' . $row["Name"] .'\')">X</button></td>';
			//$seriesHtml .= '<td class="c">' . $row['SeriesID'] .'</td>';
			//$seriesHtml .= '<td class=""><b>'.$row['Name'].'</b> - <nobr>' . $gamesCompleteText.'</nobr><br />';
			//$seriesHtml .= '<span class="note">'.$lastEntryTime. '</span><br />';			
			//$seriesHtml .= '<td class="r"><button type="button" class="square" onclick="location.href=\'update.php?seriesId='. $row['SeriesID'].'\'">Select</button></td>';
			
		}	

?><!DOCTYPE HTML>
<html>
<head>
<title>Series Results</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					<h1>Results</h1>	
					<h2>Results for All Series (<?=$i?> Results)</h2>
					
					<button type="button" onclick="javascript:location.href='results.php?showAll=true'" style="margin-top: 10px;">Show All</button>
					<table class="standard">
						<tr class="heading">
							<td class="c"><span class="note">series</span><br />#</td>
							<td class="c">Teams</td>
							<td class="c">Status / Update</td>
							<td class="">&nbsp;</td>
						</tr>	
						<?= $seriesHtml ?>
												
					</table>	
					<button type="button" onclick="javascript:location.href='results.php?showAll=true'" style="margin-top: 10px;">Show All</button>
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>