<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';

		if ($ADMIN_LOGGED_IN == true) {		

            $gameid = $_GET["gameId"];

            $gameStats = GetGameById($gameid);

            echo "s" . $gameStats["Crowd"];
             

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Update Series</title>
<?php include_once './_INCLUDES/01_HEAD.php'; ?>
    <head>
        <title>Game Results</title>
    </head>
    <body>
    		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
                    
                    <?php

                        $conn = $GLOBALS['$conn'];
                        $sql = "SHOW COLUMNS from GameStats";
                
                        $result = mysqli_query($conn, $sql);

                        echo "<table class='standard'><tr class='heading rowSpacer'>";
                        //// Display column head /// 
                        while($row = mysqli_fetch_array($result)){
                            echo "<th>$row[0]</th>";
                        }

                        echo "</tr><tr>";

                        foreach($gameStats as $value)
                        {
                            echo "<td>".$value."</td>";
                        }

                        echo "</tr>";
                        echo "</table>";

                    ?>
        


                </div>
            </div>

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