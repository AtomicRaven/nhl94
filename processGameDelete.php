<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true){

        $seriesid = (int) $_GET['seriesId'];
        $gameid = (int) $_GET['gameId'];
        $redirect = basename($_GET['redirect']);
        if ($redirect != 'update' && $redirect != 'tourney') {
            $redirect = 'update';
        }
        $tId = 0;

        if(isset($_GET['tId']))
            $tId = (int) $_GET['tId'];

        DeleteGameDataById($gameid, $seriesid);

        header("Location: " . $redirect . ".php?seriesId=" . $seriesid . "&err=6&tId=" . $tId );
}else{
    header('Location: index.php');
}


?>
