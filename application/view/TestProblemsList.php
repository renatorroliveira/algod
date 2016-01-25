<?php
	if (!isset($TESTPROBLEMSLIST_INCLUDED)) {
		$TESTPROBLEMSLIST_INCLUDED = true;
		
		@include "../controller/TestProblemsBS.php";
		@include "./controller/TestProblemsBS.php";
		@include "../view/TestProblemsEdit.php";
		@include "./view/TestProblemsEdit.php";
		
		class TestProblemsList {
			
			public function __construct() {
				
			}
			
			public function renderNotDeleted($testSelected = null) {
				$bs = new TestProblemsBS(null);
				if (!isset($testSelected) || ($testSelected == ""))
					$tproblems = $bs->findNotDeleted(null);
				else {
					$qbuilder = new QueryBuilder('testproblems');
					$qbuilder->addEqual('tpb_tst_id', $testSelected);
					$qbuilder->addOrder('tpb_questionNumber', QueryBuilder::$ASC);
					$qbuilder->addOrder('prb_title', QueryBuilder::$ASC, "problem");
					$tproblems = $bs->findNotDeleted($qbuilder);
				}
				if (count($tproblems) <= 0) {
				?>
					<br/><i>Nenhum problema anexado à esta prova ainda.</i><br />
				<?php
				} else { ?>
					<script type="text/javascript">
					function deleteTestProblem(testId, probId) {
						if (confirm("Deseja realmente desanexar este problema?")) {
							$("#tproblems-action-form input[name='_action']").val("delete");
							$("#tproblems-action-form input[name='tpb_tst_id']").val(testId);
							$("#tproblems-action-form input[name='tpb_prb_id']").val(probId);
							$("#tproblems-action-form").submit();
						}
					}
					</script>
					<form method="POST" action="./controller/TestProblemsController.php" style="display:none;"
					id="tproblems-action-form">
						<input type="hidden" name="_action" />
						<input type="hidden" name="tpb_tst_id" />
						<input type="hidden" name="tpb_prb_id" />
					</form>
					<table class="dataView">
					<tr>
						<th>Prova</th>
						<th>Nº da Questão</th>
						<th>Problema</th>
						<th>Nível de Dificuldade</th>
						<th>Peso</th>
						<th style="min-width: 60px;width: 60px;max-width: 60px;">Ações</th>
					</tr>
					<?php foreach ($tproblems as $tp => $tproblem) { ?>
						<tr class="color<?php echo ($tp % 2);?>">
							<td><?php echo $tproblem->getForeignModel('tpb_tst_id')->get('tst_title'); ?></td>
							<td><?php echo $tproblem->get('tpb_questionNumber'); ?></td>
							<td><?php echo $tproblem->getForeignModel('tpb_prb_id')->get('prb_title'); ?></td>
							<td><?php echo $tproblem->getForeignModel('tpb_prb_id')->get('prb_difficultyLevel'); ?></td>
							<td><?php echo $tproblem->get('tpb_weight'); ?></td>
							<td class="actions">
								<span class="ui-state-default ui-corner-all" title="Editar Anexo de Questão"
								onClick="location.assign('./testProblemsEdit.php?tpb_tst_id=<?php
								echo $tproblem->get("tpb_tst_id"); ?>&tpb_prb_id=<?php
								echo $tproblem->get('tpb_prb_id'); ?>');">
									<span class="ui-icon ui-icon-pencil"></span>
								</span>
								<span class="ui-state-default ui-corner-all" title="Desanexar Problema"
								onClick="deleteTestProblem(<?php echo $tproblem->get('tpb_tst_id'); ?>, <?php
								echo $tproblem->get('tpb_prb_id'); ?>)">
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