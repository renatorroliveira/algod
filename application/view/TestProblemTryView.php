<?php 
	if (!isset($TESTPROBLEMTRYVIEW_INCLUDED)) {
		$TESTPROBLEMTRYVIEW_INCLUDED = true;

		//include "controller/TestTrialController.php";
		
		class TestProblemTryView {
			
			public static function render($test, $problem, $trial, $tpb) {
				if ((!isset($test)) || (!isset($problem)))
					die("É necessário uma prova e um problema para utilizar este componente.");
				if (!isset($trial))
					$tried = false;
				else
					$tried = true;
				
				?>
					<div class="problem-try-ct">
						<h3>
							Questão <?php echo $tpb->get("tpb_questionNumber"); ?>:
							<?php echo $problem->get("prb_title"); ?>
						</h3>
						<p><b>Peso:</b> <?php echo $tpb->get("tpb_weight"); ?></p>
						<p><b>Descrição:</b></p>
						<div class="description-display-box">
							<?php echo $problem->get("prb_description"); ?>
						</div><br />
						<?php
						if ($tried === false) {
						?>
							<p><i>Você ainda não fez nenhuma tentativa nesta questão.</i></p>
						<?php } else {?>
							<p>
								<b>Ultima tentativa:</b>
								<?php echo Model::parseSQLToInputDate($trial->get('ttl_lastTrial')); ?>
							</p>
							<p>
								<b>Total de tentativas:</b>
								<?php echo $trial->get('ttl_trials'); ?>
								(máximo de <?php echo $test->get("tst_maxTrials"); ?>)
							</p>
							<p>
								<b>Nota (0 a 100):</b>
								<?php echo $trial->get('ttl_score'); ?>
							</p>
							<p>
								<b>Computador Utilizado:</b>
								<?php echo $trial->get('ttl_hostname'); ?>
							</p>
							<p>
								<b>IP Utilizado:</b>
								<?php echo $trial->get('ttl_remoteAddr'); ?>
							</p>
							<p>
								<b>Status ou Justificativa de Nota:</b>
								<?php echo $trial->get('ttl_reason'); ?>
							</p>
							<button onClick="openDivAsDialog('lastTrial<?php echo $trial->get('ttl_prb_id');?>');">Ver Última Tentativa</button>
							<div style="display:none;" id='lastTrial<?php echo $trial->get('ttl_prb_id');?>'>
								<textarea class="cpp-code-view"><?php
									echo $trial->get('ttl_sourcefile');
								?></textarea>
							</div>
						<?php
						}
						if (($tried === true) && ($trial->get('ttl_trials') >= $test->get("tst_maxTrials"))) {
						?>
						<br />
						<p><i>Você esgotou o máximo de tentativas nesta questão.</i></p>
						<br />
						<?php } else {?>
						<br />
						<form method=POST action="controller/TestTrialController.php" class="centering">
							<table class="formTable">
								<tr>
									<td><label for="ttl_code">Código Impresso pelo Corretor</label>:</td>
									<td><input name="ttl_code" type="text" size=50 class="notempty" /></td>
								</tr>
								<tr>
									<td><label for="ttl_sourcefile">Código-fonte em C++</label>:</td>
									<td><textarea name="ttl_sourcefile" cols=80 rows=10 class="notempty"></textarea></td>
								</tr>
							</table>
							<input type="hidden" name="ttl_tst_id" value="<?php echo $test->get("tst_id"); ?>" />
							<input type="hidden" name="tst_password" value="<?php echo $test->get("tst_password"); ?>" />
							<input type="hidden" name="ttl_prb_id" value="<?php echo $problem->get("prb_id"); ?>" />
                                                        <input type="hidden" name="ttl_questNumber" value="<?php echo $tpb->get("tpb_questionNumber"); ?>" />
                                                        <input type="hidden" name="ttl_score" value="<?php echo $trial->get('ttl_score'); ?>" />
							<input type="hidden" name="_action" value="save" />
							<input type="submit" value="Salvar Nova Tentiva" />
						</form>
						<br />
						<?php } ?>
					</div>
				<?php
			}
			
		}
	}
?>