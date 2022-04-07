<?php

		session_start();
		$ADMIN_PAGE = true;
		require_once('./_INCLUDES/00_SETUP.php');
        require_once("./_INCLUDES/config.php");
		include_once './_INCLUDES/dbconnect.php';	

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

    //echo 'Server: ' . $servername . "<br/>";
    //echo 'User: ' . $username . "<br/>";
    //echo 'Pass: ' . $password . "<br/>";
    //echo 'DB: ' . $dbname . "<br/>";
    //echo 'DumpPath: ' . $dumppath . "<br/>";
    

    $date = date('Y-m-d');
    exec($dumppath . ' --host=' . $servername . ' --user=' . $username . ' --password=' . $password . ' ' . $dbname . ' > ./db/nhl94db_backup_'.$date.'.sql');

    echo $dbname . " backed up.";

}
else {
        header('Location: index.php');
}	

?>