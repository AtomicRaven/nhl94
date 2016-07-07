<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';

		if ($ADMIN_LOGGED_IN == true) {
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Update Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h2>Update Series</h2>
					<form method="post" action="processUpdate.php">	
					
					<table class="standard">
						<tr class="heading rowSpacer">
							<td class="seriesNum mainTD">1.</td>
							<td class="seriesName mainTD">Series Name Here</td>
							<td class="seriesDate mainTD">Created 05/22/16</td>
						</tr>
						<tr class="heading">
							<td>&nbsp;</td>
							<td class="seriesInfo mainTD" colspan="2"><b>MTL</b> vs BOS, starting in MTL (3-3-1)</td>
						</tr>						
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm 1. <b>MTL 6</b> / BOS 3 </td>
							<td><button class="square" id="submit">Game Stats</button></td>
						</tr>			
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm 2. MTL 3 / <b>BOS</b> 4 </td>
							<td><button class="square" id="submit">Game Stats</button></td>
						</tr>			
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm 3. MTL at BOS</td>
							<td><button class="square" id="submit">Upload File</button></td>
						</tr>			
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm 4. BOS at MTL</td>
							<td><button class="square" id="submit">Upload File</button></td>
						</tr>			
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm 5. BOS at MTL </td>
							<td><button class="square" id="submit">Upload File</button></td>
						</tr>			
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm 6. BOS at MTL </td>
							<td><button class="square" id="submit">Upload File</button></td>
						</tr>		
						<tr class="normal">
							<td>&nbsp;</td>
							<td>Gm 7. MTL at BOS</td>
							<td><button class="square" id="submit">Upload File</button></td>
						</tr>								
					</table>
					</form>	
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