<?php

		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';			
		

		if ($LOGGED_IN == true && $_SESSION['Admin'] == true){
			
	

            $username='';
            $email = '';
            $password = '';
            
            
            if (isset($_POST["username"]) && !empty($_POST["username"])) {
                    $username =  $_POST["username"];
            }else{
                header('Location: logout.php');
            }

            if (isset($_POST["email"]) && !empty($_POST["email"])) {
                    $email =  $_POST["email"];
            }else{
                header('Location: logout.php');
            }
			
			if (isset($_POST["password"]) && !empty($_POST["password"])) {
                    $password =  $_POST["password"];
            }else{
                header('Location: logout.php');
            }

            if(!empty($username) && !empty($email) && !empty($password)){				
				
					if($password == 'crowslap69'){
						$returnCode = AddFakeUser($username, $email, $password);
						echo $username . " Added.";
					}else{
						header('Location: logout.php');
					}				

            }else{
				echo "u fcked up";				
				echo "u-". $username . "<br/>e-" . $email . "<br/>p-" . $password;
			}

            if($returnCode !== 1){
                header('Location: registerAdmin.php?msg=something went wrong in AddFakeUser' . $returnCode);
            }else{
                header('Location: registerAdmin.php?msg= New FakeUser ' . $username . ' Successfully added!');
            }
			
		
        }else{
            header('Location: logout.php');
        }
		
?>