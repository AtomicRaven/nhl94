<?php

$File = 'db/Draft.csv';

$arrResult  = array();
$handle     = fopen($File, "r");
if(empty($handle) === false) {
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $arrResult[] = $data;
    }
    fclose($handle);
}

echo "<table><tr><td>#</td><td>Player</td><td>Team</td></tr>";
$i=1;

foreach($arrResult as $value) {
  echo "<td>" . $i++ . "</td><td>" . $value[0] . "</td><td>" . $value[1] . "</td></tr>";
}

echo "</table>";

?>