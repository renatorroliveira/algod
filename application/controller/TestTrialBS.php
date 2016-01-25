<?php 
	if (!isset($TESTTRIALBS_INCLUDED)) {
		$TESTTRIALBS_INCLUDED = true;
		
		@include "./model/DAO.php";
		@include "../model/DAO.php";
		@include "./controller/TestProblemsBS.php";
		@include "../controller/TestProblemsBS.php";
		@include "./controller/TestBS.php";
		@include "../controller/TestBS.php";
		@include "./model/TestTrial.php";
		@include "../model/TestTrial.php";
		
		class TestTrialBS {
			protected $params;
			
			function __construct($params) {
				$this->params = $params;
			}
			
			public function delete() {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new TestTrial();
				$model->set('ttl_usr_id', $this->params['ttl_usr_id']);
				$model->set('ttl_tst_id', $this->params['ttl_tst_id']);
				$model->set('ttl_prb_id', $this->params['ttl_prb_id']);
				$model->set('ttl_deleted', 1);
				@$dao = new DAO(TestTrial);
				$qbuilder = new QueryBuilder('testtrial');
				$qbuilder->addEqual('ttl_usr_id', $model->get('ttl_usr_id'));
				$qbuilder->addEqual('ttl_tst_id', $model->get('ttl_tst_id'));
				$qbuilder->addEqual('ttl_prb_id', $model->get('ttl_prb_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Resposta inválido.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Resposta não existe.</h1>");
				$status = $dao->update($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function save() {
				if (UserSession::getInstance()->isLogged() !== true)
					die("<h1>Forbidden resource for you.</h1>");
				$model = new TestTrial();
				$model->setFields($this->params);
				$model->set('ttl_usr_id', UserSession::getInstance()->getUser()->get('usr_id'));
				$existent = $this->retrieve($model);
				$tproblemBS = new TestProblemsBS(null);
				$qbuilder = new QueryBuilder("testproblems");
				$qbuilder->addEqual("tpb_prb_id", $existent->get("ttl_prb_id"));
				$qbuilder->addEqual("tpb_tst_id", $existent->get("ttl_tst_id"));
				$tprobs = $tproblemBS->findNotDeleted($qbuilder);
				if (count($tprobs) < 1)
					die("Invalid request.");
				$tprob = $tprobs[0];
				
				$today = new DateTime('now');
				$model->set('ttl_lastTrial', $today->format(Model::$SQL_DATE_FORMAT));
				$model->set('ttl_remoteAddr', $_SERVER['REMOTE_ADDR']);
				$output = array();
				$status = 0;
				$text = exec("algoddecrypter ".$model->get('ttl_code'), $output, $status);
				//echo $text;
				$countTrial = true;
				$nTrials = $existent->get("ttl_trials");
				$attenuation = $existent->getForeignModel("ttl_tst_id")->get("tst_scoreAttenuationPerTrial");
				//echo "Att: ".$attenuation;
				//die("<br />Trials: ".$nTrials);
				if ($status != 0) {
					$countTrial = false;
					$model->set("ttl_score", "0");
					$model->set("ttl_hostname", "N/A");
					$model->set("ttl_reason", "<i>Código do corretor submetido foi inválido.</i>");
				} else {
					$mat = "";
					$host = "";
					$question = "";
					$nota = "";
					sscanf($output[0],"%s\t%s\t%d\t%d",$mat,$host,$question,$nota);
					if ($mat == UserSession::getInstance()->getUser()->get('usr_matricula')) {
						if ($question == $tprob->get("tpb_questionNumber")) {
							$notaAttenuated = $nota*pow($attenuation, $nTrials);
							//die("Nota: ".$notaAttenuated);
							$model->set("ttl_score", $notaAttenuated);
							$model->set("ttl_hostname", $host);
							$model->set("ttl_reason", "Avaliado pelo corretor.");
						} else {
							$countTrial = false;
							$model->set("ttl_score", "0");
							$model->set("ttl_hostname", $host);
							$model->set("ttl_reason", "<i>O número da questão no código do corretor não corresponde à esta questão.</i>");
						}
					} else {
						$countTrial = false;
						$model->set("ttl_score", "0");
						$model->set("ttl_hostname", $host);
						$model->set("ttl_reason", "<i>A matrícula passada ao corretor não corresponde à matrícula de seu usuário.</i>");
					}
				}
				$model->set("ttl_sourcefile", mysql_real_escape_string($model->get("ttl_sourcefile")));
				
				$test = $existent->getForeignModel("ttl_tst_id");
				if ($test->get("tst_enableLogging") != 0) {
					$testBS = new TestBS(null);
					$testBS->logAction(UserSession::getInstance()->getUser()->get('usr_id'),
							$test->get('tst_id'), "Sumetida tentativa: ".$model->get('ttl_reason')."\n"
							.$model->get('ttl_sourcefile'),
							$model->get('ttl_hostname'),
                                                        $tprob->get("tpb_questionNumber"),
                                                        $model->get('ttl_score'));
				}
				@$dao = new DAO(TestTrial);
				if ($countTrial === true)
					$model->set("ttl_trials", $existent->get("ttl_trials")+1);
				else
					$model->set("ttl_trials", $existent->get("ttl_trials"));
				$status = $dao->update($model);
				
				if ($status !== true) {
					die("Um erro ocorreu ao tentar cadastrar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function retrieve($model = null) {
				@$dao = new DAO(TestTrial);
				$qbuilder = new QueryBuilder('testtrial');
				if (!isset($model)) {
					$qbuilder->addEqual('ttl_usr_id', $this->params['ttl_usr_id']);
					$qbuilder->addEqual('ttl_tst_id', $this->params['ttl_tst_id']);
					$qbuilder->addEqual('ttl_prb_id', $this->params['ttl_prb_id']);
				} else {
					$qbuilder->addEqual('ttl_usr_id', $model->get('ttl_usr_id'));
					$qbuilder->addEqual('ttl_tst_id', $model->get('ttl_tst_id'));
					$qbuilder->addEqual('ttl_prb_id', $model->get('ttl_prb_id'));
				}
				$qbuilder->addJoin("user", "ttl_usr_id", "usr_id");
				$qbuilder->addJoin("test", "ttl_tst_id", "tst_id");
				$qbuilder->addJoin("problem", "ttl_prb_id", "prb_id");
				$ttrial = $dao->findByQuery($qbuilder);
				if (count($ttrial) > 0)
					return $ttrial[0];
				else
					return null;
			}
			
			protected function saveIfNotExists($model) {
				if (!isset($model))
					return false;
				$retrieved = $this->retrieve($model);
				if (isset($retrieved))
					return true;
				@$dao = new DAO(TestTrial);
				$today = new DateTime('now');
				$model->set('ttl_lastTrial', $today->format(Model::$SQL_DATE_FORMAT));
				$model->set("ttl_deleted", 0);
				$model->set("ttl_remoteAddr", $_SERVER['REMOTE_ADDR']);
				$model->set("ttl_hostname", "N/A");
				$model->set("ttl_reason", "<i>Nenhuma tentativa realizada ainda.</i>");
				
				$status = $dao->save($model);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar iniciar prova, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			
			public function createUserTrials($tproblems) {
				if (UserSession::getInstance()->isLogged() !== true)
					die("<h1>Forbidden resource for you.</h1>");
				
				if ((!isset($tproblems)) || (count($tproblems) <= 0))
					return false;
				else {
					foreach ($tproblems as $tp => $tproblem) {
						$model = new TestTrial();
						$model->set('ttl_usr_id', UserSession::getInstance()->getUser()->get('usr_id'));
						$model->set('ttl_prb_id', $tproblem->get("tpb_prb_id"));
						$model->set('ttl_tst_id', $tproblem->get("tpb_tst_id"));
						$model->set('ttl_trials', 0);
						$model->set('ttl_score', 0.0);
						$model->set('ttl_code', "0");
						$model->set('ttl_hostname', "<i>Nenhuma tentativa realizada ainda.</i>");
						$model->set('ttl_sourcefile', "");
						$this->saveIfNotExists($model);
					}
				}
			}
			
			public function findNotDeleted($qbuilder) {
				@$dao = new DAO(TestTrial);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('testtrial');
					$qbuilder->addOrder("tst_title", QueryBuilder::$ASC, 'test');
					$qbuilder->addOrder("prb_difficultyLevel", QueryBuilder::$ASC, 'problem');
					$qbuilder->addOrder("prb_title", QueryBuilder::$ASC, 'problem');
					$qbuilder->addOrder("ttl_score", QueryBuilder::$DESC);
				}
				$qbuilder->addJoin("user", "ttl_usr_id", "usr_id");
				$qbuilder->addJoin("test", "ttl_tst_id", "tst_id");
				$qbuilder->addJoin("problem", "ttl_prb_id", "prb_id");
				//$qbuilder->addEqual("ttl_deleted", 0);
				$ttrials = $dao->findByQuery($qbuilder);
				return $ttrials;
			}
			
			public function findAll($qbuilder) {
				if (UserSession::getInstance()->getAccessLevel() < 3)
					die("<h1>Forbidden resource for you.</h1>");
				@$dao = new DAO(TestTrial);
				if (!isset($qbuilder)) {
					$qbuilder = new QueryBuilder('testtrial');
					$qbuilder->addOrder("tst_title", QueryBuilder::$ASC, 'test');
					$qbuilder->addOrder("prb_difficultyLevel", QueryBuilder::$ASC, 'problem');
					$qbuilder->addOrder("prb_title", QueryBuilder::$ASC, 'problem');
					$qbuilder->addOrder("ttl_score", QueryBuilder::$DESC);
				}
				$qbuilder->addJoin("user", "ttl_usr_id", "usr_id");
				$qbuilder->addJoin("test", "ttl_tst_id", "tst_id");
				$qbuilder->addJoin("problem", "ttl_prb_id", "prb_id");
				$ttrials = $dao->findByQuery($qbuilder);
				return $ttrials;
			}
		}
	}
?>