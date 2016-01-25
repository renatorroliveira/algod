<?php
	if (!isset($_GET['tsl_tst_id']))
		die("<b>Forbidden.</b>");
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Logs de Atividades na Prova</h1>

<div id="testlogs-ct-list" class="centering">
	<?php
		$selectedTest = $_GET['tsl_tst_id'];
		include "view/TestLogsList.php";
		$wdg = new TestLogsList();
		$wdg->render($selectedTest);
	?>
</div>

<?php
	include "static/footer.php";
?>
