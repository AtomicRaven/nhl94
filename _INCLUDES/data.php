<?php


// Games Functions
function GetNextGameId(){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT GameID FROM GameStats ORDER BY GameID DESC";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);	
	
	return $row['GameID'] + 1;

}

function GetGameById($gameid){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM GameStats Where GameID='$gameid' LIMIT 1";
	
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
	$sql = "SELECT * FROM Schedule Where SeriesID='$seriesid' ORDER BY ID ASC";
	
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
	$sql = "SELECT * FROM Schedule WHERE ID = '$scheduleid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		logMsg("Retrieved GameById");		
	} else {
		echo("Error: GetGameById: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row;

}


//Series Functions
function AddNewSeries($seriesname, $hometeamid, $awayteamid, $homeuserid, $awayuserid){	

	$conn = $GLOBALS['$conn'];
	$sql = "INSERT INTO Series (Name, HomeTeamId, AwayTeamId, HomeUserId, AwayUserId, DateCreated) 
			VALUES ('$seriesname', '$hometeamid', '$awayteamid', '$homeuserid', '$awayuserid', NOW())";	
		
	$sqlr = mysqli_query($conn, $sql);
	
	if ($sqlr) {
		$seriesid = $conn->insert_id;
		logMsg("New Series record created successfully.  GameID: " . $seriesid);
	} else {
		echo("Error: AddNewSeries: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	for ($x = 1; $x <= 7; $x++) {

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

			//Add Games to Schedule Table - Create 7
		$sql = "INSERT INTO Schedule (HomeTeamId, AwayTeamId, HomeUserId, AwayUserID, SeriesID) 
				VALUES ('$team1', '$team2', '$user1', '$user2', '$seriesid')";				

    	$sqlr = mysqli_query($conn, $sql);

		if ($sqlr) {
			$scheduleid = $conn->insert_id;
			logMsg("New Schedule record created successfully.  ScheduleID: " . $scheduleid);
			} else {
				echo("Error: AddNewSeries: " . $sql . "<br>" . mysqli_error($conn));
		}
	} 
	
	return $seriesid;

}

function GetSeriesById($seriesid){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM Series Where ID='$seriesid'";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);	
	
	return $row;

}

function GetSeries(){	

	$conn = $GLOBALS['$conn'];
	$userid = $_SESSION['userId'];

	$sql = "SELECT * FROM Series Where HomeUserID = $userid OR AwayUserID = $userid ORDER BY ID desc";
	
	$result = mysqli_query($conn, $sql);
	
	return $result;

}

//End of Series functions



function UpdateRoster(){
	
	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM NHLTeam";
	
	$tmr = mysqli_query($conn, $sql);
		
	if ($tmr) {
		logMsg("Retrieved NHLTeam");
	} else {
		echo("Error: UpdateRoster" . $sql . "<br>" . mysqli_error($conn));
	}
	
	while($row = mysqli_fetch_array($tmr, MYSQL_ASSOC)){
		
		$Abv = $row['ABV'];
		$teamId = $row['TeamID'];
		
		$update = "UPDATE Roster SET TeamID = '$teamId' WHERE Team='$Abv'";
		
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
	$sql = "SELECT PlayerID, Last FROM Roster WHERE TeamID = '$teamid'";	
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

	$sql = "SELECT * FROM NhlTeam ORDER BY Name ASC";
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;


}

function GetTeamById($teamid){
	
	$conn = $GLOBALS['$conn'];
	//$teamid = $teamid + 1;	
	
	$sql = "SELECT * FROM NHLTeam WHERE TeamID='$teamid' LIMIT 1";	
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
	
	$conn = $GLOBALS['$conn'];
	//$teamid = $teamid + 1;	
	
	$sql = "SELECT * FROM NHLTeam WHERE TeamID='$teamid' LIMIT 1";	
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
	
	$sql = "SELECT * FROM NHLTeam WHERE TeamID='$teamid' LIMIT 1";	
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
	
	$sql = "SELECT Name FROM Users WHERE ID='$userid'";
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	return $row['Name'];
	
}  // end of function

function GetUserAlias($userid){
	
	$conn = $GLOBALS['$conn'];
	
	$sql = "SELECT Alias FROM Users WHERE ID='$userid'";
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	return $row['Alias'];
	
}  // end of function

function GetUsers(){

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT * FROM Users ORDER BY Name ASC";
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;

}

?>