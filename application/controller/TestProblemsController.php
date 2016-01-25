<?php
	if (!isset($_POST['_action'])) {
		header("Location: ../");
		die();
	}
	
	include "./TestProblemsBS.php";
	include "./UserSession.php";
	
	$tproblemsBS = new TestProblemsBS($_POST);
	
	if ($_POST['_action'] == 'save') {
		$status = $tproblemsBS->save();
		if ($status)
			header("Location: ../testProblems.php?tpb_tst_id=".$_POST['tpb_tst_id']);
		else
			die("Um erro inesperado ocorreu.");
	} else if ($_POST['_action'] == 'update') {
		$status = $tproblemsBS->update();
		if ($status)
			header("Location: ../testProblems.php?tpb_tst_id=".$_POST['tpb_tst_id']);
		else
			die("Um erro inesperado ocorreu.");
	} else if ($_POST['_action'] == 'delete') {
		$status = $tproblemsBS->delete();
		if ($status)
			header("Location: ../testProblems.php?tpb_tst_id=".$_POST['tpb_tst_id']);
		else
			die("Um erro inesperado ocorreu.");
	} else
		die("<b>Action especificada é inválida.</b>");
	die();
?>
