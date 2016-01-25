<?php 
	if (!isset($PROBLEMBS_INCLUDED)) {
		$PROBLEMBS_INCLUDED = true;
		
		@include "./model/DAO.php";
		@include "../model/DAO.php";
		@include "./model/Problem.php";
		@include "../model/Problem.php";
		
		class ProblemBS {
			protected $params;
			
			function __construct($params) {
				$this->params = $params;
			}
			
			public function delete() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new Problem();
				$model->set('prb_id', $this->params['prb_id']);
				$model->set('prb_deleted', 1);
				@$dao = new DAO(Problem);
				$qbuilder = new QueryBuilder('problem');
				$qbuilder->addEqual('prb_id', $model->get('prb_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Problema inválida.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Problema não existe.</h1>");
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function save() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new Problem();
				$model->setFields($this->params);
				$modelId = $model->get("prb_id");
				if (!isset($modelId)) {
					$status = $this->saveNew($model);
					return $status;
				} else {
					$status = $this->update($model);
					return $status;
				}
			}
			protected function saveNew($model) {
				@$dao = new DAO(Problem);
				$model->set("prb_deleted", 0);
				$status = $dao->save($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar cadastrar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			protected function update($model) {
				@$dao = new DAO(Problem);
				$qbuilder = new QueryBuilder('problem');
				$qbuilder->addEqual('prb_id', $model->get('prb_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Problema inválida.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Problema não existe.</h1>");
				
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function retrieve() {
				@$dao = new DAO(Problem);
				$qbuilder = new QueryBuilder('problem');
				$qbuilder->addEqual('prb_id', $this->params['prb_id']);
				$problem = $dao->findByQuery($qbuilder);
				return $problem[0];
			}
			
			public function findNotDeleted($qbuilder) {
				@$dao = new DAO(Problem);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('problem');
					$qbuilder->addOrder("dsc_code", QueryBuilder::$ASC, 'discipline');
					$qbuilder->addOrder("prb_difficultyLevel", QueryBuilder::$ASC);
					$qbuilder->addOrder("prb_title", QueryBuilder::$ASC);
				}
				$qbuilder->addJoin("discipline", "prb_dsc_id", "dsc_id");
				$qbuilder->addLeftJoin("evaluationcase", "prb_id", "evc_prb_id","evc_deleted");
				$qbuilder->addGroupBy("prb_id", "problem");
				$qbuilder->addEqual("prb_deleted", 0);
				$problems = $dao->findByQueryWithMetaFields($qbuilder);
				return $problems;
			}
			
			public function findAll($qbuilder) {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				@$dao = new DAO(Problem);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('problem');
					$qbuilder->addOrder("dsc_code", QueryBuilder::$ASC, 'discipline');
					$qbuilder->addOrder("prb_difficultyLevel", QueryBuilder::$ASC);
					$qbuilder->addOrder("prb_title", QueryBuilder::$ASC);
				}
				$qbuilder->addJoin("discipline", "prb_dsc_id", "dsc_id");
				$qbuilder->addLeftJoin("evaluationcase", "prb_id", "evc_prb_id");
				$qbuilder->addGroupBy("prb_id", "problem");
				$problems = $dao->findByQueryWithMetaFields($qbuilder);
				return $problems;
			}
		}
	}
?>