<?php

function GetNextGameId(){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT Game_ID FROM GameStats ORDER BY Game_ID DESC";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);	
	
	return $row['Game_ID'] + 1;

}

function CleanTable($tableName){

	$conn = $GLOBALS['$conn'];
	$sql = "DELETE FROM " . $tableName;

	$tmr = mysqli_query($conn, $sql);
		
	if ($tmr) {
		logMsg("Delete Data From Table: " .$tableName);
	} else {
		logMsg("Error: " . $sql . "<br>" . mysqli_error($conn));
	}

}

function UpdateRoster(){
	
	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM NHLTeam";
	
	$tmr = mysqli_query($conn, $sql);
		
	if ($tmr) {
		logMsg("Retrieved NHLTeam");
	} else {
		logMsg("Error: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	while($row = mysqli_fetch_array($tmr, MYSQL_ASSOC)){
		
		$Abv = $row['ABV'];
		$teamId = $row['Team_ID'];
		
		$update = "UPDATE Roster SET Team_ID = '$teamId' WHERE Team='$Abv'";
		
		$sqlr = mysqli_query($conn, $update);
		
		if ($sqlr) {
			logMsg("Row " . $Abv . " updated with ID " . $teamId );
		} else {
			logMsg("Error: " . $sqlr . "<br>" . mysqli_error($conn));
		}
		
		
	}
		
}

function getPlayerID($teamid, $offset){

	$conn = $GLOBALS['$conn'];
	// Retrieve Player_ID	
	$sql = "SELECT Player_ID, Last FROM Roster WHERE Team_ID = '$teamid'";	
	$tmr = mysqli_query($conn, $sql);
	$index = 0;

	//logMsg("Offset:" . $offset);
	while($row = mysqli_fetch_array($tmr, MYSQL_ASSOC)) {

		if($index == $offset){
			
    		//logMsg($index.' '.$row['Last']);
			return $row['Player_ID'];
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
function getTeamAbv($teamid){
	
	$conn = $GLOBALS['$conn'];
	//$teamid = $teamid + 1;	
	
	$sql = "SELECT * FROM NHLTeam WHERE Team_ID='$teamid' LIMIT 1";	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		echo "Retrieved ";
	} else {
		logMsg("Error: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row['Name'];

}  // end of function

function getUserName($userid){
	
	$conn = $GLOBALS['$conn'];
	
	$sql = "SELECT Name FROM Users WHERE ID='$userid'";
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	return $row['Name'];
	
}  // end of function

function getUserAlias($userid){
	
	$conn = $GLOBALS['$conn'];
	
	$sql = "SELECT Alias FROM Users WHERE ID='$userid'";
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	return $row['Alias'];
	
}  // end of function

function logMsg($msg){
	
	echo $msg . "</br>";
	
}

?>