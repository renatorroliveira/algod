<?php
include "controller/UserSession.php";
$session = UserSession::getInstance();
if ($session->getAccessLevel() < 3)
	die("Forbidden.");
else if (!isset($_GET['tpb_tst_id']))
	die("Forbidden.");
$testSelected = $_GET['tpb_tst_id'];

include "./controller/TestProblemsBS.php";
include "./controller/TestTrialBS.php";

header("Conten-Type: text/csv");
header('Content-Disposition:  attachment; filename="notas.csv";');

$bs = new TestProblemsBS(null);
if (!isset($testSelected) || ($testSelected == "")) {
	die("Nenhuma prova selecionada.");
} else {
	$qbuilder = new QueryBuilder('testproblems');
	$qbuilder->addEqual('tpb_tst_id', $testSelected);
	$qbuilder->addOrder('tpb_questionNumber', QueryBuilder::$ASC);
	$qbuilder->addOrder('prb_title', QueryBuilder::$ASC, "problem");
	$tproblems = $bs->findNotDeleted($qbuilder);
}
$nProblems = count($tproblems);
if ($nProblems <= 0) {
	die("Nenhum problema nesta prova.");
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
	echo $test->get('tst_title')."\n";
	echo "\nNúmero de Questões:,";
	echo $nProblems."\n";
	echo "Acumulado Máximo:,";
	echo $maxGradeSum."\n";
	echo "Média do Acumulado:,";
	echo $averageScore."\n";
	echo "Média da Nota Final:,";
	echo (100*$averageScore/$maxGradeSum)."\n";
	echo "Número de Alunos,";
	echo $nAlunos."\n";
	echo "\nMatrícula:,".
		"Nome do Aluno:,".
		"Nota Acumulada:,".
		"Nota Final:,".
		"Questões Entregues:,".
		"Nota pela Entrega:\n";
	foreach ($userGrades as $ug => $userGrade) {
		echo $userGrade['matricula'].",";
		echo $userGrade['name'].",";
		echo $userGrade['totalScore'].",";
		echo (100*$userGrade['totalScore']/$maxGradeSum).",";
		echo $userGrade["totalSubmitted"].",";
		echo (100.0*$userGrade["totalSubmitted"]/$nProblems)."\n";
	}
}
?>
