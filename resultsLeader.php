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
        $homeuserid = 0;
        $awayuserid = 0;
        $recordStyle = "all";
        $leagueType = -1;

        if (isset($_GET["homeUser"]) && isset($_GET["awayUser"])) {

		    $homeuserid = $_GET['homeUser'];
		    $awayuserid = $_GET['awayUser'];           

        }  

        if (isset($_GET["leagueType"])){

            $leagueType = $_GET["leagueType"];
        }      
        
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
       
        $homeUserSelectBox = CreateSelectBox("homeUser", "Select User", GetUsers(true), "id_user", "username", null, $homeuserid);
        $awayUserSelectBox = CreateSelectBox("awayUser", "Select User", GetUsers(true), "id_user", "username", null, $awayuserid);
        $leagueTypeSelectBox = CreateSelectBox("leagueType", "Select League", GetLeagueTypes(), "LeagueID", "Name", null, $leagueType);


?><!DOCTYPE HTML>
<html>
<head>
<title>Leaderboard Games</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					<h1>Leaderboard Games</h1>	
                    <span class="note"><a href="resultsLeaderSeries.php">Leaderboard Series</a></span>				
					
                    <div id="msg" style="color:red;"></div>

                    <form name="seriesForm" method="get" action="resultsLeader.php">                    
                    <?=$homeUserSelectBox?> &nbsp; <?=$awayUserSelectBox?> &nbsp; <?=$leagueTypeSelectBox?>
                    
                    &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Go</button>                    
					
                        <?php 
                            $j = 1;
                            $sortedLeaders = array();

                            while($row = mysqli_fetch_array($gamesleaders)){  
                                
                                if ($recordStyle == "h2h") {

                                    $games = GetHeadToHead($homeuserid, $awayuserid, $leagueType);

                                }else{

                                    $games = GetGamesByUser($row["id_user"], $leagueType);
                                    
                                }
                                
                                $GP = 0;
                                $Wins = 0;   
                                $Losses = 0;
                                $gFor = 0;
                                $gAgainst = 0;
                                $gTotal = 0;

                                $numGames = mysqli_num_rows($games);

                                if($numGames > 0){
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
                                    $sBy = "Percent";                                                
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
                             
                             if( $numGames >0 ){

                                 $sBy = "Sorted By: " . $sBy;                                 
                                 
                             }else{

                                $sBy = "These two coaches have never played each other.";

                             }

                        ?>
                        <div><?=$sBy?></div><br/>
                        <?php
                             if( $numGames >0 ){
                        ?>
                        
                        <table class="standard smallify leader">
						<tr class="heading">
							<td class="c">Team</td>
                            <td class="c">GP</td>							
							<td class="c"><button type="submit" name="s" value="w">W</button></td>
							<td class="c"><button type="submit" name="s" value="l">L</button></td>
                            <td class="c"><button type="submit" name="s" value="pct">%</button></td>
                            <td class="c"><button type="submit" name="s" value="gf">GF</button></td>
                            <td class="c"><button type="submit" name="s" value="ga">GA</button></td>
                            <td class="c"><button type="submit" name="s" value="gfa">GFA</button></td>
                            <td class="c"><button type="submit" name="s" value="gaa">GAA</button></td>
						</tr>

                        <?php
                             }
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
					
                    </form>
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>