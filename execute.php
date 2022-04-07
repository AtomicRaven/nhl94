<?php

    require_once("./_INCLUDES/config.php");

    $assetPath = $_SERVER['DOCUMENT_ROOT']. 'nhl94/';

    $exe = $assetPath . "exe/NHL94BinParser.exe";
    $bin = $assetPath . "bin/VHL8season.bin";
    $cmd = $exe . " -i " . $bin;
    
    $test=shell_exec('env');

    echo "test: " . $test . "<br/><br/>";  
   
    //$answer = exec($cmd);

    echo "exe: " . $exe . "<br/>"; 
    echo "bin: " . $bin . "<br/>"; 
    echo "cmd: " . $cmd . "<br/><br/>"; 

    //echo "answer: " . $answer;

?>
