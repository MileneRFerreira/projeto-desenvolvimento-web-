<?php
	$host = 'localhost';
	$usuario = 'root';
	$senha = 'wsbws8g5';
	$banco = 'forum';

	$con = new mysqli($host, $usuario, $senha, $banco);

	if(mysqli_connect_errno()){
		exit('Erro ao conectar-se '.mysqli_connect_error());
	}
?>