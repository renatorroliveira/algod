<?php
	$__REQUIRE_ACCESS_LEVEL__ = 5;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Disciplinas Cadastradas</h1>

<p>
	A seguir encontram-se as disciplinas já cadastradas no sistema. Você pode cadastrar novas ou excluir alguma
	disciplina existente. Disciplinas são utilizadas para categorizar problemas e provas. A criação da disciplina
	será atribuída à seu usuário e aparecerá como opção de seleção nas telas de cadastro de problemas e provas.
</p>

<button id="disciplines-bt-new">Cadastrar Disciplina</button>

<div id="disciplines-ct-list" class="centering">
	<?php
		include "view/DisciplineList.php";
		$widget = new DisciplineList();
		$widget->renderNotDeleted();
	?>
</div>

<div id="disciplines-new-form" style="display:none;">
	<?php
		include "view/DisciplineEdit.php";
		$widget = new DisciplineEdit(null);
		$widget->render();
	?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#disciplines-bt-new").click(function() {
			$("#disciplines-new-form").dialog({
				modal: true,
				width: 'auto',
				height: 'auto',
				title: 'Cadastrar Nova Disciplina',
				buttons: {
					Cancel: function() {
						$(this).dialog("close");
					}
				}
			}).dialog("open");
		});
	});
</script>

<?php
	include "static/footer.php";
?>
