<?php


require_once("dbconnect.php");

function ErrorCheck($seriesid, $scheduleid, $tourneyid, $file, $type){	
	
	$filePath = $GLOBALS['$saveFilePath'];
	$filename = $file['name'];
	 
	if($tourneyid >0){
		$tourney = GetTourneyById($tourneyid);		
		$leaguenm = $tourney["ABV"];
		$filePath = $filePath . $leaguenm;

	}else{

		$filePath .= $seriesid;
	}	

	if (!file_exists($filePath)) {		

		mkdir($filePath, 0777, true);
	}
	
	logMsg("fileName:" . $filename);

	//Check to make sure game being uploaded is correct
	$row = GetScheduleByID($scheduleid);	

	if($row){		
	
		if($type != ""){  // file ext is OK
			
			$upload_path = $filePath . "/" . "Series-" . $seriesid. '-game-'. $scheduleid . '.sv';
			logMsg("uploadPath:" . $upload_path);

			if(move_uploaded_file($file['tmp_name'], $upload_path)){
				
				// Check if teams are correct
				$fr = fopen("$upload_path", 'rb');	// reads file				
				
				if($type == 'ra'){
					$offset = 9320;
					$endianfix = 1;
				}				
				else {
					$offset = 0;
					$endianfix = 0;
				}
				
				// Away Team
				fseek ($fr,59307 - $offset - $endianfix);
				$StateAwayID = hexdec(bin2hex(fread($fr, 1))) + 1;

				// Home Team
				fseek ($fr,59305 - $offset - $endianfix);
				$StateHomeID = hexdec(bin2hex(fread($fr, 1))) + 1;				

				logMsg("StateHomeID: " . $StateHomeID . "| ScheduleHomeID: " . $row['HomeTeamID']);	
				logMsg("StateAwayID: " . $StateAwayID . "| ScheduleAwayID: " . $row['AwayTeamID']);
				
				//If we want to check games against scedule uncomment this if line
				//if($StateHomeID == $row['HomeTeamID'] && $StateAwayID == $row['AwayTeamID'])  // Teams in state match schedule
					return 0;
				//else{
					return 1;
				//}						
			}
				
			else
				return 5;  // could not upload
		}

		else 
			return 3;  // file ext not correct
	}
	
	
} // end of function

?>