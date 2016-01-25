<?php
	$__LOGIN_PAGE__ = true;
	$__REDIRECT_TO__ = "./";
	include "static/header.php";
?>

<h1>Login</h1>

<?php
	include "view/UserLogin.php";
	$loginWidget = new UserLogin();
	$loginWidget->render();
	if (isset($_GET['failed'])) {
		include "view/ErrorBox.php";
		ErrorBox::renderError("Os dados fornecidos para login são inválidos.");
	} else if (isset($_GET['recoveryok'])) {
                include "view/ErrorBox.php";
		ErrorBox::renderError("E-mail enviado com sucesso!");
        }else if (isset($_GET['recoveryfailed'])){
                include "view/ErrorBox.php";
		ErrorBox::renderError("Erro ao enviar e-mail. Contato um professor.");
        }
?>

<?php
	include "static/footer.php";
?>
