<?PHP

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
		include_once './_INCLUDES/dbconnect.php';	

//echo "Logged in:" . $LOGGED_IN ? 'true' : 'false';;

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

	$leagueTypeSelectBox = CreateSelectBox("leagueType", "Select Bin", GetLeagueTypes(), "LeagueID", "Name", null, null);
	$leagueTypeSelectBox2 = CreateSelectBox("leagueType2", "Select Bin", GetAllLeagueTypes(), "LeagueID", "Name", null, null);

	$childLg = CreateSelectBox("childLg", "Select Bin", GetAllLeagueTypes(), "LeagueID", "Name", null, null);
	$parentLg = CreateSelectBox("parentLg", "Select Bin", GetAllLeagueTypes(), "LeagueID", "Name", null, null);
	$drafterLg = CreateSelectBox("drafterLg", "Select Bin", GetAllLeagueTypes(), "LeagueID", "Name", null, null);

	$msg = "";
	if (isset($_GET["msg"])){
		$msg = $_GET["msg"];
	}

?><!DOCTYPE HTML>
<html>
<head>
<title>Admin</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>

</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">				

					<h1>Admin</h1>
					<div style="color:red;"><?=$msg?></div>

					<div style="padding:40px; margin:20px;">
						<form method="post" action="duplicateTable.php" enctype="multipart/form-data">

							<div style="padding:20px;border:1px solid black;margin-bottom:10px;">
								<h2>Import Bin</h2>							

									<select name="blitz">
										<option value="0">Normal</option>
										<option value="1">Blitz</option>
									</select>
									<input type="text" name="binName" style="width:200px">
									<input type="file" name="csv">
									<input type="submit" style="margin-top: 10px;" value="Import"/>									
																	
							</div>
							
						</form>
						<div style="padding:20px;border:1px solid black;margin-bottom:10px;">
							<h2> Tables</h2>
							Delete Table: <?=$leagueTypeSelectBox?> 
							<button id="submitBtn" onclick="DeleteTable(this)" style="margin-top: 10px;">Go</button><br/>
							Activate Table: <?=$leagueTypeSelectBox2?> <select id="activate" name="activate"><option value="1">Active</option><option value="0">Deactivate</option></select>
							<button id="submitBtn2" onclick="ActivateTable(this)" style="margin-top: 10px;">Go</button><br/>

							Assign SubLg: <?=$childLg?> to MasterLg: <?=$parentLg?>
							<button id="submitBtn3" onclick="AssignTable(this)" style="margin-top: 10px;">Go</button>

							<br/>
							Drafter Table: <?=$drafterLg?> Drafter Link: <input type="text" id="drafterLink" name="drafterLink" style="width:500px">
							<button id="submitBtn4" onclick="SetDraft(this)" style="margin-top: 10px;">Go</button>

						</div>
						<div style="padding:20px;border:1px solid black;margin-bottom:10px;">
							<h2>Links</h2>
							<ul>
								<li>
									<a href="sql.php">Do SQL</a>
								</li>
								<li>
									<a href="backup.php">BackUp DB</a>
								</li>
								<li>
									<a href="registerAdmin.php">Register Fake User</a>
								</li>
								<li>
									<a href="links.php">Links</a>
								</li>
								<li>
									<a href="draft.php">Drafter</a>
								</li>									
							</ul>
						</div>
						<div style="padding:20px;border:1px solid black;margin-bottom:10px;">
							<h2>Users</h2>							
							<table class="newTable" style="width:100%">
								<thead>
									<tr class="headerLg">
										<th>ID</th>
										<th>UserName</th>	
										<th>Email</th>
										<th>Real Name</th>										
									</tr>
								</thead>
								<tbody>
									<?php
										$users = GetUsersById(true);
										while($user = mysqli_fetch_array($users)){
									?>
									<tr class="resultsLg">
										<td class="c"><?=$user["id_user"]?></td>
										<td class="c"><?=$user["username"]?></td>
										<td class="c"><?=$user["email"]?></td>
										<td class="c"><?=$user["name"]?></td>			
									</tr>									                        
									<?php
										}
									?>
							</tbody>
							</table>
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
				header('Location: index.php');
		}	
?>