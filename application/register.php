<?php
	include "static/header.php";
?>

<h1>Cadastro de Novo Usuário</h1>

<p>É necessário possuir um usuário para realizar as provas. Ainda assim você precisa de um código de acesso para cada prova, que será
	fornecido pelo professor da disciplina no momento da realização da prova.</p>
<p>Para a correta avaliação pelos professores é <b>OBRIGATÓRIA</b> a inserção dos <b>DADOS VERDADEIROS E
	CORRETAMENTE DIGITADOS</b> neste formulário. Portanto, atenção ao preencher o cadastro.</p>
<p><b>TODOS OS CAMPOS SÃO OBRIGATÓRIOS!</b></p>

<?php
	include "./view/UserEdit.php";
	
	$form = new UserEdit(null);
	$form->render();
?>

<?php
	include "static/footer.php";
?>
