<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        $leagueType = -1;
        $homeuserid = 0;

         if (isset($_GET["leagueType"])){

            $leagueType = $_GET["leagueType"];
        }     

        if (isset($_GET["homeUser"])) {

		    $homeuserid = $_GET['homeUser'];

        }  

        
        $rosters = GetPlayerStats('P', $leagueType);
        $leagueTypeSelectBox = CreateSelectBox("leagueType", "Select Bin", GetLeagueTypes(), "LeagueID", "Name", null, $leagueType);
        $homeUserSelectBox = CreateSelectBox("homeUser", "Select User", GetUsers(true), "id_user", "username", null, $homeuserid);

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
                    
                    <form name="rosterForm" method="get" action="playerStats.php">
                        <?php
                            echo $leagueTypeSelectBox;                                
                            //echo $homeUserSelectBox;                                
                        ?>

                        &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Go</button>   

                        <div style="display:inline;visibility: <?=  $vis?>;">
                            <!--<input type="checkbox" value="true" name="forwards" onclick="RosterSubmit()" <?=$pFilter['forwards']?>/>Forwards
                            <input type="checkbox" value="true" name="defense" onclick="RosterSubmit()" <?=$pFilter['defense']?>/>Defense
                            <input type="checkbox" value="true" name="goalies" onclick="RosterSubmit()" <?=$pFilter['goalies']?>/>Goalies-->
                        
                            
                        </div>

                        <div style="margin-top: 10px;"><?= "sortBy Goals"?></div><br/>
                        <table class="standard smallify leader">                        
                                <tr class="heading">
                                    <td class="c"></td>
                                    <td class="c">Name</td>
                                    <td class="c">Tm</td>
                                    <td class="c">Pos</td>
                                    <td class="c">GP</td>
                                    <td class="c"><button type="submit" name="s" value="G">G</button></td>
                                    <td class="c"><button type="submit" name="s" value="A">A</button></td>
                                    <td class="c"><button type="submit" name="s" value="PTS">PTS</button></td>
                                    <td class="c"><button type="submit" name="s" value="PTS/G">PTS/G</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="SOG">SOG</button></td>                                                                                                  
                                    <td class="c"><button type="submit" name="s" value="SPCT">SPCT</button></td>
                                    <td class="c"><button type="submit" name="s" value="ChkF">ChkF</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="PEN">PEN</button></td>                                    
                                    <!--<td class="c"><button type="submit" name="s" value="PP">PP</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="SH">SH</button></td>-->
                                    <td class="c"><button type="submit" name="s" value="TOI/G">TOI/G</button></td>                                    
                                    <!--<input id="sOrder" type="hidden" name="sOrder" value="<?=$nSortOrder?>"/>-->
                                </tr>
                                
                                <?php     
                                    $i = 0;                               
                                     while($p = mysqli_fetch_array($rosters)){ 

                                         if($p["Pos"] != "G"){	

                                            $i++;
                                            $avgPts = number_format($p["tPoints"] / $p["GP"], 2);
                                            $timePerGame = round($p["tTOI"]/ $p["GP"]);
                                            $TOIG = secondsToTime($timePerGame);	
                                            $SPCT = GetPercent($p["tG"], $p["tSOG"]);
                                            $player = GetPlayerFromID($p["PlayerID"], $p["LeagueID"]);
                                            $team = GetTeamABVById($p["TeamID"], $p["LeagueID"]);
                                    ?>
                            
                                    <tr class="<?php print $stripe[$i & 1]; ?>">                                    

                                        <td class="c"><?=$i?></td>  
                                        <td class="c"><?=$player["First"] . " " . $player["Last"]?></td>
                                        <td class=""><?=$team?></td>
                                        <td class="c"><?=$p["Pos"]?></td>
                                        <td class="c"><?=$p["GP"]?></td>
                                        <td class="c"><?=$p["tG"]?></td>
                                        <td class="c"><?=$p["tA"]?></td>
                                        <td class="c"><?=$p["tPoints"]?></td>
                                        <td class="c"><?=$avgPts?></td>
                                        <td class="c"><?=$p["tSOG"]?></td>
                                        <td class="c"><?=$SPCT?></td>
                                        <td class="c"><?=$p["tChks"]?></td>
                                        <td class="c"><?=$p["tPIM"]?></td>                                    
                                        <td class="c"><?=$TOIG?></td>
                                    </tr>					                           
                        
                                <?php
                                         }
                                    }
                                ?>
                        </table>

                        &nbsp; <button id="clearBtn" type="button" onclick="javascript:location.href='comparePlayer.php'" style="margin-top: 10px;">Clear</button>                       
                        &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Compare</button> 
                        
                    </form>
                </div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>                                