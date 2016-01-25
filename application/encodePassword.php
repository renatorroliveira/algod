<?php
	$pass = "";
	if (isset($_POST['tst_password']))
		$pass = sha1($_POST['tst_password']);
	else
		die("<b>Forbidden.</b>");
	header("Location: testTrials.php?tst_id=".$_POST['tst_id']."&tst_password=".$pass);
?>