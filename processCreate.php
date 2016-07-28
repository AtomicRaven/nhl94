<?php
		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		require_once("./_INCLUDES/dbconnect.php");
		
		// custom code
		// add new series to db

		$seriesname = $_POST['seriesName'];
		$hometeam = $_POST['homeTeam'];
		$awayteam = $_POST['awayTeam'];

		$homeuserid = $_POST['homeUser'];
		$awayuserid = $_POST['awayUser'];

		//Add a new series

		$seriesid = AddNewSeries($seriesname, $hometeam, $awayteam, $homeuserid, $awayuserid);


		//Logs for debugging
		logMsg("SeriesId:" . $seriesid);
		logMsg("name:" . $seriesname);
		logMsg("home:" . $hometeam);
		logMsg("away:" . $awayteam);
		logMsg("awayUser:" . $homeuserid);
		logMsg("homeUser:" . $awayuserid);	
		
	
		// redirect to Update Existing series
		header('Location: update.php?seriesId=' . $seriesid);

						
		
?>