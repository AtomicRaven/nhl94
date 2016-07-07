<?php

require_once("config.php");
require_once("matt/_INCLUDES/dbconnect.php");
require_once("data.php");
require_once("errorchk.php");

	function addgame($seriesid){	// add game to database
	
		$conn = $GLOBALS['$conn'];
		$stattype = "GENS";
		$nextGameId = GetNextGameId();
		$filePath = $GLOBALS['$saveFilePath'] . "/" . $seriesid;

		$file = $filePath . "/" . "Series-" . $seriesid. '-game-'. $nextGameId . '.sv';	
		
		$fr = fopen("$file", 'rb');	// reads file	
		
		//$gmq = "SELECT * FROM Schedule WHERE Game_ID='$gameid' LIMIT 1";
		//$gmr = @mysql_query($gmq) or die("Error:  Could not retrieve game data.");
		
		//$row = mysql_fetch_array($gmr, MYSQL_ASSOC);
		
		// Has the game been uploaded while you were waiting?
		
		//if($row['H_Confirm'] == 1 && $row['A_Confirm'] == 1)
			//die("Error.  Game has been uploaded already.");
		
		// What type of save state will it be?  Check Sub_League for game
		
		//if(substr($row['Sub_League'], 0, 4) == "GENS")
			//$stattype = "GENS";
		//else if (substr($row['Sub_League'], 0, 4) == "SNES")
			//$stattype = "SNES";
		//else
			//die("Error: Problem with game data.  Please contact administrator.");
			
	/**********************************************************************************/

			
		// Retrieve Coach User IDs
	//	echo $gameid. " ". $row['Home']. " ". $row['Away'];
		//$homeid = getUserID($row['Home']);
		//$awayid = getUserID($row['Away']);
		
		//$H_User_ID = $_GET['homeuserid'];
		//$A_User_ID = $_GET['awayuserid'];	

		$H_User_ID = 1;
		$A_User_ID = 2;
				
	//	echo $gameid. " ". $homeid. " ". $awayid;
		
		// Retreive Team List type
		
		
		if($stattype == "GENS"){  // Gens save state extraction
			
					// Away Team
			fseek ($fr,59307);	
			
			$awayid = hexdec(bin2hex(fread($fr, 1))) + 1;
			
			// Home Team
			fseek ($fr,59305);
			
			$homeid = hexdec(bin2hex(fread($fr, 1))) + 1;
			
			
			// Crowd Meter
			fseek ($fr,59277);
			$PeakMeter = hexdec(bin2hex(fread($fr, 1)));
			
			logMsg ("Crowd:" . $PeakMeter);
			
			// AWAY TEAM STATS
			
			// Away Goals
			fseek ($fr,61111);
			$AwayScore = hexdec(bin2hex(fread($fr, 1)));
			
			// Away Power Play Goals/Opportunities
			fseek ($fr,61101);
			$AwayPPG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61103);
			$AwayPP = hexdec(bin2hex(fread($fr, 1)));
			
			// Away SHG
			fseek ($fr,61953);
			$AwaySHG = hexdec(bin2hex(fread($fr, 1)));

			// Away SHGA
			fseek ($fr,61085);
			$AwaySHGA = hexdec(bin2hex(fread($fr, 1))); 
		
			// Away Breakaway Goals/Opportunities 
			fseek ($fr,61957);
			$AwayBKG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61955);
			$AwayBKO = hexdec(bin2hex(fread($fr, 1)));
		
			// Away One Timer Goals:Attempts
			fseek ($fr,61961);
			$Away1TG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61959);
			$Away1TA = hexdec(bin2hex(fread($fr, 1)));
		
			// Away Penalty Shot Goals:Attempts
			fseek ($fr,61965);
			$AwayPSG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61963);
			$AwayPSA = hexdec(bin2hex(fread($fr, 1)));

			// Away Faceoffs Won
			fseek ($fr,61113);
			$AwayFOW = hexdec(bin2hex(fread($fr, 1)));
		
			// Away Body Checks
			fseek ($fr,61115);
			$AwayByCks = hexdec(bin2hex(fread($fr, 1)));
		
			// Away Team Penalties / Minutes
			fseek ($fr,61105);
			$AwayPen = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61107);
			$AwayPenM = hexdec(bin2hex(fread($fr, 1)));
		
			// Away Attack Zone
			fseek ($fr,61108);
			$AwayAZMinutes = hexdec(bin2hex(fread($fr, 1))) *256;
			fseek ($fr,61109);
			$AwayAZSeconds = hexdec(bin2hex(fread($fr, 1)));
			$AwayAZ = ($AwayAZMinutes + $AwayAZSeconds);
			$AwayAZDisplayM = Round((Floor($AwayAZ/60)),0);
			$AwayAZDisplayS = ($AwayAZ % 60);
		
			// Away Passing Stats
			fseek ($fr,61119);
			$AwayPsC = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61117);
			$AwayPsA = hexdec(bin2hex(fread($fr, 1)));
			
			// HOME TEAM STATS
			
			// Home Goals
			fseek ($fr,60243);
			$HomeScore = hexdec(bin2hex(fread($fr, 1)));
			
			// Home Power Play Goals/Opportunities
			fseek ($fr,60233);
			$HomePPG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,60235);
			$HomePP = hexdec(bin2hex(fread($fr, 1)));
		
			// Home SHG
			fseek ($fr,61085);
			$HomeSHG = hexdec(bin2hex(fread($fr, 1)));
		
			// Home SHGA
			fseek ($fr,61953);
			$HomeSHGA = hexdec(bin2hex(fread($fr, 1)));
		
			// Home Breakaway Goals/Opportunities 
			fseek ($fr,61089);
			$HomeBKG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61087);
			$HomeBKO = hexdec(bin2hex(fread($fr, 1)));
		
			// Home One Timer Goals:Attempts
			fseek ($fr,61093);
			$Home1TG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61091);
			$Home1TA = hexdec(bin2hex(fread($fr, 1)));
		
			// Home Penalty Shot Goals:Attempts
			fseek ($fr,61097);
			$HomePSG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61095);
			$HomePSA = hexdec(bin2hex(fread($fr, 1)));
		
			// Home Faceoffs Won 
			fseek ($fr,60245);
			$HomeFOW = hexdec(bin2hex(fread($fr, 1)));
		
			// Home Body Checks
			fseek ($fr,60247);
			$HomeByCks = hexdec(bin2hex(fread($fr, 1)));
		
			// Home Team Penalties / Minutes
			fseek ($fr,60237);
			$HomePen = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,60239);
			$HomePenM = hexdec(bin2hex(fread($fr, 1)));
		
			// Home Attack Zone
			fseek ($fr,60240);
			$HomeAZMinutes = hexdec(bin2hex(fread($fr, 1))) *256;
			fseek ($fr,60241);
			$HomeAZSeconds = hexdec(bin2hex(fread($fr, 1)));
			$HomeAZ = ($HomeAZMinutes + $HomeAZSeconds);
			$HomeAZDisplayM = Round((Floor($HomeAZ/60)),0);
			$HomeAZDisplayS = ($HomeAZ % 60);
		
			// Home Passing Stats
			fseek ($fr,60251);
			$HomePsC = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,60249);
			$HomePsA = hexdec(bin2hex(fread($fr, 1)));
			
			/*********PERIOD STATS*************/
			
			// Away Team Period Goals
			
			fseek ($fr,61933);
			$A1stGoals = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61935);
			$A2ndGoals = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61937);
			$A3rdGoals = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61939);
			$AOTGoals = hexdec(bin2hex(fread($fr, 1)));
		
			// Away Team Period SOG
			fseek ($fr,61941);
			$A1stSOG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61943);
			$A2ndSOG = hexdec(bin2hex(fread($fr, 1)));

			fseek ($fr,61945);
			$A3rdSOG = hexdec(bin2hex(fread($fr, 1)));

			fseek ($fr,61947);
			$AOTSOG = hexdec(bin2hex(fread($fr, 1)));
		
			//--------- End of Away Period Stats---------------

			//--------- Home Team Period Stats-----------------

			// Home Team Period Goals
			fseek ($fr,61065);
			$H1stGoals = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61067);
			$H2ndGoals = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61069);
			$H3rdGoals = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61071);
			$HOTGoals = hexdec(bin2hex(fread($fr, 1)));
		
			// Home Team Period SOG
			fseek ($fr,61073);
			$H1stSOG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61075);
			$H2ndSOG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61077);
			$H3rdSOG = hexdec(bin2hex(fread($fr, 1)));
		
			fseek ($fr,61079);
			$HOTSOG = hexdec(bin2hex(fread($fr, 1)));
		
			//------ End of Home Period Stats ---------
			
		}  // end of GENS stats		
			
		// OT Game?
		
		$HTotalGoals = $H1stGoals + $H2ndGoals + $H3rdGoals + $HOTGoals;
		$ATotalGoals = $A1stGoals + $A2ndGoals + $A3rdGoals + $AOTGoals;
		
		if(($HOTSOG > 0) || ($AOTSOG > 0) || ($HOTGoals > 0) || ($AOTGoals > 0) || ($HTotalGoals == $ATotalGoals))  // OT Game
			$OT = '1';
		else
			$OT = '0';			
		
		// Add to GameStats
		

				
		logMsg("Away Team: ID " . $awayid . " : " . getTeamAbv($awayid) . ": " . $AwayScore . " Goals. Player: " . getUserAlias($A_User_ID));
		logMsg("Home Team: ID " . $homeid . " : " . getTeamAbv($homeid) . ": " . $HomeScore . " Goals. Player: " . getUserAlias($H_User_ID));
		
		$hazstring = '00:'. $HomeAZDisplayM. ':'. $HomeAZDisplayS;		// Attack Zone String
		$aazstring = '00:'. $AwayAZDisplayM. ':'. $AwayAZDisplayS;
		
		$goalf = ", GHP1, GHP2, GHP3, GHOT, GAP1, GAP2, GAP3, GAOT";
		$goalv = ", '$H1stGoals', '$H2ndGoals', '$H3rdGoals', '$HOTGoals', '$A1stGoals', '$A2ndGoals', '$A3rdGoals', '$AOTGoals'";
		$shotf = ", SHP1, SHP2, SHP3, SHOT, SAP1, SAP2, SAP3, SAOT";
		$shotv = ", '$H1stSOG', '$H2ndSOG', '$H3rdSOG', '$HOTSOG', '$A1stSOG', '$A2ndSOG', '$A3rdSOG', '$AOTSOG'"; 
		$pmf = ", PIMH, PIMA";
		$pmv = ", '$HomePenM', '$AwayPenM'";
		$bcf = ", BCH, BCA";
		$bcv = ", '$HomeByCks', '$AwayByCks'";
		$ppshf = ", PPHG, PPAG, SHHG, SHAG, PPH, PPA";
		$ppshv = ", '$HomePPG', '$AwayPPG', '$HomeSHG', '$AwaySHG', '$HomePP', '$AwayPP'";
		$baf = ", BAHG, BAAG, BAH, BAA";
		$bav = ", '$HomeBKG', '$AwayBKG', '$HomeBKO', '$AwayBKO'";
		$otf = ", 1THG, 1TAG, 1TH, 1TA";
		$otv = ", '$Home1TG', '$Away1TG', '$Home1TA', '$Away1TA'";
		$psf = ", PSHG, PSAG, PSH, PSA";
		$psv = ", '$HomePSG', '$AwayPSG', '$HomePSA', '$AwayPSA'";
		$foakf = ", FOH, FOA, AZH, AZA";
		$foakv = ", '$HomeFOW', '$AwayFOW', '$hazstring', '$aazstring'";
		$pasf = ", PCH, PH, PCA, PA";
		$pasv = ", '$HomePsC', '$HomePsA', '$AwayPsC', '$AwayPsA'";
	

		 $sql = "INSERT INTO GameStats (League_ID, Series_ID, Crowd
				$goalf $shotf $pmf $bcf $ppshf $baf $otf $psf $foakf $pasf)
				VALUES (1, '$seriesid', '$PeakMeter' $goalv $shotv $pmv $bcv $ppshv $bav $otv $psv $foakv $pasv)";
		
		
		
		$sqlr = mysqli_query($conn, $sql);
		
		if ($sqlr) {
			$gameid = $conn->insert_id;
			logMsg("New GameStats record created successfully.  Game_ID: " . $gameid);
		} else {
			logMsg("Error: GameStats: " . $sql . "<br>" . mysqli_error($conn));
		}

		
	
		//logMsg($sql);
		// Update Schedule
		
		$schupq = "INSERT INTO Schedule (H_Team_ID, A_Team_ID, H_User_ID, A_User_ID, H_Score, A_Score, OT, Confirm_Time, Game_ID, Series_ID)
				VALUES ('$homeid', '$awayid', '$H_User_ID', '$A_User_ID', '$HomeScore', '$AwayScore', '$OT', NOW(), '$gameid', '$seriesid')";
		
		$schupr = mysqli_query($conn, $schupq);
		
		if ($schupr) {
			logMsg("Schedule Updated");
		} else {
			logMsg("Error: Schedule: " . $schupr . "<br>" . mysqli_error($conn));
		}
		
		//logMsg($schupq);
			
/**********************************************************************************/

		
	// Add Player Stats
	
	
	$i = 1;
	
	// Get Home Team 

	$plq = "SELECT Player_ID, Team_ID, Last, Pos FROM Roster WHERE Team_ID='$homeid' ORDER BY Player_ID ASC";
	
	$plr = @mysqli_query($conn, $plq) or die("Could not retrieve Home Player List.  Please contact administrator.");
	//echo $plq;
	
	logMsg("Home Player Stats<br/>");
	while($prow = mysqli_fetch_array($plr, MYSQL_ASSOC)){	// Team Roster
	
		// Home Player Stats
		
		$pid = $prow['Player_ID'];
		$pos = $prow['Pos'];
		$name = $prow['Last'];

		fseek ($fr,60409 + $i);
		$Goals = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,60435 + $i);
		$Assists = hexdec(bin2hex(fread($fr, 1)));
		$Points = $Goals + $Assists;

		fseek ($fr,60461 + $i);
		$SOG = hexdec(bin2hex(fread($fr, 1)));

		
		
		fseek ($fr,60487 + $i);		
		$PIM = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,60513 + $i);		
		$Chksfor = hexdec(bin2hex(fread($fr, 1)));
	
		// Home TOI
		fseek ($fr,(60538 + ($i * 2)));
		$TOIMinutes = hexdec(bin2hex(fread($fr, 1))) * 256;
		fseek ($fr,(60539 + ($i * 2)));
		$TOISeconds = hexdec(bin2hex(fread($fr, 1)));
		
		$TOI = ($TOIMinutes + $TOISeconds);
		$TOIDisplayM = Round((Floor($TOI/60)),0);
		$TOIDisplayS = ($TOI % 60);
		
		// Compensate for TOI bug in Genesis
		
		$TOIDisplayS = $TOIDisplayS - 2;  
		if($TOIDisplayM == 0 && $TOIDisplayS == 0)
			$TOIDisplayS == 2;
			
		$TOIString = "00:". $TOIDisplayM. ":". $TOIDisplayS;
		
		if($Goals != '0' || $Assists != '0' || $SOG != '0' || $PIM != '0' || $Chksfor != '0' || $TOI != '0'){  // Played Game 
			
			// Insert Stats into PlayerStats
			if($Goals != '0'){
				logMsg("Index:" . $i);
					logMsg("PlayerId:" . $pid);
					logMsg("PlayerName:" . $name);
		logMsg("Pos:" . $pos);

		
		logMsg("Goals:" . $Goals . "<br/>");
			}
			
			$psq = "INSERT INTO PlayerStats (Game_ID, Team_ID, Player_ID, Pos, G, A, SOG, PIM, Chks, TOI)
						VALUES ('$gameid', '$homeid', '$pid', '$pos', '$Goals', '$Assists', '$SOG', '$PIM',
						'$Chksfor', '$TOIString')";
						

			$psr = mysqli_query($conn, $psq);			
			
			if ($psr) {
				//logMsg("Updated Home Player Stats");
			} else {
				logMsg("Error: Home PlayerStats" . $psq . "<br>" . mysqli_error($conn));
			}						
			
								
		}
	
	$i++;
	}
	
	// Away Team
	
	$i = 1;
	
	$plq = "SELECT Player_ID, Team_ID,Last, Pos FROM Roster WHERE Team_ID='$awayid' ORDER BY Player_ID ASC";
	$plr = @mysqli_query($conn, $plq) or die("Could not retrieve Away Player List.  Please contact administrator.");
	
	logMsg("Away Player Stats<br/>");
	$array = array();
	while($prow = mysqli_fetch_array($plr, MYSQL_ASSOC)){  // Team Roster
	
		// Away Player Stats
		$pid = $prow['Player_ID'];
		$pos = $prow['Pos'];
		$name = $prow['Last'];

		fseek ($fr,61277 + $i);
		$Goals = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61303 + $i);
		$Assists = hexdec(bin2hex(fread($fr, 1)));
		$Points = $Goals + $Assists;

		fseek ($fr,61329 + $i);
		$SOG = hexdec(bin2hex(fread($fr, 1)));

		

			fseek ($fr,61355 + $i);
			$PIM = hexdec(bin2hex(fread($fr, 1)));

			fseek ($fr,61381 + $i);
			$Chksfor = hexdec(bin2hex(fread($fr, 1)));	

			$ChksA = 0;
			$PlusMinus = 0;

		// Away TOI
		fseek ($fr,(61406 + ($i * 2)));
		$TOIMinutes = hexdec(bin2hex(fread($fr, 1))) * 256;
		fseek ($fr,(61407 + ($i * 2)));
		$TOISeconds = hexdec(bin2hex(fread($fr, 1)));
		$TOI = ($TOIMinutes + $TOISeconds);
		$TOIDisplayM = Round((Floor($TOI/60)),0);
		$TOIDisplayS = ($TOI % 60);
		
		// Compensate for TOI bug in Genesis
		
		$TOIDisplayS = $TOIDisplayS - 2;  
		if($TOIDisplayM == 0 && $TOIDisplayS == 0)
			$TOIDisplayS == 2;
			
		$TOIString = "00:". $TOIDisplayM. ":". $TOIDisplayS;
		
		if($Goals != '0' || $Assists != '0' || $SOG != '0' || $PIM != '0' || $Chksfor != '0' || $TOI != '0'){  // Played Game 
			
			// Insert Stats into PlayerStats

				if($Goals != '0'){
					logMsg("Index:" . $i);
					logMsg("PlayerId:" . $pid);
					logMsg("PlayerName:" . $name);
		logMsg("Pos:" . $pos);

		
		logMsg("Goals:" . $Goals . "<br/>");
			}
			
			$psq = "INSERT INTO PlayerStats (Game_ID, Team_ID, Player_ID, Pos, G, A, SOG, PIM,Chks, TOI, ChksA, PlusMinus)
						VALUES ('$gameid', '$awayid', '$pid', '$pos', '$Goals', '$Assists', '$SOG', '$PIM',
						'$Chksfor', '$TOIString', $ChksA, $PlusMinus)";
			
			$psr = mysqli_query($conn, $psq);			
			
			if ($psr) {
				//logMsg("Updated Away Player Stats");
			} else {
				logMsg("Error: Away PlayerStats" . $psq . "<br>" . mysqli_error($conn));
			}		
		}
	
	$i++;
	}

	
/**********************************************************************************/			

// Scoring Summary
	
	logMsg("Scoring Summary<br/>");

	 $tmpExtract = 59627;	// Scoring Summary Length Offset
	fseek ($fr,59627);
	$EndofSS = hexdec(bin2hex(fread($fr, 1)));	
	
	for ($i=1;$i<(($EndofSS + 6) / 6);$i+=1){
		
		// Period of Goal
		fseek ($fr,$tmpExtract + 1);
		$Period = (int) (hexdec(bin2hex(fread($fr, 1))) / 64) + 1;

		// Time of Goal (in Seconds)

		fseek ($fr,$tmpExtract + 1);
		$GoalSec =  (hexdec(bin2hex(fread($fr, 1))));
		fseek ($fr,$tmpExtract + 2);
		$Goaltmp =  (hexdec(bin2hex(fread($fr, 1))));
		$GoalTime = ($GoalSec * 256) + $Goaltmp;
		$GoalSec = $GoalSec * 256 + $Goaltmp - ($Period - 1) * 16384;
		$GoalMin = (int) ($GoalSec / 60);
		$GoalSec = ($GoalSec % 60);
		if($GoalSec < 10)
			$Sec = '0'. $GoalSec;
		else
			$Sec = $GoalSec;
		$time = $GoalMin. ":". $Sec;
		
		// Team that scored, type of goal
		
		fseek ($fr,$tmpExtract + 3);
		$GoalTeam = (bin2hex(fread($fr, 1)));
		switch($GoalTeam){
		
			case('0'):
				$team = $homeid;
				$type = 'SH2';
			break;
			case('1'):
				$team = $homeid;
				$type = 'SH';
			break;
			case('2'):
				$team = $homeid;
				$type = 'EV';
			break;
			case('3'):
				$team = $homeid;
				$type = 'PP';
			break;
			case('4'):
				$team = $homeid;
				$type = 'PP2';
			break;
			case('80'):
				$team = $awayid;
				$type = 'SH2';
			break;
			case('81'):
				$team = $awayid;
				$type = 'SH';
			break;
			case('82'):
				$team = $awayid;
				$type = 'EV';
			break;
			case('83'):
				$team = $awayid;
				$type = 'PP';
			break;
			case('84'):
				$team = $awayid;
				$type = 'PP2';
			break;	
			default:
				die("Error with Scoring Summary. Could not retrieve Scoring Team Info.");
			break;
		}
		
		
		// Player that scored
			fseek ($fr,$tmpExtract + 4);
 			$GoalPlayer = (hexdec(bin2hex(fread($fr, 1))));			
			$goalid = getPlayerID($team, $GoalPlayer);
			
			// Assisters on Goal
			fseek ($fr,$tmpExtract + 5);
 			$GoalAst1 = (hexdec(bin2hex(fread($fr, 1))));
			if($GoalAst1 != 255)  // Assist occurred
				$a1id = getPlayerID($team, $GoalAst1);
			else
				$a1id = 0;
			
			fseek ($fr,$tmpExtract + 6);
 			$GoalAst2 = (hexdec(bin2hex(fread($fr, 1))));
			if($GoalAst2 != 255)  // Assist occurred
				$a2id = getPlayerID($team, $GoalAst2);
			else
				$a2id = 0;

			// Enter Scoring Summary into database
		
		$ssq = "INSERT INTO ScoreSum (Game_ID, Team_ID, Period, Time, G, A1, A2, Type)
				VALUES ('$gameid', '$team', '$Period', '$time', '$goalid', '$a1id', '$a2id', '$type')";
		$ssr = @mysqli_query($conn, $ssq) or die("Could not enter Score Summary.");
		
		$tmpExtract = ($tmpExtract + 6);  // move to next goal summary 
	
	}
	
	/**********************************************************************************/
		//Penalty Summary

		$tmpExtract2 = 59989;		// Penalty Offset Start

		fseek ($fr,59989);
 	  	$EndofPS = hexdec(bin2hex(fread($fr, 1)));

		for ($i = 2; $i < (($EndofPS + 6) / 4); $i += 1){

			// Period of Penalty
			fseek ($fr,$tmpExtract2 + 1);
	 		$PenPer = (int) (hexdec(bin2hex(fread($fr, 1))) / 64) + 1;
 
			// Time of Penalty (in Seconds)
			fseek ($fr,$tmpExtract2 + 1);
		 	$PenSec =  (hexdec(bin2hex(fread($fr, 1))));
			fseek ($fr,$tmpExtract2 + 2);
 			$Pentmp =  (hexdec(bin2hex(fread($fr, 1))));
			$PenSec = $PenSec * 256 + $Pentmp - ($PenPer - 1) * 16384;
			$PenMin = (int) ($PenSec / 60);
			$PenSec = ($PenSec % 60);
			if($PenSec < 10)
				$Sec = '0'. $PenSec;
			else
				$Sec = $PenSec;
				
			$pentime = $PenMin. ":". $Sec;

			// Team that got Penalized
			fseek ($fr,$tmpExtract2 + 3);
	 		$PenTeam = (hexdec(bin2hex(fread($fr, 1)))); 
			
			if($PenTeam == 18 || $PenTeam == 22 || $PenTeam ==  24 || $PenTeam ==  26 || $PenTeam == 28 || $PenTeam == 30 
				|| $PenTeam == 32 || $PenTeam ==  34 || $PenTeam ==  36 || $PenTeam == 38) // Home
				$team = $homeid;
			else 
				$team = $awayid;
		
			if($PenTeam == '18'  || $PenTeam == '146')
				$type = "Boarding";
			else if($PenTeam == '22' || $PenTeam == '150')
				$type = "Charging";
			else if($PenTeam == '24' || $PenTeam == '152')
				$type = "Slashing";
			else if($PenTeam == '26' || $PenTeam == '154') 			
				$type = "Roughing";
			else if($PenTeam == '28' || $PenTeam == '156') 
				$type = "Cross Check";
			else if($PenTeam == '30' || $PenTeam == '158')
				$type = "Hooking";
			else if($PenTeam == '32' || $PenTeam == '160')
				$type = "Tripping";
			else if($PenTeam == '34' || $PenTeam == '162')
				$type = "Interference";
			else if($PenTeam == '36' || $PenTeam == '164')
				$type = "Holding";
			else if($PenTeam == '38' || $PenTeam == '166')
				$type = "Holding";
				
			
			// Player
			
			fseek ($fr,$tmpExtract2 + 4);
	 		$PenPlayer = (hexdec(bin2hex(fread($fr, 1))));
			$penid = getPlayerID($team, $PenPlayer);
						
			
			// Add to database
			
			$psq = "INSERT INTO PenSum (Game_ID, Team_ID, Player_ID, Period, Time, Type)
					VALUES ('$gameid', '$team', '$penid', '$PenPer', '$pentime', '$type')";
			$psr = @mysqli_query($conn, $psq) or die("Error: PenaltySummary " . $psq . "<br>" . mysqli_error($conn));		
			
			$tmpExtract2 = ($tmpExtract2 + 4);
		}
	

		// Plus/Minus for Blitz
	
		$tmpExtract = 66413;	// Plus/Minus Info Length Offsets 66412, 66413
		fseek ($fr,66412);
  	 	$EndofPM = hexdec(bin2hex(fread($fr, 2)));
		
		for ($i=1;$i<(($EndofPM + 14) / 14);$i+=1){

			// Type of Goal, Team that Scored
			fseek ($fr,$tmpExtract + 1);		
			$GoalTeam = hexdec(bin2hex(fread($fr, 1)));
//			echo 'Goal Type: '. $GoalTeam. '<br />';
			switch($GoalTeam){
			
				case(0):
					$pm = 1;
					$type = 'SH2';
					$hplayers = 6;
					$aplayers = 6;
					$hpm = 1;
					$apm = -1;
				break;
				case(1):
					$pm = 1;
					$type = 'SH';
					$hplayers = 6;
					$aplayers = 6;
					$hpm = 1;
					$apm = -1;
				break;
				case(2):
					$pm = 1;
					$type = 'EV';
					$hplayers = 6;
					$aplayers = 6;
					$hpm = 1;
					$apm = -1;
				break;
				
				case(128):
					$pm = 1;
					$type = 'SH2';
					$hplayers = 6;
					$aplayers = 6;
					$hpm = -1;
					$apm = 1;
				break;
				case(129):
					$pm = 1;
					$type = 'SH';
					$hplayers = 6;
					$aplayers = 6;
					$hpm = -1;
					$apm = 1;
				break;
				case(130):
					$pm = 1;
					$type = 'EV';
					$hplayers = 6;
					$aplayers = 6;
					$hpm = -1;
					$apm = 1;
				break;
				
				default:
					$pm = 0;
				break;
			}
			
			if($pm == 1){  // Plus/Minus will be applied
				
				$hmonice = $tmpExtract + 3;
				$awonice = $tmpExtract + 9;
				
				// Retrieve Home Players and Add Plus/Minus
				
				for($j = 0;$j < $hplayers;$j++){
					
					fseek ($fr,$hmonice + $j);
 					$Player = (hexdec(bin2hex(fread($fr, 1))));
					
					if($Player != '0' && $Player != '1' && $Player != '255'){	// FF is in place of a player missing (like on a SH goal) or Goalie ( 0 or 1)
						$plid = getPlayerID($hmtm, $Player, $stattype, 0, 'G');
					
						$pmq = "UPDATE PlayerStats SET PlusMinus = PlusMinus + $hpm 
								WHERE Player_ID='$plid' AND Game_ID='$gameid' AND Team_ID='$hmtm' LIMIT 1";
						$pmr = @mysql_query($pmq) or die("Error:  Could not update Home Plus/Minus Stat.");
					}
				}
				
				// Away Players
				
				for($j = 0;$j < $aplayers;$j++){
					
					fseek ($fr,$awonice + $j);
 					$Player = (hexdec(bin2hex(fread($fr, 1))));
					
					if($Player != '0' && $Player != '1' && $Player != '255'){	// 255 is in place of a player missing (like on a SH goal) or Goalie ( 0 or 1)

						$plid = getPlayerID($awtm, $Player, $stattype, 0, 'G');
						$pmq = "UPDATE PlayerStats SET PlusMinus = PlusMinus + $apm
							WHERE Player_ID='$plid' AND Game_ID='$gameid' AND Team_ID='$awtm' LIMIT 1";
						$pmr = @mysql_query($pmq) or die("Error:  Could not update Away Plus/Minus Stat.");
					}
				}
			
		
			}
		
			$tmpExtract = ($tmpExtract + 14);  // move to next summary

		}
//	die();
		

	/**********************************************************************************/

	//mysqli_close($conn);	
	}  // end of function
	

	logMsg ("New Save State");

// retrieve POST variables	

	//$teamid = $_POST['teamid'];
	$seriesid = $_POST['seriesid'];
	$pwd = $_POST['pwd'];
	$userid = $_POST['userid'];

	//echo "GameId: " . $gameid . "</br>";
	echo "UserId: " . $userid . "</br>";
	echo "Password: " . $pwd . "</br>";	
	echo "SeriesId: " . $seriesid . "</br>";	
	
	$chk = errorcheck($userid, $pwd, $seriesid);
	
	if(!$chk)
		$chk = addgame($seriesid);
	else
		$error = $chk;
	
	if(!$chk)  // pass OK
		$error = 0;
	else 
		$error = $chk;	

	//$host = $_SERVER['HTTP_HOST'];
	//header("Location: http://".$host."/nhl94/log_a_state.php?lg=". $lg. "&gmid=". $gameid. "&tmid=". $teamid. 
	//	"&gn=". $gn. "&err=". $error);	
?>