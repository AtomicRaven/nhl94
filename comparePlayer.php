<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        $player1Id = 0;
        $player2Id = 0;

        if (isset($_GET["player1"]) && isset($_GET["player2"])) {

		    $player1Id = $_GET['player1'];
		    $player2Id = $_GET['player2'];           

        }   

         if($player1Id==0 || $player2Id==0){
            
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

                        <table class="standard smallify leader">
                                <tr class="heading">
                                    <td class="c"></td>
                                    <td class="c">Name</td>
                                    <td class="c">Handed</td>
                                    <td class="c">Overall</td>
                                    <td class="c">Team</td>                                    
                                    <td class="c">Pos</td>                                                                                                  
                                    <td class="c">Weight</td>                                    
                                    <td class="c">Checking</td>                                    
                                    <td class="c">Shot Power</td>                                    
                                    <td class="c">Shot Accuracy</td>                                    
                                    <td class="c">Speed</td>                                    
                                    <td class="c">Agility</td>                                    
                                    <td class="c">Stick Handle</td>                                    
                                    <td class="c">Passing</td>
                                    <td class="c">Off Aware</td>
                                    <td class="c">Def Aware</td>
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
                                    usort($sortedPlayers, 'SortByOverall');

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