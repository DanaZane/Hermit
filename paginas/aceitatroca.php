<?php

	session_start();
	require 'conexao.php';	
	$sql = "select id_pessoa from pessoa where email = '$_SESSION[user_logado]'";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$id = $dados['id_pessoa'];
	
	$sql = "select * from troca where troca.id_troca = $_GET[id] and (troca.id_pessoa1 = $id or troca.id_pessoa2 = $id)";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	if($dados == null){
		header("Location:perfil.php");
	}
	else{
		if($dados['id_pessoa1'] == $id){
			$sql = "update troca set status1 = 'Aceito' where id_troca = $_GET[id]";
			mysqli_query($con, $sql) or die(mysqli_error($con));
		}
		if($dados['id_pessoa2'] == $id){
			$sql = "update troca set status2 = 'Aceito' where id_troca = $_GET[id]";
			mysqli_query($con, $sql) or die(mysqli_error($con));
		}
	}
	
	header("Location:perfil.php");
	
?>