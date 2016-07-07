<?php

require_once("config.php");
require_once("matt/_INCLUDES/dbconnect.php");
require_once("data.php");


/*********************************************************************/
function chkpass($userid, $pwd){

	// Retrieve password

    $conn = $GLOBALS['$conn'];

	$uq = "SELECT * FROM Users WHERE ID = '$userid' LIMIT 1";

	$ur = mysqli_query($conn, $uq);

	if(mysqli_num_rows($ur)){
		$urow = mysqli_fetch_array($ur, MYSQL_ASSOC);
		if($pwd == $urow['Password'])
			return TRUE;
		else
			return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE
	}

	return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE
	
}  // end of function

function errorcheck($userid, $pwd, $seriesid){
	
	$chk = chkpass($userid, $pwd);

    if($chk){
        $nextGameId = GetNextGameId();
        $filePath = $GLOBALS['$saveFilePath'] . "/" . $seriesid;

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $filename = $_FILES['uploadfile']['name']; // Get the name of the file (including file extension).
        $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
        $upload_path = $filePath . "/" . "Series-" . $seriesid. '-game-'. $nextGameId . '.sv';

        if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $upload_path)){
            echo "File Move ok";
            echo "NextGameId:" . $nextGameId;
        }
    }else{
        return 2;  // password not correct*/
    }
	/*if($chk){	// Pass OK
		$scoreq = "SELECT S.Home Hm, S.Away Aw, S.Sub_League Sublg, H.User_ID HUD, H.Abv HAbv, A.User_ID AUD, A.Abv AAbv
			FROM Schedule S
			JOIN Teams H ON H.Team_ID = S.Home
			JOIN Teams A ON A.Team_ID = S.Away
			WHERE S.Game_ID = '$gameid' LIMIT 1";  // updated to include User_IDs
	
		$scorer = @mysql_query($scoreq) or die('ERROR 2004: Could not retrieve game information.');	
	

		if($scorer){
			$row = mysql_fetch_array($scorer, MYSQL_ASSOC);

			// Check type of league and check file
			
			$e = '.bad';
			
			if (substr($row['Sublg'], 0, 4) == "GENS"){
				$filetypes = array('.gs0','.gs1','.gs2','.gs3','.gs4','.gs5','.gs6','.gs7','.gs8','.gs9');
				$e = '.gs0';
			}
			else if (substr($row['Sublg'], 0, 4) == "SNES"){
				$filetypes = array('.zst','.zs1','.zs2','.zs3','.zs4','.zs5','.zs6','.zs7','.zs8','.zs9');	
				$e = '.zst';
			}
			
			$filename = $_FILES['uploadfile']['name']; // Get the name of the file (including file extension).
			$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
			$upload_path = $_SERVER['DOCUMENT_ROOT']. '/uploaded/'. $lg. '-'. $gameid. '.sv';
//			echo $upload_path;
			
			if(in_array($ext, $filetypes)){  // file ext is OK

				if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $upload_path)){
					
					// Check if teams are correct
					$fr = fopen("$upload_path", 'rb');	// reads file
					
					if (substr($row['Sublg'], 0, 4) == "GENS"){
						// Away Team
						fseek ($fr,59307);
						$StateAwayAbv = getTeamAbv(hexdec(bin2hex(fread($fr, 1))), $lg);

						// Home Team
						fseek ($fr,59305);
						$StateHomeAbv = getTeamAbv(hexdec(bin2hex(fread($fr, 1))), $lg);
						
//					echo $StateAwayAbv. 'vs. '. $StateHomeAbv;
//					die();
						
						if($StateHomeAbv == $row['HAbv'] && $StateAwayAbv == $row['AAbv'])  // Teams in state match schedule
							return 0;
						else
							return 1;  // teams do not match
					}
					
					else if (substr($row['Sublg'], 0, 4) == "SNES"){
						// Away Team
						fseek ($fr,10413);
						$StateAwayAbv = getTeamAbv(hexdec(bin2hex(fread($fr, 1))), $lg);

						// Home Team
						fseek ($fr,10411);
						$StateHomeAbv = getTeamAbv(hexdec(bin2hex(fread($fr, 1))), $lg);		
						if($StateHomeAbv == $row['HAbv'] && $StateAwayAbv == $row['AAbv'])  // Teams in state match schedule
							return 0;
						else
							return 1;  // teams do not match
					}
				
				}
					
				else
    				return 5;  // could not upload
			}
	
			else 
				return 3;  // file ext not correct
		}
	}
	
	else
		return 2;  // password not correct*/

} // end of function

?>