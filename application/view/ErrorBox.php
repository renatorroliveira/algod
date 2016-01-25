<?php
	if (!isset($ERRORBOX_INCLUDED)) {
		$ERRORBOX_INCLUDED = true;
		
		class ErrorBox {
			public static function renderError($message) {
				?>
				<div class="ui-state-error ui-corner-all" style="display: inline-block;">
					<p>
						<span class="ui-icon ui-icon-alert" style="float: left; margin-rigth: 3px; padding: 0px;"></span>
						<?php
						echo $message;
						?>
					</p>
				</div>
				<?php
			}
			protected function __construct() {
				// Desativando construtor.
			}
		}
	}
?>