<?php

    //Note TourneyId is only used in tournaments, else it is set to 0

    function AddGame($seriesid, $scheduleid, $homeuserid, $awayuserid, $leagueid, $tourneyid, $type){	// add game to database

            $conn = $GLOBALS['$conn'];
            $nextGameId = GetNextGameId();
            $filePath = $GLOBALS['$saveFilePath'];
            $leagueName = GetLeagueTableABV($leagueid);
            $blitz = blitzChk($leagueid);

            if($tourneyid >0){
                $tourney = GetTourneyById($tourneyid);
                $leaguenm = $tourney["ABV"];
                $filePath = $filePath . $leaguenm;
            }else{
                $filePath .= $seriesid;
            }
            $file = $filePath . "/" . "Series-" . $seriesid. '-game-'. $scheduleid . '.sv';

            //ParseFile
            $fr = fopen("$file", 'rb');	// reads file
           
            if($type == 'ra'){
                //$filetypes = array('st', 'gp');
                //$e = '.gpgx';
                $offset = 9304;
                $endian = 'little';
                $endianfix = 1;
            }				
            else {
                //$filetypes = array('gs');
                //$e = '.gs0';
                $offset = 0;
                $endian = 'big';
                $endianfix = 0;
            }
			

            // Away Team
            fseek ($fr,59307 - $offset - $endianfix);

            $awayid = hexdec(bin2hex(fread($fr, 1))) + 1;

            // Home Team
            fseek ($fr,59305 - $offset - $endianfix);

            $homeid = hexdec(bin2hex(fread($fr, 1))) + 1;


            // Crowd Meter
            fseek ($fr,59277 - $offset - $endianfix);
            $PeakMeter = hexdec(bin2hex(fread($fr, 1)));

            logMsg ("Crowd:" . $PeakMeter);

// AWAY TEAM STATS

		// Away Goals
		fseek ($fr,61111 - $offset - $endianfix);
   		$AwayScore = hexdec(bin2hex(fread($fr, 1)));

		// Away Power Play Goals/Opportunities
		fseek ($fr,61101 - $offset - $endianfix);
 		$AwayPPG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61103 - $offset - $endianfix);
 		$AwayPP = hexdec(bin2hex(fread($fr, 1)));

		// Away SHG
		fseek ($fr,61953 - $offset - $endianfix);
 		$AwaySHG = hexdec(bin2hex(fread($fr, 1)));

		// Away SHGA
		fseek ($fr,61085 - $offset - $endianfix);
 		$AwaySHGA = hexdec(bin2hex(fread($fr, 1)));

		// Away Breakaway Goals/Opportunities
		fseek ($fr,61957 - $offset - $endianfix);
 		$AwayBKG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61955 - $offset - $endianfix);
 		$AwayBKO = hexdec(bin2hex(fread($fr, 1)));

		// Away One Timer Goals:Attempts
		fseek ($fr,61961 - $offset - $endianfix);
		$Away1TG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61959 - $offset - $endianfix);
 		$Away1TA = hexdec(bin2hex(fread($fr, 1)));

		// Away Penalty Shot Goals:Attempts
		fseek ($fr,61965 - $offset - $endianfix);
 		$AwayPSG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61963 - $offset - $endianfix);
   		$AwayPSA = hexdec(bin2hex(fread($fr, 1)));

		// Away Faceoffs Won
		fseek ($fr,61113 - $offset - $endianfix);
 		$AwayFOW = hexdec(bin2hex(fread($fr, 1)));

		// Away Body Checks
		fseek ($fr,61115 - $offset - $endianfix);
 		$AwayByCks = hexdec(bin2hex(fread($fr, 1)));

		// Away Team Penalties / Minutes
		fseek ($fr,61105 - $offset - $endianfix);
 		$AwayPen = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61107 - $offset - $endianfix);
 		$AwayPenM = hexdec(bin2hex(fread($fr, 1)));

		// Away Attack Zone

		if($endian == 'little'){
			fseek ($fr,61109 - $offset);
 			$AwayAZMinutes = hexdec(bin2hex(fread($fr, 1))) *256;
			fseek ($fr,61108 - $offset);
 			$AwayAZSeconds = hexdec(bin2hex(fread($fr, 1)));
		}

		else {
			fseek ($fr,61108 - $offset);
 			$AwayAZMinutes = hexdec(bin2hex(fread($fr, 1))) *256;
			fseek ($fr,61109 - $offset);
 			$AwayAZSeconds = hexdec(bin2hex(fread($fr, 1)));
		}

		$AwayAZ = ($AwayAZMinutes + $AwayAZSeconds);

 		$AwayAZDisplayM = Round((Floor($AwayAZ/60)),0);
 		$AwayAZDisplayS = ($AwayAZ % 60);


		// Away Passing Stats
		fseek ($fr,61119 - $offset - $endianfix);
 		$AwayPsC = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61117 - $offset - $endianfix);
 		$AwayPsA = hexdec(bin2hex(fread($fr, 1)));

		// HOME TEAM STATS

		// Home Goals
		fseek ($fr,60243 - $offset - $endianfix);
   		$HomeScore = hexdec(bin2hex(fread($fr, 1)));

		// Home Power Play Goals/Opportunities
		fseek ($fr,60233 - $offset - $endianfix);
 		$HomePPG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,60235 - $offset - $endianfix);
   		$HomePP = hexdec(bin2hex(fread($fr, 1)));

		// Home SHG
		fseek ($fr,61085 - $offset - $endianfix);
 		$HomeSHG = hexdec(bin2hex(fread($fr, 1)));

		// Home SHGA
		fseek ($fr,61953 - $offset - $endianfix);
   		$HomeSHGA = hexdec(bin2hex(fread($fr, 1)));

		// Home Breakaway Goals/Opportunities
		fseek ($fr,61089 - $offset - $endianfix);
   		$HomeBKG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61087 - $offset - $endianfix);
   		$HomeBKO = hexdec(bin2hex(fread($fr, 1)));

		// Home One Timer Goals:Attempts
		fseek ($fr,61093 - $offset - $endianfix);
   		$Home1TG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61091 - $offset - $endianfix);
   		$Home1TA = hexdec(bin2hex(fread($fr, 1)));

		// Home Penalty Shot Goals:Attempts
		fseek ($fr,61097 - $offset - $endianfix);
   		$HomePSG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61095 - $offset - $endianfix);
   		$HomePSA = hexdec(bin2hex(fread($fr, 1)));

		// Home Faceoffs Won
		fseek ($fr,60245 - $offset - $endianfix);
   		$HomeFOW = hexdec(bin2hex(fread($fr, 1)));

		// Home Body Checks
		fseek ($fr,60247 - $offset - $endianfix);
   		$HomeByCks = hexdec(bin2hex(fread($fr, 1)));

		// Home Team Penalties / Minutes
		fseek ($fr,60237 - $offset - $endianfix);
   		$HomePen = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,60239 - $offset - $endianfix);
   		$HomePenM = hexdec(bin2hex(fread($fr, 1)));

		// Home Attack Zone
		if($endian == 'little'){
			fseek ($fr,60241 - $offset);
   			$HomeAZMinutes = hexdec(bin2hex(fread($fr, 1))) *256;
			fseek ($fr,60240  - $offset);
   			$HomeAZSeconds = hexdec(bin2hex(fread($fr, 1)));
		}
		else {
			fseek ($fr,60240  - $offset);
   			$HomeAZMinutes = hexdec(bin2hex(fread($fr, 1))) *256;
			fseek ($fr,60241  - $offset);
   			$HomeAZSeconds = hexdec(bin2hex(fread($fr, 1)));
		}

		$HomeAZ = ($HomeAZMinutes + $HomeAZSeconds);

		$HomeAZDisplayM = Round((Floor($HomeAZ/60)),0);
   	$HomeAZDisplayS = ($HomeAZ % 60);

		// Home Passing Stats
		fseek ($fr,60251 - $offset - $endianfix);
   		$HomePsC = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,60249 - $offset - $endianfix);
   		$HomePsA = hexdec(bin2hex(fread($fr, 1)));

		/*********PERIOD STATS*************/

		// Away Team Period Goals

		fseek ($fr,61933 - $offset - $endianfix);
   		$A1stGoals = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61935 - $offset - $endianfix);
   		$A2ndGoals = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61937 - $offset - $endianfix);
   		$A3rdGoals = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61939 - $offset - $endianfix);
   		$AOTGoals = hexdec(bin2hex(fread($fr, 1)));

		// Away Team Period SOG
		fseek ($fr,61941 - $offset - $endianfix);
   		$A1stSOG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61943 - $offset - $endianfix);
   		$A2ndSOG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61945 - $offset - $endianfix);
   		$A3rdSOG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61947 - $offset - $endianfix);
   		$AOTSOG = hexdec(bin2hex(fread($fr, 1)));

		//--------- End of Away Period Stats---------------

		//--------- Home Team Period Stats-----------------

		// Home Team Period Goals
		fseek ($fr,61065 - $offset - $endianfix);
   		$H1stGoals = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61067 - $offset - $endianfix);
   		$H2ndGoals = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61069 - $offset - $endianfix);
   		$H3rdGoals = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61071 - $offset - $endianfix);
   		$HOTGoals = hexdec(bin2hex(fread($fr, 1)));

		// Home Team Period SOG
		fseek ($fr,61073 - $offset - $endianfix);
   		$H1stSOG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61075 - $offset - $endianfix);
   		$H2ndSOG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61077 - $offset - $endianfix);
   		$H3rdSOG = hexdec(bin2hex(fread($fr, 1)));

		fseek ($fr,61079 - $offset - $endianfix);
   		$HOTSOG = hexdec(bin2hex(fread($fr, 1)));

		//------ End of Home Period Stats ---------


            // OT Game?
            //$time = gameLength($lg);  // Retrieve Period and OT Length
            $time = 5;
            $regtime = $time[0] * 3;  // Length of Regulation Game

            $HTotalGoals = $H1stGoals + $H2ndGoals + $H3rdGoals + $HOTGoals;
            $ATotalGoals = $A1stGoals + $A2ndGoals + $A3rdGoals + $AOTGoals;            

            if(($HOTSOG > 0) || ($AOTSOG > 0) || ($HOTGoals > 0) || ($AOTGoals > 0) || ($HTotalGoals == $ATotalGoals))  // OT Game
                $OT = '1';
            else
                $OT = '0';

            // Add to GameStats

            $awayTeamAbv = GetTeamById($awayid, $leagueid);
            $homeTeamAbv = GetTeamById($homeid, $leagueid);
            $schedule = GetScheduleByID($scheduleid);

            $homeUserAlias = GetUserAlias($homeuserid);
            $awayUserAlias = GetUserAlias($awayuserid);

            //logMsg("Away Team: ID " . $awayid . " : " . $awayTeamAbv["ABV"] . ": " . $AwayScore . " Goals. Player: " . $awayUserAlias);
            //logMsg("Home Team: ID " . $homeid . " : " . $homeTeamAbv["ABV"] . ": " . $HomeScore . " Goals. Player: " . $homeUserAlias);

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


            $sql = "INSERT INTO gamestats (LeagueID, SeriesID, Crowd
                    $goalf $shotf $pmf $bcf $ppshf $baf $otf $psf $foakf $pasf)
                    VALUES ($leagueid, '$seriesid', '$PeakMeter' $goalv $shotv $pmv $bcv $ppshv $bav $otv $psv $foakv $pasv)";

            $sqlr = mysqli_query($conn, $sql);

            $gameid = -1;

            if ($sqlr) {
                $gameid = $conn->insert_id;
                logMsg("New GameStats record created successfully.  GameID: " . $gameid);
            } else {
                logMsg("Error: GameStats: " . $sql . "<br>" . mysqli_error($conn));
            }


            //logMsg($sql);
            // Update Schedule

            if($HomeScore > $AwayScore){

                $WinnerUserID = $homeuserid;

            }else{

                $WinnerUserID = $awayuserid;

            }


            $schupq = "UPDATE schedule SET LeagueID='$leagueid', HomeScore= '$HomeScore', AwayScore= '$AwayScore', HomeUserID='$homeuserid', AwayUserID='$awayuserid', HomeTeamID='$homeid', AwayTeamID='$awayid', OT= '$OT', ConfirmTime= NOW(), GameID='$gameid', WinnerUserID='$WinnerUserID'
                WHERE ID= '$scheduleid' LIMIT 1";

            //$schupq = "INSERT INTO Schedule (HomeScore, AwayScore, OT, ConfirmTime, GameID, WinnerTeamID)
            //		VALUES ('$homeid', '$awayid', '$HomeUserID', '$AwayUserID', '$HomeScore', '$AwayScore', '$OT', NOW(), '$gameid', '$seriesid', '$WinnerTeamID')";

            $schupr = mysqli_query($conn, $schupq);

            if ($schupr) {
                logMsg("Schedule Updated");
            } else {
                logMsg("Error: Schedule: " . $schupr . "<br>" . mysqli_error($conn));
            }

            //Check to see if series is complete.  If so Mark as Complete
            CheckSeriesForWinner($seriesid, $homeuserid, $awayuserid);

            //logMsg($schupq);

    /**********************************************************************************/


        // Add Player Stats


        $i = 1;
        $homechk = 0;
        $swap = $endianfix;
        // Get Home Team

        //Get League Table

        $lgTable = GetLeagueTableName($leagueid);

        $plq = "SELECT PlayerID, TeamID, Last, Pos FROM $lgTable WHERE TeamID='$homeid' ORDER BY PlayerID ASC";

        $plr = @mysqli_query($conn, $plq) or die("Could not retrieve Home Player List.  Please contact administrator.");
        //echo $plq;

        logMsg("Home Player Stats<br/>");
        while($prow = mysqli_fetch_array($plr, MYSQLI_ASSOC)){	// Team Roster

 			// Home Player Stats

             $pid = $prow['PlayerID'];
             $pos = $prow['Pos'];
             $name = $prow['Last'];
 
             fseek ($fr,60409 - $offset + $i + $swap);				// Swap and Offset needed for GPGX and Picodrive. Player offset order goes 01 00 03 02 05 04 .....
                $Goals = hexdec(bin2hex(fread($fr, 1)));
 
             fseek ($fr,60435 - $offset + $i + $swap);
                $Assists = hexdec(bin2hex(fread($fr, 1)));
             $Points = $Goals + $Assists;
 
             fseek ($fr,60461 - $offset + $i + $swap);
                $SOG = hexdec(bin2hex(fread($fr, 1)));
 
             if($blitz == 1){  // Blitz League
 
                 fseek ($fr,60487 - $offset + $i + $swap);		// In Blitz, this is Chks For
                    $Chksfor = hexdec(bin2hex(fread($fr, 1)));
 
                 fseek ($fr,60513 - $offset + $i + $swap);		// In Blitz, this is Chks Against
                    $ChksA = hexdec(bin2hex(fread($fr, 1)));
 
                 $PIM = 0;  // will be calculated later
                 $PlusMinus = 0;
 
             }
             else {
 
                 fseek ($fr,60487 - $offset + $i + $swap);
                    $PIM = hexdec(bin2hex(fread($fr, 1)));
 
                 fseek ($fr,60513 - $offset + $i + $swap);
                    $Chksfor = hexdec(bin2hex(fread($fr, 1)));
 
                 $ChksA = 0;
                 $PlusMinus = 0;
 
             }
 
             $homechk += $Chksfor;
 
             // Home TOI
             if($endian == 'little'){
                 fseek ($fr,(60539  - $offset + ($i * 2)));
                    $TOIMinutes = hexdec(bin2hex(fread($fr, 1))) * 256;
                 fseek ($fr,(60538  - $offset + ($i * 2)));
                    $TOISeconds = hexdec(bin2hex(fread($fr, 1)));
             }
             else {
                 fseek ($fr,(60538  - $offset + ($i * 2)));
                    $TOIMinutes = hexdec(bin2hex(fread($fr, 1))) * 256;
                 fseek ($fr,(60539  - $offset + ($i * 2)));
                    $TOISeconds = hexdec(bin2hex(fread($fr, 1)));
             }
 
             if(!$OT && ($TOIMinutes + $TOISeconds) > $regtime)
                 $TOI = $regtime + 2;
             else
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

                $psq = "INSERT INTO playerstats (GameID, TeamID, PlayerID, Pos, G, A, SOG, PIM, Chks, TOI, ChksA, PlusMinus, LeagueID)
                            VALUES ('$gameid', '$homeid', '$pid', '$pos', '$Goals', '$Assists', '$SOG', '$PIM',
                            '$Chksfor', '$TOIString', '$ChksA', '$PlusMinus', '$leagueid')";


                $psr = mysqli_query($conn, $psq);

                if ($psr) {
                    //logMsg("Updated Home Player Stats");
                } else {
                    logMsg("Error: Home PlayerStats" . $psq . "<br>" . mysqli_error($conn));
                }


            }
        $swap = ($swap * -1);
        $i++;
        }

        // Away Team

        $i = 1;
        $awaychk = 0;
        $swap = $endianfix;

        $plq = "SELECT PlayerID, TeamID,Last, Pos FROM $lgTable WHERE TeamID='$awayid' ORDER BY PlayerID ASC";
        $plr = @mysqli_query($conn, $plq) or die("Could not retrieve Away Player List.  Please contact administrator.");

        logMsg("Away Player Stats<br/>");
        $array = array();
        while($prow = mysqli_fetch_array($plr, MYSQLI_ASSOC)){  // Team Roster
        

			// Away Player Stats

			$pid = $prow['PlayerID'];
            $pos = $prow['Pos'];
            $name = $prow['Last'];

			fseek ($fr,61277 - $offset + $i + $swap);
   			$Goals = hexdec(bin2hex(fread($fr, 1)));

			fseek ($fr,61303 - $offset + $i + $swap);
   			$Assists = hexdec(bin2hex(fread($fr, 1)));
   			$Points = $Goals + $Assists;

			fseek ($fr,61329 - $offset + $i + $swap);
   			$SOG = hexdec(bin2hex(fread($fr, 1)));

			if($blitz == 1){  // Blitz League

				fseek ($fr,61355 - $offset + $i + $swap);		// In Blitz, this is Chks For
   				$Chksfor = hexdec(bin2hex(fread($fr, 1)));

				fseek ($fr,61381 - $offset + $i + $swap);		// In Blitz, this is Chks Against
   				$ChksA = hexdec(bin2hex(fread($fr, 1)));

				$PIM = 0;  // will be calculated later
				$PlusMinus = 0;

			}
			else {

				fseek ($fr,61355 - $offset + $i + $swap);
   				$PIM = hexdec(bin2hex(fread($fr, 1)));

				fseek ($fr,61381 - $offset + $i + $swap);
   				$Chksfor = hexdec(bin2hex(fread($fr, 1)));

				$ChksA = 0;
				$PlusMinus = 0;

			}

			$awaychk += $Chksfor;

			// Away TOI
			if($endian == 'little'){
				fseek ($fr,(61407  - $offset + ($i * 2)));
   				$TOIMinutes = hexdec(bin2hex(fread($fr, 1))) * 256;
				fseek ($fr,(61406  - $offset + ($i * 2)));
   				$TOISeconds = hexdec(bin2hex(fread($fr, 1)));
			}
			else {
				fseek ($fr,(61406  - $offset + ($i * 2)));
   				$TOIMinutes = hexdec(bin2hex(fread($fr, 1))) * 256;
				fseek ($fr,(61407  - $offset + ($i * 2)));
   				$TOISeconds = hexdec(bin2hex(fread($fr, 1)));
			}

   			if(!$OT && ($TOIMinutes + $TOISeconds) > $regtime)
				$TOI = $regtime + 2;
			else
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

                logMsg("ChksA:" . $ChksA . "<br/>");

                $psq = "INSERT INTO playerstats (GameID, TeamID, PlayerID, Pos, G, A, SOG, PIM, Chks, TOI, ChksA, PlusMinus, LeagueID)
                            VALUES ('$gameid', '$awayid', '$pid', '$pos', '$Goals', '$Assists', '$SOG', '$PIM', '$Chksfor', '$TOIString', '$ChksA', '$PlusMinus', '$leagueid')";

                $psr = mysqli_query($conn, $psq);

                if ($psr) {
                    //logMsg("Updated Away Player Stats");
                } else {
                    logMsg("Error: Away PlayerStats" . $psq . "<br>" . mysqli_error($conn));
                }
            }
        $swap = ($swap * -1);   // Byte Swap trick
        $i++;
        }


    /**********************************************************************************/

    // Scoring Summary

        logMsg("Scoring Summary<br/>");

        $tmpExtract = 59627 - $offset;
		fseek ($fr,59627 - $offset - $endianfix);	// Scoring Summary Length Offset
  	 	$EndofSS = hexdec(bin2hex(fread($fr, 1)));
		$swap = $endianfix;

		for ($i=1;$i<(($EndofSS + 6) / 6);$i+=1){

			// Period of Goal
			fseek ($fr,$tmpExtract + 1 + $swap);
 			$Period = (int) (hexdec(bin2hex(fread($fr, 1))) / 64) + 1;

			// Time of Goal (in Seconds)

			fseek ($fr,$tmpExtract + 1 + $swap);
	 		$GoalSec =  (hexdec(bin2hex(fread($fr, 1))));
			fseek ($fr,$tmpExtract + 2 - $swap);
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

			fseek ($fr,$tmpExtract + 3 + $swap);
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
			fseek ($fr,$tmpExtract + 4 - $swap);
 			$GoalPlayer = (hexdec(bin2hex(fread($fr, 1))));
			$goalid = getPlayerID($team, $GoalPlayer, $leagueid);

			// Assisters on Goal
			fseek ($fr,$tmpExtract + 5 + $swap);
            $GoalAst1 = (hexdec(bin2hex(fread($fr, 1))));
            if($GoalAst1 != 255)  // Assist occurred
                $a1id = getPlayerID($team, $GoalAst1, $leagueid);
            else
                $a1id = 0;

			fseek ($fr,$tmpExtract + 6 - $swap);
            $GoalAst2 = (hexdec(bin2hex(fread($fr, 1))));
            if($GoalAst2 != 255)  // Assist occurred
                $a2id = getPlayerID($team, $GoalAst2, $leagueid);
            else
                $a2id = 0;

			// Enter Scoring Summary into database

            $ssq = "INSERT INTO scoresum (GameID, TeamID, Period, Time, G, A1, A2, Type)
                    VALUES ($gameid, '$team', '$Period', '$time', '$goalid', '$a1id', '$a2id', '$type')";
            $ssr = @mysqli_query($conn, $ssq) or die($ssq. " Could not enter Score Summary." .  mysqli_error($conn));

            //echo $ssq;
            $tmpExtract = ($tmpExtract + 6);  // move to next goal summary

        }

        /**********************************************************************************/
            //Penalty Summary

            $tmpExtract2 = 59989 - $offset;		// Penalty Offset Start

		fseek ($fr,59989 - $offset - $endianfix);
 	  	$EndofPS = hexdec(bin2hex(fread($fr, 1)));
		$swap = $endianfix;

		for ($i = 2; $i < (($EndofPS + 6) / 4); $i += 1){

			// Period of Penalty
			fseek ($fr,$tmpExtract2 + 1 + $swap);
	 		$PenPer = (int) (hexdec(bin2hex(fread($fr, 1))) / 64) + 1;

			// Time of Penalty (in Seconds)
			fseek ($fr,$tmpExtract2 + 1 + $swap);
		 	$PenSec =  (hexdec(bin2hex(fread($fr, 1))));
			fseek ($fr,$tmpExtract2 + 2 - $swap);
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
			fseek ($fr,$tmpExtract2 + 3 + $swap);
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

			fseek ($fr,$tmpExtract2 + 4 - $swap);
	 		$PenPlayer = (hexdec(bin2hex(fread($fr, 1))));
            $penid = getPlayerID($team, $PenPlayer, $leagueid);


			// Add to database

                $psq = "INSERT INTO pensum (GameID, TeamID, PlayerID, Period, Time, Type)
                        VALUES ('$gameid', '$team', '$penid', '$PenPer', '$pentime', '$type')";
                $psr = @mysqli_query($conn, $psq) or die("Error: PenaltySummary " . $psq . "<br>" . mysqli_error($conn));

                if($blitz == 1){  // Blitz League

                    $pq = "UPDATE playerstats SET PIM=PIM+2 WHERE PlayerID='$penid' AND GameID='$gameid' LIMIT 1";
                    //echo $pq . "<br/>";

                    $psr = mysqli_query($conn, $pq);

                    //echo mysqli_affected_rows($conn);

                    if ($psr) {
                        //logMsg("Updated Home Player Stats");
                    } else {
                        die("Error: COuld not add Blitz Penalties" . $pq . "<br>" . mysqli_error($conn));
                    }


                }

                $tmpExtract2 = ($tmpExtract2 + 4);
            }


            // Plus/Minus for Blitz

        $tmpExtract = 66413 - $offset;	// Plus/Minus Info Length Offsets 66412, 66413
		$swap = $endianfix;
		fseek ($fr,66412 - $offset + $swap);
		$firstPM = hexdec(bin2hex(fread($fr, 1)));
		fseek ($fr, 66413 - $offset - $swap);
		$secondPM = hexdec(bin2hex(fread($fr, 1)));
  	 	$EndofPM = ($firstPM * 256) + $secondPM;


		for ($i=1;$i<(($EndofPM + 14) / 14);$i+=1){

			// Type of Goal, Team that Scored
			fseek ($fr,$tmpExtract + 1 + $swap);
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

			if(false){  // Plus/Minus will be applied

				$hmonice = $tmpExtract + 3;
				$awonice = $tmpExtract + 9;

				// Retrieve Home Players and Add Plus/Minus

				for($j = 0;$j < $hplayers;$j++){

					fseek ($fr,$hmonice + $j);
 					$Player = (hexdec(bin2hex(fread($fr, 1))));

					if($Player != '255'){	// 255 is in place of a player missing (like on a SH goal)
						$plid = getPlayerID($homeid, $Player, $stattype, 0, 'G');

						$pmq = "UPDATE PlayerStats SET PlusMinus = PlusMinus + $hpm
								WHERE PlayerID='$plid' AND Game_ID='$gameid' AND Team_ID='$homeid' LIMIT 1";
						$pmr = @mysql_query($pmq) or die("Error:  Could not update Home Plus/Minus Stat.");
					}
				}

				// Away Players

				for($j = 0;$j < $aplayers;$j++){

					fseek ($fr,$awonice + $j);
 					$Player = (hexdec(bin2hex(fread($fr, 1))));

					if($Player != '255'){	// 255 is in place of a player missing (like on a SH goal)

						$plid = getPlayerID($awayid, $Player, $stattype, 0, 'G');
						$pmq = "UPDATE PlayerStats SET PlusMinus = PlusMinus + $apm
							WHERE PlayerID='$plid' AND Game_ID='$gameid' AND Team_ID='$awayid' LIMIT 1";
						$pmr = @mysql_query($pmq) or die("Error:  Could not update Away Plus/Minus Stat.");
					}
				}


			}

			$tmpExtract = ($tmpExtract + 14);  // move to next summary

            }
    //	die();
    	/**********************************************************************************/

        fclose($fr);
        rename ($file, $filePath . "/" . $awayTeamAbv["Team"] . "-" . $awayUserAlias . "_at_" . $homeTeamAbv["Team"] . "-" . $homeUserAlias . "_gameId-" . $gameid . "_seriesId-" .$seriesid . "_bin-" . $leagueName . ".gs0" );

	//mysqli_close($conn);
	}  // end of function
?>