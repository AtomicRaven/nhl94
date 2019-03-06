<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

            if (isset($_FILES['csv']['name'])) {

                $filename = $_FILES['csv']['name'];
                $isblitz = $_POST["blitz"];                
                $binName = $_POST["binName"];                
                $upload_path = $GLOBALS['$csvUploadPath'] . $filename;

                //echo "CSV Path:" . $upload_path. $filename;
                echo "isBlitz:" . $isblitz . "<br/>";

                $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
                
                if(move_uploaded_file($_FILES['csv']['tmp_name'], $upload_path)){

                    if($ext == ".csv"){

                        $newCsv = $upload_path;
                        $newTable = str_replace(".csv","",$filename);        
                        $newTable = str_replace("_","",$newTable);
                        $newTable = "roster_" . $newTable;

                        echo "newTable:" . $newTable . "<br/>";

                        echo ("Duplicating Roster Table<br/>");
                        DuplicateRosterTable($newTable);

                        //Replace All TeamId data with new Team ID'sin

                        if($binName == ""){
                            $binName = $newTable;
                        }

                        TransferPlayerRoster($newTable, $newCsv, $isblitz, $binName);
                    }else{
                        echo "<br/>Wrong file type!";    
                    }
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