<?php


require_once("./_INCLUDES/dbconnect.php");
require_once("./_INCLUDES/errorchk.php");	
require_once("./_INCLUDES/addgame.php");		

	logMsg ("New Save State");

	// retrieve variables	

	$datemodified = [];
	$gamesUploaded = [];
	$scheduleid = 0;
	$error = "";
	$isBulk = false;

	$seriesid = $_POST['seriesid'];
	$leagueid = $_POST['leagueid'];
	$homeuserid = $_POST['homeUser'];
	$awayuserid = $_POST['awayUser'];
	
	if(isset($_POST['scheduleid'])){
		$scheduleid = $_POST['scheduleid'];
	}

	if(isset($_POST['datemodified'])){
		$datemodified = $_POST['datemodified'];
		$isBulk = true;
	}

	$gamesplayed = GetGamesBySeriesId($seriesid);	

	if($isBulk){
		
		logMsg("BeforeSort:");
		while($row = mysqli_fetch_array($gamesplayed, MYSQL_ASSOC)){

			$schedule[] = array(
				"ID"=> $row["ID"],
				"HomeUserID" => $row["HomeUserID"],
				"AwayUserID" => $row['AwayUserID']
			);
		}		

		$gamesUploaded = ReArrayFiles($_FILES['uploadfile'], $datemodified, $schedule);
		
		foreach ($gamesUploaded as $file) {		

			$type = GetFileType($file['name']);
			logMsg("FileType:" . $type);

			$scheduleid = $file['scheduleid'];
			$homeuser = $file['HomeUserID'];
			$awayuser = $file['AwayUserID'];
			
			$chk = ErrorCheck($seriesid, $scheduleid, 0, $file, $type);			
			
			if(!$chk){				
				$chk = AddGame($seriesid, $scheduleid, $homeuser, $awayuser, $leagueid, 0, $type);
			}
			else
				$error = $chk;	

			logMsg("Error:" . $error);

			$qString = "seriesId=" . $seriesid;

			if($error != ""){
				$qString .= "&err=". $error;
			}
						
		}
		

	}else{

		$type = GetFileType($_FILES['uploadfile']['name']);
		logMsg("FileType:" . $type);

		$chk = ErrorCheck($seriesid, $scheduleid, 0, $_FILES['uploadfile'], $type);
				
		if(!$chk)
			$chk = AddGame($seriesid, $scheduleid, $homeuserid, $awayuserid, $leagueid, 0, $type);
		else
			$error = $chk;	

		logMsg("Error:" . $error);

		$qString = "seriesId=" . $seriesid;

		if($error != ""){
			$qString .= "&err=". $error;
		}
	}	

	header("Location: update.php?" . $qString );	
	
?>