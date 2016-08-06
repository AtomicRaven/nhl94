<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';

		if ($LOGGED_IN == true) {
			
?><!DOCTYPE HTML>
<html>
<head>
<title>Manage Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h1>Manage</h1>

					<h2>1. Create New Series</h2>
					
					<table class="two-column">
						<tr class="">
							<td class=""><a href="./create.php" class="square-button">Create</a></td>
							<td class="">Create a new series</td>
						</tr>
						<tr class="">
							<td class=""><a href="./update.php" class="square-button">Update</a></td>
							<td class="">Update existing series</td>
						</tr>
					</table>



					<p><br /></p>  	
					<p><br /></p> 
					<h2>2. Update Existing Series (Rob, this replaces Update button above)</h2> 
					  	
					<table class="hidden lined">
						<tr class="">
							<td><button type="button" class="square">X</button></td>
							<td class="c">101</td>
							<td class=""><b>TOR vs WPG</b> - <nobr>4 games completed</nobr><br />
								<span class="note">Last Updated 3 minutes ago</span><br />
								<!-- Series creator: matt -->
								</td>
							<td class="r"><button type="button" class="square" onclick="location.href='update.php?seriesId=95'">Select</button></td>
						</tr>
						<tr class="">
							<td><button type="button" class="square">X</button></td>
							<td class="c">98</td>
							<td class="">BUF vs WPG - <nobr>6 games completed</nobr><br />
								<span class="note">Last Updated 1 week ago</span><br />
								<!-- Series creator: matt -->
								</td>
							<td class="r"><button type="button" class="square" onclick="location.href='update.php?seriesId=95'">Select</button></td>
						</tr>
						<tr class="">
							<td><button type="button" class="square">X</button></td>
							<td class="c">96</td>
							<td class="">BUF vs MTL - <nobr>1 game completed</nobr><br />
								<span class="note">Last Updated 2 weeks ago</span><br />
								<!-- Series creator: matt -->
								</td>
							<td class="r"><button type="button" class="square" onclick="location.href='update.php?seriesId=95'">Select</button></td>
						</tr>
						<tr class="">
							<td><button type="button" class="square">X</button></td>
							<td class="c">95</td>
							<td class="">TOR vs WPG - <nobr>4 games completed</nobr><br />
								<span class="note">Last Updated over 2 weeks ago</span><br />
								<!-- Series creator: matt -->
								</td>
							<td class="r"><button type="button" class="square" onclick="location.href='update.php?seriesId=94'">Select</button></td>
						</tr>


					</table>						  

					
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