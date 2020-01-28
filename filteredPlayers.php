<?php
$file = fopen("https://docs.google.com/spreadsheets/d/1FPbQs0gWqhUwm6yCEZRGTRHBIgSXVxn1yoGCG3hyfn4/export?format=csv&id=1FPbQs0gWqhUwm6yCEZRGTRHBIgSXVxn1yoGCG3hyfn4&gid=0","r");
$draftedPlayers = array();
  while(!feof($file))
  {
    $draftedPlayers[] = fgetcsv($file)[3];
    //print_r(fgetcsv($file));
    //print_r("<br/>");
  }

  //print_r($draftedPlayers);
  fclose($file);

  foreach($draftedPlayers as $key => $value)
  {
    echo $key." :". $value . "<br/>";
  }

?>