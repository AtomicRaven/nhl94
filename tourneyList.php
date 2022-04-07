<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		// custom code
		
		if (isset($_GET["m"])) {
				$message = '<p>Welcome back!  Click the "Details" button to see more information on a League.</p>';
		}
		else {
				$message = '<p>Click the "Details" button to see more information on a League.</p>';
		}
?><!DOCTYPE HTML>
<html>
<head>
<title>Series Results</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					<h1>Leagues</h1>	
					
					<?php print $message; ?>
					
					<table class="standard" style="margin-top: 1em;">
						<tr class="heading">
							<td class="c">#</td>
							<td>League Name</td>
							<td>Description</td>
							<td></td>
						</tr>
						<?php
							$allTourney = GetTourneys();	
							$i=0;
							while($t = mysqli_fetch_array($allTourney)){
								$i++;
						?>
						<tr class="<?php print $stripe[$i & 1]; ?>">
							<td class="c"><?=$i?></td>
							<td><?=$t["Name"]?></td>
							<td><?=$t["Description"]?></td>
							<td>
									<button type="button" class="square" onclick="location.href='./tourney.php?tId=<?=$t["ID"]?>'">&nbsp;Details&nbsp;</button>
							</td>							
						</tr>	
						<?php
							}
						?>
					</table>	
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>