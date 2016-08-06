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
					
					<h2>Results - Series Overview</h2>
					
					<table class="standard">
						<tr class="heading">
							<td class="c"><span class="note">series</span><br />#</td>
							<td class="c">Teams</td>
							<td class="c">Status / Update</td>
							<td class="">&nbsp;</td>
						</tr>						
						<tr class="stripe">
							<td class="c">101</td>
							<td class="c">MTL<br/>vs<br/>BOS</td>
							<td class="stanley">MTL wins <nobr>in 7</nobr><br /> 
								<span class="note">Updated Aug 01, 2016 @ 6:30pm</span></td>
							<td class=""><button type="button" class="square" onclick="location.href='resultsSeries.php'">Details</button></td>
						</tr>
						<tr class="">
							<td class="c">100</td>
							<td class="c">TOR<br/>vs<br/>WPG</td>
							<td class="">In progress <nobr>(4 gms)</nobr><br /> 
								<span class="note">Updated Aug 01, 2016 @ 6:30pm</span></td>
							<td class=""><button type="button" class="square" onclick="location.href='resultsSeries.php'">Details</button></td>
						</tr>
						<tr class="stripe">
							<td class="c">99</td>
							<td class="c">BOS<br/>vs<br/>MTL</td>
							<td class="stanley">BOS wins <nobr>in 6</nobr><br /> 
								<span class="note">Updated Aug 01, 2016 @ 6:30pm</span></td>
							<td class=""><button type="button" class="square" onclick="location.href='resultsSeries.php'">Details</button></td>
						</tr>												
					</table>	
					
				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>