<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		if ($LOGGED_IN == true) {

		$Section_A_Mssg = '<div style="">Select Home and Away and upload a game:</div>';
		// $Section_A_Mssg = '<div style="color: red">Error uploading game:</div>';
		// $Section_A_Mssg = '<div style="color: green">Game upload successfull!</div>';
		
?><!DOCTYPE HTML>
<html>
<head>
<title>Prince and Paupers League</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
				
					<h1>Prince and Pauper League I</h1><br />

					<h2>A. Upload a League Game</h2>

					<?= $Section_A_Mssg ?><br/>
					
					<table class="">
						<tr class="">
							<td class="">
									<select>
										<option>Home</option>
										<option>Aqua</option>
										<option>Icestorm</option>
									</select>
							</td>
							<td class="">vs</td>
							<td class="">
									<select>
										<option>Away</option>
										<option>Aqua</option>
										<option>Icestorm</option>
									</select>
							</td>					
							<td>
								<button type="button" class="square" id="" onclick="">Upload File</button>
							</td>	
								
						</tr>
					</table>



					<p><br /></p>  	
					<h2>B. Standings</h2> 
					
					<table class="lg_standings">
						<tr class="">
							<td class="">Rank</td>
							<td class="">Team</td>
							<td class="">Coach</td>
							<td class="">Wins</td>
							<td class="">Losses</td>
							<td class="c">%</td>
						</tr>
						<tr class="">
							<td class="c">1</td>
							<td class="">Anaheim</td>
							<td class=""><a href="javascript:location='./leagueStats.php';">(kingraph)</a></td>
							<td class="c">5</td>
							<td class="c">0</td>
							<td class="c">1.00</td>
						</tr>						
						<tr class="show_game_histories" id="tempHandler"><td><br/></td><td colspan="4">
							<ol>
								<li><button type="button" class="square" id="" onclick="">x</button> ANH wins @ SJ [ 7 to 2 ]</li>
								<li><button type="button" class="square" id="" onclick="">x</button> ANH wins @ BOS [ 3 to 1 ]</li>
								<li><button type="button" class="square" id="" onclick="">x</button> ANH wins @ DET [ 1 to 0 ]</li>
								<li><button type="button" class="square" id="" onclick="">x</button> ANH wins @ CHI [ 7 to 0 ]</li>
								<li><button type="button" class="square" id="" onclick="">x</button> ANH wins @ BUF [ 5 to 2 ]</li>
							</ol>	
						</td></tr>
						<tr class="">
							<td class="c">2</td>
							<td class="">San Jose</td>
							<td class=""><a href="javascript:location='./leagueStats.php';">(depch)</a></td>
							<td class="c">4</td>
							<td class="c">0</td>
							<td class="c">.80</td>
						</tr>						
						<tr class="">
							<td class="c">3</td>
							<td class="">San Jose</td>
							<td class=""><a href="javascript:location='./leagueStats.php';">(depch)</a></td>
							<td class="c">4</td>
							<td class="c">0</td>
							<td class="c">.80</td>
						</tr>						
						<tr class="">
							<td class="c">4</td>
							<td class="">San Jose</td>
							<td class=""><a href="javascript:location='./leagueStats.php';">(depch)</a></td>
							<td class="c">4</td>
							<td class="c">0</td>
							<td class="c">.80</td>
						</tr>						
						<tr class="">
							<td class="c">5</td>
							<td class="">San Jose</td>
							<td class=""><a href="javascript:location='./leagueStats.php';">(depch)</a></td>
							<td class="c">4</td>
							<td class="c">0</td>
							<td class="c">.80</td>
						</tr>						
						<tr class="">
							<td class="c">6</td>
							<td class="">San Jose</td>
							<td class=""><a href="javascript:location='./leagueStats.php';">(depch)</a></td>
							<td class="c">4</td>
							<td class="c">0</td>
							<td class="c">.80</td>
						</tr>						
						<tr class="">
							<td class="c">7</td>
							<td class="">San Jose</td>
							<td class=""><a href="javascript:location='./leagueStats.php';">(depch)</a></td>
							<td class="c">4</td>
							<td class="c">0</td>
							<td class="c">.80</td>
						</tr>						
						<tr class="">
							<td class="c">8</td>
							<td class="">San Jose</td>
							<td class=""><a href="javascript:location='./leagueStats.php';">(depch)</a></td>
							<td class="c">4</td>
							<td class="c">0</td>
							<td class="c">.80</td>
						</tr>						
					  <tr>
							<td></br /></td>
						  <td colspan="4" style="padding-top: 20px;">
								<button type="button" class="square" id="" onclick="javascript:showGameHistories();">Show Game Histories</button>
							</td>
						</tr>
					</table>			

<script>

	function showGameHistories() {
			$('.show_game_histories').show();
	}

</script>					
					
		
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