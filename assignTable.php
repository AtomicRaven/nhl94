<?php

    session_start();
	$ADMIN_PAGE = true;
    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

    $leagueType = -1;
    $activate = -1;

     if (isset($_GET["childTableId"])){

        $childTableId = $_GET["childTableId"];
    }     

     if (isset($_GET["parentTableId"])){

        $parentTableId = $_GET["parentTableId"];
    }     

    if($parentTableId != -1 && $childTableId != -1){

        AssignTable($childTableId, $parentTableId);
        $childLgName = GetLeagueTableABV($childTableId);
        $parentLgName = GetLeagueTableABV($parentTableId);
        $msg = "Child Lg '" . $childLgName . "' assigned to MasterLg '" . $parentLgName . "'.";
    }

    //echo "child:" .$childTableId . "<br/>";
    //echo "parent:" .$parentTableId . "<br/>";
    //echo "TblName:" .$leagueName . "<br/>";
    
    header('Location: admin.php?msg=' . $msg );

}else{
    header('Location: index.php');
}


?>