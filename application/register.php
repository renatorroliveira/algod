<?php
	include "static/header.php";
?>

<h1>Cadastro de Novo Usu�rio</h1>

<p>� necess�rio possuir um usu�rio para realizar as provas. Ainda assim voc� precisa de um c�digo de acesso para cada prova, que ser�
	fornecido pelo professor da disciplina no momento da realiza��o da prova.</p>
<p>Para a correta avalia��o pelos professores � <b>OBRIGAT�RIA</b> a inser��o dos <b>DADOS VERDADEIROS E
	CORRETAMENTE DIGITADOS</b> neste formul�rio. Portanto, aten��o ao preencher o cadastro.</p>
<p><b>TODOS OS CAMPOS S�O OBRIGAT�RIOS!</b></p>

<?php
	include "./view/UserEdit.php";
	
	$form = new UserEdit(null);
	$form->render();
?>

<?php
	include "static/footer.php";
?>
