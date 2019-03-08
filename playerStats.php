<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        $limit = 15;
        $homeuserid = 0;
        $pos = "P";
        $fChecked = 'checked';
        $gChecked = '';

        $pTitleArray = array("Pos","GP","G","A","PTS","AvgPts","SOG","SPCT","Chk","PEN","PP","SH","TOIG");
        $gTitleArray = array("Pos","GP","GAA","GA","SV","TSA","SV%","SH","TOI/G","A","PTS","", "PTS/G");        

        $TnA = $pTitleArray;

        if (isset($_GET["s"]) && !empty($_GET["s"])) {

		    $s =  $_GET["s"];
            
		}else{

            $s = "G";
        }
        
        if (isset($_GET["pos"])){

            $pos = $_GET["pos"];

            if($pos == 'G'){
                $fChecked = '';
                $gChecked = 'checked';
                $TnA = $gTitleArray;
            }                
        }              

        if (isset($_GET["homeUser"])) {

		    $homeuserid = $_GET['homeUser'];

        }  
                
        $leagueTypeSelectBox = CreateSelectBox("leagueType", null, GetLeagueTypes(), "LeagueID", "Name", null, $leagueType);
        $homeUserSelectBox = CreateSelectBox("homeUser", "Select Coach", GetUsers(true), "id_user", "username", null, $homeuserid);
        $GLOBALS["subLg"] = GetSubLeagues($leagueType);

        if($homeuserid > 0){
            $rosters = GetPlayerStatsByCoachId($pos, $leagueType, $homeuserid, $limit);            
        } else {
            $rosters = GetPlayerStats($pos, $leagueType);
        }

    
?><!DOCTYPE HTML>
<html>
<head>
<title>Player Stats</title>
<?php include_once './_INCLUDES/01_HEAD.php';
 ?>

</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
                    <h1>Player Stats</h1>		
                    <a href="resultsLeaderSeries.php" class="square-button">Series Stats</a>	
                    <a href="resultsLeader.php" class="square-button">Game Stats</a>
                    <a href="playerStats.php" class="square-button">Player Stats</a>			
                    
                    <form name="rosterForm" method="get" action="playerStats.php">
                        <?php
                            echo $homeUserSelectBox;    
                            echo $leagueTypeSelectBox;                                                                                        
                        ?>

                        &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Go</button>   

                        <div style="display:inline;visibility: <?=  $vis?>;">
                            <input type="radio" value="F" name="pos" <?=$fChecked?>/>Forwards                            
                            <input type="radio" value="G" name="pos" <?=$gChecked?>/>Goalies                        
                        </div>

                        
                                
                                <?php     
                  
                                   while($p = mysqli_fetch_array($rosters)){ 	                                            

                                            $avgPts = number_format($p["tPoints"] / $p["GP"], 2);
                                            $timePerGame = round($p["tTOI"]/ $p["GP"]);
                                            $TOIG = secondsToTime($timePerGame);	
                                            $SPCT = GetPercent($p["tG"], $p["tSOG"]);
                                            $player = GetPlayerFromID($p["PlayerID"], $p["LeagueID"]);
                                            $team = GetTeamABVById($p["TeamID"], $p["LeagueID"]);

                                            $sortedPlayers[] = array(
                                                "Player"=>$player["First"] . " " . $player["Last"],
                                                "Team"=>$team,
                                                "Pos"=>$p["Pos"],
                                                "GP"=>$p["GP"],
                                                "tG"=>$p["tG"],
                                                "tA"=>$p["tA"],
                                                "tPoints"=>$p["tPoints"],
                                                "avgPts"=>$avgPts,
                                                "tSOG"=>$p["tSOG"],
                                                "SPCT"=>$SPCT,
                                                "tChks"=>$p["tChks"],
                                                "tPIM"=>$p["tPIM"],
                                                "TOIG"=>$TOIG                                                
                                            );
                                    }

                                    $sBy = "Goals";

                                    switch ($s) {
        
                                        case 'GP':                                    
                                            usort($sortedPlayers, 'SortByGP');
                                            $sBy = "Games Played";
                                            break;
                                        case 'G':                                    
                                            usort($sortedPlayers, 'SortByG');
                                            $sBy = "Goals";
                                            break;
                                        case 'A':                                    
                                            usort($sortedPlayers, 'SortByA');
                                            $sBy = "Assists";
                                            break;                                           
                                        case 'AvgPts':                                    
                                            usort($sortedPlayers, 'SortByAVGPts');
                                            $sBy = "Avg Points / Game";
                                            break; 
                                        case 'PTS':                                    
                                            usort($sortedPlayers, 'SortByPts');
                                            $sBy = "Avg Points / Game";
                                            break; 
                                        case 'SOG':                                    
                                            usort($sortedPlayers, 'SortBySOG');
                                            $sBy = "Shots on Goal";
                                            break;
                                        case 'SPCT	':                                    
                                            usort($sortedPlayers, 'SortBySPCT');
                                            $sBy = "Shot Percentage";
                                            break;
                                        case 'Chk':                                    
                                            usort($sortedPlayers, 'SortByChk');
                                            $sBy = "Checks";
                                            break;
                                        case 'PEN':                                    
                                            usort($sortedPlayers, 'SortByPEN');
                                            $sBy = "Total Penalties";
                                            break;
                                        case 'TOIG':                                    
                                            usort($sortedPlayers, 'SortByTOIG');
                                            $sBy = "Average Time on Ice / Game";
                                            break;                                       
                                    }

                                    $sBy = "Sorted By: " . $sBy;

                                    ?>

                                <div style="margin-top: 10px;"><?=$sBy?></div><br/>
                                <table class="standard smallify leader">                        
                                <tr class="heading">
                                    <td class="c"></td>
                                    <td class="c">Name</td>
                                    <td class="c">Tm</td>
                                    <td class="c"><?=$TnA[0]?></td>
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[1]?>"><?=$TnA[1]?></button></td>
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[2]?>"><?=$TnA[2]?></button></td>
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[3]?>"><?=$TnA[3]?></button></td>
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[4]?>"><?=$TnA[4]?></button></td>
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[5]?>"><?=$TnA[5]?></button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[6]?>"><?=$TnA[6]?></button></td>                                                                                                  
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[7]?>"><?=$TnA[7]?></button></td>
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[8]?>"><?=$TnA[8]?></button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[9]?>"><?=$TnA[9]?></button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="<?=$TnA[12]?>"><?=$TnA[12]?></button></td>                                    
                                    <!--<input id="sOrder" type="hidden" name="sOrder" value="<?=$nSortOrder?>"/> -->
                                </tr>

                                    <?php 
                                    $i = 1;                               
                                        foreach($sortedPlayers as $sp){  
                                    ?>

                                    <tr class="<?php print $stripe[$i & 1]; ?>">                                    

                                        <td class="c"><?=$i?></td>  
                                        <td class="c"><?=$sp["Player"]?></td>
                                        <td class="c"><?=$sp["Team"]?></td>
                                        <td class="c"><?=$sp["Pos"]?></td>
                                        <td class="c"><?=$sp["GP"]?></td>
                                        <td class="c"><?=$sp["tG"]?></td>
                                        <td class="c"><?=$sp["tA"]?></td>
                                        <td class="c"><?=$sp["tPoints"]?></td>
                                        <td class="c"><?=$sp["avgPts"]?></td>
                                        <td class="c"><?=$sp["tSOG"]?></td>
                                        <td class="c"><?=$sp["SPCT"]?></td>
                                        <td class="c"><?=$sp["tChks"]?></td>
                                        <td class="c"><?=$sp["tPIM"]?></td>                                    
                                        <td class="c"><?=$sp["TOIG"]?></td>
                                    </tr>					                           
                        
                                <?php
                                     $i++;
                                    }
                                ?>
                        </table>                       
                        
                    </form>
                </div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>                                