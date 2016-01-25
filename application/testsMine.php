<?php
	// Nelson Haha! Haha! 
//	header("Location: /algod/nelson.html");


	$__REQUIRE_ACCESS_LEVEL__ = 1;
	$__REDIRECT_TO__ = './login.php';
	include "static/header.php";
?>

<h1>Minhas Provas</h1>

<?php

	include "view/UserTestsList.php";
	$wdg = new UserTestsList(UserSession::getInstance()->getUser());
	$wdg->render();

?>

<?php
	include "static/footer.php";
?>
