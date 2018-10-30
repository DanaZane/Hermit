<?php
	session_start();
	require 'conexao.php';
	
	$json = file_get_contents('https://www.googleapis.com/books/v1/volumes/'.$_GET['id']);	
	$data = json_decode($json, true);
	
	$titulo = $data['volumeInfo']['title'];
	
	$titulo = preg_replace("/'/", '', $titulo);
	
	$autor = @implode(", ", $data['volumeInfo']['authors']);
	$isbn = $data['volumeInfo']['industryIdentifiers'][0]['identifier'];
	$editora =  $data['volumeInfo']['publisher'];
	
	$sql = "select id_pessoa from pessoa where pessoa.email = '$_SESSION[user_logado]'";	
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));	
	
	$linha = mysqli_fetch_assoc($result);
	$idpessoa = $linha['id_pessoa'];	
	$status = 'desejado';
	
	$sql = "insert into livro(titulo,autor,isbn,editora,id_pessoa,status) values ('$titulo','$autor','$isbn','$editora',$idpessoa,'$status')";
	mysqli_query($con, $sql);		
	
	header('Location:search.php');
?>