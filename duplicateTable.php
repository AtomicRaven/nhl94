<?php

    session_start();
	$ADMIN_PAGE = true;
    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';
    include_once './_INCLUDES/01_HEAD.php';

if ($LOGGED_IN == true && ($_SESSION['username']="Atomic" || $_SESSION['username']="Aqua")){

            if (isset($_FILES['csv']['name'])) {

                $filename = $_FILES['csv']['name'];
                $upload_path = $GLOBALS['$csvUploadPath'] . $filename;

                //echo "CSV Path:" . $upload_path. $filename;

                if(move_uploaded_file($_FILES['csv']['tmp_name'], $upload_path)){

                    $newCsv = $upload_path;
                    $newTable = str_replace(".csv","",$filename);                
                    $newTable = str_replace("_","",$newTable);

                    echo "newTable:" . $newTable . "<br/>";

                    echo ("Duplicating Roster Table<br/>");
                    DuplicateRosterTable($newTable);

                    //Replace All TeamId data with new Team ID'sin

                    TransferPlayerRoster($newTable, $newCsv);

                }else{
                    echo "<br/>didn't work";
                }
            }else{

                echo "No CSV";

            }
        
}   else {

    //echo "not logged in";
    header('Location: index.php');
}

?>