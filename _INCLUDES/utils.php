<?php

function logMsg($msg){

    //echo $msg . "</br>";

}

function Rom($offSet){

    $fr = $GLOBALS['$binFile'];
    fseek ($fr, $offSet);
    $val = hexdec(bin2hex(fread($fr, 1)));
    return $val;
}

function GetValue($data, $index, $size) {

    $value = 0;
    for ($i = 0; $i <= ($size - 1); $i++) {
        $value = ($value  + (($data(($index + I)) * 256)  | ($size - ($i - 1))));
    }
    
    return $value;
}

function pFile($func, $file){

	logMsg($func . ":");
	logMsg($file['name']);
	logMsg($file['datetime']);
	logMsg($file['scheduleid']);
	logMsg("-------------");
    logMsg('');
    
}

function ReArrayFiles(&$file_post, $datemodified, $schedule) {
    
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);       

    for ($i=0; $i<$file_count; $i++) {

        $dateArray = explode("|", $datemodified[$i]); 
        $filename = $dateArray[0];				
        $datetime = $dateArray[1];

        foreach ($file_keys as $key) {

            $file_ary[$i][$key] = $file_post[$key][$i];                       
                        
            if($filename == $file_ary[$i][$key]){                
                $file_ary[$i]['datetime'] = $datetime;                
            }                        
        }        
    }    
    
    usort($file_ary, 'SortByUnixTime'); 

    //COmbine the arrays
    foreach($file_ary as $key=>$val) {

        $sortedGames[] = array(
            "name"=> $val["name"],
            "datetime" => $val["datetime"],
            "tmp_name" => $val['tmp_name'],
            "scheduleid" => $schedule[$key]["ID"],
            "HomeUserID" => $schedule[$key]["HomeUserID"],
		    "AwayUserID" => $schedule[$key]['AwayUserID']
        );
    }  

    return $sortedGames;
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

    $retVal = "";
    $selectBox = "<select id='" . $selectName . "' name='" . $selectName . "'";

    if($onChange != null){
        $selectBox .= " onchange='" . $onChange . "'";
    }

    $selectBox .= ">";

    if($selectTitle != null){

        $selectBox .= "<option value='-1'>" . $selectTitle . "</option>";
    }

    while($row = mysqli_fetch_array($data)){

        $selectBox .= "<option value='" . $row[$id] . "'";

        if($indexSelected != null){

            if($row[$id] == $indexSelected){

                $selectBox .= " selected";
            }
        }

        $retVal = $row[$value];

        if($selectName = "leagueType")
            $retVal =  str_replace(".bin","", $retVal);


        if($selectTitle == "Select Player"){

            $retVal =  $row["Last"] . ", " . $row["First"];
        }

        $selectBox .= ">" . $retVal . "</option>";
    }

    $selectBox .= "</select>";

    return $selectBox;

}

function GetDateFromSQL($time){

    $lastEntryDate = new DateTime($time);

    //$lastEntryDate->sub(new DateInterval('PT2H'));
    $formattedEntryDate = date_format($lastEntryDate, 'M d, Y @ h:i A') . " " . GetTimeZone();

    return $formattedEntryDate;

}

function GetFileType($filename){

    $type = "";
    $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.    

    $filetypes = array('.gs0','.gs1','.gs2','.gs3','.gs4','.gs5','.gs6','.gs7','.gs8','.gs9');
	//$filetypesRA = array ('.state0','.state1','.state2','.state3','.state4','.state5','.state6','.state7','.state8','.state9');	
	
	for ($i = 0; $i <= 99; ++$i) {
		$raExt = '.state' . $i;
        $filetypesRA[] = $raExt;
    }
	
    if(in_array($ext, $filetypes)){  // file ext is GEns 
        $type = "gens";
    }

    if(in_array($ext, $filetypesRA)){  // file ext is RA
        $type = "ra";
    }

    return $type;
}

function GetTimeZone(){

    $dateTime = new DateTime();
    $timeZone = $dateTime->getTimezone();       
    $dateTime->setTimeZone(new DateTimeZone($timeZone->getName()));
    return $dateTime->format('T');
}

function HumanTiming ($time)
{

    //$time = time() - (strtotime($time) + 7200); // to get the time since that moment
    $time = time() - (strtotime($time));

    $time = ($time<1)? 1 : $time;
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

    while($row = mysqli_fetch_array($gamesplayed, MYSQLI_ASSOC)){
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

    if($val1 != 0 && $val2 !=0)
        return ltrim(number_format((float)$val1 / $val2, 3, '.', ''), "0");
    else
        return ".000";

}

function FormatZoneTime($time){

    $t = explode(':', $time);
    return $t[1] . ":" .$t[2];
}

function FormatPercent($val1, $val2){

    return $val1 . "/" . $val2 ." (" . GetPercent($val1, $val2) . "%)";


}


//PLayer sorting
function SortByG($x, $y) {
    return $y['tG'] - $x['tG'];
}
function SortByA($x, $y) {
    return $y['tA'] - $x['tA'];
}
function SortByAvgPts($x, $y) {
    return $y['avgPts'] - $x['avgPts'];
}
function SortByPts($x, $y) {
    return $y['tPoints'] - $x['tPoints'];
}
function SortBySOG($x, $y) {
    return $y['tSOG'] - $x['tSOG'];
}
function SortBySPCT($x, $y) {
    return $y['SPCT'] - $x['SPCT'];
}
function SortByChk($x, $y) {
    return $y['tChks'] - $x['tChks'];
}
function SortByPEN($x, $y) {
    return $y['tPIM'] - $x['tPIM'];
}
function SortByTOIG($x, $y) {
    return $y['TOIG'] - $x['TOIG'];
}

//Leader

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
function SortByOverall($x, $y) {
    return $y['Overall'] > $x['Overall'];
}
function SortByUnixTime($x, $y) {
    return $y['datetime'] < $x['datetime'];
}
function SortByID($x, $y) {
    return $y['ID'] < $x['ID'];
}
function SortBy($x, $y, $field, $sOrder) {

    if($sOrder == "ASC")
        return $y[$field] < $x[$field];
    else
        return $y[$field] > $x[$field];
}

function CalculateOverallRanking($p){

    if($p["Pos"] == "F"){

        $total = ($p["Agl"]) + ($p["Spd"] * 1.5) + ($p["OfA"] * 1.5) + ($p["DfA"]) + ($p["ShP"] * 0.5) +
                ($p["ChK"]) + ($p["StH"] * 1.5) + ($p["ShA"]) + ($p["End"] * 0.5) + ($p["Pas"] * 0.5);
    }else{
        //Its a Goalie

        $total = (($p["Agl"] * 2.25) - (0.25 * ($p["Agl"] % 2))) +
          (($p["DfA"] * 2.25)  - (0.25 * ($p["DfA"] % 2)))+
          (($p["ShP"] * 2.25)  - (0.25 * ($p["DfA"] % 2)))+
          ($p["End"] * 0.5) +
          ($p["Rgh"] * 0.5) +
          ($p["Pas"] * 0.5) +
          ($p["Agr"] * 0.5);

    }

    $base = 25;
    $sum = $total + $base;
    $def_total = $total - $base;

    if ($def_total  < 0)
        $bonus = 0;
    else
        $bonus = $def_total;

    $x = round($sum + $bonus);

    if ($x > 99)
        $overall_player = 99;
    else
        $overall_player  = $x;

    return $overall_player;

}

function GetUploadMsg($state){

    $msg = "";

    switch ($state){

        case 0:
            $msg = "Game has been uploaded and submitted.";
        break;
        case 1:
            $msg = "Teams in the save state file do not match the game on the schedule.  Please try a different file.";
        break;
        case 2:
            $msg = "Password is incorrect.  Please try again.";
        break;
        case 3:
            $msg = "File is not valid.  Please choose a file that ends in .gs (Genesis)"; // or .zs (SNES).";
        break;
        case 4:
            $msg = "Error submitting game.  Please contact the administrator.";
        break;
        case 5:
            $msg = "Game could not be uploaded.  Please contact the administrator.";
        break;
        case 6:
            $msg = "Game has been Deleted.";
        break;
        default:
            $msg = "";
        break;
    }

    return $msg;
}

?>