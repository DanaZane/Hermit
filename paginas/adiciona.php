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
	$status = 'troca';
	
	$sql = "insert into livro(titulo,autor,isbn,editora,id_pessoa,status) values ('$titulo','$autor','$isbn','$editora',$idpessoa,'$status')";
	mysqli_query($con, $sql);		
	
	$sql = "select * from livro where status = 'desejado' and id_pessoa != $idpessoa";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_all($result,MYSQLI_ASSOC);
	if($dados != null){		
		foreach($dados as $d){			
			if ( strtolower($d['titulo']) == strtolower($titulo) ){
				$sql = "insert into notificacoes(texto,tipo,id_pessoa) values ('O livro \"$d[titulo]\" está disponível para troca','desejado','$d[id_pessoa]')";
				mysqli_query($con, $sql) or die(mysqli_error($con));
			}
		}
	}
	
	$_SESSION['avisos'] = 'Adicionado com sucesso!';
	
	header('Location:search.php');
?>