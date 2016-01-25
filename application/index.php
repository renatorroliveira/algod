<?php
	include "static/header.php";
?>

<h1>Bem vindo ao AlGod!</h1>

<p>Encontre a prova que deseja fazer e clique em Fazer a Prova.</p>
<p>
Uma senha de acesso será solicitada para realizar cada prova.
Essa senha será fornecida pelo professor da disciplina no momento
em que a prova iniciar.
</p>


<h3>Provas em Aberto:</h3>

<?php
	if (UserSession::getInstance()->isLogged() === true) {
		include "view/TestOpenedList.php";
		$wdg = new TestOpenedList();
		$wdg->renderNotDeleted();
	} else
		echo "<br /><i>Faça login para ver as provas.</i><br />";
?>
<br />
<?php
	include "static/footer.php";
?>
