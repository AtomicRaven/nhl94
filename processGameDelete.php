<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

        $seriesid = $_GET['seriesId'];
        $gameid = $_GET['gameId'];

        DeleteGameDataById($gameid, $seriesid);

        header("Location: update.php?seriesId=" . $seriesid . "&err=6" );

?>