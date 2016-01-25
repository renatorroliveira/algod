<?php
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Editar Anexo de Problema à uma Prova</h1>

<div class="centering">
<?php
	include "controller/TestProblemsBS.php";
	include "view/TestProblemsEdit.php";
	$bs = new TestProblemsBS($_GET);
	$model = $bs->retrieve();
	if (isset($model)) {
		$widget = new TestProblemsEdit($model);
		$widget->render();
	} else
		echo "<br /><i>Este problema não está anexado a essa prova.</i><br />";
?>
<br />
<button onClick="location.assign('./testProblems.php?tpb_tst_id=<?php
	echo $_GET['tpb_tst_id'];
	?>');">Voltar à Composição da Prova</button>
</div>

<?php
	include "static/footer.php";
?>
