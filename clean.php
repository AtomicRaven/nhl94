<?php

require_once("dbconnect.php");
require_once("utils.php");

    CleanTable("GameStats");
    CleanTable("PenSum");
    CleanTable("ScoreSum");

    CleanTable("Schedule");
    CleanTable("PlayerStats");
    
?>