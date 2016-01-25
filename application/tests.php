<?php
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Provas Cadastradas</h1>

<p>
	A seguir encontram-se as provas j� cadastradas no sistema. Voc� pode cadastrar novas ou excluir alguma
	prova existente. A prova � a unidade de informa��o principal no AlGod. Uma prova � composta por um conjunto
	de problemas. Alunos cadastrados no sistema poder�o ver quais provas est�o dispon�veis para responder.
	Por�m, a senha de acesso � prova, cadastrada nesta tela, � necess�ria para abrir a prova e responder as
	quest�es (problemas).
</p>

<button id="tests-bt-new">
	Cadastrar Prova
</button>

<div id="tests-ct-list" class="centering">
	<?php
		include "view/DisciplineSelect.php";
		$filter = new DisciplineSelect();
		$selectedDiscipline = '';
		if (isset($_GET['tst_dsc_id']))
			$selectedDiscipline = $_GET['tst_dsc_id'];
		?><br /><div><label for="tst_dsc_id">Filtrar por Disciplina</label>:<?php
		$filter->render("tst_dsc_id", $selectedDiscipline, null, 'discipline-filter');
		?></div><?php
	
		include "view/TestList.php";
		$widget = new TestList();
		$widget->renderNotDeleted($selectedDiscipline);
	?>
</div>

<div id="tests-new-form" style="display:none;">
	<?php
		include "view/TestEdit.php";
		$widget = new TestEdit(null);
		$widget->render();
	?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#tests-bt-new").click(function() {
			$("#tests-new-form").dialog({
				modal: true,
				width: 'auto',
				height: 'auto',
				title: 'Cadastrar Nova Prova',
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
				location.assign("./tests.php?tst_dsc_id="+$(this).val());
		});
	});
</script>

<?php
	include "static/footer.php";
?>
