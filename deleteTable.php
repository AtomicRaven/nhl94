<?php

    session_start();
	$ADMIN_PAGE = true;
    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

    $newTable = "rosterplabspre6";

    DropNewRosterTable($newTable);

    echo $newTable . " Deleted";

}else{
    header('Location: index.php');
}


?>