<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
		include_once './_INCLUDES/dbconnect.php';	

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

if ($LOGGED_IN == true && $_SESSION['Admin'] == true) {

	$msg = "";
	
	if (isset($_GET["m"])){
		$msg = 'SQL executed!';
		$sql = $_POST["sql"];
		$pieces = explode(" ", $sql );
		$command = strtoupper($pieces[0]);
	}
	else {
		$msg = 'Default SQL used...';
		$sql = 'SELECT id_user, username, name, email FROM users ORDER BY id_user';
		$command = 'SELECT';
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
	
</style>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">				

					<h1>SQL EXECUTOR 1.0</h1>
					<div><?=$msg?></div>

					<div style="padding:40px; margin:20px;">

					<p><u>Examples</u></p>
					<p>SELECT id_user, username, name, email FROM users ORDER BY id_user DESC</p>
					<p>UPDATE users SET name='Matt' WHERE id_user = 2</p>
					
					<form method="post" action="sql.php?m=1">
					
					<textarea id="sql" name="sql"><?php print $sql; ?></textarea>
					<button type="submit" class="submit">Submit</button>
					
					
<?php
$servername = "localhost";
$username = "nhl94";
$password = "Mysp@ce2174";
$dbname = "nhl94db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//$sql = "SELECT id_user, username, name, email FROM users ORDER BY id_user";
$result = $conn->query($sql);

if ($command == 'SELECT') {
		print '<table><tr><td><b>id_user</b></td><td><b>username</b></td><td><b> name</b></td><td><b>email</b></td></tr>';
		if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
							print "<tr><td>" . $row["id_user"] . "</td><td>" . $row["username"] . "</td><td>" . $row["name"] . "</td><td>" . $row["email"] . "</td></tr>";
				}
		} else {
				print '<tr><td colspan="4">0 results</td></tr>';
		}
	}	

	print '</table>';

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