<?php
		$stripe[0] = '';
		$stripe[1] = ' stripe'; // odd
?>		

					<!-- rob: start of series stats table -->	
					<h3>Game 3 Stats</h3>
					<table class="standard">
						<tr class="heading">
							<td class="">&nbsp;</td>
							<td class="">TEAM 1</td>
							<td class="">TEAM 2</td>
						</tr>	
						<tr class="tight stripe"><!-- RECORD -->
							<td class="heading">Record</td><td class="">3 wins</td><td class="">4 wins</td>
						</tr>							
						<tr class="tight"><!-- GOALS -->
							<td class="heading">G</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- ASSISTS -->
							<td class="heading">A</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight"><!-- POINTS -->
							<td class="heading">Pts</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- Points Per Game -->
							<td class="heading">PPG</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight"><!-- Shooting % -->
							<td class="heading">Sh %</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- Faceoffs -->
							<td class="heading">Faceoffs</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight"><!-- Att Zone -->
							<td class="heading">Att Zone</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- Passing -->
							<td class="heading">Passing</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight"><!-- Penalty Shots -->
							<td class="heading">Pen Shots</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- PIM 
																	 This should be number of penalties a team takes.  
																	 Note: Each Penalty shot (for opposition) should be added here, which
																	 will give total penatlies	
																									-->
							<td class="heading">PENALTIES</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight"><!-- Breakways -->
							<td class="heading">Breakways</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							
						<tr class="tight stripe"><!-- One Timers -->
							<td class="heading">One Timers</td><td class="">xxx</td><td class="">xxx</td>
						</tr>							

					</table>		


					<!-- rob: start of series stats table -->	
					<h3>Game 3 Points Leaderboard</h3>
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
			for ($j=1; $j <= 11; $j++)
			{
?>								
						<tr class="tight<?php print $stripe[$j & 1]; ?>">
							<td class=""><?php print $j; ?></td>
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
					<h3>Game 3 Goalies Leaderboard</h3>
					
					<!-- start loop for all goalies with time on ice, sorted by save % -->
					<table class="standard" style="margin-bottom: 1em;">
						<tr class="heading">
							<td class="heading">#</td>
							<td class="heading">Name</td>
							<td class="heading">Team</td>
							<td class="heading">Save %</td>
						</tr>	

<?php 
			for ($j=1; $j <= 3; $j++)
			{
?>								
						<tr class="tight<?php print $stripe[$j & 1]; ?>">
							<td class=""><?php print $j; ?></td>
							<td class="">Patrick Roi</td>
							<td class="">MTL</td>
							<td class=""> 72.3% (17/72)<!-- 17 goals on 72 shots) --></td>
						</tr>	
<?php 
			}
?>							
						<!-- end of loop -->	

					</table>					

