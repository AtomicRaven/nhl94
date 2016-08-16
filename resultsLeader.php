<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        $gamesleaders = GetGamesLeaders();


?><!DOCTYPE HTML>
<html>
<head>
<title>Leaderboard</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					<h1>Leaderboard</h1>					
					
					<table class="standard">
						<tr class="heading">
							<td class="c">Team</td>
                            <td class="c">Series Wins</td>
                            <td class="c">GP</td>							
							<td class="c">W</td>
							<td class="c">L</td>
                            <td class="c">PCT</td>
                            <td class="c">GF</td>
                            <td class="c">GA</td>
                            <td class="c">GFA</td>
                            <td class="c">GAA</td>
						</tr>
                        <?php 
                            $j = 1;
                            while($row = mysqli_fetch_array($gamesleaders)){
                                
                                $seriesWins = GetSeriesLeadersByUserID($row["HomeUserID"]);
                        ?>

                                <tr class="<?php print $stripe[$j & 1]; ?>">
                                    <td class="c"><?=GetUserAlias($row["HomeUserID"])?></td>
                                    <td class="c"><?=$seriesWins["sWins"]?></td>
                                    <td class="c"><?=$row["GP"]?></td>
                                    <td class="c"><?=$row["Wins"]?></td>
                                    <td class="c"><?=$row["Losses"]?></td>
                                    <td class="c"><?=GetAvg($row["Wins"], $row["GP"])?></td>
                                    <td class="c"><?=$row["gFor"]?></td>
                                    <td class="c"><?=$row["gAgainst"]?></td>
                                    <td class="c"><?=GetAvg($row["gFor"], $row["gTotal"])?></td>
                                    <td class="c"><?=GetAvg($row["gAgainst"], $row["gTotal"])?></td>
                                </tr>							
                            
                        <?php
                            $j++;
                            }
                        ?>									
					</table>	
					
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>