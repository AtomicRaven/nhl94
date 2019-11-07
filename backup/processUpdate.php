<?php


require_once("./_INCLUDES/dbconnect.php");
require_once("./_INCLUDES/errorchk.php");	
require_once("./_INCLUDES/addgame.php");		

	logMsg ("New Save State");

	// retrieve variables	
//print_r($_POST);
	$seriesid = $_POST['seriesid'];
	$leagueid = $_POST['leagueid'];
	$filename = $_FILES['uploadfile']['name'];
	$scheduleid = $_POST['scheduleid'];

	$homeuserid = $_POST['homeUser'];
	$awayuserid = $_POST['awayUser'];

	$error ="";

	//echo "FileName: " . $filename . "</br>";

	logMsg("ScheduleID:" . $scheduleid);
	logMsg("HomeUser:" . $homeuserid);
	logMsg("AwayUser:" . $awayuserid);
	
	$chk = ErrorCheck($seriesid, $scheduleid, 0);
	
	if(!$chk)
		$chk = AddGame($seriesid, $scheduleid, $homeuserid, $awayuserid, $leagueid, 0);
	else
		$error = $chk;	

	logMsg("Error:" . $error);
	
	$qString = "seriesId=" . $seriesid;

	if($error != ""){
		$qString .= "&err=". $error;
	}
	
	header("Location: update.php?" . $qString );	
?>