<?php
$file = fopen("https://docs.google.com/spreadsheets/d/1uNS6Ejp8d7QqOtqsY36D9gQPXc8QyuPhlFFx2pfe--A/export?format=csv&id=1uNS6Ejp8d7QqOtqsY36D9gQPXc8QyuPhlFFx2pfe--A&gid=1625831949","r");
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