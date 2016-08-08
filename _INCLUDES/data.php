<?php


// Games Functions
function GetNextGameId(){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT GameID FROM gamestats ORDER BY GameID DESC";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);	
	
	return $row['GameID'] + 1;

}

function DeleteGameDataById($gameid, $seriesid){

	//Tables gamestats, pensum, playerstats, scoresum, schedule
	DeleteGameFromTable('gamestats', $gameid);
	DeleteGameFromTable('pensum', $gameid);
	DeleteGameFromTable('playerstats', $gameid);
	DeleteGameFromTable('scoresum', $gameid);
	ResetScheduleByGameID($gameid);		

	CheckSeriesForWinner($seriesid, 0,0);
	
}

function DeleteGameFromTable($tableName, $gameid){

	$conn = $GLOBALS['$conn'];
	$sql = "DELETE FROM " . $tableName . " WHERE GameID='$gameid'";

	$tmr = mysqli_query($conn, $sql);
		
	if ($tmr) {
		logMsg("Delete Data From Table: " .$tableName);
	} else {
		echo("Error: DeleteGameFromTable " . $sql . "<br>" . mysqli_error($conn));
	}

}

function GetGameById($gameid){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM gamestats Where GameID='$gameid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		logMsg("Retrieved GameById");		
	} else {
		echo("Error: GetGameById: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row;

}

function GetGamesBySeriesId($seriesid){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM schedule Where SeriesID='$seriesid' ORDER BY ID ASC";
	
	$result = mysqli_query($conn, $sql);

	if ($result) {
		logMsg("Games Grabbed.  NumGames: " . mysqli_num_rows($result));
	} else {
		echo("Error: GetGamesBySeriesId: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $result;

}

function GetScheduleByID($scheduleid){

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM schedule WHERE ID = '$scheduleid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		logMsg("Retrieved GameById");		
	} else {
		echo("Error: GetGameById: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row;

}

function GetSeriesAndGames(){

	$conn = $GLOBALS['$conn'];
	//$sql = "SELECT * FROM schedule INNER JOIN series ON schedule.SeriesID = series.ID ORDER BY series.ID ASC";

	$sql = "SELECT a.*, b.*, MAX(b.ConfirmTime) as lastEntryDate,
		COUNT(CASE WHEN b.GameID >= 0 then 1 ELSE NULL END) AS TotalGames
		FROM series a INNER JOIN schedule b		
		ON a.ID = b.SeriesID WHERE a.Active != 0
		GROUP BY a.ID
		ORDER BY (CASE WHEN MAX(b.ConfirmTime) > MAX(a.DateCreated) THEN MAX(b.ConfirmTime) ELSE MAX(a.DateCreated) END) DESC";
			
	$result = mysqli_query($conn, $sql);

	if ($result) {
		//logMsg("Games Grabbed.  NumGames: " . mysqli_num_rows($result));
	} else {
		echo("Error:  GetSeriesAndGames: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $result;

}

function GetSeriesTypes(){

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT * FROM seriestype ORDER BY SeriesID ASC";
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;


}

function MarkSeriesAsWon($seriesid, $winneruserid, $losernumgames){

	$conn = $GLOBALS['$conn'];
	$sql = "UPDATE series SET SeriesWonBy= $winneruserid, LoserNumGames= $losernumgames, DateCompleted= NOW()	
	WHERE ID= '$seriesid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);
	//$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	//if ($row) {
	//	logMsg("Updated Series Won");		
	//} else {
	//	echo("Error: MarkSeriesAsWon: " . $sql . "<br>" . mysqli_error($conn));
	//}

}

function MarkSeriesAsInactive($seriesid){

	$conn = $GLOBALS['$conn'];
	$sql = "UPDATE series SET Active= 0	
	WHERE ID= '$seriesid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);

}

function ResetScheduleByGameID($gameid){	

	$conn = $GLOBALS['$conn'];
	$sql = "UPDATE schedule SET HomeScore= NULL, AwayScore= NULL, ConfirmTime = NOW(),
	HomeTeamID=NULL, AwayTeamID=NULL, OT= NULL, GameID=NULL, WinnerUserID=NULL
	WHERE GameID= '$gameid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);
	//$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	//if ($row) {
	//	logMsg("Updated Schedule");		
	//} else {
		//echo("Error: ResetScheduleByGameID: " . $sql . "<br>" . mysqli_error($conn));
	//}
}

//Series Functions
//function AddNewSeries($seriesname, $hometeamid, $awayteamid, $homeuserid, $awayuserid, $seriestype){

function AddNewSeries($seriesname, $homeuserid, $awayuserid, $seriestype){
	
	$conn = $GLOBALS['$conn'];
	//$sql = "INSERT INTO series (Name, HomeTeamId, AwayTeamId, HomeUserId, AwayUserId, DateCreated) 
	//		VALUES ('$seriesname', '$hometeamid', '$awayteamid', '$homeuserid', '$awayuserid', NOW())";	

	$sql = "INSERT INTO series (Name, HomeUserId, AwayUserId, DateCreated) 
			VALUES ('$seriesname', '$homeuserid', '$awayuserid', NOW())";
		
	$sqlr = mysqli_query($conn, $sql);
	
	if ($sqlr) {
		$seriesid = $conn->insert_id;
		logMsg("New Series record created successfully.  GameID: " . $seriesid);
	} else {
		echo("Error: AddNewSeries: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	for ($x = 1; $x <= 7; $x++) {

		// $seriestype
		// Type 1: 3-3-1
		// Type 2: 2-2-1-1-1
		// Type 1: 7

		if($seriestype == 1){

			switch ($x) {
				case 1:
				case 2:
				case 3:
				case 7:					
					$team1 =  $hometeamid;
					$team2 = $awayteamid;				
					$user1 = $homeuserid;
					$user2 = $awayuserid;
					break;
				case 4:
				case 5:
				case 6:
					$team2 =  $hometeamid;
					$team1 = $awayteamid;
					$user2 = $homeuserid;
					$user1 = $awayuserid;				
					break;
					
			}
		}elseif ($seriestype == 2) {
		
			switch ($x) {
				case 1:
				case 2:
				case 5:
				case 7:					
					$team1 =  $hometeamid;
					$team2 = $awayteamid;				
					$user1 = $homeuserid;
					$user2 = $awayuserid;
					break;
				case 3:
				case 4:
				case 6:
					$team2 =  $hometeamid;
					$team1 = $awayteamid;
					$user2 = $homeuserid;
					$user1 = $awayuserid;				
					break;
					
			}

		}elseif ($seriestype == 3) {

			$team1 =  $hometeamid;
			$team2 = $awayteamid;				
			$user1 = $homeuserid;
			$user2 = $awayuserid;

		}

		//if seriesType = 4 we are uploading games 1 at a time
		if($seriestype != 4){

			//Add Games to Schedule Table - Create 7
			//$sql = "INSERT INTO schedule (HomeTeamId, AwayTeamId, HomeUserId, AwayUserID, SeriesID) 
			//		VALUES ('$team1', '$team2', '$user1', '$user2', '$seriesid')";
			
			$sql = "INSERT INTO schedule (HomeUserId, AwayUserID, SeriesID) 
					VALUES ('$user1', '$user2', '$seriesid')";

			$sqlr = mysqli_query($conn, $sql);

			if ($sqlr) {
				$scheduleid = $conn->insert_id;
				logMsg("New Schedule record created successfully.  ScheduleID: " . $scheduleid);
				} else {
					echo("Error: AddNewSeries2: " . $sql . "<br>" . mysqli_error($conn));
			}

		}
	} 
	
	return $seriesid;

}

function GetSeriesById($seriesid){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM series Where ID='$seriesid'";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);	
	
	return $row;

}

function GetSeries(){	

	$conn = $GLOBALS['$conn'];
	$userid = $_SESSION['userId'];

	$sql = "SELECT * FROM series Where HomeUserID = $userid OR AwayUserID = $userid ORDER BY ID desc";
	
	$result = mysqli_query($conn, $sql);
	
	return $result;

}

//End of Series functions



function UpdateRoster(){
	
	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM nhlteam";
	
	$tmr = mysqli_query($conn, $sql);
		
	if ($tmr) {
		logMsg("Retrieved NHLTeam");
	} else {
		echo("Error: UpdateRoster" . $sql . "<br>" . mysqli_error($conn));
	}
	
	while($row = mysqli_fetch_array($tmr, MYSQL_ASSOC)){
		
		$Abv = $row['ABV'];
		$teamId = $row['TeamID'];
		
		$update = "UPDATE roster SET TeamID = '$teamId' WHERE Team='$Abv'";
		
		$sqlr = mysqli_query($conn, $update);
		
		if ($sqlr) {
			logMsg("Row " . $Abv . " updated with ID " . $teamId );
		} else {
			echo("Error: UpdateRoster : While" . $sqlr . "<br>" . mysqli_error($conn));
		}
		
		
	}
		
}

function GetPlayerID($teamid, $offset){

	$conn = $GLOBALS['$conn'];
	// Retrieve PlayerID	
	$sql = "SELECT PlayerID, Last FROM roster WHERE TeamID = '$teamid'";	
	$tmr = mysqli_query($conn, $sql);
	$index = 0;

	//logMsg("Offset:" . $offset);
	while($row = mysqli_fetch_array($tmr, MYSQL_ASSOC)) {

		if($index == $offset){
			
    		//logMsg($index.' '.$row['Last']);
			return $row['PlayerID'];
		}
    	$index++;
	}
	
}  // end of function

function GetTeams(){

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT * FROM nhlteam ORDER BY Name ASC";
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;


}

function GetTeamById($teamid){
	
	$conn = $GLOBALS['$conn'];
	//$teamid = $teamid + 1;	
	
	$sql = "SELECT * FROM nhlteam WHERE TeamID='$teamid' LIMIT 1";	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		logMsg("Retrieved TeamById");
	} else {
		echo("Error: GetTeamById: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row;

}  // end of function

function GetTeamNameById($teamid){
	
	if($teamid == 0){
		return false;
	}
	$conn = $GLOBALS['$conn'];
	//$teamid = $teamid + 1;	
	
	$sql = "SELECT * FROM nhlteam WHERE TeamID='$teamid' LIMIT 1";	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		//logMsg("Retrieved GetTeamNameById");
	} else {
		echo("Error: GetTeamNameById: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row["Name"];

}  // end of function

function GetTeamABVById($teamid){
	
	$conn = $GLOBALS['$conn'];
	//$teamid = $teamid + 1;	
	
	$sql = "SELECT * FROM nhlteam WHERE TeamID='$teamid' LIMIT 1";	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		logMsg("Retrieved GetTeamABVById");
	} else {
		echo("Error: GetTeamABVById: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row["ABV"];

}  // end of function

function GetUserName($userid){
	
	$conn = $GLOBALS['$conn'];
	
	$sql = "SELECT Name FROM users WHERE ID='$userid'";
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	return $row['Name'];
	
}  // end of function

function GetUserAlias($userid){
	
	$conn = $GLOBALS['$conn'];
	
	$sql = "SELECT Alias FROM users WHERE ID='$userid'";
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	return $row['Alias'];
	
}  // end of function

function GetUsers(){

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT * FROM users ORDER BY Name DESC";
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;
}

function GetUsersFromSeries($seriesid){

	$conn = $GLOBALS['$conn'];

	$series = GetSeriesById($seriesid);
	$homeuserid = $series['HomeUserID'];
	$awayuserid = $series['AwayUserID'];

	$sql = "SELECT * FROM users WHERE ID = '$homeuserid' OR ID = '$awayuserid'   ORDER BY Name DESC";
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;
}


function ChkPass($userid, $pwd){

    // Retrieve password

    $conn = $GLOBALS['$conn'];

    $sql = "SELECT * FROM users WHERE Alias = '$userid' LIMIT 1";
    $tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);

	if ($row) {

		logMsg("Retrieved User");
		if($pwd == $row['Password'])
            return $row;
        else
            return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE

	} else {
		//echo("Error: ChkPass: " . $sql . "<br>" . mysqli_error($conn));
		return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE
	}
 
}  // end of function

?>