<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';

?><!DOCTYPE HTML>
<html>
<head>
<title>No Title</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					
					<h2>Results</h2>
					
					<table class="standard">
						<tr class="heading stripe">
							<td class="seriesNum mainTD">1.</td>
							<td class="seriesName mainTD">MTL</td>
							<td class="seriesName mainTD">vs</td>
							<td class="seriesName mainTD">BOS</td>
							<td class="seriesName mainTD">MTL wins in 7</td>
							<td class="seriesName mainTD"><button type="button" class="square" onclick="location.href='resultsSeries.php'">Details</button></td>
						</tr>
						<tr class="heading">
							<td class="seriesNum mainTD">2.</td>
							<td class="seriesName mainTD">TOR</td>
							<td class="seriesName mainTD">vs</td>
							<td class="seriesName mainTD">WPG</td>
							<td class="seriesName mainTD">In progress (4 gms)</td>
							<td class="seriesName mainTD"><button type="button" class="square" onclick="location.href='resultsSeries.php'">Details</button></td>
						</tr>
						<tr class="heading stripe">
							<td class="seriesNum mainTD">3.</td>
							<td class="seriesName mainTD">BOS</td>
							<td class="seriesName mainTD">vs</td>
							<td class="seriesName mainTD">MTL</td>
							<td class="seriesName mainTD">BOS wins in 6</td>
							<td class="seriesName mainTD"><button type="button" class="square" onclick="location.href='resultsSeries.php'">Details</button></td>
						</tr>												
					</table>	
					
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>