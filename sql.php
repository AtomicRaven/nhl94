<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
    require_once("./_INCLUDES/config.php");
		include_once './_INCLUDES/dbconnect.php';	



if ($LOGGED_IN == true && $_SESSION['Admin'] == true) {

	$msg = "";
	$commandType = 0;
	
	if (isset($_POST["sql"])){
			$sql = $_POST["sql"];
			$pieces = explode(" ", $sql );
			$command = strtoupper($pieces[0]);

			if ( $command == 'SELECT' ) {
					if ( $pieces[1] == '*' ) {
							$commandType = 2;
					}		
					else {
							$commandType = 1;
					}
			}			
			
			if ( $command == 'SHOW' ) {
				$commandType = 3;
			}
			
			$msg = 'SQL executed using ' . $command;
	}
	else {
		$msg = 'Default SQL used...';
		$sql = 'SELECT id_user, username, name, email FROM users ORDER BY id_user';
		$command = 'SELECT';
		$commandType = 1;
	}
	

?><!DOCTYPE HTML>
<html>
<head>
<title>Useful Stuff</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
<style>

	#sql {
			width: 100%;
			height: 100px;
			padding: 10px;
			margin-bottom: 1em;
	}
	
	button.submit {
		margin-bottom: 1em;
	}
	
	table.sqlTable {
		border-collapse: collapse;
	}

	table.sqlTable td {
		border: 1px solid #ccc;
	}
	
	table.sqlTable tr.header td {
		background-color: #ccc;
		border: 1px solid #999;
	}	
	
</style>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">				

					<h1>SQL EXECUTOR 1.4</h1>
					<div><?=$msg?></div>

					<div style="padding:40px; margin:20px;">

					<p><u>Examples</u></p>
					<p>SELECT id_user, username, name, email FROM users ORDER BY id_user DESC</p>
					<p>SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'nhl94db'</p>
					<p>UPDATE users SET name='Matt' WHERE id_user = 2</p>
					<p>INSERT INTO `nhl94db`.`schedule` (`ID`, `HomeTeamID`, `AwayTeamID`, `HomeScore`, `AwayScore`, `OT`, `ConfirmTime`, `GameID`, `SeriesID`, `HomeUserID`, `AwayUserID`, `WinnerUserID`, `LeagueID`, `TourneyID`) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '696', '2', '1', NULL, '44', '0');</p>
					<p>UPDATE  `nhl94db`.`league` SET  `Name` =  'JJ Summer 2018' WHERE  `league`.`LeagueID` =49;</p>
					<p>SELECT * FROM `league` ORDER BY `LeagueID` ASC</p>
					
					<form method="post" action="sql.php">
					
					<textarea id="sql" name="sql"><?php print $sql; ?></textarea>
					<button type="submit" class="submit">Submit</button>
					<button type="button" class="submit" onclick="window.open('backup.php')">BackUp DB</button>
					
<?php

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($commandType == 0 ) { 
//$sql = "SELECT id_user, username, name, email FROM users ORDER BY id_user";
$result = $conn->query($sql);
}
else {
		if ($commandType == 1 ) {
					// this needs to extract field names between SELECT and FROM
					$result = $conn->query($sql);
					print '<table class="sqlTable"><tr class="header"><td><b>id_user</b></td><td><b>username</b></td><td><b> name</b></td><td><b>email</b></td></tr>';
					if ($result->num_rows > 0) {
							// output data of each row
							while($row = $result->fetch_assoc()) {
										print "<tr><td>" . $row["id_user"] . "</td><td>" . $row["username"] . "</td><td>" . $row["name"] . "</td><td>" . $row["email"] . "</td></tr>";
							}
					} else {
							print '<tr><td colspan="4">0 results</td></tr>';
					}
					print '</table>';
		}
		else {
				if ($commandType == 2 ) {
						$sqlCols = 'SHOW COLUMNS FROM ' . $pieces[3];
						$result = $conn->query($sqlCols);
						$fieldName = [];
						print '<table class="sqlTable"><tr class="header">';
						while($row = mysqli_fetch_array($result)) {
								print '<td>' .  $row['Field'] . '</td>';		
								array_push( $fieldName, $row['Field'] );
						}
						print '</tr>';
						$fieldMax = count($fieldName);
						// now do fields
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
								// output data of each row
								while($row = $result->fetch_assoc()) {
											print "<tr>";
											 for( $i=0; $i < $fieldMax; $i++ ) {
													print '<td>' . $row[$fieldName[$i]] . '</td>';
											 }
											 print "</tr>";
								}
						} else {
								print '<tr><td colspan="' . $fieldMax . '">0 results</td></tr>';
						}
					print '</table>';			
				}		
				else {
						// must be $commandType == 3 (SHOW)
						$result = $conn->query($sql);
						while ($row = mysql_fetch_row($result)) {
								print 'Table: ' . $row[0] . '<br />';
						}
						//mysql_free_result($result);
				}
		}		
}		
$conn->close();
?>												
																	
							
						</form>

					</div>
					
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
<script>

$(function() {

		$( "button.submit" ).click(function() {
				document.forms[0].submit();
		});			

});

</script>

</body>
</html>
<?php
		}
		else {
				header('Location: index.php');
		}	
?>