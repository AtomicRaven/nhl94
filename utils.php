<?php

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

function getPlayerID($teamid, $offset, $list, $classic, $type){
	
	// Retrieve Abv first
	
	$abv = getAbv($teamid);

	// Retrieve Player_ID	
	$pq = "SELECT Player_ID FROM Roster WHERE League_ID='0' AND Abv='$abv' AND Type='$type' AND Offset='$offset' LIMIT 1";
	
	
/*	$pq = "SELECT Player_ID FROM NHLPlayer WHERE List='$list' AND Type='$type' AND Abv='$abv' AND Offset='$offset' AND Roster='N' LIMIT 1";
*/
	$pr = @mysqli_query($conn, $pq) or die("Could not retrieve Player_ID.");
	
	$row = mysql_fetch_array($pr, MYSQL_ASSOC);
	
	return $row['Player_ID'];
	
	
}  // end of function

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