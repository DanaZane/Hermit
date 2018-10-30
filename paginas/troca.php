<?php
	require 'header.php';	
	require 'conexao.php';
	$id_livro = $_GET['id'];	
	$sql = "select id_pessoa from pessoa where email = '$_SESSION[user_logado]'";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$id = $dados['id_pessoa'];	
	
	$sql = "select count(id_troca) from troca where (id_pessoa1 = $id or id_pessoa2 = $id) and (status1 = 'Aceito' or status2= 'Aceito')";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	if($dados['count(id_troca)'] >= 3){
		$_SESSION['avisos'] =	'Você só pode ter três trocas simultâneas';
		header:("Location:perfil.php");
	}
	
	if($id_livro){
		?>
		<div><?php		
		$sql = "select * from livro where id_livro = $id_livro";
		$result = mysqli_query($con,$sql) or die (mysqli_error($con));
		$dados = mysqli_fetch_assoc($result);
		echo "<p>Escolha seu livro para oferecer pelo \"$dados[titulo]\" do autor \"$dados[autor]\" de ISBN \"$dados[isbn]\" da editora \"$dados[editora]\"</p>";
		?></div>
		<div class="container-fluid mt-5">
			<hr>
			<h2>Livros disponiveis para troca</h2>
			<?php		
			$sql = "select livro.* from livro where id_pessoa = $id and status = 'troca' and (livro.id_livro not in (select id_livro1 from troca where id_livro1 = id_livro) and livro.id_livro not in (select id_livro2 from troca where id_livro2 = id_livro))";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));
			$dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
			if($dados == NULL){
				echo "<div> Você não possui livros para trocar </div>";				
			}
			else{
				?>
				<!-- Mobile -->
				<div class="d-md-none">
				    <div class="container-fluid">
				       <?php foreach ($dados as $val){ ?>			
    					<div class="row">
    						<div class="col-sm-12 border"> 
    							<?php echo "<span> Titulo: $val[titulo] </span><br>";?> 
    							<?php echo "<span> Autor: $val[autor] </span><br>"; ?> 
    						    <?php echo "<span> ISBN: $val[isbn] </span><br>"; ?> 
    							<?php echo "<span> Editora: $val[editora] </span><br>"; ?> 
    							<?php echo "<div class='float-right'> <a type='button' class='btn btn-primary' href='mandatroca.php?id1=$val[id_livro]&id2=$id_livro' role='button'>Enviar Pedido</a> </div>" ?>
    						</div>
    					</div>			
    			       
    				 <?php } ?>
				    </div>
				</div>
				<!-- Desktop -->
				<div class="d-none d-md-block container-fluid">
    				<div class="row"> 
    					<div class="col-sm-3 border"><p class="font-weight-bold">Titulo</p></div>
    					<div class="col-sm-3 border"><p class="font-weight-bold">Autor(es)</p></div>
    					<div class="col-sm-2 border"><p class="font-weight-bold">ISBN</p></div>
    					<div class="col-sm-3 border"><p class="font-weight-bold">Editora</p></div>
    					<div class="col-sm-1 border"></div>
    				</div>				
    			
    				<?php foreach ($dados as $val){ ?>			
    					<div class="row">
    						<div class="col-sm-3 border"> 
    							<?php echo $val['titulo'] ?> 
    						</div>
    						<div class="col-sm-3 border"> 
    							<?php echo $val['autor']; ?> 
    						</div>
    						<div class="col-sm-2 border"> 
    							<?php echo $val['isbn']; ?> 
    						</div>
    						<div class="col-sm-3 border"> 
    							<?php echo $val['editora'] ?> 
    						</div>
    						<?php echo "<div class='col-sm-1 border'> <a type='button' class='btn btn-primary' href='mandatroca.php?id1=$val[id_livro]&id2=$id_livro' role='button'>Enviar Pedido</a> </div>" ?>
    					</div>			
        			<?php
        				}?>
        				
        		</div>
        		<?php } ?>
				
			
		
		</div>
		<?php
	}
		
	require 'footer.php';
?>