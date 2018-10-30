<!doctype html>
<?php
	if(session_status()!=PHP_SESSION_ACTIVE) session_start();
	require 'conexao.php';
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	if(!isset($_SESSION['user_logado'])){
		header('Location: login.php');	
	}

	
	$sql = "select id_pessoa from pessoa where email = '$_SESSION[user_logado]'";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$id = $dados['id_pessoa'];
	

?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!-- 
    <style>
        .btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:visited {
            background-color: #7900c4 !important;
            #590072
        }
    </style>
    -->
  </head>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000e8e;">
  <a class="navbar-brand" href="#">Hermit</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav ml-auto w-100 justify-content-end">
      <li class="nav-item">
        <a class="nav-link" href="https://goo.gl/forms/tUKD7RtTPgoFvTxK2">Questionário</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="perfil.php">Perfil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="search.php">Busca</a>
      </li>
      <li class="nav-item dropdown">
          
          <?php 
          
          	    $sql = "select count(id_notificacoes) from notificacoes where id_pessoa = $id";
	            $result = mysqli_query($con, $sql) or die(mysqli_error($con));
            	$dados = mysqli_fetch_assoc($result);
          ?>
          
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Notificações (<?php echo $dados['count(id_notificacoes)']?>)
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
           <?php
                    	$sql = "select * from notificacoes where id_pessoa = $id";
	                    $result = mysqli_query($con, $sql) or die(mysqli_error($con));
                    	$dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
           
    				
    				if($dados != null){
    					foreach($dados as $d){
    						echo "<a class='dropdown-item' href='limpanot.php?id=$d[id_notificacoes]'>$d[texto]</a>";
    					}
    				}
    				else{
    					echo "<a class='dropdown-item disabled' href='#'>Você não possui notificacoes</a>";
    				}
    			?> 
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Sair</a>
      </li>
    </ul>
  </div>
</nav>