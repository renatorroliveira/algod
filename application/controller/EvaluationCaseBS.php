<?php 
	if (!isset($EVALUATIONCASEBS_INCLUDED)) {
		$EVALUATIONCASEBS_INCLUDED = true;
		
		@include "./model/DAO.php";
		@include "../model/DAO.php";
		@include "./model/EvaluationCase.php";
		@include "../model/EvaluationCase.php";
		
		class EvaluationCaseBS {
			protected $params;
			
			function __construct($params) {
				$this->params = $params;
			}
			
			public function delete() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new EvaluationCase();
				$model->set('evc_id', $this->params['evc_id']);
				$model->set('evc_deleted', 1);
				@$dao = new DAO(EvaluationCase);
				$qbuilder = new QueryBuilder('evaluationcase');
				$qbuilder->addEqual('evc_id', $model->get('evc_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Caso de teste inválida.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Caso de teste não existe.</h1>");
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function save() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new EvaluationCase();
				$model->setFields($this->params);
				$modelId = $model->get("evc_id");
				if (!isset($modelId)) {
					$status = $this->saveNew($model);
					return $status;
				} else {
					$status = $this->update($model);
					return $status;
				}
			}
			protected function saveNew($model) {
				@$dao = new DAO(EvaluationCase);
				$model->set("evc_deleted", 0);
				$status = $dao->save($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar cadastrar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			protected function update($model) {
				@$dao = new DAO(EvaluationCase);
				$qbuilder = new QueryBuilder('evaluationcase');
				$qbuilder->addEqual('evc_id', $model->get('evc_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Caso de teste inválida.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Caso de teste não existe.</h1>");
				
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function retrieve() {
				@$dao = new DAO(EvaluationCase);
				$qbuilder = new QueryBuilder('evaluationcase');
				$qbuilder->addEqual('evc_id', $this->params['evc_id']);
				$problem = $dao->findByQuery($qbuilder);
				return $problem[0];
			}
			
			public function findNotDeleted($qbuilder) {
				@$dao = new DAO(EvaluationCase);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('evaluationcase');
					$qbuilder->addOrder("prb_title", QueryBuilder::$ASC, 'discipline');
					$qbuilder->addOrder("evc_id", QueryBuilder::$ASC);
				}
				$qbuilder->addJoin("problem", "evc_prb_id", "prb_id");
				$qbuilder->addEqual("evc_deleted", 0);
				$ecases = $dao->findByQuery($qbuilder);
				return $ecases;
			}
			
			public function findAll($qbuilder) {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				@$dao = new DAO(EvaluationCase);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('evaluationcase');
					$qbuilder->addOrder("prb_title", QueryBuilder::$ASC, 'discipline');
					$qbuilder->addOrder("evc_id", QueryBuilder::$ASC);
				}
				$qbuilder->addJoin("problem", "evc_prb_id", "prb_id");
				$ecases = $dao->findByQuery($qbuilder);
				return $ecases;
			}
		}
	}
?>