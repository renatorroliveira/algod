<?php 
	if (!isset($TESTTRYVIEW_INCLUDED)) {
		$TESTTRYVIEW_INCLUDED = true;

		include "controller/TestBS.php";
		include "controller/TestTrialBS.php";
		include "controller/TestProblemsBS.php";
		include "controller/UserSession.php";
		include "view/TestProblemTryView.php";
		
		class TestTryView {
			protected $testId;
			protected $password;
			protected $user;
			protected $problems;
			protected $trials;
			protected $test;
			
			public function __construct($testId, $password) {
				$this->user = UserSession::getInstance()->getUser();
				$this->testId = $testId;
				$this->password = $password;
				
				$tprbBS = new TestProblemsBS(null);
				$qbuilder = new QueryBuilder('testproblems');
				$qbuilder->addEqual("tpb_tst_id", $testId);
				$qbuilder->addOrder("tpb_questionNumber", QueryBuilder::$ASC);
				$this->problems = $tprbBS->findNotDeleted($qbuilder);
				
				$testBS = new TestBS(null);
				$qbuilder = new QueryBuilder('test');
				$qbuilder->addEqual("tst_id", $testId);
				$qbuilder->addEqual("tst_password", $password);
				$tests = $testBS->findNotDeleted($qbuilder);
				if (count($tests) < 1)
					die("<h3>ACESSO NEGADO</h3>");
				$this->test = $tests[0];
				
				$ttrialBS = new TestTrialBS(null);
				$ttrialBS->createUserTrials($this->problems);
				$qbuilder = new QueryBuilder('testtrial');
				$qbuilder->addEqual("ttl_tst_id", $testId);
				$qbuilder->addEqual("ttl_usr_id", $this->user->get('usr_id'));
				$trials = $ttrialBS->findNotDeleted($qbuilder);
				$this->trials = array();
				if (count($trials) > 0) {
					foreach ($trials as $t => $trial) {
						$this->trials[$trial->get('ttl_prb_id')] = $trial;
					}
				}
				
				// Gravando log
				if ($this->test->get("tst_enableLogging") != 0)
					$testBS->logAction($this->user->get('usr_id'), $this->testId, "Usuário abriu a prova.");
			}
			
			public function render() {
				$today = new DateTime('now');
				$since = DateTime::createFromFormat(Model::$SQL_DATE_FORMAT, $this->test->get('tst_openSince'));
				$until = DateTime::createFromFormat(Model::$SQL_DATE_FORMAT, $this->test->get('tst_openUntil'));
				if ($today->getTimestamp() < $since->getTimestamp())
					echo "<i>Essa prova não está aberta ainda.</i>";
				else if ($today->getTimestamp() > $until->getTimestamp())
					echo "<i>Essa prova já está fechada.</i>";
				else {
				?>
					<h2><?php echo $this->test->get('tst_title'); ?></h2>
					<p>
						<b>Corretor:</b>
						<a href="correctors/algod<?php echo $this->test->get('tst_id'); ?>">Baixar</a>
					</p>
					<p>
						<b>Prova Aberta Até:</b>
						<?php echo Model::parseSQLToInputDate($this->test->get('tst_openUntil')); ?>
					</p>
					<p>
						<b>Número Máximo de Tentativas:</b>
						<?php echo $this->test->get('tst_maxTrials'); ?>
					</p>
					<p>
						<b>Atenuação da Nota por Tentativa (multiplicativa):</b>
						<?php echo $this->test->get('tst_scoreAttenuationPerTrial'); ?>
					</p>
					<p>
						<b>Nota Máxima por Tentativa:</b>
						<ol style="text-align: left; padding-left: 12ex;">
						<?php
							for ($i = 0; $i < $this->test->get('tst_maxTrials'); $i++) {
							?>
								<li><?php echo 100.0*pow($this->test->get('tst_scoreAttenuationPerTrial'),$i);?></li>
							<?php
							}
						?>
						</ol>
					</p>
					<p>
						<b>Descrição:</b>
					</p>
					<div class="description-display-box">
						<?php echo $this->test->get('tst_description'); ?>
					</div>
					<br /><h3>QUESTÕES</h3><br /><br />
					<?php
					foreach ($this->problems as $tp => $testpb) {
						TestProblemTryView::render($this->test,
							$testpb->getForeignModel('tpb_prb_id'), @$this->trials[$testpb->get('tpb_prb_id')], $testpb);
					}
					?>
				<?php
				}
			}
			
		}
	}
?>