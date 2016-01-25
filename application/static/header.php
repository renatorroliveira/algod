<?php
	if(empty($_SERVER["HTTPS"]) || ($_SERVER["HTTPS"] !== "on")) {
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}

	// Inicializações gerais, incluindo sessão.
	include "controller/UserSession.php";
	$session = UserSession::getInstance();
	if (isset($__LOGIN_PAGE__) && ($__LOGIN_PAGE__ === true)) {
		if ($session->isLogged() === true) {
			header("Location: ".$__REDIRECT_TO__);
			die();
		}
	}
	if (isset($__REQUIRED_ACCESS_LEVEL__)) {
		if ($session->isLogged() === true) {
			if ($session->getAccessLevel() < $__REQUIRED_ACCESS_LEVEL__) {
				header("Location: ".$__REDIRECT_TO__);
				die();
			}
		} else {
			header("Location: ".$__REDIRECT_TO__);
			die();
		}
	}
	$today = new DateTime("now");
	$todaystring = $today->format("d/m/Y H:i");
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; ISO-8859-1" />
	<title>AlGod - Módulo Web</title>
	
	<link rel="stylesheet" type="text/css" href="../public/css/style.css" />
	<link rel="stylesheet" type="text/css" href="../public/css/jqueryui/jquery-ui-1.9.1.custom.prd.css" />
	
	<script src="../public/js/jquery-1.9.0.min.js"></script>
	<script src="../public/js/jquery-ui.min.js?v192"></script>
	<script src="../public/js/jquery.maskedinput.min.js"></script>
	<script src="../public/js/jquery.alphanumeric.js"></script>
	<script>
	$(document).ready(function() {
		$(document).tooltip();
		$("button, input[type='submit'], input[type='reset']").button();

		$("input.date").mask("99/99/9999").attr("title","Exemplo: 11/09/2001").tooltip();
		$("input.datetime").mask("99/99/9999 99:99:99").attr("title","Exemplo: 11/09/2001 10:17:37").tooltip();
		$("input.integer").numeric().attr("title","Exemplo: 2").tooltip();
		$("input.number").numeric({allow:"."}).attr("title","Exemplo: 0.5").tooltip();
		$("textarea").keypress(function(event) {
			if (event.ctrlKey && (event.which == 112)) {
				event.preventDefault();
				$("#data-view-dialog").html("<div class='data-view-box' style='width: 90%;'>"+$(this).val()+"</div>").dialog("open");
			}
		});

		$("textarea.cpp-code-view").attr("readonly","readonly");
		$("div.cpp-code-view").each(function() {
			var text = $(this).html();
			text.replace("&","&amp;");
			text.replace("<","&lt;");
			text.replace(">","&gt;");
			text.replace("\r","");

			// Keywords
			text.replace("float ","<b>float</b> ");
			text.replace("double ","<b>double</b> ");
			text.replace("int ","<b>int</b> ");
			text.replace("long ","<b>long</b> ");
			text.replace("if ","<b>if</b> ");
			text.replace("else ","<b>else</b> ");
			text.replace("for ","<b>for</b> ");
			text.replace("while ","<b>while</b> ");
			text.replace("return","<b>return</b>");
			text.replace("break","<b>break</b>");
			text.replace("using","<b>using</b>");
			text.replace("namespace","<b>namespace</b>");
			text.replace("#include","<b>#include</b>");

			text.replace(" ","&nbsp;");
			text.replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;");
			text.replace("\n","<br />");
			$(this).html(text);
		});
		
		$("#alert-dialog").dialog({
			title: "Aviso",
			width: "auto",
			height: "auto",
			modal: true,
			buttons: {
				Ok: function() {
					$(this).dialog("close");
				}
			}
		}).dialog("close");

		$("#data-view-dialog").dialog({
			title: "",
			width: 800,
			height: "auto",
			modal: true,
			buttons: {
				Ok: function() {
					$(this).dialog("close");
				}
			}
		}).dialog("close");
		
		$("form").submit(function() {
			var $validationText = "";
			$(this).find(".notempty").each(function() {
				if (($(this).val() == undefined) || ($(this).val() == "")) {
					$validationText += "<li>"+$("label[for='"+$(this).attr('name')+"']").html()+"</li>";
				}
			});
			if ($validationText != "") {
				$validationText = "<p>Os seguintes campos não podem estar vazios:</p><ul>"+$validationText+"</ul>";
				$("#alert-dialog").html($validationText).dialog({modal: true}).dialog("open");
				return false;
			} else {
				var $form = $(this);
				$form.find("input.passwordconfirm").each(function() {
					if ($(this).val() != $form.find("input.password").val()) {
						$validationText = "As senhas digitadas não conferem.";
					}
				});
				if ($validationText != "") {
					$("#alert-dialog").html($validationText).dialog({modal: true}).dialog("open");
					return false;
				} else
					return true;
			}
		});
	});
	function openDivAsDialog(id) {
		$("div#"+id).dialog({
			title: "",
			width: "auto",
			height: "auto",
			modal: true,
			buttons: {
				Ok: function() {
					$(this).dialog("close");
				}
			}
		}).dialog("open");
	};
	</script>
</head>
<body>
	<div id="alert-dialog" style="display:none;box-shadow: #444444;"></div>
	<div id="data-view-dialog" style="display:none;box-shadow: #444444;text-align:center;"></div>
	<div id="topct" class="ui-corner-bottom"><div id="banner">
		<h1>AlGod - Módulo de Provas Online</h1>
		<div style="display:inline-block;text-align:right;width: 100%;">
			<?php echo $todaystring;
			if (UserSession::getInstance()->isLogged() === true) {
				?> - <?php
				echo UserSession::getInstance()->getUser()->get('usr_name');
			} ?>
		</div>
		<div id="topmenu">
			<button id="menu-bt-home" onClick="location.assign('./');">Principal</button>
			<?php
				if (UserSession::getInstance()->isLogged() === true) {
					if (UserSession::getInstance()->getAccessLevel() > 2) { ?>
						<button id="menu-bt-problems" onClick="location.assign('./problems.php');">Problemas</button>
						<button id="menu-bt-tests" onClick="location.assign('./tests.php');">Provas</button>
					<?php } ?>
					<?php if (UserSession::getInstance()->getAccessLevel() > 4) { ?>
						<button id="menu-bt-disciplines" onClick="location.assign('./disciplines.php');">Disciplinas</button>
					<?php } ?>
					<button id="menu-bt-profile" onClick="location.assign('./testsMine.php');">Minhas Provas</button>
					<button id="menu-bt-profile" onClick="location.assign('./profile.php');">Perfil</button>
					<button id="menu-bt-logout" onClick="location.assign('./logout.php');">Sair</button>
					<?php
				} else {
					?>
					<button id="menu-bt-register" onClick="location.assign('./register.php');">Cadastrar-se</button>
					<button id="menu-bt-login" onClick="location.assign('./login.php');">Login</button>
					<?php
				}
			?>
		</div>
	</div></div><div id="contentct"><div id="content">
