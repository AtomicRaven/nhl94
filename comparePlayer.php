<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        $sBy = "Sorted By: Overall";  
        $sOrder = "DESC";
        $nSortOrder = "DESC";
        $s = "";


        //Need to massivley simplfy this logic
        if (isset($_GET["sOrder"]) && !empty($_GET["sOrder"])) {
            
            if (isset($_GET["s"]) && !empty($_GET["s"])) {

                $s =  $_GET["s"];
                $sOrder = $_GET["sOrder"]; 

                //echo "Session: " . $_SESSION['s'] . "<br/>";
                //echo "SortBy: " . $s . "<br/>";

                if($_SESSION['s']){                

                    if($_SESSION['s'] == $s){                                       

                        if($sOrder == "DESC"){
                            $nSortOrder = "ASC";
                            $sOrder = "ASC";
                        }else{
                            $nSortOrder = "DESC";               
                            $sOrder = "DESC";
                        }

                        //echo "Session equals s so flipping" . "<br/>";    

                    }else{

                        //echo "Session did not equal s" . "<br/>";
                        //Do Nothing with sortOrder
                        $nSortOrder = $sOrder;
                        
                    }
                }

                $_SESSION['s'] = $s;
            }

        }

        if (isset($_GET["s"]) && !empty($_GET["s"])) {

            $s =  $_GET["s"];
            $sBy = "Sorted By: " . $s . " " . $sOrder;              
        
        }else{

            $sBy = "Sorted By: Overall". " " . $sOrder;  
        }            

        //Get all the player comparisons
        $str = $_SERVER['QUERY_STRING'];
        $compare = false;
        parse_str($str, $playerArray);

        foreach($playerArray as $key => $value){            

            if (strpos($key, 'player') !== false) {
                $compare = true;
            }else{
                unset($playerArray[$key]);
            }            
        }       

         if(!$compare){
            
            $rosters = GetRosters();

        }else{

            $rosters = ComparePlayers($playerArray);
        }

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

                        &nbsp; <button id="clearBtn" type="button" onclick="javascript:location.href='comparePlayer.php'" style="margin-top: 10px;">Clear</button>                       
                        &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Compare</button>   

                        <!--<input type="checkbox" name="forwards" checked/>Forwards
                        <input type="checkbox" name="defense" checked/>Defense
                        <input type="checkbox" name="goalies" checked/>Goalies-->

                        <div style="margin-top: 10px;"><?=$sBy?></div><br/>
                        <table class="standard smallify leader">
                                <tr class="heading">
                                    <td class="c"></td>
                                    <td class="c">Rnk</td>
                                    <td class="c">Nm</td>
                                    <td class="c"><button type="submit" name="s" value="Handed">Hnd</button></td>
                                    <td class="c"><button type="submit" name="s" value="Overall">Ovrll</button></td>
                                    <td class="c"><button type="submit" name="s" value="Team">Tm</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Pos">Pos</button></td>                                                                                                  
                                    <td class="c"><button type="submit" name="s" value="Weight">Wgt</button></td>
                                    <td class="c"><button type="submit" name="s" value="Checking">Chk</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="ShotP">ShP</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="ShotA">ShA</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Speed">Spd</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Agility">Agl</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Stick">Stk</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Pass">Pass</button></td>
                                    <td class="c"><button type="submit" name="s" value="Off">OffA</button></td>
                                    <td class="c"><button type="submit" name="s" value="Def">DefA</button></td>
                                    <input type="hidden" name="sOrder" value="<?=$nSortOrder?>"/>
                                </tr>
                                
                                <?php                                    
                                    $sortedPlayers = array();

                                    while($row = mysqli_fetch_array($rosters)){                                          

                                        $hField = $row["H/F"];

                                        if ($hField & 1) {
                                            $handed = 'R';
                                        } else { 
                                            $handed = 'L';
                                        }

                                         //$handed .= " " . $hField;

                                        $overall = CalculateOverallRanking($row);

                                        $sortedPlayers[] = array(
                                                        "ID"=>$row["PlayerID"],
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
                                    
                                   $i = 0;
                                   $array = array_values($playerArray);
                                   foreach($sortedPlayers as $p){                                         

                                       $checked = "";

                                       if($compare){                                            
                                            $checked = "checked";                                                 
                                            
                                        }

                                       $i++;
                                ?>
                        
                                 <tr class="<?php print $stripe[$i & 1]; ?>">
                                    
                                    <td class="c"><input type="checkbox" name="player<?=$i?>" value="<?=$p["ID"]?>" <?=$checked?>/></td>
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

                        &nbsp; <button id="clearBtn" type="button" onclick="javascript:location.href='comparePlayer.php'" style="margin-top: 10px;">Clear</button>                       
                        &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Compare</button> 
                    </form>
                </div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>                                