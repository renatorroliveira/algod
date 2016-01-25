<?php
	if (!isset($EVALUATIONCASEEDIT_INCLUDED)) {
		$EVALUATIONCASEEDIT_INCLUDED = true;
		
		include "./model/EvaluationCase.php";
		include "./view/ProblemSelect.php";
		
		class EvaluationCaseEdit {
			protected $ecase;
			
			public function __construct($ecase) {
				$this->ecase = $ecase;
			}
			
			public function render($selectProblem = null) {
				$update = true;
				if (!isset($this->ecase)) {
					$this->ecase = new EvaluationCase();
					$update = false;
				}
				?>
				<form method="POST" class="centering"
					action="./controller/EvaluationCaseController.php">
					<table class="formTable">
						<tr>
							<td><label for="evc_inputs">Entradas</label>:</td>
							<td><textarea name="evc_inputs" rows=5 cols=40 class="notempty"
								title="Inserir as entradas separadas por '&'. S�o permitidas quebras de linha."
								><?php echo @$this->ecase->get("evc_inputs"); ?></textarea>*</td>
						</tr>
						<tr>
							<td><label for="evc_inputsDataTypes">Tipos de Dados das Entradas</label>:</td>
							<td><textarea name="evc_inputsDataTypes" rows=5 cols=40 class="notempty"
								title="Inserir os tipos de dados (do C++) das entradas separados por '&'. N�O s�o permitidas quebras de linha ou espa�os desnecess�rios."
								><?php echo @$this->ecase->get("evc_inputsDataTypes"); ?></textarea>*</td>
						</tr>
						<tr>
							<td><label for="evc_outputs">Sa�das</label>:</td>
							<td><textarea name="evc_outputs" rows=5 cols=40 class="notempty"
								title="Inserir as sa�das separadas por '&'. S�o permitidas quebras de linha."
								><?php echo @$this->ecase->get("evc_outputs"); ?></textarea>*</td>
						</tr>
						<tr>
							<td><label for="evc_outputsDataTypes">Tipos de Dados das Sa�das</label>:</td>
							<td><textarea name="evc_outputsDataTypes" rows=5 cols=40 class="notempty"
								title="Inserir os tipos de dados (do C++) das sa�das separados por '&'. N�O s�o permitidas quebras de linha ou espa�os desnecess�rios."
								><?php echo @$this->ecase->get("evc_outputsDataTypes"); ?></textarea>*</td>
						</tr>
						<tr>
							<td><label for="evc_prb_id">Problema</label>:</td>
							<td>
								<?php
								$select = new ProblemSelect();
								if ($update === true)
									$select->render("evc_prb_id", $this->ecase->get("evc_prb_id"), "notempty");
								else
									$select->render("evc_prb_id", $selectProblem, "notempty");
								?> *
							</td>
						</tr>
					</table>
					<?php if ($update === true) { ?>
						<input type="hidden" name="evc_id" value="<?php echo @$this->ecase->get("evc_id"); ?>" />
					<?php } ?>
					<input type="hidden" name="_action" value="save" />
					<input type="submit" value="Salvar" />
					<input type="reset" value="Limpar Campos" />
				</form>
				<?php
			}
		}
	}
?>