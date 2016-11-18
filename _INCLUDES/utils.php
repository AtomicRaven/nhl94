<?php

function logMsg($msg){

	//echo $msg . "</br>";

}

function CleanTable($tableName){

	$conn = $GLOBALS['$conn'];
	$sql = "DELETE FROM " . $tableName;

	$tmr = mysqli_query($conn, $sql);
		
	if ($tmr) {
		logMsg("Delete Data From Table: " .$tableName);
	} else {
		echo("Error: CleanTable " . $sql . "<br>" . mysqli_error($conn));
	}

}

function NeededWins($seriesId){

    $gamesplayed = GetGamesBySeriesId($seriesId);  
    
    $half = ( 50/100 * mysqli_num_rows($gamesplayed));
    $win = $half + 1;

    return floor($win);

}


function NumGamesPerSeries($seriesId){

    $gamesplayed = GetGamesBySeriesId($seriesId);  
    
    return mysqli_num_rows($gamesplayed);

}

function CreateSelectBox($selectName, $selectTitle, $data, $id, $value, $onChange, $indexSelected){

    $selectBox = "<select id='" . $selectName . "' name='" . $selectName . "'";

    if($onChange != null){
        $selectBox .= "onchange='" . $onChange;
    }        

    $selectBox .= "'>";

    if($selectTitle != null){
        $selectBox .= "<option value='0'>" . $selectTitle . "</option>";
    }		
    
    while($row = mysqli_fetch_array($data)){
        
        $selectBox .= "<option value='" . $row[$id] . "'"; 
        
        if($indexSelected != null){

            if($row[$id] == $indexSelected){

                $selectBox .= " selected";
            }
        }

        $selectBox .= ">" . $row[$value] . "</option>";
    }	
    
    $selectBox .= "</select>";

    return $selectBox;

}	

function HumanTiming ($time)
{
	
    $time = time() - strtotime($time); // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%I:%S');
}

function CheckSeriesForWinner($seriesid, $homeuserid, $awayuserid){

    //Check to see if series is complete.  If so Mark as Complete
    $gamesplayed = GetGamesBySeriesId($seriesid);
	$gamesNeededToWin = NeededWins($seriesid);

    $homeWinnerCount = 0;
    $awayWinnerCount = 0;

    while($row = mysqli_fetch_array($gamesplayed, MYSQL_ASSOC)){
        if($row["WinnerUserID"] != 0){								
                            
            if($row["WinnerUserID"] == $homeuserid){
                $homeWinnerCount++;
            }

            if($row["WinnerUserID"] == $awayuserid){
                $awayWinnerCount++;
            }
        }
    }

    if($homeWinnerCount >= $gamesNeededToWin || $awayWinnerCount >= $gamesNeededToWin){

        if($homeWinnerCount >= $gamesNeededToWin){        
            MarkSeriesAsWon($seriesid, $homeuserid, $awayWinnerCount);
        }

        if($awayWinnerCount >= $gamesNeededToWin){
            MarkSeriesAsWon($seriesid, $awayuserid, $homeWinnerCount);	
        } 
    }else{

        //If game has been deleted and series is no longer won we set back to 0
         MarkSeriesAsWon($seriesid, 0, 0);
        
    } 

}

function GetPercent($val1, $val2){

    if($val2 > 0)
        return round($val1 / $val2 * 100, 1);
    else
        return 0;

}

function GetAvg($val1, $val2){

    return ltrim(number_format((float)$val1 / $val2, 3, '.', ''), "0");
}

function FormatZoneTime($time){

    $t = explode(':', $time); 
    return $t[1] . ":" .$t[2];
}

function FormatPercent($val1, $val2){

    return $val1 . "/" . $val2 ." (" . GetPercent($val1, $val2) . "%)";


}


//LeaderBoard sorting

function SortByGP($x, $y) {
    return $y['GP'] - $x['GP'];
}
function SortByWins($x, $y) {
    return $y['Wins'] - $x['Wins'];
}
function SortByLosses($x, $y) {
    return $y['Losses'] - $x['Losses'];
}
function SortByPercent($x, $y) {
    return $y['PCT'] > $x['PCT'] ? 1 : -1;
}
function SortByGF($x, $y) {
    return $y['gFor'] - $x['gFor'];
}
function SortByGA($x, $y) {
    return $y['gAgainst'] - $x['gAgainst'];
}
function SortByGFA($x, $y) {
    return $y['GFA'] > $x['GFA'] ? 1 : -1;
}
function SortByGAA($x, $y) {
    return $y['GAA'] > $x['GAA'] ? 1 : -1;
}                        

?>