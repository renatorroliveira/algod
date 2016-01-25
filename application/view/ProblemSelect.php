<?php
	if (!isset($PROBLEMSELECT_INCLUDED)) {
		$PROBLEMSELECT_INCLUDED = true;
		
		include "./controller/ProblemBS.php";
		
		class ProblemSelect {
			protected $bs;
			protected $problems;
			
			public function __construct() {
				$this->bs = new ProblemBS(null);
				$this->problems = $this->bs->findNotDeleted(null);
			}
			
			public function render($fieldName, $selected, $fieldClass = "", $fieldId = 'problems-select') {
				?>
				<select name="<?php echo $fieldName; ?>" id="<?php echo $fieldId; ?>" class="<?php echo $fieldClass; ?>">
					<option value="">-- Selecione um Problema --</option>
					<?php
						if (count($this->problems) > 0) {
							foreach ($this->problems as $p => $problem) {
                                                                
                                                                echo '<option value="'.$problem->get("prb_id").'"';
                                                                
								if (isset($selected) && ($selected == $problem->get("prb_id"))) {
                                                                    echo " selected ";
                                                                }
                                                                
                                                                echo '>('.$problem->getForeignModel('prb_dsc_id')->get('dsc_code')
                                                                        .' - ND='.$problem->get('prb_difficultyLevel').') '
                                                                        .$problem->get('prb_title')
                                                                        .'</option>'.PHP_EOL;
							}
						}
					?>
				</select>
				<?php
			}
		}
	}
?>

							