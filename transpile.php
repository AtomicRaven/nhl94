<?php

include_once './_INCLUDES/dbconnect.php';	

    //$assetPath = $_SERVER['DOCUMENT_ROOT']. 'nhl94/';

    $binFileName = $assetPath . "bin/VHL8season.bin";
    $outFileName = $assetPath . "bin/VHL8season.csv";    
    $GLOBALS['$binFile'] = fopen($binFileName, "r") or die("Unable to open file!");

    $numTeams = 28;
    $teamsOffset = hexdec("30E");
    $playerOffset;
    $dataOffset;

    $roster = array (
        array("Goalie","First","Last","Team","Pos","JNo","Wgt","Agl","Spd","OfA","DfA","PkC","Lv0","GlH","Lv0","Lv0","StR","StL","GvR","GvL"),
        array("Player","First","Last","Team","Pos","JNo","Wgt","Agl","Spd","OfA","DfA","ShP","ChK","H/F","StH","ShA","End","Rgh","Pas","Agr")
    );

    logMsg("numTeams:" . $numTeams);
    logMsg("teamsOffset:" . $teamsOffset);

    $teamOffset = 2004182;
    $playerOffset = 2004328;

    logMsg("Data: " . Rom($teamOffset));

    $counter = 0;
    //Parse all teams
    //while ($counter < $numTeams) {
        //Determine overall team offset and first player offset

        //$teamOffset = Rom($binFile, $teamsOffset + 4 * $counter);
        //$playerOffset = Rom($binFile, $teamOffset + romClass[teamOffset, 2];

        //$numForwards = Rom($binFile, $teamOffset + 79);

        //logMsg("numForwards: " . $numForwards);
        //logMsg("counter: " . $counter);
        //$counter++;

    //}   

    //array_push($roster, array("Player","First","Last","Team","Pos","JNo","Wgt","Agl","Spd","OfA","DfA","ShP","ChK","H/F","StH","ShA","End","Rgh","Pas","Agr"));


    //Write CSV
    $fp = fopen($outFileName, 'w');    

    foreach ($roster as $fields) {
        fputcsv($fp, $fields);
    }

    fclose($fp);
    //echo fread($binFile,filesize($binFileName));
    fclose($GLOBALS['$binFile']);

?>
