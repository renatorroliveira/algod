<?php
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Problemas/Quest�es Cadastrados(as)</h1>

<p>
	A seguir encontram-se os problemas j� cadastrados no sistema. Voc� pode cadastrar novos ou excluir algum
	problema existente. Os problemas ser�o utilizados como quest�es para montar as provas de implementa��o pr�tica.
	Nesta tela est�o os bancos de problemas (quest�es) de todas as disciplinas cadastradas no sistema. Ap�s cadastrar
	os problemas e seus casos de teste voc� poder� inclu�-los em provas. Al�m disso, os problemas poder�o ser exportados
	para serem embutidos no m�dulo de corre��o em C++ (AlGod corretor).
</p>

<button id="problems-bt-new">
	Cadastrar um Problema
</button>

<div id="problems-ct-list" class="centering">
	<?php
		include "view/DisciplineSelect.php";
		include "view/ProblemList.php";
		$filter = new DisciplineSelect();
		$selectedDiscipline = '';
		if (isset($_GET['prb_dsc_id']))
			$selectedDiscipline = $_GET['prb_dsc_id'];
		?><br /><div><label for="prb_dsc_id">Filtrar por Disciplina</label>:<?php
		$filter->render("prb_dsc_id", $selectedDiscipline, null, 'discipline-filter');
		?></div>
		<br /><div>
			<label for="title-filter">Filtrar por T�tulo</label>:
			<input id="title-filter" type="text" size=30 value="<?php echo @$_GET['title']; ?>" />
		</div>
		<?php
		
		$widget = new ProblemList();
		$widget->renderNotDeleted($selectedDiscipline, @$_GET['title']);
	?>
</div>

<div id="problems-new-form" style="display:none;">
	<?php
		include "view/ProblemEdit.php";
		$widget = new ProblemEdit(null);
		$widget->render();
	?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#problems-bt-new").click(function() {
			$("#problems-new-form").dialog({
				modal: true,
				width: 'auto',
				height: 'auto',
				title: 'Cadastrar Novo Problema',
				buttons: {
					Cancel: function() {
						$(this).dialog("close");
					}
				}
			}).dialog("open");
		});

		$("#discipline-filter").change(function() {
			var initial = "<?php echo $selectedDiscipline; ?>";
			if ($(this).val() != initial)
				location.assign("./problems.php?prb_dsc_id="+$(this).val());
		});
		$("#title-filter").keypress(function(event){
			if ( event.which == 13 ) {
				event.preventDefault();
				var disciplineFilter = $("#discipline-filter");
				var url = "./problems.php?title="+$(this).val();
				if (disciplineFilter.val() != "")
					url += "&prb_dsc_id="+disciplineFilter.val();
				location.assign(url);
			}
		});
	});
</script>

<?php
	include "static/footer.php";
?>
