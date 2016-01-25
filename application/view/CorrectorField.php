<?php 
	if (!isset($CORRECTORFIELD_INCLUDED)) {
		$CORRECTORFIELD_INCLUDED = true;
		
		class CorrectorField {
			
			public static function render($testId, $fieldName, $fieldClass="file", $fieldId="corrector-file") {
				if (!isset($testId)) {
					$existent = false;
				} else {
					$correctorfile = "algod".$testId;
					$base = "correctors/";
					$existent = file_exists($base.$correctorfile);
					if ($existent === true)
						$filename = $base.$correctorfile;
				}
				if ($existent === true) {
				?>
					<a href="<?php echo $filename; ?>">
						<?php echo $correctorfile; ?>
					</a>&nbsp;&nbsp;
					<span id="corrector-file-path-<?php echo $testId; ?>"></span>
					<button id="corrector-file-bt-<?php echo $testId; ?>">
						Substituir Executável do Corretor
					</button>
				<?php
				} else {
				?>
					<span id="corrector-file-path-<?php echo $testId; ?>"></span>
					<button id="corrector-file-bt-<?php echo $testId; ?>">
						Enviar Executável do Corretor
					</button>
				<?php
				}
				?>
				<input type="file" style="display:none;"
					id="<?php echo $fieldId; ?>"
					class="<?php echo $fieldClass; ?>"
					name="<?php echo $fieldName; ?>"
				/>
				<script>
				$(document).ready(function(){
					$("#corrector-file-bt-<?php echo $testId; ?>").click(function(){
						$("#<?php echo $fieldId; ?>").click();
					});
					$("#<?php echo $fieldId; ?>").change(function() {
						if ($(this).val() != "") {
							$("#corrector-file-path-<?php echo $testId; ?>").html($(this).val());
						}
					});
				});
				</script>
				<?php
			}
		}
		
	}
?>