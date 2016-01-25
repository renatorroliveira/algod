<?php 
	if (!isset($TESTBS_INCLUDED)) {
		$TESTBS_INCLUDED = true;
		
		@include "./model/DAO.php";
		@include "../model/DAO.php";
		@include "./model/Test.php";
		@include "../model/Test.php";
		@include "./model/TestLog.php";
		@include "../model/TestLog.php";
		
		class TestBS {
			protected $params;
			
			function __construct($params) {
				$this->params = $params;
			}
			
			public function delete() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new Test();
				$model->set('tst_id', $this->params['tst_id']);
				$model->set('tst_deleted', 1);
				@$dao = new DAO(Test);
				$qbuilder = new QueryBuilder('test');
				$qbuilder->addEqual('tst_id', $model->get('tst_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Prova inválida.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Prova não existe.</h1>");
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function save() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new Test();
				$model->setFields($this->params);
				$modelId = $model->get("tst_id");
				if (!isset($modelId)) {
					$status = $this->saveNew($model);
					return $status;
				} else {
					$status = $this->update($model);
					return $status;
				}
			}
			protected function saveNew($model) {
				@$dao = new DAO(Test);
				$today = new DateTime('now');
				$model->set("tst_createdAt", $today->format(Model::$SQL_DATE_FORMAT));
				$model->set("tst_password", sha1($model->get("tst_password")));
				$model->set("tst_visibleSince", Model::parseInputToSQLDate($model->get("tst_visibleSince")));
				$model->set("tst_visibleUntil", Model::parseInputToSQLDate($model->get("tst_visibleUntil")));
				$model->set("tst_openSince", Model::parseInputToSQLDate($model->get("tst_openSince")));
				$model->set("tst_openUntil", Model::parseInputToSQLDate($model->get("tst_openUntil")));
				$model->set("tst_deleted", 0);
				$status = $dao->save($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar cadastrar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			protected function update($model) {
				@$dao = new DAO(Test);
				$model->set("tst_password", sha1($model->get("tst_password")));
				$model->set("tst_visibleSince", Model::parseInputToSQLDate($model->get("tst_visibleSince")));
				$model->set("tst_visibleUntil", Model::parseInputToSQLDate($model->get("tst_visibleUntil")));
				$model->set("tst_openSince", Model::parseInputToSQLDate($model->get("tst_openSince")));
				$model->set("tst_openUntil", Model::parseInputToSQLDate($model->get("tst_openUntil")));
				$qbuilder = new QueryBuilder('test');
				$qbuilder->addEqual('tst_id', $model->get('tst_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Prova inválida.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Prova não existe.</h1>");
				
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function retrieve() {
				@$dao = new DAO(Test);
				$qbuilder = new QueryBuilder('test');
				$qbuilder->addEqual('tst_id', $this->params['tst_id']);
				$test = $dao->findByQuery($qbuilder);
				return $test[0];
			}
			
			public function findNotDeleted($qbuilder) {
				@$dao = new DAO(Test);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('test');
					$qbuilder->addOrder("dsc_code", QueryBuilder::$ASC, 'discipline');
					$qbuilder->addOrder('tst_openUntil', QueryBuilder::$DESC);
					$qbuilder->addOrder('tst_visibleUntil', QueryBuilder::$DESC);
					$qbuilder->addOrder('tst_createdAt', QueryBuilder::$DESC);
					$qbuilder->addOrder('tst_title', QueryBuilder::$ASC);
					$qbuilder->addOrder('tst_openSince', QueryBuilder::$DESC);
					$qbuilder->addOrder('tst_visibleSince', QueryBuilder::$DESC);
				}
				$qbuilder->addJoin("discipline", "tst_dsc_id", "dsc_id");
				$qbuilder->addLeftJoin("testproblems", "tst_id", "tpb_tst_id","tpb_deleted");
				$qbuilder->addGroupBy("tst_id", "test");
				$qbuilder->addEqual("tst_deleted", 0, 'test');
				$tests = $dao->findByQueryWithMetaFields($qbuilder);
				return $tests;
			}
			
			public function findAll($qbuilder) {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				@$dao = new DAO(Test);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('test');
					$qbuilder->addOrder("dsc_code", QueryBuilder::$ASC, 'discipline');
					$qbuilder->addOrder("tst_title", QueryBuilder::$ASC);
				}
				$qbuilder->addJoin("discipline", "tst_dsc_id", "dsc_id");
				$qbuilder->addLeftJoin("testproblems", "tst_id", "tpb_tst_id");
				$qbuilder->addGroupBy("tst_id", "test");
				$tests = $dao->findByQueryWithMetaFields($qbuilder);
				return $tests;
			}
			

			public function logAction($user, $test, $msg, $hostname = 'N/A', $question = 'N/A', $score = 'N/A') {
				if (($hostname == 'N/A') && isset($_SERVER['REMOTE_HOST']))
					$hostname = $_SERVER['REMOTE_HOST'];
				@$dao = new DAO(TestLog);
				$model = new TestLog();
				$today = new DateTime('now');
				$model->set("tsl_time", $today->format(Model::$SQL_DATE_FORMAT));
				$model->set("tsl_usr_id", $user);
				$model->set("tsl_tst_id", $test);
				$model->set("tsl_message", $msg);
				$model->set("tsl_hostname", $hostname);
				$model->set("tsl_remoteAddr", $_SERVER['REMOTE_ADDR']);
                                $model->set("tsl_questNumber", $question);
                                $model->set("tsl_score", $score);
				$status = $dao->save($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar salvar log, favor contatar um professor:<br />".$status);
				}
			}
		}
	}
?>