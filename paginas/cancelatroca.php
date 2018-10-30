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
		if($id == $dados['id_pessoa1']){
			$idp = $dados['id_pessoa2'];
		}
		if($id == $dados['id_pessoa2']){
			$idp = $dados['id_pessoa1'];
		}		
		
		$sql = "select pessoa.nome from pessoa where id_pessoa = $id";
		$result = mysqli_query($con, $sql) or die(mysqli_error($con));
		$dados = mysqli_fetch_assoc($result);
		$nome = $dados['nome'];
		
		$sql = "insert into notificacoes(texto,tipo,id_pessoa) values ('$nome cancelou a troca','cancela','$idp')";
		mysqli_query($con, $sql) or die(mysqli_error($con));
		
		$sql = "delete from troca where id_troca = $_GET[id]";
		mysqli_query($con, $sql) or die(mysqli_error($con));
		
		
		
		
		
	}	
	
	header("Location:perfil.php");

?>