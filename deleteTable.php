<?php

    session_start();
	$ADMIN_PAGE = true;
    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

     if (isset($_GET["leagueType"])){

        $leagueType = $_GET["leagueType"];
        $newTable = GetLeagueTableName($leagueType);    
        DropNewRosterTable($newTable);
    }     
    
    //echo $newTable . " Deleted";

    header('Location: admin.php?msg=Table ' . $newTable . ' successfully dropped.');

}else{
    header('Location: index.php');
}


?>