<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		$allseries = GetSeriesAndGames(false);
		$seriesHtml = "";	
		$i = 0;

		while($row = mysqli_fetch_array($allseries)){

			$gamesCompleteText = "";
			$lastEntryTime = "";
			$homeTeam = GetUserAlias($row["HomeUserID"]);
			$awayTeam = GetUserAlias($row["AwayUserID"]);
			$stanleyClass = "";

			if($row["SeriesWonBy"] != 0){

				$totalGames = 4 + $row["LoserNumGames"];
				$lastEntryTime = "Series Completed " . $row["DateCompleted"];
				$gamesCompleteText = GetUserAlias($row["SeriesWonBy"]) . " wins <nobr>in ".$totalGames."</nobr>" ;
				$stanleyClass = "stanley";

			}else if($row["TotalGames"] == 0){
				$lastEntryTime = "Series Created " . $row["DateCreated"];
				//$gamesCompleteText = "Series not yet started.";

			}else{

				$gamesCompleteText .= "In progress <nobr>(" .$row["TotalGames"]. " gms)</nobr>";	
				$lastEntryTime = "Last Updated " . HumanTiming($row["LastEntryDate"]) . " ago";
			}

			$seriesHtml .= '<tr';

			if($i % 2 == 0){
				$seriesHtml .= " class='stripe'";
			}

			//Format the Date 
			$lastEntryDate = new DateTime($row["LastEntryDate"]);
			$formattedEntryDate = date_format($lastEntryDate, 'M d, Y @ h:i A');

			$seriesHtml .= '>';
			$seriesHtml .= '<td class="c">'.$row['SeriesID'].'</td>';
			$seriesHtml .= '<td class="c">'.$awayTeam .'<br/>@<br/>'.$homeTeam.'</td>';
			$seriesHtml .= '<td class="'. $stanleyClass .'">'.$gamesCompleteText.'<br />'; 
			$seriesHtml .= '<span class="note">Updated ' . $formattedEntryDate. '</span></td>';
			$seriesHtml .= '<td class="c"><button type="button" class="square" onclick="location.href=\'resultsSeries.php?seriesId='. $row['SeriesID'].'\'">Select</button>';

			if($LOGGED_IN == true)
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
					<h2>Results for All Series</h2>
					
					<table class="standard">
						<tr class="heading">
							<td class="c"><span class="note">series</span><br />#</td>
							<td class="c">Teams</td>
							<td class="c">Status / Update</td>
							<td class="">&nbsp;</td>
						</tr>	
						<?= $seriesHtml ?>

						<!--<tr class="stripe">
							<td class="c">101</td>
							<td class="c">MTL<br/>vs<br/>BOS</td>
							<td class="stanley">MTL wins <nobr>in 7</nobr><br /> 
								<span class="note">Updated Aug 01, 2016 @ 6:30pm</span></td>
							<td class="c"><button type="button" class="square" onclick="location.href='resultsSeries.php'">Select</button></td>
						</tr>
						<tr class="">
							<td class="c">100</td>
							<td class="c">TOR<br/>vs<br/>WPG</td>
							<td class="">In progress <nobr>(4 gms)</nobr><br /> 
								<span class="note">Updated Aug 01, 2016 @ 6:30pm</span></td>
							<td class="c"><button type="button" class="square" onclick="location.href='resultsSeries.php'">Select</button></td>
						</tr>
						<tr class="stripe">
							<td class="c">99</td>
							<td class="c">BOS<br/>vs<br/>MTL</td>
							<td class="stanley">BOS wins <nobr>in 6</nobr><br /> 
								<span class="note">Updated Aug 01, 2016 @ 6:30pm</span></td>
							<td class="c"><button type="button" class="square" onclick="location.href='resultsSeries.php'">Select</button></td>
						</tr>	-->											
					</table>	
					
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>