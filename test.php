<!doctype>
<html>
<head>
</head>
<body>
<?php
require_once "Classes/PHPExcel.php";
		//$tmpfname = "test.xlsx";
		$url = "https://docs.google.com/spreadsheets/d/1uNS6Ejp8d7QqOtqsY36D9gQPXc8QyuPhlFFx2pfe--A/export?format=xlsx&id=1uNS6Ejp8d7QqOtqsY36D9gQPXc8QyuPhlFFx2pfe--A";
		$filecontent = file_get_contents($url);
		$tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
		file_put_contents($tmpfname,$filecontent);
		
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheet = $excelObj->getSheet(0);
		$lastRow = $worksheet->getHighestRow();
		
		echo "<table>";
		for ($row = 1; $row <= $lastRow; $row++) {
			 echo "<tr><td>";
			 echo $worksheet->getCell('A'.$row)->getValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('B'.$row)->getValue();
			 echo "</td><tr>";
		}
		echo "</table>";	
?>

</body>
</html>