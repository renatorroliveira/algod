<?php
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Editar Prova</h1>

<div class="centering">
<?php
	include "controller/TestBS.php";
	include "view/TestEdit.php";
	$bs = new TestBS($_GET);
	$model = $bs->retrieve();
	if (isset($model)) {
		$widget = new TestEdit($model);
		$widget->render();
	} else
		echo "<br /><i>Esta prova não existe.</i><br />";
?>
<br />
<button onClick="location.assign('./tests.php');">Voltar às Provas</button>
</div>

<?php
	include "static/footer.php";
?>
