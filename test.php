<?php
		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

        $gamesplayed = GetGamesBySeriesId(198);
        $sGoalieStats = GetPlayerStatsBySeriesId(198, 'G', 3);
        $series = GetSeriesById(198);

        $sHomeShots = 0;
        $sAwayShots = 0;
        $savePct = 0;

        while($row = mysqli_fetch_array($gamesplayed)){
            if($row["GameID"] != NULL){
					
					$gStats = GetGameById($row["GameID"]);
				
                    if($series["HomeUserID"] == $row["HomeUserID"]){
                        $sHomeShots += $gStats["SHP1"] + $gStats["SHP2"] + $gStats["SHP3"];
                        $sAwayShots += $gStats["SAP1"] + $gStats["SAP2"] + $gStats["SAP3"];
                    }else{                            
                        $sAwayShots += $gStats["SHP1"] + $gStats["SHP2"] + $gStats["SHP3"];
                        $sHomeShots += $gStats["SAP1"] + $gStats["SAP2"] + $gStats["SAP3"];
                    }
            }
        }

        while($row = mysqli_fetch_array($sGoalieStats))
					{
						$player = GetPlayerFromID($row["PlayerID"], 3);															

						if($row["Pos"] == "G"){

						//		if($row["SOG"]!= 0){									

									$savePct = $row["tSOG"];									
                                    echo "Saves= " .$savePct . "</br>";
						//		}
                        }
                    }

        echo "HomeShots= " . $sHomeShots ."</br>";
        echo "AwayShots= " . $sAwayShots ."</br>";
        


?>