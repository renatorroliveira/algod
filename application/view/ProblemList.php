<?php
	if (!isset($PROBLEMLIST_INCLUDED)) {
		$PROBLEMLIST_INCLUDED = true;
		
		@include "../controller/ProblemBS.php";
		@include "./controller/ProblemBS.php";
		@include "../controller/EvaluationCaseBS.php";
		@include "./controller/EvaluationCaseBS.php";
		@include "../view/ProblemEdit.php";
		@include "./view/ProblemEdit.php";
		
		class ProblemList {
			
			public function __construct() {
				
			}
			
			public function renderNotDeleted($disciplineSelected = null, $titleFilter = null) {
				$bs = new ProblemBS(null);
				$ecaseBS = new EvaluationCaseBS(null);
				
				$qbuilder = new QueryBuilder('problem');
				if (isset($titleFilter) && ($titleFilter != ""))
					$qbuilder->addLike("prb_title", "%".$titleFilter."%");
				if (isset($disciplineSelected) && ($disciplineSelected != ""))
					$qbuilder->addEqual('prb_dsc_id', $disciplineSelected);
				$qbuilder->addOrder('prb_difficultyLevel', QueryBuilder::$ASC);
				$qbuilder->addOrder('prb_title', QueryBuilder::$ASC);
				$problems = $bs->findNotDeleted($qbuilder);
				if (count($problems) <= 0) {
				?>
					<br/><i>Nenhum problema cadastrada ainda.</i><br />
				<?php
				} else { ?>
					<script type="text/javascript">
					function deleteProblem(id) {
						if (confirm("Deseja realmente excluir este problema?")) {
							$("#problem-action-form input[name='_action']").val("delete");
							$("#problem-action-form input[name='prb_id']").val(id);
							$("#problem-action-form").submit();
						}
					}
					</script>
					<form method="POST" action="./controller/ProblemController.php" style="display:none;"
					id="problem-action-form">
						<input type="hidden" name="_action" />
						<input type="hidden" name="prb_id" />
					</form>
					<table class="dataView">
					<tr>
						<th>Disciplina</th>
						<th>Título</th>
						<th>Nível de Dificuldade</th>
						<th>Casos de Teste</th>
						<th style="min-width: 80px;width: 80px;max-width: 80px;">Ações</th>
					</tr>
					<?php foreach ($problems as $p => $problem) { ?>
						<tr class="color<?php echo ($p % 2);?>">
							<td><?php echo $problem->getForeignModel('prb_dsc_id')->get('dsc_code'); ?></td>
							<td><?php echo $problem->get('prb_title'); ?></td>
							<td><?php echo $problem->get('prb_difficultyLevel'); ?></td>
							<?php
								$nECases = $problem->getMetaField("prb_numberOfTestCases");
								if (!isset($nECases) || ($nECases < 1)) {?>
									<td style="background:#ff8888;"><?php
								} else {?>
									<td><?php
								}
								echo $nECases;
							?></td>
							<td class="actions">
								<span class="ui-state-default ui-corner-all" title="Editar Problema"
								onClick="location.assign('./problemEdit.php?prb_id=<?php echo $problem->get("prb_id"); ?>');">
									<span class="ui-icon ui-icon-pencil"></span>
								</span>
								<span class="ui-state-default ui-corner-all" title="Configurar Casos de Teste"
								onClick="location.assign('./evaluationCases.php?evc_prb_id=<?php echo $problem->get("prb_id"); ?>');">
									<span class="ui-icon ui-icon-gear"></span>
								</span>
								<span class="ui-state-default ui-corner-all" title="Excluir Problema"
								onClick="deleteProblem(<?php echo $problem->get('prb_id'); ?>)">
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