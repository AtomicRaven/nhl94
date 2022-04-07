<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
		include_once './_INCLUDES/dbconnect.php';	

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

if ($LOGGED_IN == true && $_SESSION['Admin'] == true) {

	$msg = "";
	
	if (isset($_GET["msg"])){
		$msg = $_GET["msg"];
	}
	else {
		$msg = 'SELECT run successfully!';
		$sql = 'SELECT id_user, username, name, email FROM users ORDER BY id_user';
	}

?><!DOCTYPE HTML>
<html>
<head>
<title>Useful Stuff</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
<style>

	#sql {
			width: 100%;
			height: 200px;
	}
	
</style>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">				

					<h1>SQL</h1>
					<div><?=$msg?></div>

					<div style="padding:40px; margin:20px;">

					<form method="post" action="sql.php">
					
					<textarea id="sql" name="sql"><?php print $sql; ?></textarea>
					<table>
					  <tr>
						  <td><b>id_user</b></td>
						  <td><b>username</b></td>
						  <td><b> name</b></td>
						  <td><b>email</b></td>
						</tr>
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

$sql = "SELECT id_user, username, name, email FROM users ORDER BY id_user";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
					print "<tr><td>" . $row["id_user"] . "</td><td>" . $row["username"] . "</td><td>" . $row["name"] . "</td><td>" . $row["email"] . "</td></tr>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>												
</table>
																	
							
						</form>

					</div>
					
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>

</body>
</html>
<?php
		}
		else {
				header('Location: index.php');
		}	
?>