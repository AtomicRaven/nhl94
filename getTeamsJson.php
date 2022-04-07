<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        
        $leagues = GetTeams($leagueType);
       
            
?>
                                
<?php                                    
   
    $leagueArr = array();

    while($row = mysqli_fetch_array($leagues)){                                          

        array_push($leagueArr,$row);
   
    }

    //Sort by Overall    
    
    echo json_encode($leagueArr, JSON_NUMERIC_CHECK);    
?>

                        
                                                         