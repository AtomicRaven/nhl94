<?php

include_once realpath(dirname(__FILE__)). './config.php';
include_once realpath(dirname(__FILE__)). './utils.php';
include_once realpath(dirname(__FILE__)). './data.php';


// Create connection
$GLOBALS['$conn'] = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$GLOBALS['$conn']) {
    die("Connection failed: " . mysqli_connect_error());
} else{
	logMsg("DB Connected");
    date_default_timezone_set('America/Toronto'); // set timezone in php
    mysqli_query($GLOBALS['$conn'], "SET `time_zone` = '".date('P')."'"); // set timezone in MySQL
}

?>
