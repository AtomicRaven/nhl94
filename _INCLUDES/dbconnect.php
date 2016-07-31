<?php

require_once("config.php");
require_once("utils.php");
require_once("data.php");

//local
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nhl94db";

//prod

//$username = "nhl94";
//$password = "Mysp@ce2174";

// Create connection
$GLOBALS['$conn'] = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$GLOBALS['$conn']) {
    die("Connection failed: " . mysqli_connect_error());
} else{
	logMsg("DB Connected");
}

?>
