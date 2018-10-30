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
  <ul class="navbar-nav ml-auto w-100 justify-content-end">
      <li class="nav-item">
        <a class="nav-link" href="login.php">Voltar</a>
      </li>
    </ul>
  
</nav>

<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-md-4"></div>
            <div class="mx-auto col-md-4" style="width:30rem;">
            	<form method="post" action="signup.php">
            		<div class="form-group">
            			<label for="nome">Nome</label>
            			<input type="text" class="form-control" id="nome" name="nome" ></input>
            		</div>		
            		<div class="form-group">
            			<label for="email">Email (usado como opção de contato)</label>
            			<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" ></input>
            		</div>
            		<div class="form-group">
            			<label for="senha">Senha</label>
            			<input type="password" class="form-control" id="senha" name="senha"></input>
            		</div>
            		<div class="form-group">
            			<label for="senha-rpt">Repetir Senha</label>
            			<input type="password" class="form-control" id="senha-rpt" name="senha-rpt"></input>
            		</div>
            		<div class="form-group">
            			<span>Opção de contato 1(Opcional)</span>			
            			<select class="custom-select" name="op1con">
            				<option selected>Escolha...</option>
            				<option value="Facebook">Facebook</option>
            				<option value="E-mail">E-mail</option>
            				<option value="Telefone">Telefone</option>						
            				<option value="Outro">Outro(Especificar)</option>
            			</select>
            		<div class="form-group">
            			<input type="text" class="form-control" id="valopp" name="valopp"></input>
            		</div>	
            		</div>		
            		<div class="form-group">
            			<span>Opção de contato 2(Opcional)</span>
            			<select class="custom-select" name="op2con">
            				<option selected>Escolha...</option>
            				<option value="Facebook">Facebook</option>
            				<option value="E-mail">E-mail</option>
            				<option value="Telefone">Telefone</option>						
            				<option value="Outro">Outro(Especificar)</option>
            			</select>
            		</div>
            		<div class="form-group">
            			<input type="text" class="form-control" id="valops" name="valops"></input>
            		</div>					
            		<div>
            			<div class="float-right">
            				<button type="submit" class="btn btn-primary" name="signup" id="signup">Sign up</button>
            			</div>						
            		</div>
            	</form>			
            </div>
        <div class="col-md-4"></div>
    </div>
</div>





<?php 

function registrar(){
    	require 'conexao.php';

	if( $_POST['nome'] != '' &&  $_POST['email'] != '' && $_POST['senha'] != '' && $_POST['senha-rpt'] != '' ){
		if( $_POST['senha'] != $_POST['senha-rpt']){
			?>
			<script type="text/javascript">
				alert('Senhas diferentes');
			</script>
			<?php
			return;
		}
		$nome = mysqli_real_escape_string($con, $_POST['nome']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$senha = mysqli_real_escape_string($con, $_POST['senha']);
		
		$senha = password_hash($senha,PASSWORD_DEFAULT);
		
		$sql = "insert into pessoa(nome,email,hash) values ('$nome', '$email', '$senha')"; 
		mysqli_query($con, $sql);
		
		$sql = "select id_pessoa from pessoa where email = '$email'";
		$result = mysqli_query($con, $sql) or die(mysqli_error());
		$result = mysqli_query($con,$sql);
		$dados = mysqli_fetch_assoc($result);
		$id = $dados['id_pessoa'];
		
		$sql = "insert into contato(tipo,cont,id_pessoa) values ('E-mail', '$email', '$id')"; 
		mysqli_query($con, $sql);
		
		if($_POST['valopp'] != '' && $_POST['op1con'] != 'Escolha...'){			
			$opconv1 = mysqli_real_escape_string($con, $_POST['valopp']);
			$sql = "insert into contato(tipo,cont,id_pessoa) values ('$_POST[op1con]', '$opconv1', '$id')";
			mysqli_query($con,$sql);
		}
		
		if($_POST['valops'] != '' && $_POST['op2con'] != 'Escolha...'){			
			$opconv2 = mysqli_real_escape_string($con, $_POST['valops']);
			$sql = "insert into contato(tipo,cont,id_pessoa) values ('$_POST[op2con]', '$opconv2', '$id')"; 
			mysqli_query($con,$sql);
		}
		
		$_SESSION['user_logado']=$email;
		mysqli_close($con);
		header('Location: login.php');
	}
	else{
		?>
		<script type="text/javascript">
				alert('Preencha todos os campos');
		</script>
		<?php	
	}
}
	
if(isset($_POST['signup'])){
	registrar();
}

require 'footer.php';

?>