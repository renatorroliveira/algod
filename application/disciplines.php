<?php
	$__REQUIRE_ACCESS_LEVEL__ = 5;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Disciplinas Cadastradas</h1>

<p>
	A seguir encontram-se as disciplinas j� cadastradas no sistema. Voc� pode cadastrar novas ou excluir alguma
	disciplina existente. Disciplinas s�o utilizadas para categorizar problemas e provas. A cria��o da disciplina
	ser� atribu�da � seu usu�rio e aparecer� como op��o de sele��o nas telas de cadastro de problemas e provas.
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
