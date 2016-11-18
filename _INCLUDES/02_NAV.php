<div id="header">
<?php
			if ($LOGGED_IN == true) {
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
			<a href="./index.php">LOGIN</a>
		</li>
	<?php
				}
				else {
	?>				
		<li>
			<a href="./manage.php">MANAGE</a>
		</li>
	<?php
				}
	?>		
		<li>
			<a href="./results.php">RESULTS</a>
		</li>
		<li>
			<a href="./resultsLeader.php">LEADER</a>
		</li>
		<?php

	//echo "IsLoggedIn:" . $LOGGED_IN;

				if ($LOGGED_IN == 1) {
	?>	
		<li>
			<a href="changePassword.php">ADMIN</a>	
		</li>
	<?php
				}
	?>	
	</ul>	

</div>	