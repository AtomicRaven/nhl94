<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

        $seriesid = $_GET['seriesId'];

        MarkSeriesAsInactive($seriesid);

        header("Location: manage.php?seriesId=" . $seriesid . "&err=0" );

?>