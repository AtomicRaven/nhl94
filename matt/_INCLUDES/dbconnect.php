<?php

require_once("data.php");
require_once("config.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nhl94db";

// Create connection
$GLOBALS['$conn'] = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$GLOBALS['$conn']) {
    die("Connection failed: " . mysqli_connect_error());
} else{
	logMsg("DB Connected");
}

?>
