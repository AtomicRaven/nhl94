<div id="header">
<img src="images/logo.png" class="site-logo" onclick="location.href='index.php'"/>
<?php
			if ($LOGGED_IN == true) {

				//echo "UserName:" . $_SESSION['username'];
?>	
<div class="loginInfo"><a href="./logout.php" class="small-button">LOGOUT</a> <span><?php print $_SESSION['username']; ?></span></div>
<?php
			}
?>	
	
	<ul id="nav">
	<?php

	//echo "IsLoggedIn:" . $LOGGED_IN;

				if ($LOGGED_IN != 1) {
	?>				
		<li>
			<a href="./login.php">LOGIN</a>
		</li>
	<?php
				}
				else {
	?>				
		<li>
			<a href="./manage.php">EXIS</a>
		</li>
	<?php
				}
	?>		
		<!--<li>
			<a href="./tourneyList.php">LEAGUES</a>
		</li>		-->	
	
		<li>
			<a href="./results.php">RESULTS</a>
		</li>
		<li>
			<a href="./resultsLeader.php">STATS</a>
		</li>
		<?php		

				if ($LOGGED_IN == 1 && $_SESSION['Admin'] == true) {					
		?>		
		<li>
			<a href="./roster.php">ROSTER</a>
		</li>
		<?php
					
				}
				else {
		?>		
		<li>
			<a href="./roster.php">ROSTER</a>
		</li>
		<?php
				}
		?>
		
		<li>
			<a href="http://nhl94.com" target="nhl94">NHL94</a>
		</li>

		<?php

	//echo "IsLoggedIn:" . $LOGGED_IN;
	//echo "UserName:" . $_SESSION['username'];

		if ($LOGGED_IN == 1) {
			if ($_SESSION['Admin'] == true){
				?>	
		<li>
			<a href="admin.php">ADMIN</a>	
		</li>
		<?php
			}else{
		?>	
		<li>
			<a href="changePassword.php">ACCOUNT</a>	
		</li>
		<?php
			}
		}
		?>	


</div>	