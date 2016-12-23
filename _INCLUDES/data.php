<?php


//Duplaicate Ro
function DuplicateRosterTable($newTable){

	$conn = $GLOBALS['$conn'];

	//Duplicate Table structure
	$sql = "CREATE TABLE $newTable LIKE roster";
	$tmr = mysqli_query($conn, $sql);

	if ($tmr) {
		logMsg("Table Roster Duplicated into: " .$newTable);
	} else {
		echo("Error: DuplicateRosterTable: Duplicate " . $sql . "<br>" . mysqli_error($conn));
	}
}

function TransferPlayerRoster($newTable, $newCsv){
	
	$conn = $GLOBALS['$conn'];
	$File = $newCsv;

	$arrResult = array();
	$handle = fopen($File, "r");

	if(empty($handle) === false) {
		while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
			$arrResult[] = $data;
		}
		fclose($handle);
	}

	$i = 0;

	//don't want the first two rows they are headers 
	array_shift($arrResult);
	array_shift($arrResult);

	foreach($arrResult as $value) {		

		$playerid = $value[0];
		$firstname = $value[1];
		$lastname = $value[2];
		$teamABV = $value[3];

		if($firstname != "" && $teamABV !="ASB"){

			$i++;		

			//ROM exports different team names for QUE and OTT
			if($teamABV == "QB")
				$teamABV = "QUE";

			if($teamABV == "OTT")
				$teamABV = "OTW";

			$teamid = GetTeamIdByABV($teamABV);						
			
			$sql = "SELECT * FROM roster WHERE (First = '$firstname' AND Last = '$lastname') LIMIT 1";
			$tmr = mysqli_query($conn, $sql);
			$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
			$pos = $row["Pos"];			

			 $sql = "INSERT INTO $newTable (ID, PlayerID, First, Last, Team, TeamID, Pos) 
			 VALUES ('$i', '$playerid', '$firstname', '$lastname', '$teamABV', '$teamid', '$pos')";

			$sqlr = mysqli_query($conn, $sql);

			if ($sqlr) {
				echo($i . " Player: " . $firstname . " " . $lastname . " moved to team name: " . $teamABV . " teamId: " .$teamid . "<br/>");
			} else {
				echo("Error: UpdateRoster2 : " . $sqlr . mysqli_error($conn));
			}	

		}
	}

	AddLeague($newTable);
}

function DropNewRosterTable($newTable){

	$conn = $GLOBALS['$conn'];

	//Duplicate Table structure
	$sql = "DROP TABLE $newTable";
	$tmr = mysqli_query($conn, $sql);
	
}

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
	//$sql = "SELECT * FROM schedule Where SeriesID='$seriesid' ORDER BY ID ASC";
	$sql = "SELECT *,  
			(select count(CASE WHEN GameID >= 0 then 1 ELSE NULL END) FROM schedule Where SeriesID='$seriesid') as TotalGames, 
			(select MAX(ConfirmTime) FROM schedule Where SeriesID='$seriesid') AS LastEntryDate FROM schedule Where SeriesID='$seriesid' ORDER BY ID ASC";
	
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

function  GetScheduleByGameId($gameid){

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM schedule WHERE GameID = '$gameid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		logMsg("Retrieved GameById");		
	} else {
		echo("Error: GetGameById: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row;

}

function GetPlayerStatsByGameId($gameid, $dir){	

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT * FROM playerstats Where GameID='$gameid' ORDER BY G+A $dir";
	
	$result = mysqli_query($conn, $sql);		
	
	return $result;
}

function GetPlayerStatsBySeriesId($seriesid, $pos){	

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT s.*, p.*, 
		SUM(p.G) as 'tG', 
		SUM(p.A) as 'tA', 
		SUM(p.SOG) as 'tSOG', 
		SUM(p.PIM) as 'tPIM', 
		SUM(p.G + p.A) as 'tPoints',
		sum(TIME_TO_SEC(p.TOI)) as 'tTOI',		
		COUNT(p.GameID) as 'GP'
		FROM schedule s INNER JOIN playerstats p 
		ON s.GameID = p.GameID WHERE (s.SeriesID = '$seriesid') 
		GROUP BY p.PlayerID ";

		if($pos == 'G')
			$sql .= "ORDER BY ROUND(SUM(p.G)/SUM(p.SOG)*100) ASC";
		if($pos == 'P')
			$sql .= "ORDER BY tPoints DESC";
	
	$result = mysqli_query($conn, $sql);

	if ($result) {
		//logMsg("Games Grabbed.  NumGames: " . mysqli_num_rows($result));
	} else {
		echo("Error:  GetPlayerStatsBySeriesId: " . $sql . "<br>" . mysqli_error($conn));
	}		
	
	return $result;
}

function GetGamesLeaders(){

	$conn = $GLOBALS['$conn'];	
	$sql = "SELECT HomeUserID, AwayUserID, 
			SUM(HomeScore) as 'gFor', 
			SUM(AwayScore) as 'gAgainst', 
			SUM(HomeScore + AwayScore) as 'gTotal',
			SUM(OT) as 'OT', 
			COUNT(CASE WHEN WinnerUserID = HomeUserID then 1 ELSE NULL END) as HomeWins, 
			COUNT(CASE WHEN WinnerUserID = AwayUserID then 1 ELSE NULL END) as AwayWins,
			COUNT(CASE WHEN GameID > 0 then 1 ELSE NULL END) as GP						
			FROM schedule 
			WHERE WinnerUserID > 0
			GROUP BY HomeUserID 
			ORDER BY HomeWins DESC";
		
	$result = mysqli_query($conn, $sql);

	if ($result) {
		//logMsg("Games Grabbed.  NumGames: " . mysqli_num_rows($result));
	} else {
		echo("Error:  GetLeaders: " . $sql . "<br>" . mysqli_error($conn));
	}		
	
	return $result;
}



function GetGamesByUser($userId){

	$conn = $GLOBALS['$conn'];	
	$sql = "SELECT * FROM `schedule` WHERE (HomeUserID = '$userId' OR AwayUserID = '$userId') AND WinnerUserID >0";
		
	$result = mysqli_query($conn, $sql);

	if ($result) {
		logMsg("Games Grabbed.  NumGames: " . mysqli_num_rows($result));
		//echo("Error:  GetLeaders: " . $sql . "<br>" . mysqli_error($conn));
	} else {
		echo("Error:  GetLeaders: " . $sql . "<br>" . mysqli_error($conn));
	}		
	
	return $result;

}

function AddFakeUser($username, $email, $password){

	$conn = $GLOBALS['$conn'];	
	$password = md5($password);

	$sql = "INSERT INTO users (name, email, username, password, confirmcode, role) 
			VALUES ('FakeName', '$email', '$username', '$password', 'y', '')";
		
	$sqlr = mysqli_query($conn, $sql);	

	if ($sqlr) {		
		logMsg("Fake User Added: " . $username);
		return 1;
	} else {
		echo("Error: AddFakeUser: " . $sql . "<br>" . mysqli_error($conn));
		return 0;
	}


}

function GetHeadToHead($userId1, $userId2){

	$conn = $GLOBALS['$conn'];	
	$sql = "SELECT * FROM `schedule` WHERE (HomeUserID = '$userId1' OR AwayUserID = '$userId1') AND (HomeUserID = '$userId2' OR AwayUserID = '$userId2')  AND WinnerUserID >0";
		
	$result = mysqli_query($conn, $sql);

	if ($result) {
		logMsg("Games Grabbed.  NumGames: " . mysqli_num_rows($result));
		//echo("Error:  GetLeaders: " . $sql . "<br>" . mysqli_error($conn));
	} else {
		echo("Error:  GetLeaders: " . $sql . "<br>" . mysqli_error($conn));
	}		
	
	return $result;

}

function GetSeriesLeadersByUserID($userid){

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT COUNT(CASE WHEN SeriesWonBy = '$userid' then 1 ELSE NULL END) as sWins
			FROM series WHERE active=1 LIMIT 1";
			
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result, MYSQL_ASSOC);

	if ($row) {
		//logMsg("Games Grabbed.  NumGames: " . mysqli_num_rows($result));
	} else {
		echo("Error:  GetSeriesLeadersByUserID: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row;
}

function GetSeriesAndGames($useronly){

	$conn = $GLOBALS['$conn'];
	//$sql = "SELECT * FROM schedule INNER JOIN series ON schedule.SeriesID = series.ID ORDER BY series.ID ASC";
	if (isset($_SESSION['userId'])){
		$userid = $_SESSION['userId'];
	}

	if($_SESSION['Admin'])
		$useronly = false;

	$sql = "SELECT a.*, b.*, MAX(b.ConfirmTime) as LastEntryDate,
		COUNT(CASE WHEN b.GameID >= 0 then 1 ELSE NULL END) AS TotalGames
		FROM series a INNER JOIN schedule b		
		ON a.ID = b.SeriesID WHERE a.Active != 0";
		
		if($useronly){
			$sql .= " and (b.HomeUserID ='$userid' or b.AwayUserID = '$userid')";
		}

	$sql .= " GROUP BY a.ID
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

function GetLeagueTypes(){

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT * FROM league WHERE Visible = true ORDER BY LeagueID ASC";
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;

}

function AddLeague($tablename){

	$conn = $GLOBALS['$conn'];

	$sql = "INSERT INTO league (Name, TableName, Visible) 
			VALUES ('$tablename', '$tablename', 1)";
		
	$sqlr = mysqli_query($conn, $sql);	


	if ($sqlr) {		
		logMsg("New League Table created: " . $tablename);
	} else {
		echo("Error: AddLeague: " . $sql . "<br>" . mysqli_error($conn));
	}

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

	//Mark series as inactive

	$sql = "UPDATE series SET Active= 0	
	WHERE ID= '$seriesid' LIMIT 1";

	$tmr = mysqli_query($conn, $sql);

	//Update Schedule

	//Delete all the games for this series (actual delete)
	$gamestodelete = GetGamesBySeriesId($seriesid);
	while($row = mysqli_fetch_array($gamestodelete, MYSQL_ASSOC)){
		DeleteGameDataById($row["GameID"], $seriesid);
	}	

}

function ResetScheduleByGameID($gameid){	

	$conn = $GLOBALS['$conn'];
	$sql = "UPDATE schedule SET HomeScore= NULL, AwayScore= NULL, ConfirmTime = NOW(),
	HomeTeamID=NULL, AwayTeamID=NULL, OT= NULL, GameID=NULL, WinnerUserID=NULL
	WHERE GameID= '$gameid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);

}

//Series Functions
//function AddNewSeries($seriesname, $hometeamid, $awayteamid, $homeuserid, $awayuserid, $seriestype){

function AddNewSeries($seriesname, $homeuserid, $awayuserid, $seriestype, $numGames, $leagueid){

	$conn = $GLOBALS['$conn'];		

	$sql = "INSERT INTO series (Name, HomeUserId, AwayUserId, DateCreated, Active, LeagueID) 
			VALUES ('$seriesname', '$homeuserid', '$awayuserid', NOW(), 1, $leagueid)";
		
	$sqlr = mysqli_query($conn, $sql);
	
	if ($sqlr) {
		$seriesid = $conn->insert_id;
		logMsg("New Series record created successfully.  GameID: " . $seriesid);
	} else {
		echo("Error: AddNewSeries: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	for ($x = 1; $x <= $numGames; $x++) {

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
					//$team1 =  $hometeamid;
					//$team2 = $awayteamid;				
					$user1 = $homeuserid;
					$user2 = $awayuserid;
					break;
				case 4:
				case 5:
				case 6:
					//$team2 =  $hometeamid;
					//$team1 = $awayteamid;
					$user2 = $homeuserid;
					$user1 = $awayuserid;				
					break;
					
			}
		}elseif ($seriestype == 3 || $seriestype == 0) {

			//$team1 =  $hometeamid;
			//$team2 = $awayteamid;				
			$user1 = $homeuserid;
			$user2 = $awayuserid;

		}

		//if seriesType = 4 we are uploading games 1 at a time
		//if($seriestype != 4){

			//Add Games to Schedule Table - Create 7
			//$sql = "INSERT INTO schedule (HomeTeamId, AwayTeamId, HomeUserId, AwayUserID, SeriesID) 
			//		VALUES ('$team1', '$team2', '$user1', '$user2', '$seriesid')";
			
			$sql = "INSERT INTO schedule (HomeUserId, AwayUserID, SeriesID, LeagueID) 
					VALUES ('$user1', '$user2', '$seriesid', '$leagueid')";

			$sqlr = mysqli_query($conn, $sql);

			if ($sqlr) {
				$scheduleid = $conn->insert_id;
				logMsg("New Schedule record created successfully.  ScheduleID: " . $scheduleid);
				} else {
					echo("Error: AddNewSeries2: " . $sql . "<br>" . mysqli_error($conn));
			}

		//}
	} 
	
	return $seriesid;

}

function GetLeagueTableName($leagueid){

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT TableName FROM league WHERE LeagueID='$leagueid' Limit 1";
	$result = mysqli_query($conn, $sql);

	$row = mysqli_fetch_array($result, MYSQL_ASSOC);	
	return $row["TableName"];

}

function GetLeagueTableABV($leagueid){

	$conn = $GLOBALS['$conn'];
	$sql = "SELECT Name FROM league WHERE LeagueID='$leagueid' Limit 1";
	$result = mysqli_query($conn, $sql);

	$row = mysqli_fetch_array($result, MYSQL_ASSOC);	
	return str_replace(".bin","",$row["Name"]);

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

function GetPlayerID($teamid, $offset, $leagueid){

	$conn = $GLOBALS['$conn'];
	// Retrieve PlayerID

	$tblName = GetLeagueTableName($leagueid);

	$sql = "SELECT PlayerID, Last FROM $tblName WHERE TeamID = '$teamid'";	
	$tmr = mysqli_query($conn, $sql);
	$index = 0;

	logMsg("Offset:" . $offset);
	while($row = mysqli_fetch_array($tmr, MYSQL_ASSOC)) {

		if($index == $offset){
			
    		//logMsg($index.' '.$row['Last']);
			return $row['PlayerID'];
		}
    	$index++;
	}
	
}  // end of function

function GetPlayerFromID($playerid, $leagueid){

	$conn = $GLOBALS['$conn'];
	
	$tblName = GetLeagueTableName($leagueid);

	$sql = "SELECT * FROM $tblName WHERE PlayerID = '$playerid' LIMIT 1";
	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		//logMsg("Retrieved GameById");		
	} else {
		echo("Error: GetPlayerFromID: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row;	

}  // 
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

function GetTeamIdByABV($abv){
	
	$conn = $GLOBALS['$conn'];
	//$teamid = $teamid + 1;	
	
	$sql = "SELECT * FROM nhlteam WHERE ABV='$abv' LIMIT 1";	
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	if ($row) {
		//logMsg("Retrieved GetTeamABVById:" .$row["TeamID"]);
	} else {
		echo("Error: GetTeamABVById: " . $sql . "<br>" . mysqli_error($conn));
	}
	
	return $row["TeamID"];

}  // end of function

function GetUserName($userid){
	
	$conn = $GLOBALS['$conn'];
	
	$sql = "SELECT Name FROM users WHERE id_user='$userid'";
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	return $row['Name'];
	
}  // end of function

function GetUserAlias($userid){
	
	$conn = $GLOBALS['$conn'];
	
	$sql = "SELECT username FROM users WHERE id_user='$userid'";
	$tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);
	
	return strtoupper($row['username']);
	
}  // end of function

function GetUsers($orderAsc){

	$conn = $GLOBALS['$conn'];

	if($orderAsc)
		$sql = "SELECT * FROM users WHERE confirmcode='y' ORDER BY username ASC";
	else
		$sql = "SELECT * FROM users WHERE confirmcode='y' ORDER BY username DESC";
	
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;
}

function GetRosters(){

	$conn = $GLOBALS['$conn'];
	
	$sql = "SELECT * FROM roster WHERE Team != 'ASW' AND Team != 'ASE' AND Team!='ANH' AND Team !='FLA' ORDER BY Last ASC";
	
	$result = mysqli_query($conn, $sql);

	if ($result) {
		logMsg("Retrieved PlayerRosters" );
	} else {
		echo("Error: GetRosters: " . $sql . "<br>" . mysqli_error($conn));
	}

	return $result;
}

function ComparePlayers($playerArray){

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT * FROM roster WHERE ";

	$lastElement = end($playerArray);
	foreach($playerArray as $key => $value){            

            if (strpos($key, 'player') !== false) {
				$sql .=  "PlayerID = " . "'$value'";

				if($value != $lastElement){
					$sql .= " OR ";
				}
            }			
    }


	$sql .= " ORDER BY Last ASC";
	//echo $sql;

	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;

}

function CompareUsers($userid1, $userid2){

	$conn = $GLOBALS['$conn'];

	$sql = "SELECT * FROM users WHERE id_user = '$userid1' OR id_user = '$userid2'  ORDER BY username DESC";

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

	$sql = "SELECT * FROM users WHERE id_user = '$homeuserid' OR id_user = '$awayuserid'  ORDER BY username DESC";
	$result = mysqli_query($conn, $sql);

	if($result === FALSE) { 
		die(mysql_error()); // TODO: better error handling
	}	

	return $result;
}


function ChkPass($userid, $pwd){

    // Retrieve password

    $conn = $GLOBALS['$conn'];
	$pwdmd5 = md5($pwd);
    
	$sql = "Select * from users where username='$userid' and password='$pwdmd5' and confirmcode='y'";
    $tmr = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($tmr, MYSQL_ASSOC);

	if ($row) {
		return $row;	
	} else {
		//echo("Error: ChkPass: " . $sql . "<br>" . mysqli_error($conn));
		return FALSE; //  CHANGE TO FALSE BEFORE UPLOADING TO SITE
	}
 
}  // end of function

?>