<?php
	if (!isset($_POST['_action'])) {
		header("Location: ../");
		die();
	}
	
	include "./ProblemBS.php";
	include "./UserSession.php";
	
	$problemBS = new ProblemBS($_POST);
	
	if ($_POST['_action'] == 'save') {
		$status = $problemBS->save();
		if ($status)
			header("Location: ../problems.php");
		else
			die("Um erro inesperado ocorreu.");
	} else if ($_POST['_action'] == 'delete') {
		$status = $problemBS->delete();
		if ($status)
			header("Location: ../problems.php");
		else
			die("Um erro inesperado ocorreu.");
	} else
		die("<b>Action especificada é inválida.</b>");
	die();
?>
