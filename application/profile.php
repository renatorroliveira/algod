<?php
	$__REQUIRED_ACCESS_LEVEL__ = 1;
	$__REDIRECT_TO__ = "./";
	include "static/header.php";
?>

<h1>Dados de Usu�rio</h1>

<p>
	Nesta janela voc� pode alterar/visualizar os dados do seu cadastro.
</p>

<?php
	include "view/UserEdit.php";
	$widget = new UserEdit(UserSession::getInstance()->getUser());
	$widget->render();
?>

<?php
	include "static/footer.php";
?>
