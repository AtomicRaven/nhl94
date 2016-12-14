<?php

    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';

    $newTable = "rosterplabsgdl";

    DropNewRosterTable($newTable);

    echo $newTable . " Deleted";

?>