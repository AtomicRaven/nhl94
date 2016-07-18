<?php
		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		require_once("./_INCLUDES/dbconnect.php");
		
		// custom code
		// add new series to db

		$seriesname = $_POST['series_name'];
		$hometeam = $_POST['HomeTeam'];
		$awayteam = $_POST['AwayTeam'];

		$homeuserid = $_POST['HomeUser'];
		$awayuserid = $_POST['AwayUser'];

		logMsg("name:" . $seriesname);
		logMsg("home:" . $hometeam);
		logMsg("away:" . $awayteam);
		logMsg("awayUser:" . $homeuserid);
		logMsg("homeUser:" . $awayuserid);

		//Add a new series

		$seriesid = AddNewSeries($seriesname, $hometeam, $awayteam, $homeuserid, $awayuserid);
		logMsg("SeriesId:" . $seriesid);	
		
	
		// redirect to Update Existing series
		header('Location: update.php?series_id=' . $seriesid);

						
		
?>