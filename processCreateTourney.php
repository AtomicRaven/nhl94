<?php
		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		require_once("./_INCLUDES/dbconnect.php");
		require_once("./_INCLUDES/errorchk.php");	
		require_once("./_INCLUDES/addgame.php");
		
		// custom code
		// add new series to db
//print_r($_POST);

		$error = 0;
		$seriesname = $_POST['seriesName'];
		//$hometeam = $_POST['homeTeam'];
		//$awayteam = $_POST['awayTeam'];
		$seriestype = $_POST['seriesType'];
		$leaguetype = $_POST['leagueType'];
		$numGames = $_POST['numGames'];
		$tourneyid = $_POST['tId'];

		$homeuserid = $_POST['homeUser'];
		$awayuserid = $_POST['awayUser'];
		$filename = $_FILES['uploadfile']['name'];

		//Add a new series

		$seriesid = AddNewSeries($seriesname, $homeuserid, $awayuserid, $seriestype, $numGames, $leaguetype, $tourneyid);
		$scheduleid = GetScheduleBySeriesID($seriesid);		

		//Upload Game
		$chk = ErrorCheck($seriesid, $scheduleid, $tourneyid);
	
		if(!$chk)
			$chk = AddGame($seriesid, $scheduleid, $homeuserid, $awayuserid, $leaguetype, $tourneyid);
		else
			$error = $chk;	
		
		logMsg("Error:" . $error);
	
		$qString = 'tId=' . $tourneyid;

		if($error != ""){
			$qString .= "&err=". $error;
		}else{
			$qString .= "&err=0";
		}

		//Logs for debugging
		logMsg("SeriesId:" . $seriesid);
		logMsg("ScheduleId:" . $scheduleid);
		logMsg("name:" . $seriesname);		
		logMsg("awayUser:" . $homeuserid);
		logMsg("homeUser:" . $awayuserid);
		logMsg("SeriesType:" . $seriestype);	
		logMsg("LeagueType:" . $leaguetype);
		logMsg("FileName:" . $filename);
		logMsg("TourneyId:" . $tourneyid);
		
		
	
		// redirect to Update Existing series
		header('Location: tourney.php?'. $qString);

						
		
?>