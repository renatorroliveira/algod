<?php
	if (!isset($TESTEDIT_INCLUDED)) {
		$TESTEDIT_INCLUDED = true;
		
		@include "../model/Test.php";
		@include "./model/Test.php";
		@include "./view/DisciplineSelect.php";
		
		class TestEdit {
			protected $test;
			
			public function __construct($test) {
				$this->test = $test;
			}
			
			public function render() {
				$update = true;
				if (!isset($this->test)) {
					$this->test = new Test();
					$update = false;
				}
				@$enableLogging = $this->test->get("tst_enableLogging");
				?>
				<form method="POST" class="centering"
					action="./controller/TestController.php">
					<table class="formTable">
						<tr>
							<td><label for="tst_title">Título da Prova</label>:</td>
							<td><input name="tst_title" type="text" size=30 class="notempty"
								value="<?php echo @$this->test->get("tst_title"); ?>" /> *</td>
						</tr>
						<tr>
							<td><label for="tst_description">Descrição/Instruções</label>:</td>
							<td><textarea name="tst_description" rows=8 cols=60 class="notempty"
								><?php echo @$this->test->get("tst_description"); ?></textarea>*</td>
						</tr>
						<tr>
							<td><label for="tst_password">Senha da Prova</label>:</td>
							<td><input name="tst_password" type="password" size=30 class="password" /> *</td>
						</tr>
						<tr>
							<td><label for="passwordconfirm">Confirmar Senha</label>:</td>
							<td><input name="passwordconfirm" type="password" size=30 class="passwordconfirm" /> *</td>
						</tr>
						<tr>
							<td><label for="tst_visibleSince">Visível a partir de</label>:</td>
							<td>
								<input name="tst_visibleSince" type="text" size=30 class="notempty datetime"
									value="<?php echo @Model::parseSQLToInputDate($this->test->get("tst_visibleSince")); ?>" /> 
								* (dd/mm/yyyy hh:mm:ss)
							</td>
						</tr>
						<tr>
							<td><label for="tst_visibleUntil">Visível até</label>:</td>
							<td>
								<input name="tst_visibleUntil" type="text" size=30 class="notempty datetime"
									value="<?php echo @Model::parseSQLToInputDate($this->test->get("tst_visibleUntil")); ?>" /> 
								* (dd/mm/yyyy hh:mm:ss)
							</td>
						</tr>
						<tr>
							<td><label for="tst_openSince">Aberta a partir de</label>:</td>
							<td>
								<input name="tst_openSince" type="text" size=30 class="notempty datetime"
									value="<?php echo @Model::parseSQLToInputDate($this->test->get("tst_openSince")); ?>" /> 
								* (dd/mm/yyyy hh:mm:ss)
							</td>
						</tr>
						<tr>
							<td><label for="tst_openUntil">Aberta até</label>:</td>
							<td>
								<input name="tst_openUntil" type="text" size=30 class="notempty datetime"
									value="<?php echo @Model::parseSQLToInputDate($this->test->get("tst_openUntil")); ?>" /> 
								* (dd/mm/yyyy hh:mm:ss)
							</td>
						</tr>
						<tr>
							<td><label for="tst_maxTrials">Número Máximo de Tentativas</label>:</td>
							<td><input name="tst_maxTrials" type="text" size=30 class="notempty integer"
								value="<?php echo @$this->test->get("tst_maxTrials"); ?>" /> * Ex: 2</td>
						</tr>
						<tr>
							<td><label for="tst_scoreAttenuationPerTrial">Atenuação da Nota por Tentativa</label>:</td>
							<td><input name="tst_scoreAttenuationPerTrial" type="text" size=30 class="notempty number"
								value="<?php echo @$this->test->get("tst_scoreAttenuationPerTrial"); ?>" /> * Ex: 0.5</td>
						</tr>
						<tr>
							<td><label for="tst_enableLogging">Habilitar Log da Prova</label>:</td>
							<td>
								<input id="disableLogging" name="tst_enableLogging" type="radio" value="0"
									<?php echo (!isset($enableLogging) || ($enableLogging == 0) ? "checked":""); ?> />
									<label for="disableLogging">Não</label>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<input id="enableLogging" name="tst_enableLogging" type="radio" value="1"
									<?php echo (isset($enableLogging) && ($enableLogging != 0) ? "checked":""); ?> />
									<label for="enableLogging">Sim</label>
							</td>
						</tr>
						<tr>
							<td><label for="tst_dsc_id">Disciplina</label>:</td>
							<td>
								<?php
								$select = new DisciplineSelect();
								if ($update === true)
									$select->render("tst_dsc_id", $this->test->get("tst_dsc_id"), "notempty");
								else
									$select->render("tst_dsc_id", null, "notempty");
								?> *
							</td>
						</tr>
					</table>
					<?php if ($update === true) { ?>
						<input type="hidden" name="tst_id" value="<?php echo @$this->test->get("tst_id"); ?>" />
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