<!doctype html>

<?php	
	if(session_status()!=PHP_SESSION_ACTIVE) session_start();
	
	if(isset($_SESSION['user_logado']) && $_SESSION['user_logado'] != null){
		header("Location:perfil.php");
	}
?>

<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	
</head>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000e8e;">
  <a class="navbar-brand" href="#">Hermit</a>
</nav>

<div class="container-fluid">
    <div class="row  mt-3">
        <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="mx-auto">
    		      <form method="post" action="login.php">
        		  <div class="form-group">
        			<label for="email">Email</label>
        			<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" ></input>
        		  </div>
        		  <div class="form-group">
        			<label for="senha">Senha</label>
        			<input type="password" class="form-control" id="senha" name="senha"></input>
        		  </div>
        		  <div>
        			<div class="float-left">
        				<a href="signup.php" class="btn btn-primary" role="button">Sign Up</a>
        			</div>	
        			<div class="float-right">
        				<button type="submit" class="btn btn-primary" name="login" id="login">Log in</button>
        			</div>
        		  </div> 
        		</form>
            </div>
        </div>
        <div class="col-md-4"></div>
        
    </div>
   
</div>


<?php 

function logar(){	
	if($_POST['email'] != '' && $_POST['senha'] != ''){
	    require 'conexao.php';
		$usuario = mysqli_real_escape_string($con, $_POST['email']);
		$senha = mysqli_real_escape_string($con, $_POST['senha']);
		$sql = "SELECT * FROM pessoa WHERE pessoa.email = '$usuario'"; 
		$result = mysqli_query($con, $sql) or die(mysqli_error());
		$linhas = mysqli_num_rows($result); 
		$linha = mysqli_fetch_assoc($result); 
		if ($linhas <= 0 or !password_verify($senha, $linha['hash'])) { 
			?>
			<script type="text/javascript">
				alert('E-mail ou senha inválidos');
			</script>
			<?php
		}			
		else {
			$_SESSION['user_logado']=$usuario ;
			mysqli_close($con);
			header('Location: search.php');
		}
	}	
	else{
	    
		$_SESSION['avisos'] = 'E-mail ou senha incorretos';
		
	}
}

if(isset($_POST['login'])){
	logar();
}

require 'footer.php';

?>