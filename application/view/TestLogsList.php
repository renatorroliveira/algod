<?php
	if (!isset($TESTLOGSLIST_INCLUDED)) {
		$TESTLOGSLIST_INCLUDED = true;
		
		@include "../controller/TestLogBS.php";
		@include "./controller/TestLogBS.php";
		
		class TestLogsList {
			
			public function __construct() {
				
			}
			
			public function render($testSelected = null) {
				$bs = new TestLogBS(null);
				if (!isset($testSelected) || ($testSelected == "")) {
					echo "<i>Nenhuma prova selecionada.</i>";
					return;
				} else {
					$qbuilder = new QueryBuilder('testlog');
					$qbuilder->addEqual('tsl_tst_id', $testSelected);
					$qbuilder->addOrder("usr_name", QueryBuilder::$ASC, 'user');
					$qbuilder->addOrder("tsl_time", QueryBuilder::$DESC);
					$qbuilder->addOrder("tsl_remoteAddr", QueryBuilder::$ASC);
					$tlogs = $bs->findAll($qbuilder);
				}
				$nLogs = count($tlogs);
				if ($nLogs <= 0) {
				?>
					<br/><i>Nenhum log recuperado referente à esta prova.</i><br />
				<?php
				} else {
					$test = $tlogs[0]->getForeignModel('tsl_tst_id');
					?>
					<h2><?php echo $test->get('tst_title'); ?></h2><br /><br />
					<?php
					$first = true;
					$prevUser = -1;
					foreach ($tlogs as $tl => $tlog) {
						if ($prevUser != $tlog->get('tsl_usr_id')) {
							$user = $tlog->getForeignModel('tsl_usr_id');
							$prevUser = $tlog->get('tsl_usr_id');
							if ($first == true)
								$first = false;
							else {
							?>
								</table>
							<?php
							}
							?>
							<br /><h3><?php echo $user->get("usr_name"); ?></h3>
							<table class="dataView">
							<tr>
								<th style="min-width:150px;width:150px;max-width:150px;">Horário</th>
								<th style="min-width:130px;width:130px;max-width:130px;">IP</th>
								<th style="min-width:150px;width:150px;max-width:150px;">Hostname</th>
                                                                <th style="min-width:150px;width:150px;max-width:150px;">Questão</th>
                                                                <th style="min-width:150px;width:150px;max-width:150px;">Nota</th>
								<th>Mensagem</th>
							</tr>
						<?php } ?>
						<tr class="color<?php echo ($tl % 2);?>">
							<td><?php echo Model::parseSQLToInputDate($tlog->get('tsl_time')); ?></td>
							<td><?php echo $tlog->get('tsl_remoteAddr'); ?></td>
							<td><?php echo $tlog->get('tsl_hostname'); ?></td>
                                                        <td><?php echo $tlog->get('tsl_questNumber'); ?></td>
                                                        <td><?php echo $tlog->get('tsl_score'); ?></td>
							<td style="cursor:pointer" onClick="openDivAsDialog('logmessagediv-<?php echo $tl; ?>');">
								<?php echo substr($tlog->get('tsl_message'), 0, 50); ?>...
								<div style='display:none;' id='logmessagediv-<?php echo $tl; ?>'>
									<textarea class="cpp-code-view"><?php echo $tlog->get('tsl_message'); ?></textarea>
								</div>
							</td>
						</tr>
						<?php
					}
					?></table><?php
				}
			}
		}
	}
	
?>