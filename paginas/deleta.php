<?php
	session_start();
	require 'conexao.php';
	$sql = "select id_pessoa from pessoa where email = '$_SESSION[user_logado]'";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$id_pessoa = $dados['id_pessoa'];
	$id_livro = $_GET['id'];
	$sql = "select * from livro where id_livro = $id_livro";	
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	if($dados != null && $dados['id_pessoa'] == $id_pessoa){
		$sql = "delete from livro where id_livro = $id_livro";
		$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	}
	header("Location:perfil.php");
?>