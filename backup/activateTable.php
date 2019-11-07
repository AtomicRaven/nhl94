<?php

    session_start();
	$ADMIN_PAGE = true;
    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

    $leagueType = -1;
    $activate = -1;

     if (isset($_GET["leagueType"])){

        $leagueType = $_GET["leagueType"];
    }     

     if (isset($_GET["activate"])){

        $activate = $_GET["activate"];
    }     

    if($activate != -1 && $leagueType != -1){

        ActivateTable($leagueType, $activate);    
        $leagueName = GetLeagueTableABV($leagueType);
        $msg = ($activate == 1 ? "activated" : "deactivated");
    }

    //echo "lg:" .$leagueType . "<br/>";
    //echo "Active:" .$activate . "<br/>";
    //echo "TblName:" .$leagueName . "<br/>";
    
    header('Location: admin.php?msg=Table ' . $leagueName . ' ' . $msg );

}else{
    //header('Location: index.php');
}


?>