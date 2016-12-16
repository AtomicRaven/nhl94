<?php

    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';

    $newTable = "rosterplabspre6";

    DropNewRosterTable($newTable);

    echo $newTable . " Deleted";

?>