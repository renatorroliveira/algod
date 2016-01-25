<?php
	if (!isset($DISCIPLINESELECT_INCLUDED)) {
		$DISCIPLINESELECT_INCLUDED = true;
		
		include "./controller/DisciplineBS.php";
		
		class DisciplineSelect {
			protected $bs;
			protected $disciplines;
			
			public function __construct() {
				$this->bs = new DisciplineBS(null);
				$qbuilder = new QueryBuilder('discipline');
				$qbuilder->addOrder("dsc_name", QueryBuilder::$ASC);
				$qbuilder->addOrder("dsc_code", QueryBuilder::$ASC);
				$this->disciplines = $this->bs->findNotDeleted($qbuilder);
			}
			
			public function render($fieldName, $selected, $fieldClass = "", $fieldId = 'disciplines-select') {
				?>
				<select name="<?php echo $fieldName; ?>" id="<?php echo $fieldId; ?>" class="<?php echo $fieldClass; ?>">
					<option value="">-- Selecione uma Disciplina --</option>
					<?php
						if (count($this->disciplines) > 0) {
							foreach ($this->disciplines as $d => $discipline) {
								?>
								<option value="<?php echo $discipline->get("dsc_id"); ?>" <?php
								if (isset($selected) && ($selected == $discipline->get("dsc_id"))) echo " selected "; ?>>
									<?php echo $discipline->get("dsc_name"); ?>
									(<?php echo $discipline->get("dsc_code"); ?>)
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