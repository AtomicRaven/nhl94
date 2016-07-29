<ul id="nav">
<?php

			print "IsLoggedIn:" . $LOGGED_IN;

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
	<li>
		<a href="./logout.php">LOGOUT</a>
	</li>
<?php
			}
?>		
	<li>
		<a href="./results.php">RESULTS</a>
	</li>
</ul>	
