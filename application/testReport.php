<?php
	if (!isset($_GET['tpb_tst_id']))
		die("<b>Forbidden.</b>");
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Relatório de Notas da Prova</h1>

<button id="testreport-bt-export" onClick="location.assign('testReportCSVExport.php?tpb_tst_id=<?php echo $_GET['tpb_tst_id']; ?>');">
	Exportar para CSV
</button>

<div id="testreport-ct-list" class="centering">
	<?php
		$selectedTest = $_GET['tpb_tst_id'];
		include "view/TestReportList.php";
		$wdg = new TestReportList();
		$wdg->render($selectedTest);
	?>
</div>

<?php
	include "static/footer.php";
?>
