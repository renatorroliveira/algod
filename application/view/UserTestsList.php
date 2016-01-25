<?php 
	if (!isset($USERTESTSLIST_INCLUDED)) {
		$USERTESTSLIST_INCLUDED = true;

		include "controller/TestBS.php";
		include "controller/TestTrialBS.php";
		include "view/TestProblemTryView.php";
		
		class UserTestsList {
			protected $user;
			protected $trials;
			
			public function __construct($user) {
				$this->user = $user;
				
				$today = new DateTime("now");
				$ttrialBS = new TestTrialBS(null);
				$qbuilder = new QueryBuilder('testtrial');
				$qbuilder->addEqual("ttl_usr_id", $this->user->get('usr_id'));
				$qbuilder->addLess("tst_openUntil", $today->format(Model::$SQL_DATE_FORMAT), "test");
				$qbuilder->addOrder("ttl_tst_id", QueryBuilder::$DESC);
				$qbuilder->addOrder("prb_title", QueryBuilder::$ASC, 'problem');
				$qbuilder->addOrder("ttl_score", QueryBuilder::$DESC);
				$this->trials = $ttrialBS->findNotDeleted($qbuilder);
			}
			
			public function render() {
				if (count($this->trials) < 1) {
					?>
					<i>Nenhuma prova realizada por você ainda.</i>
					<?php
					return true;
				}
				$lastTest = -1;
				$first = true;
				$total = 0;
				$avg = 0.0;
				foreach ($this->trials as $tt => $trial) {
					$prob = $trial->getForeignModel("ttl_prb_id");
					if ($lastTest != $trial->get("ttl_tst_id")) {
						$lastTest = $trial->get("ttl_tst_id");
						$test = $trial->getForeignModel("ttl_tst_id");
						if ($first == true)
							$first = false;
						else {
							$avg /= $total;
						?>
							<tr class="color<?php echo ($tt % 2);?>">
								<td><b>Nota Final</b></td><td><?php echo $avg; ?></td><td></td>
							</tr>
							</table>
						<?php
						}
						$total = 1;
						$avg = $trial->get("ttl_score");
						?>
						<br /><h3><?php echo $test->get('tst_title'); ?></h3>
						<table class="dataView">
						<tr>
							<th>Problema</th>
							<th>Nota</th>
							<th>Código-Fonte</th>
						</tr>
						<?php
					} else {
						$total += 1;
						$avg += $trial->get("ttl_score");
					}
					?>
					<tr class="color<?php echo ($tt % 2);?>">
						<td><?php echo $prob->get("prb_title"); ?></td>
						<td><?php echo $trial->get("ttl_score"); ?></td>
						<td class="actions">
							<span class="ui-state-default ui-corner-all" onClick="openDivAsDialog('usertestssource-<?php echo $tt; ?>');"
								title="Visualizar código-fonte.">
								<span class="ui-icon ui-icon-search"></span>
							</span>
							<div style="display:none;" id='usertestssource-<?php echo $tt; ?>'>
								<textarea class="cpp-code-view"><?php echo $trial->get("ttl_sourcefile"); ?></textarea>
							</div>
						</td>
					</tr>
					<?php
				}
				$avg /= $total;
				?>
				<tr class="color<?php echo (count($this->trials) % 2);?>">
					<td><b>Nota Final</b></td><td><?php echo $avg; ?></td><td></td>
				</tr>
				</table>
				<?php
			}
			
		}
	}
?>