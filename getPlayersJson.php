<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	


        $str = $_SERVER['QUERY_STRING'];
        $compare = false;
        parse_str($str, $playerArray);

        $pFilter = array(
            'forwards' => "checked",
            'defense' => "checked",
            'goalies' => "checked"
        );   

        foreach($playerArray as $key => $value){            

            if (strpos($key, 'player') !== false) {                
                $compare = true;
            }else{
                unset($playerArray[$key]);
            }            
        }       

         if(!$compare){            
            $rosters = GetRosters($pFilter);

        }else{            
            $rosters = ComparePlayers($playerArray);
        }
            
?>
                                
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
    
    usort($sortedPlayers, 'SortByOverall');
    echo json_encode($sortedPlayers, JSON_NUMERIC_CHECK);    
?>

                        
                                                         