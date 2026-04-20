<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	


        $str = $_SERVER['QUERY_STRING'];
        $compare = false;
        $leagueid = 1;
        
        if (isset($_GET["binId"])){
            $leagueid = $_GET["binId"];
        }

        $leagues = GetLeagueTypes();
       
            
?>
                                
<?php                                    
   
    $leagueArr = array();

    while($row = mysqli_fetch_array($leagues)){                                          

        array_push($leagueArr,$row);
   
    }

    //Sort by Overall    
    
    echo json_encode($leagueArr, JSON_NUMERIC_CHECK);    
?>

                        
                                                         