<?php

require_once("config.php");
require_once("utils.php");
require_once("data.php");

// Create connection
$GLOBALS['$conn'] = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$GLOBALS['$conn']) {
    die("Connection failed: " . mysqli_connect_error());
} else{
	logMsg("DB Connected");
    date_default_timezone_set('America/Toronto'); // set timezone in php
    mysql_query("SET `time_zone` = '".date('P')."'"); // set timezone in MySQL
}

?>
