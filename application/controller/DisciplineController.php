<?php
	if (!isset($_POST['_action'])) {
		header("Location: ../");
		die();
	}
	
	include "./DisciplineBS.php";
	include "./UserSession.php";
	
	$disciplineBS = new DisciplineBS($_POST);
	
	if ($_POST['_action'] == 'save') {
		$status = $disciplineBS->save();
		if ($status)
			header("Location: ../disciplines.php");
		else
			die("Um erro inesperado ocorreu.");
	} else if ($_POST['_action'] == 'delete') {
		$status = $disciplineBS->delete();
		if ($status)
			header("Location: ../disciplines.php");
		else
			die("Um erro inesperado ocorreu.");
	} else
		die("<b>Action especificada é inválida.</b>");
	die();
?>
