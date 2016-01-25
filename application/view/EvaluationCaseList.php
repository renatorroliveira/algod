<?php
	if (!isset($EVALUATIONCASELIST_INCLUDED)) {
		$EVALUATIONCASELIST_INCLUDED = true;
		
		@include "../controller/EvaluationCaseBS.php";
		@include "./controller/EvaluationCaseBS.php";
		@include "../view/EvaluationCaseEdit.php";
		@include "./view/EvaluationCaseEdit.php";
		
		class EvaluationCaseList {
			
			public function __construct() {
				
			}
			
			public function renderNotDeleted($problemSelected = null) {
				$bs = new EvaluationCaseBS(null);
				if (!isset($problemSelected) || ($problemSelected == ""))
					$ecases = $bs->findNotDeleted(null);
				else {
					$qbuilder = new QueryBuilder('evaluationcase');
					$qbuilder->addEqual('evc_prb_id', $problemSelected);
					$qbuilder->addOrder('evc_id', QueryBuilder::$ASC);
					$ecases = $bs->findNotDeleted($qbuilder);
				}
				if (count($ecases) <= 0) {
				?>
					<br/><i>Nenhum caso de teste cadastrado ainda.</i><br />
				<?php
				} else { ?>
					<script type="text/javascript">
					function deleteECase(id, probId) {
						if (confirm("Deseja realmente excluir este caso de teste?")) {
							$("#ecase-action-form input[name='_action']").val("delete");
							$("#ecase-action-form input[name='evc_id']").val(id);
							$("#ecase-action-form input[name='evc_prb_id']").val(probId);
							$("#ecase-action-form").submit();
						}
					}
					</script>
					<form method="POST" action="./controller/EvaluationCaseController.php" style="display:none;"
					id="ecase-action-form">
						<input type="hidden" name="_action" />
						<input type="hidden" name="evc_id" />
						<input type="hidden" name="evc_prb_id" />
					</form>
					<table class="dataView">
					<tr>
						<th>Problema</th>
						<th>Entradas</th>
						<th>Tipos das Entradas</th>
						<th>Saídas</th>
						<th>Tipos das Saídas</th>
						<th style="min-width: 60px;width: 60px;max-width: 60px;">Ações</th>
					</tr>
					<?php foreach ($ecases as $ec => $ecase) { ?>
						<tr class="color<?php echo ($ec % 2);?>">
							<td><?php echo $ecase->getForeignModel('evc_prb_id')->get('prb_title'); ?></td>
							<td><?php echo $ecase->get('evc_inputs'); ?></td>
							<td><?php echo $ecase->get('evc_inputsDataTypes'); ?></td>
							<td><?php echo $ecase->get('evc_outputs'); ?></td>
							<td><?php echo $ecase->get('evc_outputsDataTypes'); ?></td>
							<td class="actions">
								<span class="ui-state-default ui-corner-all" title="Editar Caso de Teste"
								onClick="location.assign('./evaluationCaseEdit.php?evc_id=<?php
								echo $ecase->get("evc_id"); ?>&evc_prb_id=<?php
								echo $ecase->get('evc_prb_id'); ?>');">
									<span class="ui-icon ui-icon-pencil"></span>
								</span>
								<span class="ui-state-default ui-corner-all" title="Excluir Problema"
								onClick="deleteECase(<?php echo $ecase->get('evc_id'); ?>, <?php echo $ecase->get('evc_prb_id'); ?>)">
									<span class="ui-icon ui-icon-trash"></span>
								</span>
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