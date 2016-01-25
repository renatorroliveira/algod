<?php
	if (!isset($DISCIPLINELIST_INCLUDED)) {
		$DISCIPLINELIST_INCLUDED = true;
		
		@include "../controller/DisciplineBS.php";
		@include "./controller/DisciplineBS.php";
		@include "../view/DisciplineEdit.php";
		@include "./view/DisciplineEdit.php";
		
		class DisciplineList {
			
			public function __construct() {
				
			}
			
			public function renderNotDeleted() {
				$bs = new DisciplineBS(null);
				$disciplines = $bs->findNotDeleted(null);
				if (count($disciplines) <= 0) {
				?>
					<br/><i>Nenhuma disciplina cadastrada ainda.</i><br />
				<?php
				} else {
					$wdg = new DisciplineEdit(new Discipline());
					$wdg->renderWithJSFieldsObject();
				?>
					<script type="text/javascript">
					function editDiscipline(id, code, name) {
						DisciplineEdit_form.id.val(id);
						DisciplineEdit_form.code.val(code);
						DisciplineEdit_form.name.val(name);
						DisciplineEdit_form.dialogCt.dialog({
							modal:true,
							title: "Editar Disciplina",
							width: 'auto',
							height: 'auto',
							buttons: {
								Cancel: function(){
									$(this).dialog("close");
								}
							}
						});
					}
					function deleteDiscipline(id) {
						if (confirm("Deseja realmente excluir esta disciplina?")) {
							$("#dicipline-action-form input[name='_action']").val("delete");
							$("#dicipline-action-form input[name='dsc_id']").val(id);
							$("#dicipline-action-form").submit();
						}
					}
					</script>
					<form method="POST" action="./controller/DisciplineController.php" style="display:none;"
					id="dicipline-action-form">
						<input type="hidden" name="_action" />
						<input type="hidden" name="dsc_id" />
					</form>
					<table class="dataView">
					<tr>
						<th>Código</th>
						<th>Nome</th>
						<th style="min-width: 60px;width: 60px;max-width: 60px;">Ações</th>
					</tr>
					<?php foreach ($disciplines as $d => $discipline) { ?>
						<tr class="color<?php echo ($d % 2);?>">
							<td><?php echo $discipline->get('dsc_code'); ?></td>
							<td><?php echo $discipline->get('dsc_name'); ?></td>
							<td class="actions">
							<?php if ($discipline->get("dsc_usr_id") == UserSession::getInstance()->getUser()->get('usr_id')) { ?>
								<span class="ui-state-default ui-corner-all" title="Editar Disciplina"
								onClick="editDiscipline(<?php
									echo "'".$discipline->get('dsc_id')."'";
									echo ",'".$discipline->get('dsc_code')."'";
									echo ",'".$discipline->get('dsc_name')."'";
								?>);">
									<span class="ui-icon ui-icon-pencil"></span>
								</span>
								<span class="ui-state-default ui-corner-all" title="Excluir Disciplina"
								onClick="deleteDiscipline(<?php echo $discipline->get('dsc_id'); ?>)">
									<span class="ui-icon ui-icon-trash"></span>
								</span>
							<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</table>
				<?php
				}
			}
		}
	}
	
?>