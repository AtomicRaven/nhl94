<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
        require_once("./reg/include/membersite_config.php");

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

//if ($LOGGED_IN == true) {
if (true) {
?><!DOCTYPE HTML>
<html>
<head>
<title>Useful Stuff</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>


</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>						
				
				<div id="main">
				
				<h1>Links</h1>
				
				<div style="padding:20px; margin:20px;">
					
					<div class="boxy">
						<ul>						
							<li>
								<a href="https://forum.nhl94.com/index.php?/topic/19603-vhl-8-draft/" target="_blank">VHL8 Draft Forum</a>                        
							</li>					
							<li>
								<a href="https://docs.google.com/spreadsheets/d/1FPbQs0gWqhUwm6yCEZRGTRHBIgSXVxn1yoGCG3hyfn4/edit#gid=0" target="_blank">VHL8 Draft Sheet</a>                        
							</li>
							<li>
								<a href="https://docs.google.com/spreadsheets/d/1SdeD5hzxvWYoAMZxMU8Z3arpVVYtb70xwjADtpVjuIE/edit#gid=0" target="_blank">GDL 16 Draft Sheet</a>
							</li>
							<li>
								<a href="https://docs.google.com/spreadsheets/d/1UcRu8RP7YzvoZmu35CzrqoGztCYQMD7jwgU1PFLSjAY/pubhtml/sheet?headers=false&gid=0" target="_blank">Player Appendix</a>
							</li>
						</ul>
						<br/>
						<!--<div>
							<h2>VHL8 Notes:</h2>
							
							Timing: Draft - Today thru likely Monday<br/><br/>

							Exihibitions & Trades: Post-Draft to Begin Reg Season, approx 4 days to 1 week for trades.<br/><br/>

							Begin Regular Season - Mon. January 27th<br/><br/>

							Reg. Season Ends - Approx. March 8th, maybe earlier<br/><br/>

							Playoffs - Immediately following Reg Season<br/><br/>

							Rosters - 6 fwds, 4 dmen, 2 goalies for 12 total rounds of drafting.<br/><br/>
							
						</div>-->
                    </div>
				</div>
			</div>			
		</div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>

</body>
</html>
<?php
		}
		else {
				header('Location: ../index.php');
		}	
?>