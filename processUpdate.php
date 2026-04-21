<?php

session_start();
$ADMIN_PAGE = true;
require_once("./_INCLUDES/00_SETUP.php");

require_once("./_INCLUDES/dbconnect.php");
require_once("./_INCLUDES/errorchk.php");	
require_once("./_INCLUDES/addgame.php");		

if ($LOGGED_IN != true) {
	header('Location: index.php');
	exit;
}

		logMsg ("New Save State");

	// retrieve variables	

	$datemodified = [];
	$gamesUploaded = [];
	$scheduleid = 0;
	$error = "";
	$isBulk = false;

	$seriesid = (int) $_POST['seriesid'];
	$leagueid = (int) $_POST['leagueid'];
	$homeuserid = (int) $_POST['homeUser'];
	$awayuserid = (int) $_POST['awayUser'];
	
	if(isset($_POST['scheduleid'])){
		$scheduleid = (int) $_POST['scheduleid'];
	}

	if(isset($_POST['datemodified'])){
		$datemodified = $_POST['datemodified'];
		$isBulk = true;
	}

	$gamesplayed = GetGamesBySeriesId($seriesid);	

	if($isBulk){
		
		logMsg("BeforeSort:");
		while($row = mysqli_fetch_array($gamesplayed, MYSQLI_ASSOC)){

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
