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

?>