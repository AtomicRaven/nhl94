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
						$seriesid= $_GET['seriesId'];
						$msg = "Series # " . $seriesid ." has been deleted.";
					break;			
					default:
						$msg = "";
					break;
			
				}
			}	

			echo "admin:" .$_SESSION['Admin'];

			// User wants to update an existing series so gab all the series games and show in drop down box
			$allseries = GetSeriesAndGames(true);
			$numSeries = mysqli_num_rows($allseries);
			$seriesHtml = '<table class="hidden lined">';

			while($row = mysqli_fetch_array($allseries)){

				$gamesCompleteText = $row["TotalGames"];
				$lastEntryTime = "";
				$gamesNeededToWin = NeededWins($row["SeriesID"]);

				if($row["SeriesWonBy"] != 0){

					$lastEntryTime = "Series Completed " . $row["DateCompleted"];
					$gamesCompleteText = "Series won by " . GetUserAlias($row["SeriesWonBy"]) . " " . $gamesNeededToWin ." games to " . $row["LoserNumGames"];

				}else if($row["TotalGames"] == 0){
					$lastEntryTime = "Series Created " . $row["DateCreated"];
					$gamesCompleteText = "Series not yet started.";

				}else{

					if($row["TotalGames"] == 1)
						$gamesCompleteText .= " game completed";
					else 
						$gamesCompleteText .= " games completed";

					$lastEntryTime = "Last Updated " . HumanTiming($row["LastEntryDate"]) . " ago";
				}
																									
				$seriesHtml .= '<tr class="">';
				$seriesHtml .= '<td><button type="button" class="square" onclick="DeleteSeries(\'' .$row['SeriesID'].'\',\'' . $row["Name"] .'\')">X</button></td>';
				$seriesHtml .= '<td class="c">' . $row['SeriesID'] .'</td>';
				$seriesHtml .= '<td class=""><b>'.$row['Name'].'</b> - <nobr>' . $gamesCompleteText.'</nobr><br />';
				$seriesHtml .= '<span class="note">'.$lastEntryTime. '</span><br />';
				$seriesHtml .= '<!-- Series creator: matt -->';
				$seriesHtml .= '</td>';
				$seriesHtml .= '<td class="r"><button type="button" class="square" onclick="location.href=\'update.php?seriesId='. $row['SeriesID'].'\'">Select</button></td>';
				$seriesHtml .= '</tr>';
			}	

			$seriesHtml .= '</table>';
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Manage Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h1>Manage</h1>

					<h2>1. Create New Series</h2>

					<div style="color:red;"><?= $msg ?></div><br/><br/>
					
					<table class="two-column">
						<tr class="">
							<td class=""><a href="./create.php" class="square-button">Create</a></td>
							<td class="">Create a new series</td>
						</tr>

					</table>



					<p><br /></p>  	
					<h2>2. Update Existing Series</h2> 
					

						Total Series: <?= $numSeries ?></br>
						<?= $seriesHtml ?>
											  
						<!-- Example
						<table class="hidden lined">
						<tr class="">
							<td><button type="button" class="square">X</button></td>
							<td class="c">101</td>
							<td class=""><b>TOR vs WPG</b> - <nobr>4 games completed</nobr><br />
								<span class="note">Last Updated 3 minutes ago</span><br />								
								</td>
							<td class="r"><button type="button" class="square" onclick="location.href='update.php?seriesId=95'">Select</button></td>
						</tr>
					</table>	
					End Example -->
					
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