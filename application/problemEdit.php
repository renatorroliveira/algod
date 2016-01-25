<?php
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Editar Problema</h1>

<div class="centering">
<?php
	include "controller/ProblemBS.php";
	include "view/ProblemEdit.php";
	$bs = new ProblemBS($_GET);
	$model = $bs->retrieve();
	if (isset($model)) {
		$widget = new ProblemEdit($model);
		$widget->render();
	} else
		echo "<br /><i>Este problema não existe.</i><br />";
?>
<br />
<button onClick="location.assign('./problems.php');">Voltar aos Problemas</button>
</div>

<?php
	include "static/footer.php";
?>
