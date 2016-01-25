<?php
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Editar Caso de Teste</h1>

<div class="centering">
<?php
	include "controller/EvaluationCaseBS.php";
	include "view/EvaluationCaseEdit.php";
	$bs = new EvaluationCaseBS($_GET);
	$model = $bs->retrieve();
	if (isset($model)) {
		$widget = new EvaluationCaseEdit($model);
		$widget->render();
	} else
		echo "<br /><i>Este problema não existe.</i><br />";
?>
<br />
<button onClick="location.assign('./evaluationCases.php?evc_prb_id=<?php
	echo $_GET['evc_prb_id'];
	?>');">Voltar aos Casos de Teste</button>
</div>

<?php
	include "static/footer.php";
?>
