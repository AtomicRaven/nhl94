<?php

function logMsg($msg){

	//echo $msg . "</br>";

}

function CleanTable($tableName){

	$conn = $GLOBALS['$conn'];
	$sql = "DELETE FROM " . $tableName;

	$tmr = mysqli_query($conn, $sql);
		
	if ($tmr) {
		logMsg("Delete Data From Table: " .$tableName);
	} else {
		echo("Error: CleanTable " . $sql . "<br>" . mysqli_error($conn));
	}

}

/*********************************************************************/
function ChkPass($userid, $pwd){

    // Retrieve password

    $conn = $GLOBALS['$conn'];

    $uq = "SELECT * FROM Users WHERE Alias = '$userid' LIMIT 1";

    $ur = mysqli_query($conn, $uq);

    if(mysqli_num_rows($ur)){
        $urow = mysqli_fetch_array($ur, MYSQL_ASSOC);
        if($pwd == $urow['Password'])
            return $urow;
        else
            return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE
    }

    return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE
    
}  // end of function

    function CreateSelectBox($selectName, $selectTitle, $data, $id, $value, $onChange, $indexSelected){

        $selectBox = "<select id='" . $selectName . "' name='" . $selectName . "'";

        if($onChange != null){
            $selectBox .= "onchange='" . $onChange;
        }        

        $selectBox .= "'>";

        if($selectTitle != null){
            $selectBox .= "<option value='0'>" . $selectTitle . "</option>";
        }		
        
        while($row = mysqli_fetch_array($data)){

            $selectBox .= "<option value='" . $row[$id] . "'"; 
            
            if($indexSelected != null){

                if($row[$id] == $indexSelected){

                    $selectBox .= " selected";
                }
            }

            $selectBox .= ">" . $row[$value] . "</option>";
        }	
        
        $selectBox .= "</select>";

        return $selectBox;

    }	

?>