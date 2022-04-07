<?php

    session_start();
	$ADMIN_PAGE = true;
    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

    $leagueType = -1;
    $activate = -1;

     if (isset($_GET["drafterTableId"])){

        $drafterTableId = $_GET["drafterTableId"];
    }     

     if (isset($_GET["drafterLink"])){

        $drafterLink = $_GET["drafterLink"];
    }     

    if($drafterLink != "" && $drafterTableId != -1){

        SetDraft($drafterTableId, $drafterLink);
        $draftLgName = GetLeagueTableABV($drafterTableId);
        $msg = "Table '" . $draftLgName . "' assigned to DraftSheet '" . $drafterLink . "'.";
    }

    //echo "child:" .$childTableId . "<br/>";
    //echo "parent:" .$parentTableId . "<br/>";
    //echo "TblName:" .$leagueName . "<br/>";
    
    header('Location: admin.php?msg=' . $msg );

}else{
    header('Location: index.php');
}


?>