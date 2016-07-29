<?php
			if ($ADMIN_LOGGED_IN == true) {

				$text = "Admin ";
			}else{

				$text = " ";
			}

			if ($LOGGED_IN == true) {
?>	
<div class="loginInfo"><?= $text?> Logged in as: <?php print $_SESSION['username']; ?></div>
<?php
			}
?>	