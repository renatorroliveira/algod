<?php
		@include "./model/DAO.php";
		@include "../model/DAO.php";
                include "./UserBS.php";
              
	if (!isset($_POST['_action'])) {
		header("Location: ../");
		die();
	}
        
        $userBS = new UserBS($_POST);
        if ($_POST['_action'] == 'recovery'){
            
            $email = $_POST['usr_email'];
            $resultSend = $userBS->recovery($email);
            if($resultSend){
                header("Location: ../login.php?recoveryok=true");

            }else{
                header("Location: ../login.php?recoveryfailed=true");

            }            
        }
?>
