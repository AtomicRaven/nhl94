<?php


require_once("dbconnect.php");

function ErrorCheck($seriesid, $scheduleid, $tourneyid){	
	
	$filePath = $GLOBALS['$saveFilePath'];

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

	$filename = $_FILES['uploadfile']['name']; // Get the name of the file (including file extension).
	//echo "fileName:" . $filename;
	$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.

	$upload_path = $filePath . "/" . "Series-" . $seriesid. '-game-'. $scheduleid . '.sv';
	//echo "uploadPath:" . $upload_path;
	//Check to make sure game being uploaded is correct
	$row = GetScheduleByID($scheduleid);

	if($row){

		// Check type of league and check file
		
		$e = '.bad';
		
		$filetypes = array('.gs0','.gs1','.gs2','.gs3','.gs4','.gs5','.gs6','.gs7','.gs8','.gs9');
		$e = '.gs0';	
	
		if(in_array($ext, $filetypes)){  // file ext is OK

			if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $upload_path)){
				
				// Check if teams are correct
				$fr = fopen("$upload_path", 'rb');	// reads file				
				
				// Away Team
				fseek ($fr,59307);
				$StateAwayID = hexdec(bin2hex(fread($fr, 1))) + 1;

				// Home Team
				fseek ($fr,59305);
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