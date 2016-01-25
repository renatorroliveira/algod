<?php
	if (!isset($_POST['_action'])) {
		header("Location: ../");
		die();
	}
	
	include "./UserBS.php";
	include "./UserSession.php";
	
	$userBS = new UserBS($_POST);
	
	if ($_POST['_action'] == 'save') {
		$status = $userBS->save();
		if ($status)
			header("Location: ../login.php");
		else
			die("Um erro inesperado ocorreu.");
	} else if ($_POST['_action'] == 'login') {
		$status = $userBS->login();
		if ($status)
			header("Location: ../");
		else
			header("Location: ../login.php?failed=true");
	} else
		die("<b>Action especificada é inválida.</b>");
	die();
?>
