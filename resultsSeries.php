<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';

?><!DOCTYPE HTML>
<html>
<head>
<title>No Title</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
<script>

	function showGameDetails(obj, x) {
		if ( obj.innerHTML === '+ Details' ) {
			// dipslay table row beneath button	
			$('#' + x).css('display','table-row');
			// fetch game stats
			$('#fetch_' + x).load('fragment_game_stats_template.php');
			// toggle button	
			obj.innerHTML = '- Details'
		}
		else {
			// hide table row beneath button	
			$('#' + x).css('display','none');			
			// toggle button	
			obj.innerHTML = '+ Details'
		}	

		return;
	}

</script>	
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					
					<h2>Series Results</h2>
					
					<table class="standard">
						<tr class="heading">
							<td class=""><!-- Rob: series_id -->1.</td>
							<td class="" colspan="5">MTL (rob) vs BOS (matt)</td>
						</tr>			
						<tr class="tight">
							<td class="">&nbsp;</td>
							<td class="" colspan="5">&#x2713; MTL wins in 7</td>
						</tr>							
						<tr class="heading">
							<td class="">#</td>
							<td class="">HOME</td>
							<td class="">&nbsp;</td>
							<td class="">AWAY</td>
							<td class="">&nbsp;</td>
							<td class="">&nbsp;</td>
						</tr>	
						<!-- loop starts here -->
<?php 
			for ($i=1; $i <= 7; $i++)
			{
?>						
						<tr class="tight<?php print $stripe[$i & 1]; ?>">
							<td class="c"><?php print $i; ?>.</td>
							<td class="c winner">MTL</td>
							<td class="c winner">5</td>
							<td class="c">BOS</td>
							<td class="c">3</td>
							<td class="c"><button type="button" class="square" onclick="showGameDetails(this, 'detail_<?php print $i; ?>')">+ Details</button></td>
						</tr>	
						<tr class="tight" id="detail_<?php print $i; ?>" style="display: none">
							<td colspan="6" id="fetch_detail_<?php print $i; ?>">

							</td>

						</tr>		
<?php 
			}
?>								
						<!-- loop ends -->	
					</table>


					<!-- rob: start of series stats table -->	
					<h3>Series Stats</h3>
					<table class="standard">
						<tr class="heading">
							<td class="">&nbsp;</td>
							<td class="c">TEAM 1</td>
							<td class="c">TEAM 2</td>
						</tr>	
						<tr class="tight stripe"><!-- RECORD -->
							<td class="heading">Record</td><td class="c">3 wins</td><td class="c">4 wins</td>
						</tr>							
						<tr class="tight"><!-- GOALS -->
							<td class="heading">Goals</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- ASSISTS -->
							<td class="heading">Assists</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight"><!-- POINTS -->
							<td class="heading">Pts</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- Points Per Game -->
							<td class="heading">PPG</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight"><!-- Shooting % -->
							<td class="heading">Sh %</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- Faceoffs -->
							<td class="heading">Faceoffs</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight"><!-- Att Zone -->
							<td class="heading">Att Zone</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- Passing -->
							<td class="heading">Psssing</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight"><!-- Penalty Shots -->
							<td class="heading">Pen Shots</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- PIM 
																	 This should be number of penalties a team takes.  
																	 Note: Each Penalty shot (for opposition) should be added here, which
																	 will give total penatlies	
																									-->
							<td class="heading"># Penalties</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight"><!-- Breakways -->
							<td class="heading">Breakways</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- One Timers -->
							<td class="heading">One Timers</td><td class="c">xxx</td><td class="c">xxx</td>
						</tr>							

					</table>		


					<!-- rob: start of series stats table -->	
					<h3>Series Points Leaderboard</h3>
					<table class="standard">
						<tr class="heading">
							<td class="heading">#</td>
							<td class="heading">Name</td>
							<td class="heading">Team</td>
							<td class="heading">Pts</td>
							<td class="heading">G</td>
							<td class="heading">A</td>
							<td class="heading">PPG</td>
						</tr>	
						<!-- start loop for all players with points -->
<?php 
			for ($i=1; $i <= 11; $i++)
			{
?>								
						<tr class="tight<?php print $stripe[$i & 1]; ?>">
							<td class=""><?php print $i; ?></td>
							<td class="">Denis Savard</td>
							<td class="">MTL</td>
							<td class="">14</td>
							<td class="">9</td>
							<td class="">5</td>
							<td class="">2.0/7</td>
						</tr>	
<?php 
			}
?>							
						<!-- end of loop -->	

					</table>					


					<!-- rob: start of series goalies table -->	
					<h3>Series Goalies Leaderboard</h3>
					
					<!-- start loop for all goalies with time on ice, sorted by save % -->
					<table class="standard">
						<tr class="heading">
							<td class="heading">#</td>
							<td class="heading">Name</td>
							<td class="heading">Team</td>
							<td class="heading">Save %</td>
						</tr>	

<?php 
			for ($i=1; $i <= 3; $i++)
			{
?>								
						<tr class="tight<?php print $stripe[$i & 1]; ?>">
							<td class=""><?php print $i; ?></td>
							<td class="">Patrick Roi</td>
							<td class="">MTL</td>
							<td class=""> 72.3% (17/72)<!-- 17 goals on 72 shots) --></td>
						</tr>	
<?php 
			}
?>							
						<!-- end of loop -->	

					</table>					


				</div>	
		
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>