<?php
	$__REQUIRE_ACCESS_LEVEL__ = 1;
	$__REDIRECT_TO__ = './login.php';
	include "static/header.php";
?>

<h1>Realizar Prova</h1>

<?php
	if ((!isset($_GET['tst_id'])) || (!isset($_GET['tst_password']))) {
		echo "<br /><i>Acesso negado.</i><br />";
	} else {
		$testId = $_GET['tst_id'];
		$password = $_GET['tst_password'];
		include "view/TestTryView.php";
		$wdg = new TestTryView($testId, $password);
		$wdg->render();
	}
?>

<?php
	include "static/footer.php";
?>
