<?php

    include_once './_INCLUDES/00_SETUP.php';
	include_once './_INCLUDES/dbconnect.php';

    $newTable = "rosterplabsgdl";

    echo ("Duplicating Roster Table<br/>");
    DuplicateRosterTable($newTable);

    //Replace All TeamId data with new Team ID'sin

    TransferPlayerRoster($newTable);

?>