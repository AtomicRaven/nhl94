<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

if ($LOGGED_IN == true){

        $seriesid = $_GET['seriesId'];

        MarkSeriesAsInactive($seriesid);

        header("Location: manage.php?seriesId=" . $seriesid . "&err=0" );

}else{
    header('Location: index.php');
}

?>