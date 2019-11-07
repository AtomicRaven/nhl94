<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        if (isset($_GET["s"]) && !empty($_GET["s"])) {

		    $s =  $_GET["s"];
            
		}else{

            $s = "";
        }

        $numGames = 0;
        $recordStyle = "all";    
        
        if($homeuserid==0 || $awayuserid==0){
            
            $gamesleaders = GetUsers(false);

        }else if($homeuserid == $awayuserid){

            $gamesleaders = GetUsers(false);
            $sBy = "You've selected the same coach.";

        }else{
            //Head to head
            $recordStyle = "h2h";
            $gamesleaders = CompareUsers($homeuserid, $awayuserid);
        }
       
        $homeUserSelectBox = CreateSelectBox("homeUser", "Select Coach", GetUsers(true), "id_user", "username", null, $homeuserid);
        $awayUserSelectBox = CreateSelectBox("awayUser", "Select Coach", GetUsers(true), "id_user", "username", null, $awayuserid);
        $leagueTypeSelectBox = CreateSelectBox("leagueType", "Select League", GetLeagueTypes(), "LeagueID", "Name", null, $leagueType);
        $GLOBALS["subLg"] = GetSubLeagues($leagueType);


?><!DOCTYPE HTML>
<html>
<head>
<title>Series Stats </title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
                    <h1>Series Stats </h1>				
                    <a href="resultsLeaderSeries.php" class="square-button">Series Stats</a>	
                    <a href="resultsLeader.php" class="square-button">Game Stats</a>
                    <a href="playerStats.php" class="square-button">Player Stats</a>
                    
                    <div id="msg" style="color:red;"></div>

                    <form name="seriesForm" method="get" action="resultsLeaderSeries.php">                    
                    <?=$homeUserSelectBox?> &nbsp; <?=$awayUserSelectBox?> &nbsp; <?=$leagueTypeSelectBox?>
                    
                    &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Go</button>                    
					
                        <?php 
                            $j = 1;
                            $sortedLeaders = array();

                            while($row = mysqli_fetch_array($gamesleaders)){  
                                
                                if ($recordStyle == "h2h") {

                                    $games = GetHeadToHeadSeries($homeuserid, $awayuserid, $leagueType);

                                }else{
                                    
                                    $games = GetSeriesByUser($row["id_user"], $leagueType);
                                    
                                }
                                
                                $GP = 0;
                                $Wins = 0;   
                                $Losses = 0;
                                $gFor = 0;
                                $gAgainst = 0;
                                $gTotal = 0;

                                $numGames = mysqli_num_rows($games);

                                
                                    while($game = mysqli_fetch_array($games)){

                                        if($game["SeriesWonBy"] == $row["id_user"]){

                                            $Wins++;
                                            //$gFor = $game[""];
                                        }

                                        /*if($game["HomeUserID"] == $row["id_user"] ){
                                            
                                            $gFor += $game["HomeScore"];
                                            $gAgainst += $game["AwayScore"];
                                        }

                                        if($game["AwayUserID"] == $row["id_user"] ){
                                            
                                            $gFor += $game["AwayScore"];
                                            $gAgainst += $game["HomeScore"];
                                        }*/


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
                                    $sBy = "Series Played";
                                    break;
                                case 'w':   
                                default:                                 
                                    usort($sortedLeaders, 'SortByWins');
                                    $sBy = "Series Wins";
                                    break;
                                case 'l':                                    
                                    usort($sortedLeaders, 'SortByLosses');
                                    $sBy = "Series Losses";
                                    break;
                                 case 'pct':                                 					
                                    usort($sortedLeaders, 'SortByPercent');
                                    $sBy = "Series Percent";                                                
                                    break;    
                               
                            }
                             
                             

                            $sBy = "Sorted By: " . $sBy;                                 
                                 
                            if( $numGames <1 && $recordStyle == "h2h" ){

                                $sBy = "These two coaches have never played each other.";

                             }

                        ?>
                        <div><?=$sBy?></div><br/>
                       
                        
                        <table class="standard smallify leader">
						<tr class="heading">
							<td class="c">Team</td>
                            <td class="c">SP</td>							
							<td class="c"><button type="submit" name="s" value="w">SW</button></td>
							<td class="c"><button type="submit" name="s" value="l">SL</button></td>
                            <td class="c"><button type="submit" name="s" value="pct">%</button></td>    
						</tr>

                        <?php
                             
                             foreach($sortedLeaders as $user){  
                                if($user["GP"] > 0){
                        ?>

                                <tr class="<?php print $stripe[$j & 1]; ?>">
                                    <td class="c"><?=$user["UserName"]?></td>
                                    <td class="c"><?=$user["GP"]?></td>
                                    <td class="c"><?=$user["Wins"]?></td>
                                    <td class="c"><?=$user["Losses"]?></td>
                                    <td class="c"><?=$user["PCT"]?></td>
                                </tr>					
                            
                        <?php
                            $j++;
                                }
                            }  
                            
                        ?>									
					</table>	
					
                    </form>
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>