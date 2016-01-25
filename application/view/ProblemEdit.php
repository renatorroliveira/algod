<?php
	if (!isset($PROBLEMEDIT_INCLUDED)) {
		$PROBLEMEDIT_INCLUDED = true;
		
		@include "../model/Problem.php";
		@include "./model/Problem.php";
		@include "./view/DisciplineSelect.php";
		
		class ProblemEdit {
			protected $problem;
			
			public function __construct($problem) {
				$this->problem = $problem;
			}
			
			public function render() {
				$update = true;
				if (!isset($this->problem)) {
					$this->problem = new Problem();
					$update = false;
				}
				?>
				<form method="POST" class="centering"
					action="./controller/ProblemController.php">
					<table class="formTable">
						<tr>
							<td><label for="prb_title">Título do Problema</label>:</td>
							<td><input name="prb_title" type="text" size=30 class="notempty"
								value="<?php echo @$this->problem->get("prb_title"); ?>" /> *</td>
						</tr>
						<tr>
							<td><label for="prb_description">Descrição/Instruções</label>:</td>
							<td><textarea name="prb_description" rows=8 cols=60 class="notempty"
								><?php echo @$this->problem->get("prb_description"); ?></textarea>*</td>
						</tr>
						<tr>
							<td><label for="prb_difficultyLevel">Nível de Dificuldade</label>:</td>
							<td><input name="prb_difficultyLevel" type="text" size=30 class="notempty integer"
								value="<?php echo @$this->problem->get("prb_difficultyLevel"); ?>" /> *</td>
						</tr>
						<tr>
							<td><label for="prb_dsc_id">Disciplina</label>:</td>
							<td>
								<?php
								$select = new DisciplineSelect();
								if ($update === true)
									$select->render("prb_dsc_id", $this->problem->get("prb_dsc_id"), "notempty");
								else
									$select->render("prb_dsc_id", null, "notempty");
								?> *
							</td>
						</tr>
					</table>
					<?php if ($update === true) { ?>
						<input type="hidden" name="prb_id" value="<?php echo @$this->problem->get("prb_id"); ?>" />
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