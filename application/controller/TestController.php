<?php
	if (!isset($_POST['_action'])) {
		header("Location: ../");
		die();
	}
	
	include "./TestBS.php";
	include "./UserSession.php";
	
	$testBS = new TestBS($_POST);
	
	if ($_POST['_action'] == 'save') {
		$status = $testBS->save();
		if ($status)
			header("Location: ../tests.php");
		else
			die("Um erro inesperado ocorreu.");
	} else if ($_POST['_action'] == 'delete') {
		$status = $testBS->delete();
		if ($status)
			header("Location: ../tests.php");
		else
			die("Um erro inesperado ocorreu.");
	} else
		die("<b>Action especificada é inválida.</b>");
	die();
?>
