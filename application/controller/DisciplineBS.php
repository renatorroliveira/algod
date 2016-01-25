<?php 
	if (!isset($DISCIPLINEBS_INCLUDED)) {
		$DISCIPLINEBS_INCLUDED = true;
		
		@include "./model/DAO.php";
		@include "../model/DAO.php";
		@include "./model/Discipline.php";
		@include "../model/Discipline.php";
		
		class DisciplineBS {
			protected $params;
			
			function __construct($params) {
				if (UserSession::getInstance()->isLogged() !== true) {
					die("<h1>Forbidden resource for you.</h1>");
				}
				$this->params = $params;
			}
			
			public function delete() {
				if (UserSession::getInstance()->getAccessLevel() < 5)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new Discipline();
				$model->set('dsc_id', $this->params['dsc_id']);
				$model->set('dsc_deleted', 1);
				@$dao = new DAO(Discipline);
				$qbuilder = new QueryBuilder('discipline');
				$qbuilder->addEqual('dsc_id', $model->get('dsc_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Disciplina inválida.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Disciplina não existe.</h1>");
				if ($existent->get('dsc_usr_id') != UserSession::getInstance()->getUser()->get("usr_id")) {
					if (UserSession::getInstance()->getAccessLevel() < 6)
						die("<h1>Forbidden resource for you.</h1>");
				}
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function save() {
				if (UserSession::getInstance()->getAccessLevel() < 5)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new Discipline();
				$model->setFields($this->params);
				$model->set('dsc_usr_id', UserSession::getInstance()->getUser()->get("usr_id"));
				$modelId = $model->get("dsc_id");
				if (!isset($modelId)) {
					$status = $this->saveNew($model);
					return $status;
				} else {
					$status = $this->update($model);
					return $status;
				}
			}
			protected function saveNew($model) {
				@$dao = new DAO(Discipline);
				$model->set('dsc_deleted', 0);
				$status = $dao->save($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar cadastrar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			protected function update($model) {
				@$dao = new DAO(Discipline);
				$qbuilder = new QueryBuilder('discipline');
				$qbuilder->addEqual('dsc_id', $model->get('dsc_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Disciplina inválida.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Disciplina não existe.</h1>");
				if ($existent->get('dsc_usr_id') != UserSession::getInstance()->getUser()->get("usr_id")) {
					if (UserSession::getInstance()->getAccessLevel() < 6)
						die("<h1>Forbidden resource for you.</h1>");
				}
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}

			public function retrieve() {
				@$dao = new DAO(Discipline);
				$qbuilder = new QueryBuilder('discipline');
				$qbuilder->addEqual('dsc_id', $this->params['dsc_id']);
				$discipline = $dao->findByQuery($qbuilder);
				return $discipline[0];
			}
			
			public function findNotDeleted($qbuilder) {
				@$dao = new DAO(Discipline);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('discipline');
					$qbuilder->addOrder("dsc_code", QueryBuilder::$ASC);
					$qbuilder->addOrder("dsc_name", QueryBuilder::$ASC);
				}
				$qbuilder->addJoin("user", "dsc_usr_id", "usr_id");
				$qbuilder->addEqual("dsc_deleted", 0, 'discipline');
				$disciplines = $dao->findByQuery($qbuilder);
				return $disciplines;
			}
			
			public function findAll($qbuilder) {
				if (UserSession::getInstance()->getAccessLevel() < 5)
					die("<h1>Forbidden resource for you.</h1>");
				@$dao = new DAO(Discipline);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('discipline');
					$qbuilder->addOrder("dsc_code", QueryBuilder::$ASC);
					$qbuilder->addOrder("dsc_name", QueryBuilder::$ASC);
				}
				$disciplines = $dao->findByQuery($qbuilder);
				return $disciplines;
			}
		}
	}
?>