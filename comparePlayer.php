<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        $player1Id = -1;
        $player2Id = -1;
        $sBy = "Sorted By: Overall";  
        $sOrder = "DESC";
        $nSortOrder = "DESC";

        if (isset($_GET["sOrder"]) && !empty($_GET["sOrder"])) {

		    $sOrder = $_GET["sOrder"];

            if($sOrder == "DESC")
                $nSortOrder = "ASC";
            else 
                $nSortOrder = "DESC";
            
		}else{

            $nSortOrder = "DESC";

        }

         if (isset($_GET["s"]) && !empty($_GET["s"])) {

		    $s =  $_GET["s"];
            $sBy = "Sorted By: " . $s . " " . $sOrder;  
            
		}else{

            $sBy = "Sorted By: Overall". " " . $sOrder;  
            $s = "";
        }

        

        if (isset($_GET["player1"]) && isset($_GET["player2"])) {

		    $player1Id = $_GET['player1'];
		    $player2Id = $_GET['player2'];           

        }   

         if($player1Id==-1 || $player2Id==-1){
            
            $rosters = GetRosters();

        }else if($player1Id == $player2Id){

            $rosters = GetRosters();
            $sBy = "You've selected the same coach.";

        }else{
            //Head to head
            $recordStyle = "h2h";
            $rosters = ComparePlayers($player1Id, $player2Id);
        }

        $player1SelectBox = CreateSelectBox("player1", "Select Player", GetRosters(), "PlayerID", "Last", null, $player1Id);
        $player2SelectBox = CreateSelectBox("player2", "Select Player", GetRosters(), "PlayerID", "Last", null, $player2Id);

        

?><!DOCTYPE HTML>
<html>
<head>
<title>Compare Player</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					<h1>Compare Player</h1>					
                    
                    <form name="seriesForm" method="get" action="comparePlayer.php">                    
                        <?=$player1SelectBox?> &nbsp; <?=$player2SelectBox?>
                    
                        &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Go</button>   

                        <input type="checkbox" name="forwards" checked/>Forwards
                        <input type="checkbox" name="defense" checked/>Defense
                        <input type="checkbox" name="goalies" checked/>Goalies

                        <div><?=$sBy?></div><br/>
                        <table class="standard smallify leader">
                                <tr class="heading">
                                    <td class="c"></td>
                                    <td class="c">Name</td>
                                    <td class="c"><button type="submit" name="s" value="Handed">Handed</button></td>
                                    <td class="c"><button type="submit" name="s" value="Overall">Overall</button></td>
                                    <td class="c"><button type="submit" name="s" value="Team">Team</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Pos">Pos</button></td>                                                                                                  
                                    <td class="c"><button type="submit" name="s" value="Weight">Weight</button></td>
                                    <td class="c"><button type="submit" name="s" value="Checking">Checking</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="ShotP">Shot<br/>Power</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="ShotA">Shot<br/>Accuracy</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Speed">Speed</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Agility">Agility</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Stick">Stick<br/>Handle</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Pass">Passing</button></td>
                                    <td class="c"><button type="submit" name="s" value="Off">Off<br/>Aware</button></td>
                                    <td class="c"><button type="submit" name="s" value="Def">Def<br/>Aware</button></td>
                                    <input type="hidden" name="sOrder" value="<?=$nSortOrder?>"/>
                                </tr>
                                
                                <?php
                                    $i = 0;
                                    $sortedPlayers = array();

                                    while($row = mysqli_fetch_array($rosters)){                                          

                                        $hField = $row["H/F"];

                                        if ($hField & 1) {
                                            $handed = 'Righty';
                                        } else { 
                                            $handed = 'Lefty';
                                        }

                                         //$handed .= " " . $hField;

                                        $overall = CalculateOverallRanking($row);

                                        $sortedPlayers[] = array(
                                                        "Name"=>$row["First"] . " " . $row["Last"],
                                                        "Handed"=>$handed,
                                                        "Overall"=>$overall,
                                                        "Team"=>$row["Team"],
                                                        "Pos"=>$row["Pos"],
                                                        "Weight"=>$row["Wgt"],
                                                        "Checking"=>$row["ChK"],
                                                        "ShotP"=>$row["ShP"],
                                                        "ShotA"=>$row["ShA"],
                                                        "Speed"=>$row["Spd"],
                                                        "Agility"=>$row["Agl"],
                                                        "Stick"=>$row["StH"],
                                                        "Pass"=>$row["Pas"],
                                                        "Off"=>$row["OfA"],
                                                        "Def"=>$row["DfA"]
                                        );
                                    }

                                    //Sort by Overall
                                    if($s!=""){
                                        //usort($sortedPlayers, 'SortBy');
                                        usort($sortedPlayers, function($a, $b) use ($s, $sOrder) {
                                            //$myExtraArgument is available in this scope
                                            //perform sorting, return -1, 0, 1
                                            return SortBy($a, $b, $s, $sOrder);
                                        });
                                    }else{
                                        usort($sortedPlayers, 'SortByOverall');
                                    }                                    
                                    

                                   foreach($sortedPlayers as $p){  
                                       $i++;
                                ?>
                        
                                 <tr class="<?php print $stripe[$i & 1]; ?>">
                                    
                                    <td class="c"><?=$i?></td>                                    
                                    <td class="c"><?=$p["Name"]?></td>
                                    <td class="c"><?=$p["Handed"]?></td>
                                    <td class="c"><?=$p["Overall"]?></td>
                                    <td class="c"><?=$p["Team"]?></td>
                                    <td class="c"><?=$p["Pos"]?></td>
                                    <td class="c"><?=$p["Weight"]?></td>
                                    <td class="c"><?=$p["Checking"]?></td>
                                    <td class="c"><?=$p["ShotP"]?></td>
                                    <td class="c"><?=$p["ShotA"]?></td>
                                    <td class="c"><?=$p["Speed"]?></td>
                                    <td class="c"><?=$p["Agility"]?></td>
                                    <td class="c"><?=$p["Stick"]?></td>
                                    <td class="c"><?=$p["Pass"]?></td>
                                    <td class="c"><?=$p["Off"]?></td>
                                    <td class="c"><?=$p["Def"]?></td>
                                </tr>					                           
                        
                                <?php
                                    }
                                ?>
                        </table>
                    </form>
                </div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>                                