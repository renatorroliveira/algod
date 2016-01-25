<?php
	if (!isset($DISCIPLINEEDIT_INCLUDED)) {
		$DISCIPLINEEDIT_INCLUDED = true;
		
		@include "../model/Discipline.php";
		@include "./model/Discipline.php";
		
		class DisciplineEdit {
			protected $discipline;
			
			public function __construct($discipline) {
				$this->discipline = $discipline;
			}
			
			public function render() {
				$update = true;
				if (!isset($this->discipline)) {
					$this->discipline = new Discipline();
					$update = false;
				}
				?>
				<form method="POST" class="centering"
					action="./controller/DisciplineController.php">
					<table class="formTable">
						<tr>
							<td><label for="dsc_name">Nome da Disciplina</label>:</td>
							<td><input name="dsc_name" type="text" size=30 class="notempty"
								value="<?php echo @$this->discipline->get("dsc_name"); ?>" /> *</td>
						</tr>
						<tr>
							<td><label for="dsc_code">Código da Disciplina</label>:</td>
							<td><input name="dsc_code" type="text" size=30 class="notempty"
								value="<?php echo @$this->discipline->get("dsc_code"); ?>" /> *</td>
						</tr>
					</table>
					<?php if ($update === true) { ?>
						<input type="hidden" name="dsc_id" value="<?php echo @$this->discipline->get("dsc_id"); ?>" />
					<?php } ?>
					<input type="hidden" name="_action" value="save" />
					<input type="submit" value="Salvar" />
					<input type="reset" value="Limpar Campos" />
				</form>
				<?php
			}
			
			public function renderWithJSFieldsObject() {
				if (!isset($this->discipline)) {
					$this->discipline = new Discipline();
				}
				?>
				<div id="discipline-dynamic-ct" style="display:none;">
				<?php $this->render(); ?>
				</div>
				<script type="text/javascript">
				DisciplineEdit_form = {
					dialogCt: $("#discipline-dynamic-ct"),
					form: $("#discipline-dynamic-ct").find("form"),
					code: $("#discipline-dynamic-ct").find("input[name='dsc_code']"),
					name: $("#discipline-dynamic-ct").find("input[name='dsc_name']"),
					id: $("#discipline-dynamic-ct").find("input[name='dsc_id']")
				};
				console.log(DisciplineEdit_form);
				</script>
				<?php
			}
		}
	}
	$DISCIPLINEEDIT_INCLUDED = true;
?>