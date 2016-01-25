<?php
	if (!isset($_POST['_action'])) {
		header("Location: ../");
		die();
	}
	
	include "./EvaluationCaseBS.php";
	include "./UserSession.php";
	
	$ecaseBS = new EvaluationCaseBS($_POST);
	
	if ($_POST['_action'] == 'save') {
		$status = $ecaseBS->save();
		if ($status)
			header("Location: ../evaluationCases.php?evc_prb_id=".$_POST['evc_prb_id']);
		else
			die("Um erro inesperado ocorreu.");
	} else if ($_POST['_action'] == 'delete') {
		$status = $ecaseBS->delete();
		if ($status)
			header("Location: ../evaluationCases.php?evc_prb_id=".$_POST['evc_prb_id']);
		else
			die("Um erro inesperado ocorreu.");
	} else
		die("<b>Action especificada é inválida.</b>");
	die();
?>
