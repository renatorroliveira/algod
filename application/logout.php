<?php
	@session_start();
	unset($_SESSION['logged']);
	unset($_SESSION['user']);
	@session_write_close();
	header("Location: ./");
?>