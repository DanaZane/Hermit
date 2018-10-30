<?php
	session_start();
	require 'conexao.php';
	$sql = "select id_pessoa from pessoa where email = '$_SESSION[user_logado]'";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$id = $dados['id_pessoa'];
	
	//Pega o id do segundo usuario
	$sql = "select id_pessoa from livro where id_livro = $_GET[id2]";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$id2 = $dados['id_pessoa'];
	
	//Checa se os livros não estão em trocas
	$sql = "select id_livro1 from troca where id_livro1 = $_GET[id1] OR id_livro2 = $_GET[id1]";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	if($dados == null){
		header("Location:perfil.php");
	}
	
	$sql = "select id_livro1 from troca where id_livro2 = $_GET[id2] OR id_livro2 = $_GET[id2]";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	if($dados == null){
		header("Location:perfil.php");
	}
	
	//Checa se o livro 1 é do usuario logado e está disponivel para troca
	$sql = "select * from livro where id_livro = $_GET[id1] and id_pessoa=$id and id_livro not in (select id_livro from livro where status ='desejado' and id_pessoa = $id)";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	if($dados == null){
		header("Location:perfil.php");
	}

	//Checa se o livro 2 não é do usuário logado
	$sql = "select * from livro where id_livro = $_GET[id2] and id_pessoa = $id2 and id_livro not in (select id_livro from livro where status ='desejado' and id_pessoa = $id2)";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	if($dados == null){
		header("Location:perfil.php");
	}	

	$sql = "insert into troca(id_livro1,id_livro2,id_pessoa1,id_pessoa2,status1,status2) values ($_GET[id1],$_GET[id2],$id,$id2,'Aceito','Pendente')";
	mysqli_query($con, $sql) or die(mysqli_error($con));
	
	$sql = "select nome from pessoa where id_pessoa = $id";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$nome = $dados['nome'];
	
	$sql = "insert into notificacoes(texto,tipo,id_pessoa) values ('Você recebeu uma nova troca do usuário $nome','troca','$id2')";
	mysqli_query($con, $sql) or die(mysqli_error($con));
	
	header("Location:perfil.php");
?>