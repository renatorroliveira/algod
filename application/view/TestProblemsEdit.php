<?php
	if (!isset($TESTPROBLEMSEDIT_INCLUDED)) {
		$TESTPROBLEMSEDIT_INCLUDED = true;
		
		include "./model/TestProblems.php";
		include "./view/TestSelect.php";
		include "./view/ProblemSelect.php";
		
		class TestProblemsEdit {
			protected $tproblems;
			
			public function __construct($tproblems) {
				$this->tproblems = $tproblems;
			}
			
			public function render($selectTest = null, $selectProblem = null) {
				$update = true;
				if (!isset($this->tproblems)) {
					$this->tproblems = new TestProblems();
					$update = false;
				}
				?>
				<form method="POST" class="centering"
					action="./controller/TestProblemsController.php">
					<table class="formTable">
						<tr>
							<td><label for="tpb_questionNumber">Número da Questão</label>:</td>
							<td><input name="tpb_questionNumber" type="text" size=30 class="notempty integer"
								value="<?php echo @$this->tproblems->get("tpb_questionNumber"); ?>" />*</td>
						</tr>
						<tr>
							<td><label for="tpb_weight">Peso da Questão</label>:</td>
							<td><input name="tpb_weight" type="text" size=30 class="notempty number"
								value="<?php echo @$this->tproblems->get("tpb_weight"); ?>" />*</td>
						</tr>
						<?php if ($update !== true) { ?>
						<tr>
							<td><label for="tpb_tst_id">Prova</label>:</td>
							<td>
								<?php
								$select = new TestSelect();
								$select->render("tpb_tst_id", $selectTest, "notempty");
								?> *
							</td>
						</tr>
						<tr>
							<td><label for="tpb_prb_id">Problema</label>:</td>
							<td>
								<?php
								$select = new ProblemSelect();
								$select->render("tpb_prb_id", $selectProblem, "notempty");
								?> *
							</td>
						</tr>
						<?php } ?>
					</table>
					<?php if ($update === true) { ?>
						<input type="hidden" name="tpb_tst_id" value="<?php echo @$this->tproblems->get("tpb_tst_id"); ?>" />
						<input type="hidden" name="tpb_prb_id" value="<?php echo @$this->tproblems->get("tpb_prb_id"); ?>" />
					<?php } ?>
					<input type="hidden" name="_action" value="<?php echo ($update === true ? "update":"save"); ?>" />
					<input type="submit" value="Salvar" />
					<input type="reset" value="Limpar Campos" />
				</form>
				<?php
			}
		}
	}
?>