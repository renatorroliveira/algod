<?php
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Casos de Teste do Problema</h1>

<p>
	Nesta tela estão sendo exibidos os casos de teste cadastrados para este problema. Esses
	casos de teste serão exportados juntamente com os problemas para o módulo corretor em C++.
	São utilizados para realizar a correção da solução proposta aos problemas. São compostos
	cada um por um conjuntos de entradas e saídas. Deve-se definir também os tipos de dados das
	entradas e saídas.
</p>

<button id="evaluationcases-bt-new">
	Cadastrar um Caso de Teste
</button>

<div id="evaluationcases-ct-list" class="centering">
	<?php
		include "view/ProblemSelect.php";
		include "view/EvaluationCaseList.php";
		$filter = new ProblemSelect();
		$selectedProblem = '';
		if (isset($_GET['evc_prb_id']))
			$selectedProblem = $_GET['evc_prb_id'];
		?><br /><div><label for="prb_dsc_id">Filtrar por Problema</label>:<?php
		$filter->render("evc_prb_id", $selectedProblem, null, 'problem-filter');
		?></div><?php
		
		$widget = new EvaluationCaseList();
		$widget->renderNotDeleted($selectedProblem);
	?>
</div>

<div id="evaluationcases-new-form" style="display:none;">
	<?php
		include "./view/EvaluationCaseEdit.php";
		$widget = new EvaluationCaseEdit(null);
		$widget->render($selectedProblem);
	?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#evaluationcases-bt-new").click(function() {
			$("#evaluationcases-new-form").dialog({
				modal: true,
				width: 'auto',
				height: 'auto',
				title: 'Cadastrar Novo Caso de Teste',
				buttons: {
					Cancel: function() {
						$(this).dialog("close");
					}
				}
			}).dialog("open");
		});

		$("#problem-filter").change(function() {
			var initial = "<?php echo $selectedProblem; ?>";
			if ($(this).val() != initial)
				location.assign("./evaluationCases.php?evc_prb_id="+$(this).val());
		});
	});
</script>

<?php
	include "static/footer.php";
?>
