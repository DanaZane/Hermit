<?php
	session_start();
	require 'conexao.php';
	
	$sql = "select id_pessoa from pessoa where email = '$_SESSION[user_logado]'";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$idp = $dados['id_pessoa'];

	$sql = "select * from notificacoes where id_notificacoes = $_GET[id] and id_pessoa = $idp";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	if($dados == null){
		header("Location:perfil.php");
	}
	else{
	    $tipo = $dados['tipo'];
	    $texto = $dados['texto'];
		$sql = "delete from notificacoes where id_pessoa = $idp and id_notificacoes = $_GET[id]";
		mysqli_query($con, $sql) or die(mysqli_error($con));
	}
	if($tipo == 'desejado'){
	    preg_match('/"([^"]+)"/', $texto, $m);
	    $texto = $m[0];
	    $texto = str_replace('"','',$texto);
	    header("Location:search.php?titulo=$texto&autor=&isbn=&option=troca&search=");
	}
	else{
	    header("Location:perfil.php");
	}
	
?>