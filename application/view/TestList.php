<?php
	if (!isset($TESTLIST_INCLUDED)) {
		$TESTLIST_INCLUDED = true;
		
		@include "../controller/TestBS.php";
		@include "./controller/TestBS.php";
		@include "../view/TestEdit.php";
		@include "./view/TestEdit.php";
		
		class TestList {
			
			public function __construct() {
				
			}
			
			public function renderNotDeleted($disciplineSelected = null) {
					$bs = new TestBS(null);
				if (!isset($disciplineSelected) || ($disciplineSelected == ""))
					$tests = $bs->findNotDeleted(null);
				else {
					$qbuilder = new QueryBuilder('test');
					$qbuilder->addEqual('tst_dsc_id', $disciplineSelected);
					$qbuilder->addOrder('tst_openUntil', QueryBuilder::$DESC);
					$qbuilder->addOrder('tst_visibleUntil', QueryBuilder::$DESC);
					$qbuilder->addOrder('tst_createdAt', QueryBuilder::$DESC);
					$qbuilder->addOrder('tst_title', QueryBuilder::$ASC);
					$qbuilder->addOrder('tst_openSince', QueryBuilder::$DESC);
					$qbuilder->addOrder('tst_visibleSince', QueryBuilder::$DESC);
					$tests = $bs->findNotDeleted($qbuilder);
				}
				if (count($tests) <= 0) {
				?>
					<br/><i>Nenhuma prova cadastrada ainda.</i><br />
				<?php
				} else { ?>
					<script type="text/javascript">
					function deleteTest(id) {
						if (confirm("Deseja realmente excluir esta prova?")) {
							$("#test-action-form input[name='_action']").val("delete");
							$("#test-action-form input[name='tst_id']").val(id);
							$("#test-action-form").submit();
						}
					}
					</script>
					<form method="POST" action="./controller/TestController.php" style="display:none;"
					id="test-action-form">
						<input type="hidden" name="_action" />
						<input type="hidden" name="tst_id" />
					</form>
					<table class="dataView">
					<tr>
						<th>Disciplina</th>
						<th>Título</th>
						<th>Visível de</th>
						<th>Visível até</th>
						<th>Aberta de</th>
						<th>Aberta até</th>
						<th>Nº de Questões</th>
						<th style="min-width:140px;width:140px;max-width:140px;">Ações</th>
					</tr>
					<?php foreach ($tests as $t => $test) { ?>
						<tr class="color<?php echo ($t % 2);?>">
							<td><?php echo $test->getForeignModel('tst_dsc_id')->get('dsc_code'); ?></td>
							<td><?php echo $test->get('tst_title'); ?></td>
							<td><?php echo Model::parseSQLToInputDate($test->get('tst_visibleSince')); ?></td>
							<td><?php echo Model::parseSQLToInputDate($test->get('tst_visibleUntil')); ?></td>
							<td><?php echo Model::parseSQLToInputDate($test->get('tst_openSince')); ?></td>
							<td><?php echo Model::parseSQLToInputDate($test->get('tst_openUntil')); ?></td>
							<?php
								$questions = $test->getMetaField("tst_numberOfQuestions");
								if (!isset($questions) || ($questions < 1)) {?>
									<td style="background:#ff8888;"><?php
								} else {?>
									<td><?php
								}
								echo $questions;
							?></td>
							<td class="actions">
								<span class="ui-state-default ui-corner-all" title="Editar Prova"
								onClick="location.assign('./testEdit.php?tst_id=<?php echo $test->get("tst_id"); ?>');">
									<span class="ui-icon ui-icon-pencil"></span>
								</span>
								<span class="ui-state-default ui-corner-all" title="Configurar Problemas/Questões da Prova"
								onClick="location.assign('./testProblems.php?tpb_tst_id=<?php echo $test->get("tst_id"); ?>');">
									<span class="ui-icon ui-icon-gear"></span>
								</span>
								<span class="ui-state-default ui-corner-all"
								title="Baixar ProblemBundleInit.hpp para o módulo AlGod Corretor (C++)."
								onClick="location.assign('./problemBundleInit.php?tpb_tst_id=<?php echo $test->get("tst_id"); ?>');">
									<span class="ui-icon ui-icon-arrowthickstop-1-s"></span>
								</span>
								<span class="ui-state-default ui-corner-all"
								title="Visualizar logs da Prova."
								onClick="location.assign('./testLogs.php?tsl_tst_id=<?php echo $test->get("tst_id"); ?>');">
									<span class="ui-icon ui-icon-note"></span>
								</span>
								<span class="ui-state-default ui-corner-all"
								title="Gerar relatório de notas."
								onClick="location.assign('./testReport.php?tpb_tst_id=<?php echo $test->get("tst_id"); ?>');">
									<span class="ui-icon ui-icon-document"></span>
								</span>
								<span class="ui-state-default ui-corner-all" title="Excluir Prova"
								onClick="deleteTest(<?php echo $test->get('tst_id'); ?>)">
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