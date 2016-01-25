<?php
	if (!isset($TESTREPORTLIST_INCLUDED)) {
		$TESTREPORTLIST_INCLUDED = true;
		
		@include "../controller/TestProblemsBS.php";
		@include "./controller/TestProblemsBS.php";
		@include "../controller/TestTrialBS.php";
		@include "./controller/TestTrialBS.php";
		
		class TestReportList {
			
			public function __construct() {
				
			}
			
			public function render($testSelected = null) {
				$bs = new TestProblemsBS(null);
				if (!isset($testSelected) || ($testSelected == "")) {
					echo "<i>Nenhuma prova selecionada.</i>";
					return;
				} else {
					$qbuilder = new QueryBuilder('testproblems');
					$qbuilder->addEqual('tpb_tst_id', $testSelected);
					$qbuilder->addOrder('tpb_questionNumber', QueryBuilder::$ASC);
					$qbuilder->addOrder('prb_title', QueryBuilder::$ASC, "problem");
					$tproblems = $bs->findNotDeleted($qbuilder);
				}
				$nProblems = count($tproblems);
				if ($nProblems <= 0) {
				?>
					<br/><i>Nenhum problema anexado à esta prova ainda.</i><br />
				<?php
				} else {
					$test = $tproblems[0]->getForeignModel('tpb_tst_id');
					$problemWeights = array();
					$maxGradeSum = 0.0;
					foreach ($tproblems as $tp => $tproblem) {
						if (!isset($problemWeights[$tproblem->get('tpb_prb_id')]))
							$problemWeights[$tproblem->get('tpb_prb_id')] = 0;
						$problemWeights[$tproblem->get('tpb_prb_id')] += $tproblem->get('tpb_weight');
						$maxGradeSum += (100.0*$tproblem->get('tpb_weight'));
					}
					
					$qbuilder = new QueryBuilder('testtrial');
					$qbuilder->addEqual('ttl_tst_id', $testSelected);
					$qbuilder->addOrder('usr_name', QueryBuilder::$ASC, "user");
					$qbuilder->addOrder('usr_matricula', QueryBuilder::$ASC, "user");
					$qbuilder->addOrder('ttl_usr_id', QueryBuilder::$ASC);
					$bs = new TestTrialBS(null);
					$ttrials = $bs->findNotDeleted($qbuilder);
					$userGrades = array();
					$lastId = -1;
					$lastPos = -1;
					$averageScore = 0.0;
					foreach ($ttrials as $tt => $ttrial) {
						if ($lastId != $ttrial->get('ttl_usr_id')) {
							$lastId = $ttrial->get('ttl_usr_id');
							$lastPos++;
						}
						if (!isset($userGrades[$lastPos])) {
							$userGrades[$lastPos] = array();
							$userGrades[$lastPos]['matricula'] = $ttrial->getForeignModel("ttl_usr_id")->get("usr_matricula");
							$userGrades[$lastPos]['name'] = $ttrial->getForeignModel("ttl_usr_id")->get("usr_name");
							$userGrades[$lastPos]['totalScore'] = 0.0;
							$userGrades[$lastPos]['totalSubmitted'] = 0;
						}
						if (strlen($ttrial->get("ttl_sourcefile")) > 50) {
							$userGrades[$lastPos]['totalSubmitted']++;
						}
						$userGrades[$lastPos]['totalScore'] += ($ttrial->get('ttl_score') * $problemWeights[$ttrial->get('ttl_prb_id')]);
						$averageScore += ($ttrial->get('ttl_score') * $problemWeights[$ttrial->get('ttl_prb_id')]);
					}
					$nAlunos = count($userGrades);
					if ($nAlunos > 0) {
						$averageScore /= $nAlunos;
					}
					?>
					<br />
					<h3><?php echo $test->get('tst_title'); ?></h3>
					<b>Número de Questões:</b> <?php echo $nProblems; ?><br />
					<b>Acumulado Máximo:</b> <?php echo $maxGradeSum; ?><br />
					<b>Média do Acumulado:</b> <?php echo $averageScore; ?><br />
					<b>Média da Nota Final:</b> <?php echo (100*$averageScore/$maxGradeSum); ?><br />
					<b>Número de Alunos:</b> <?php echo $nAlunos; ?><br />
					<table class="dataView">
					<tr>
						<th>Matrícula</th>
						<th>Nome do Aluno</th>
						<th>Nota Acumulada</th>
						<th>Nota Final</th>
						<th>Questões Entregues</th>
						<th>Nota pela Entrega</th>
					</tr>
					<?php foreach ($userGrades as $ug => $userGrade) { ?>
						<tr class="color<?php echo ($ug % 2);?>">
							<td><?php echo $userGrade['matricula']; ?></td>
							<td><?php echo $userGrade['name']; ?></td>
							<td><?php echo $userGrade['totalScore']; ?></td>
							<td><?php echo (100*$userGrade['totalScore']/$maxGradeSum); ?>%</td>
							<td><?php echo $userGrade["totalSubmitted"];?></td>
							<td><?php echo (100.0*$userGrade["totalSubmitted"]/$nProblems);?>%</td>
						</tr>
					<?php } ?>
				</table>
				<?php
				}
			}
		}
	}
	
?>