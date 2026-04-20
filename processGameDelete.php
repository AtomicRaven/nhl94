<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true){

        $seriesid = $_GET['seriesId'];
        $gameid = $_GET['gameId'];
        $redirect = $_GET['redirect'];
        $tId = 0;

        if(isset($_GET['tId']))
            $tId = $_GET['tId'];

        DeleteGameDataById($gameid, $seriesid);

        header("Location: " . $redirect . ".php?seriesId=" . $seriesid . "&err=6&tId=" . $tId );
}else{
    header('Location: index.php');
}


?>