<?php
		session_start();
		$ADMIN_PAGE = true;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';			

		if ($LOGGED_IN == true && $_SESSION['Admin'] == true){

            $username='';
            $email = '';
            $password = '5678';
            
            
            if (isset($_POST["username"]) && !empty($_POST["username"])) {
                    $username =  $_POST["username"];
            }else{
                header('Location: registerAdmin.php?msg=no username');
            }

            if (isset($_POST["email"]) && !empty($_POST["email"])) {
                    $email =  $_POST["email"];
            }else{
                header('Location: registerAdmin.php?msg=no email');
            }

            if(!empty($username) && !empty($email) && !empty($password)){

                $returnCode = AddFakeUser($username, $email, $password);

            }

            if($returnCode !== 1){
                header('Location: registerAdmin.php?msg=something went wrong in AddFakeUser');
            }else{
                header('Location: registerAdmin.php?msg= New FakeUser ' . $username . ' Successfully added!');
            }
        }else{
            header('Location: index.php');
        }
		
?>