<?php
			if ($ADMIN_LOGGED_IN == true) {

				$text = "Admin ";
			}else{

				$text = " ";
			}

			if ($LOGGED_IN == true) {
?>	
<div class="loginInfo"><?= $text?> Llllooogged in as: <?php print $_SESSION['username']; ?></div>
<?php
			}
?>	