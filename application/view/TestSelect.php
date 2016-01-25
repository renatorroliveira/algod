<?php
	if (!isset($TESTSELECT_INCLUDED)) {
		$TESTSELECT_INCLUDED = true;
		
		include "./controller/TestBS.php";
		
		class TestSelect {
			protected $bs;
			protected $tests;
			
			public function __construct() {
				$this->bs = new TestBS(null);
				$this->tests = $this->bs->findNotDeleted(null);
			}
			
			public function render($fieldName, $selected, $fieldClass = "", $fieldId = 'tests-select') {
				?>
				<select name="<?php echo $fieldName; ?>" id="<?php echo $fieldId; ?>" class="<?php echo $fieldClass; ?>">
					<option value="">-- Selecione um Problema --</option>
					<?php
						if (count($this->tests) > 0) {
							foreach ($this->tests as $t => $test) {
								?>
								<option value="<?php echo $test->get("tst_id"); ?>" <?php
								if (isset($selected) && ($selected == $test->get("tst_id"))) echo " selected "; ?>>
									(<?php echo $test->getForeignModel('tst_dsc_id')->get('dsc_code'); ?>)
									<?php echo $test->get("tst_title"); ?>
								</option>
								<?php
							}
						}
					?>
				</select>
				<?php
			}
		}
	}
?>