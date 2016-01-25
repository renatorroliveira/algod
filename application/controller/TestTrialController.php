<?php
	if (!isset($_POST['_action'])) {
		header("Location: ../");
		die();
	}
	
	include "./TestTrialBS.php";
	include "./UserSession.php";
	
	$ttrialBS = new TestTrialBS($_POST);
	
	if ($_POST['_action'] == 'save') {
		$status = $ttrialBS->save();
		if ($status)
			header("Location: ../testTrials.php?tst_id=".$_POST['ttl_tst_id']."&tst_password=".$_POST['tst_password']);
		else
			die("Um erro inesperado ocorreu.");
	} else if ($_POST['_action'] == 'delete') {
		$status = $ttrialBS->delete();
		if ($status)
			header("Location: ../testTrials.php?ttl_tst_id=".$_POST['ttl_tst_id']."&tst_password=".$_POST['tst_password']);
		else
			die("Um erro inesperado ocorreu.");
	} else
		die("<b>Action especificada é inválida.</b>");
	die();
?>
