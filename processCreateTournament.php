<?php
		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		require_once("./_INCLUDES/dbconnect.php");
		
		// custom code
		// add new series to db
//print_r($_POST);
		$tournamentName = $_POST['tournamentName'];
		$tournamentType = $_POST['tournamentType'];
		$leaguetype = $_POST['leagueType'];
		$bracketSize = $_POST['bracketSize'];
		$startDate = $_POST['startDate'];

		//No more teams all open ended
		$tournamentid = AddNewTournament($tournamentName, $tournamentType, $leaguetype, $bracketSize, $startDate);

		//Logs for debugging
		logMsg("TournamentId:" . $tournamentid);
		logMsg("Tournament Name:" . $tournamentName);		
		logMsg("Tournament Type:" . $tournamentType);
		logMsg("LeagueType:" . $leaguetype);
		logMsg("BracketSize:" . $bracketSize);
		logMsg("StartDate:" . $startDate);
		
		
			
		// redirect to Update Existing series
		//header('Location: update.php?seriesId=' . $seriesid);

						
		
?>