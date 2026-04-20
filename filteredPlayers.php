<?php

  include_once './_INCLUDES/00_SETUP.php';
  include_once './_INCLUDES/dbconnect.php';	
    
$leagueid = 1;

if (isset($_GET["binId"])){
  $leagueid = $_GET["binId"];
}

$selectedLg = GetLeague($leagueid);

if($selectedLg["DraftSheet"] != null){
  $file = fopen($selectedLg["DraftSheet"],"r");
  echo $selectedLg["DraftSheet"] . "<br/><br/>";

$draftedPlayers = array();
  while(!feof($file))
  {
    $draftedPlayers[] = fgetcsv($file)[3];
    //print_r(fgetcsv($file));
    //print_r("<br/>");
  }

  //print_r($draftedPlayers);
  fclose($file);

  echo "<h2>Drafted Players:</h2>";
  
  foreach($draftedPlayers as $key => $value)
  {
    echo $key." :". $value . "<br/>";
  }
}else{
  echo 'There is no DraftSheet for this League';
}
?>