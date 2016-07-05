<?php // comfunc.php - Common functions and includes

require_once("dbconnect.php");

// Report simple running errors, no warnings (E_WARNING)

error_reporting(E_ERROR | E_PARSE);

/*************************************************************************/

function ChkLogos($lg){  // Checks for the correct icon set

	$lq = "SELECT Icon from Info WHERE League_ID='$lg' LIMIT 1";
	$lr = mysql_query($lq);

	return (mysql_result($lr,0));

}  // end of function

function chkList($lg){  // Checks list type depending on league (for player and team stat purposes)
	
	$lq = "SELECT List from Info WHERE League_ID='$lg'";
	$lr = mysql_query($lq);
	
	return (mysql_result($lr,0));
	
}  // end of function

function ChkTeam($city, $nick) {  // Checks for duplicate team names
	
	if($city == "New York")
		return ("NY $nick");
	else if($city == "Ottawa" && ($nick == "Civics" || $nick == "Nationals"))
		return ("Ott $nick");
	else if($city == "Pittsburgh" && ($nick == "Pirates"))
		return ("Pit $nick");
	else
		return $city;


} // end of function

function chk20($lg, $sublg){
	
	$lq = "SELECT * from Info WHERE League_ID='$lg' LIMIT 1";
	$lr = @mysql_query($lq);
	
	$row = mysql_fetch_array($lr, MYSQL_ASSOC);
	
	if($row['Gm20_Lg']){  // League has 20 game schedules for some sub leagues
		$list = $row['Sub_Leagues20'];
		$sublist = explode(',', $list);
		$ok = 0;
		
		for($i=0;$sublist[$i]!='0';$i++){
			if($sublist[$i] == $sublg)
				$ok = 1;
		}
		
		return $ok;
		
	}
	else
		return 0;
	
}  // end of function

function validsublg($lg, $sublg){  // Checks if Sub_League exists in current League (error checking)

	$slq = "SELECT Sub_Leagues FROM Info WHERE League_ID = '$lg' LIMIT 1";  // Reg. Season List
	$slr = @mysql_query($slq);

	// Get Sub League list
	
	$slist = mysql_result($slr, 0);
	$sarray = explode(",", $slist);
	$ok = FALSE;
	

	// Compare with current saved Sub League

	for($i=0;$i<count($sarray);$i++){
		if($sarray[$i] == $sublg)
			$ok = TRUE;
	}
	
/*	// Check playoff Sub Leagues
	
	$playpage = '/html/playoffs.php';
	$playlog = '/html/playoffs_log_game.php';
	$playchange = '/admin/update-playscore.php';
	$page = $_SERVER['SCRIPT_NAME'];
	
	if($page == $playpage || $page == $playlog || $page == $playchange){
	
		$plq = "SELECT DISTINCT Sub_League FROM Play_Seeds WHERE League_ID = '$lg'";
		$plr = @mysql_query($plq);
		
		while($row = mysql_fetch_array($plr, MYSQL_NUM))
			if($row[0] == $sublg)
				$ok = TRUE;
		
	}
*/	
	if($ok)
		return $sublg;
	else
		return $sarray[0];

}  // end of function

function chkpl20($lg, $playslg){
	
	$lq = "SELECT * from Info WHERE League_ID='$lg' LIMIT 1";
	$lr = @mysql_query($lq);
	
	$row = mysql_fetch_array($lr, MYSQL_ASSOC);
	
	if($row['Gm20_Lg']){  // League has 20 game schedules for some sub leagues
		$list = $row['Pl_Sublg20'];
		$sublist = explode(',', $list);
		$ok = 0;
		
		for($i=0;$sublist[$i]!='0';$i++){
			if($sublist[$i] == $playslg)
				$ok = 1;
		}
		
		return $ok;
		
	}
	else
		return 0;
	
}  // end of function


function validplayslg($lg, $playslg){  // Checks if current Sub_League is valid for playoffs (Error checking)

	$plq = "SELECT DISTINCT Sub_League FROM Play_Seeds WHERE League_ID = '$lg'";  // Playoff Sublg List
	$plr = @mysql_query($plq);

	$ok = FALSE;
	$first = "";

	// Compare with current saved Sub League

	while($row = mysql_fetch_array($plr, MYSQL_NUM)){
		if(empty($first))
			$first = $row[0];
		if($row[0] == $playslg)
			$ok = TRUE;
	}

	if($ok)
		return $playslg;
	else
		return $first;

}  // end of function

function lgname($lg){  // Retrieves League Name

	$lgnmq = "SELECT Name FROM Leagues WHERE League_ID = '$lg' LIMIT 1";
	$lgnmr = @mysql_query($lgnmq);

	if($lgnmr){
		$lgnmrow = mysql_fetch_array($lgnmr, MYSQL_NUM);
		return ($lgnmrow[0]);		
	}

} // end of function

function playChk($lg){	// Checks to see if Playoffs have started
	
	$plytq = "SELECT Seasonend FROM Leagues WHERE League_ID = '$lg' LIMIT 1";
	
	$plytr = mysql_query($plytq) or die("ERROR 6000: Could not check if season has ended.");
	
	$row = mysql_fetch_array($plytr, MYSQL_NUM);
	
	return $row[0];
	
}  // end of function

function displaylevel($sublg){ // Displays "Genesis" or "Super Nintendo" depending on Sub League
	
	if (substr($sublg, 0, 4) == "GENS"){
		$type = "Genesis";
		$level = substr($sublg, 5);
	}

	else if (substr($sublg, 0, 4) == "SNES"){
		$type = "Super Nintendo";
		$level = substr($sublg, 5);
	}
	
	else
		return $sublg;
		

	return ($type. " ". $level);		

}  // end of function

function chkSys($sublg){  // Checks if GENS or SNES

	if (substr($sublg, 0, 4) == "GENS")
		return "GENS";
	else if (substr($sublg, 0, 4) == "SNES")
		return "SNES";
	else
		die("Could not determine system type.");

} // end of function

function chkLg($lg){  // Checks if league is currently active
	
	$lgregq = "SELECT LgStart FROM Leagues WHERE League_ID = '$lg' LIMIT 1";
	$lgregr = mysql_query($lgregq) or die("Error finding League.  Please contact administrator.");

	$row = mysql_result($lgregr, 0);
	
	if($row[0] == 1)
		return 1;
	else
		return 0;

	return 0;

}  // end of function

function EmailChange($email){  // changes @ to [at] and . to [dot] for email display

	$email = str_replace("@", " [at] ", $email);
	$email = str_replace(".", " [dot] ", $email);

	return $email;

}  // end of function

function saveStateChk($lg){  // Checks if the league is a save state stat league

	$ssq = "SELECT SaveState FROM Info WHERE League_ID='$lg' LIMIT 1";
	$ssr = mysql_query($ssq) or die("Error retrieving League Save State status.  Please contact administrator.");
	
	$row = mysql_result($ssr, 0);
	
	if($row[0] == 1)
		return 1;
	else
		return 0;

}  // end of function

function blitzChk($lg){  // Checks to see if it is a Blitz League (modified stats)

	$bq = "SELECT Blitz FROM Info WHERE League_ID='$lg' LIMIT 1";
	$br = mysql_query($bq) or die("Error retrieving Blitz status.  Please contact administrator.");
	
	$row = mysql_result($br, 0);
	
	if($row[0] == 1)
		return 1;
	else
		return 0;

}  // end of function

function classicChk($lg){  // Checks to see if it is a Classic League (modified roster)

	$bq = "SELECT Classic FROM Info WHERE League_ID='$lg' LIMIT 1";
	$br = mysql_query($bq) or die("Error retrieving Classic status.  Please contact administrator.");
	
	$row = mysql_result($br, 0);
	
	if($row[0] == 1)
		return 1;
	else
		return 0;

}  // end of function

	// Declare global variables and set cookies
	
	global $lg, $sublg, $playslg;
	
	if(isset($_GET['lg']))
		$lg = $_GET['lg'];
	else if(isset($_COOKIE['lg']))
		$lg = $_COOKIE['lg'];
	else  // no cookie or league is selected.  Defaults to newest league
		$lg = "31";

	if(isset($_GET['sublg']) && (substr($_GET['sublg'],0,4) == "GENS" || substr($_GET['sublg'],0,4) == "SNES"))
		$sublg = $_GET['sublg'];
	else if(isset($_COOKIE['sublg']))
		$sublg = $_COOKIE['sublg'];

	if(isset($_GET['playslg']) && (substr($_GET['playslg'],0,4) == "GENS" || 
		substr($_GET['playslg'],0,4) == "SNES"))
		$playslg = $_GET['playslg'];
	else if(isset($_COOKIE['playslg']))
		$playslg = $_COOKIE['playslg'];

	
	$sublg = validsublg($lg, $sublg);  // Check to see if Sub Lg. is in chosen Lg.
	$playslg = validplayslg($lg, $playslg);

	setcookie('lg', $lg, time()+60*60*240, "/"); 
	setcookie('sublg', $sublg, time()+60*60*240, "/");
	setcookie('playslg', $playslg, time()+60*60*240, "/"); 
	
?>
