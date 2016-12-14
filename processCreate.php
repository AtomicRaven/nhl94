<?php
		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		require_once("./_INCLUDES/dbconnect.php");
		
		// custom code
		// add new series to db
//print_r($_POST);
		$seriesname = $_POST['seriesName'];
		//$hometeam = $_POST['homeTeam'];
		//$awayteam = $_POST['awayTeam'];
		$seriestype = $_POST['seriesType'];
		$leaguetype = $_POST['leagueType'];
		$numGames = $_POST['numGames'];

		$homeuserid = $_POST['homeUser'];
		$awayuserid = $_POST['awayUser'];

		//Add a new series

		//$seriesid = AddNewSeries($seriesname, $hometeam, $awayteam, $homeuserid, $awayuserid, $seriestype);

		//No more teams all open ended
		$seriesid = AddNewSeries($seriesname, $homeuserid, $awayuserid, $seriestype, $numGames, $leaguetype);


		//Logs for debugging
		logMsg("SeriesId:" . $seriesid);
		logMsg("name:" . $seriesname);		
		logMsg("awayUser:" . $homeuserid);
		logMsg("homeUser:" . $awayuserid);
		logMsg("SeriesType:" . $seriestype);	
		logMsg("LeagueType:" . $leaguetype);
		
	
		// redirect to Update Existing series
		header('Location: update.php?seriesId=' . $seriesid);

						
		
?>