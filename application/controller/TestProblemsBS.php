<?php 
	if (!isset($TESTPROBLEMSBS_INCLUDED)) {
		$TESTPROBLEMSBS_INCLUDED = true;
		
		@include "./model/DAO.php";
		@include "../model/DAO.php";
		@include "./model/TestProblems.php";
		@include "../model/TestProblems.php";
		
		class TestProblemsBS {
			protected $params;
			
			function __construct($params) {
				$this->params = $params;
			}
			
			public function delete() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new TestProblems();
				$model->set('tpb_tst_id', $this->params['tpb_tst_id']);
				$model->set('tpb_prb_id', $this->params['tpb_prb_id']);
				$model->set('tpb_deleted', 1);
				@$dao = new DAO(TestProblems);
				$qbuilder = new QueryBuilder('testproblems');
				$qbuilder->addEqual('tpb_tst_id', $model->get('tpb_tst_id'));
				$qbuilder->addEqual('tpb_prb_id', $model->get('tpb_prb_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Problema inválido.</h1>");
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
				$model = new TestProblems();
				$model->setFields($this->params);
				$model->set("tpb_deleted", 0);
				@$dao = new DAO(TestProblems);
				$qbuilder = new QueryBuilder('testproblems');
				$qbuilder->addEqual('tpb_tst_id', $model->get('tpb_tst_id'));
				$qbuilder->addEqual('tpb_prb_id', $model->get('tpb_prb_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) < 1)
					$status = $dao->save($model);
				else
					$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar cadastrar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			public function update() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new TestProblems();
				$model->setFields($this->params);
				@$dao = new DAO(TestProblems);
				$qbuilder = new QueryBuilder('testproblems');
				$qbuilder->addEqual('tpb_tst_id', $model->get('tpb_tst_id'));
				$qbuilder->addEqual('tpb_prb_id', $model->get('tpb_prb_id'));
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
				@$dao = new DAO(TestProblems);
				$qbuilder = new QueryBuilder('testproblems');
				$qbuilder->addEqual('tpb_tst_id', $this->params['tpb_tst_id']);
				$qbuilder->addEqual('tpb_prb_id', $this->params['tpb_prb_id']);
				$tproblems = $dao->findByQuery($qbuilder);
				return $tproblems[0];
			}
			
			public function findNotDeleted($qbuilder) {
				@$dao = new DAO(TestProblems);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('testproblems');
					$qbuilder->addOrder("tst_title", QueryBuilder::$ASC, 'test');
					$qbuilder->addOrder("tpb_questionNumber", QueryBuilder::$ASC);
					$qbuilder->addOrder("prb_title", QueryBuilder::$ASC, 'problem');
				}
				$qbuilder->addJoin("test", "tpb_tst_id", "tst_id");
				$qbuilder->addJoin("problem", "tpb_prb_id", "prb_id");
				$qbuilder->addEqual("tpb_deleted", 0);
				$tproblems = $dao->findByQuery($qbuilder);
				return $tproblems;
			}
			
			public function findAll($qbuilder) {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				@$dao = new DAO(TestProblems);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('testproblems');
					$qbuilder->addOrder("tst_title", QueryBuilder::$ASC, 'test');
					$qbuilder->addOrder("tpb_questionNumber", QueryBuilder::$ASC);
					$qbuilder->addOrder("prb_title", QueryBuilder::$ASC, 'problem');
				}
				$qbuilder->addJoin("test", "tpb_tst_id", "tst_id");
				$qbuilder->addJoin("problem", "tpb_prb_id", "prb_id");
				$tproblems = $dao->findByQuery($qbuilder);
				return $tproblems;
			}
		}
	}
?>