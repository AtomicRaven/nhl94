<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        $gamesleaders = GetGamesLeaders2();
        $s = "";

        if (isset($_GET["s"]) && !empty($_GET["s"])) {
				$s =  $_GET["s"];
		}


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
					
					<table class="standard smallify">
						<tr class="heading">
							<td class="c">Team</td>
                            <td class="c"><a href="resultsLeader.php?s=gp">GP</td>							
							<td class="c"><a href="resultsLeader.php?s=w">W</a></td>
							<td class="c"><a href="resultsLeader.php?s=l">L</td>
                            <td class="c"><a href="resultsLeader.php?s=pct">%</a></td>
                            <td class="c"><a href="resultsLeader.php?s=gf">GF</td>
                            <td class="c"><a href="resultsLeader.php?s=ga">GA</td>
                            <td class="c"><a href="resultsLeader.php?s=gfa">GFA</td>
                            <td class="c"><a href="resultsLeader.php?s=gaa">GAA</td>
						</tr>
                        <?php 
                            $j = 1;
                            $sortedLeaders = array();

                            while($row = mysqli_fetch_array($gamesleaders)){  

                                $games = GetGamesByUser($row["id_user"]);
                                $GP = 0;
                                $Wins = 0;   
                                $Losses = 0;
                                $gFor = 0;
                                $gAgainst = 0;
                                $gTotal = 0;   
                                
                                while($game = mysqli_fetch_array($games)){

                                    if($game["WinnerUserID"] == $row["id_user"]){

                                        $Wins++;
                                        //$gFor = $game[""];
                                    }

                                    if($game["HomeUserID"] == $row["id_user"] ){
                                        
                                        $gFor += $game["HomeScore"];
                                        $gAgainst += $game["AwayScore"];
                                    }

                                    if($game["AwayUserID"] == $row["id_user"] ){
                                        
                                        $gFor += $game["AwayScore"];
                                        $gAgainst += $game["HomeScore"];
                                    }


                                    $GP++;                                   

                                }
                                
                                $Losses = $GP - $Wins;
                                $gTotal = $gFor + $gAgainst;  

                                
                                $sortedLeaders[] = array(
                                                    "UserName"=>GetUserAlias($row["id_user"]),
                                                    "Wins"=>$Wins,
                                                    "Losses"=>$Losses,
                                                    "gFor"=>$gFor,
                                                    "gAgainst"=>$gAgainst,
                                                    "gTotal"=>$gTotal,
                                                    "GP"=>$GP,
                                                    "PCT"=>GetAvg($Wins, $GP),
                                                    "GFA"=>GetAvg($gFor, $GP),
                                                    "GAA"=>GetAvg($gAgainst, $GP)
                                );

                                  

                             
                            }

                            $sBy = "Wins";

                            switch ($s) {

                                case 'gp':                                    
                                    usort($sortedLeaders, 'SortByGP');
                                    $sBy = "Games Played";
                                    break;
                                case 'w':   
                                default:                                 
                                    usort($sortedLeaders, 'SortByWins');
                                    $sBy = "Wins";
                                    break;
                                case 'l':                                    
                                    usort($sortedLeaders, 'SortByLosses');
                                    $sBy = "Losses";
                                    break;
                                 case 'pct':                                 					
                                    usort($sortedLeaders, 'SortByPercent');                                                
                                    break;
                                case 'gf':                                    
                                    usort($sortedLeaders, 'SortByGF');
                                    $sBy = "Goals For";
                                    break;
                                case 'ga':                                    
                                    usort($sortedLeaders, 'SortByGA');
                                    $sBy = "Goals Against";
                                    break;
                                case 'gfa':                                    
                                    usort($sortedLeaders, 'SortByGFA');
                                    $sBy = "Goals For Average";
                                    break;                                
                                case 'gaa':                                    
                                    usort($sortedLeaders, 'SortByGAA');
                                    $sBy = "Goals Against Average";
                                    break; 
                               
                            }
                             
                             echo "Sorted By: " . $sBy;

                             foreach($sortedLeaders as $user){  
                        ?>

                                <tr class="<?php print $stripe[$j & 1]; ?>">
                                    <td class="c"><?=$user["UserName"]?></td>
                                    <td class="c"><?=$user["GP"]?></td>
                                    <td class="c"><?=$user["Wins"]?></td>
                                    <td class="c"><?=$user["Losses"]?></td>
                                    <td class="c"><?=$user["PCT"]?></td>
                                    <td class="c"><?=$user["gFor"]?></td>
                                    <td class="c"><?=$user["gAgainst"]?></td>
                                    <td class="c"><?=$user["GFA"]?></td>
                                    <td class="c"><?=$user["GAA"]?></td>
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