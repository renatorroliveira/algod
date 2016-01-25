<?php
	if (!isset($TESTOPENEDLIST_INCLUDED)) {
		$TESTOPENEDLIST_INCLUDED = true;
		
		@include "../controller/TestBS.php";
		@include "./controller/TestBS.php";
		@include "../view/TestEdit.php";
		@include "./view/TestEdit.php";
		
		class TestOpenedList {
			
			public function __construct() {
				
			}
			
			public function renderNotDeleted() {
				$bs = new TestBS(null);
				$qbuilder = new QueryBuilder('test');
				$today = new DateTime('now');
				$qbuilder->addLessEqual("tst_visibleSince", $today->format(Model::$SQL_DATE_FORMAT));
				$qbuilder->addGreaterEqual("tst_visibleUntil", $today->format(Model::$SQL_DATE_FORMAT));
				$qbuilder->addOrder('tst_openSince', QueryBuilder::$DESC);
				$qbuilder->addOrder('tst_visibleSince', QueryBuilder::$DESC);
				$qbuilder->addOrder('tst_title', QueryBuilder::$ASC);
				$qbuilder->addOrder('tst_createdAt', QueryBuilder::$DESC);
				$tests = $bs->findNotDeleted($qbuilder);
				
				if (count($tests) <= 0) {
				?>
					<br/><i>Nenhuma prova aberta no momento.</i><br />
				<?php
				} else {
					foreach ($tests as $t => $test) { ?>
						<div class="data-view-box">
							<h3><?php echo $test->getForeignModel('tst_dsc_id')->get('dsc_code'); ?> -
							<?php echo $test->get('tst_title'); ?></h3>
							<p><b>INFORMAÇÕES:</b><br />
							<b>Visível de:</b>
							<?php echo Model::parseSQLToInputDate($test->get('tst_visibleSince')); ?><br />
							<b>Visível até:</b>
							<?php echo Model::parseSQLToInputDate($test->get('tst_visibleUntil')); ?><br />
							<b>Aberta de:</b>
							<?php echo Model::parseSQLToInputDate($test->get('tst_openSince')); ?><br />
							<b>Aberta até:</b>
							<?php echo Model::parseSQLToInputDate($test->get('tst_openUntil')); ?><br />
							<b>Descrição:</b><br />
							<?php echo $test->get('tst_description'); ?></p>
							<form method="POST" class="centering" action="encodePassword.php">
								<table class="formTable">
									<tr>
										<td>Senha de Acesso:</td>
										<td><input type="password" name="tst_password" size=40 /></td>
									</tr>
								</table>
								<input type="hidden" value="<?php echo $test->get("tst_id"); ?>" name="tst_id" />
								<input type="submit" value="Abrir Prova" />
							</form>
						</div><br />
				<?php }
				}
			}
		}
	}
	
?>