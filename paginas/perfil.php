<?php
	require 'header.php';
	require 'conexao.php';
	$sql = "select id_pessoa from pessoa where email = '$_SESSION[user_logado]'";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));
	$dados = mysqli_fetch_assoc($result);
	$id = $dados['id_pessoa'];
?>
<div class="container-fluid">
	<div class="row">
		<div class = "col-sm-12 border mt-2 p-1">
		    <h3>Opções de contato</h3>
			<?php
				$sql = "select tipo,cont from contato where id_pessoa = $id";
				$result = mysqli_query($con, $sql) or die(mysqli_error($con));
				$dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
				echo "<ul>";
				foreach($dados as $valor){
					echo "<li>$valor[tipo]: $valor[cont]</li>";
				}
				echo "</ul>"
			?>	
		</div>
	</div>
	<div class="row">
		<div class = "col-sm-12 border mt-2 p-1">
		    <div class= "container-fluid">
		    <h3>Trocas em andamento</h3>
		    
			<?php
				$sql = "select * from troca where (id_pessoa1 = $id OR id_pessoa2 = $id)";				
				$result = mysqli_query($con, $sql) or die(mysqli_error($con));
				$dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
				
				if($dados != null){
				    foreach($dados as $k => $v){
						if( $v['status1'] == 'Concluido' && $v['status2'] == 'Concluido' ){
							unset($dados[$k]);
						}
					}
				}
				
				
				if($dados != null){
					
					
					
					$dados = array_merge($dados);
					
					foreach($dados as $valor){
					    
					    if($id == $valor['id_pessoa1']){
					        $p1 = $valor['id_pessoa1'];
					        $s1 = $valor['status1'];
					        $p2 = $valor['id_pessoa2'];
					        $s2 = $valor['status2'];
					        $l1 = $valor['id_livro1'];
					        $l2 = $valor['id_livro2'];
					    }
					    else{
					        $p1 = $valor['id_pessoa2'];
					        $s1 = $valor['status2'];
					        $p2 = $valor['id_pessoa1'];
					        $s2 = $valor['status1'];
					        $l2 = $valor['id_livro1'];
					        $l1 = $valor['id_livro2'];
					    }
					       
					    
					    echo '<div class="border row p-1" style="min-height:3rem;">';
					    echo '<div class="col-md-8">';

								$sql = "select livro.titulo from livro inner join troca on livro.id_livro = $l1 where livro.id_pessoa= $p1 and troca.id_troca = $valor[id_troca]";
								$result = mysqli_query($con, $sql) or die(mysqli_error($con));
								$d = mysqli_fetch_assoc($result);
								
								echo "<span>O seu livro \"$d[titulo]\" ";
								$sql = "select pessoa.nome,livro.titulo from pessoa inner join livro on pessoa.id_pessoa = livro.id_pessoa where pessoa.id_pessoa = $p2 and livro.id_pessoa= $p2 and livro.id_livro = $l2";
								$result = mysqli_query($con, $sql) or die(mysqli_error($con));
								$d = mysqli_fetch_assoc($result);
								echo "em troca de \"$d[titulo]\" do usuario \"$d[nome]\"</span>";
								//echo "$valor[status1] $valor[status2]";
							
								if( ($s1 == 'Aceito' || $s1 == 'Concluido') && ($s2 == 'Aceito' || $s2 == 'Concluido') ){
									$sql = "select * from contato where id_pessoa = $p2";
									$result = mysqli_query($con, $sql) or die(mysqli_error($con));
									$d = mysqli_fetch_all($result, MYSQLI_ASSOC);
									echo "<ul>";
									foreach($d as $v){
										echo "<li>";
										echo "$v[tipo]: $v[cont]";
										echo "</li>";
									}
									echo "</ul>";
									
								}
								echo "</div>";
								echo "<div class='col-md-4'>";
							    echo "<div class='float-right'>";
								if ($s1 == 'Pendente' || $s2 == 'Pendente'){
									if($s1 == 'Aceito'){
										echo '<a type="button" class="btn btn-primary disabled mx-1" role="button">Aceito</a>';
									}else{
										echo "<a type='button' class='btn btn-primary mx-1' href='aceitatroca.php?id=$valor[id_troca]' role='button'>Aceitar</a>";
									}								
								}
								elseif($s1 == 'Aceito' || $s2 == 'Aceito'){
									
									if($s1 == 'Concluido'){
										echo "<a type='button' class='btn btn-primary disabled mx-1' role='button'>Concluido</a>";
									}else{
										echo "<a type='button' class='btn btn-primary mx-1' href='concluitroca.php?id=$valor[id_troca]' role='button'>Concluir</a>";
									}
								}
								echo "<a type='button' class='btn btn-primary mx-1' href='cancelatroca.php?id=$valor[id_troca]' role='button'>Cancelar</a>";
							
							    echo "</div>";
							    echo "</div>";
							    
							echo "</div>";	
						}
					
						
				}
				else{
					echo "Voce não possui nenhuma troca";
				}
				
			?>
			</div>
			</div>
		</div>
	
	
	<div class="row">
	    <div class = "col-sm-12 border mt-2 p-1">
			<h3>Livros disponíveis para troca</h3>
			
			<?php
			$sql = "select livro.* from livro where id_pessoa = $id and status='troca' and (livro.id_livro not in (select id_livro1 from troca where id_livro1 = id_livro) and livro.id_livro not in (select id_livro2 from troca where id_livro2 = id_livro))";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));
			$dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
			if($dados == NULL){
				echo "<div> Livros não encontrados </div>";				
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
    							<?php echo "<div class='float-right'> <a type='button' class='btn btn-primary' href='deleta.php?id=$val[id_livro]' role='button'>Deletar</a> </div>" ?>
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
    						<?php echo "<div class='col-sm-1 border'> <a type='button' class='btn btn-primary' href='deleta.php?id=$val[id_livro]' role='button'>Deletar</a> </div>" ?>
    					</div>			
        			<?php
        				}?>
        				
        		</div>
        		<?php } ?>
		</div>
	</div>
	
	<div class="row">
	    <div class="col-sm-12 border mt-2 p-1">
		
		<h3>Livros desejados</h3>
		<?php
			$sql = "select livro.* from livro where id_pessoa = $id and status='desejado' and (livro.id_livro not in (select id_livro1 from troca where id_livro1 = id_livro) and livro.id_livro not in (select id_livro2 from troca where id_livro2 = id_livro))";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));
			$dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
			if($dados == NULL){
				echo "<div> Livros não encontrados </div>";				
			}
			else{
				?>
				<!-- Mobile -->
				<div class="d-md-none">
				    <div>
				       <?php foreach ($dados as $val){ ?>			
    					<div class="row">
    						<div class="col-sm-12 border"> 
    							<?php echo "<span> Titulo: $val[titulo] </span><br>";?> 
    							<?php echo "<span> Autor: $val[autor] </span><br>"; ?> 
    						    <?php echo "<span> ISBN: $val[isbn] </span><br>"; ?> 
    							<?php echo "<span> Editora: $val[editora] </span><br>"; ?> 
    							<?php echo "<div class='float-right'> <a type='button' class='btn btn-primary' href='deleta.php?id=$val[id_livro]' role='button'>Deletar</a> </div>" ?>
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
    						<?php echo "<div class='col-sm-1 border'> <a type='button' class='btn btn-primary' href='deleta.php?id=$val[id_livro]' role='button'>Deletar</a> </div>" ?>
    					</div>			
    			<?php
    				}
				    
				echo '</div>';
				
				
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class = "col-sm-12 border mt-2 p-1">
    		<h3>Trocas completadas com sucesso</h3>
    		<?php
    			$sql = "select * from troca where (id_pessoa1 = $id OR id_pessoa2 = $id) and status1 = 'Concluido' and status1 = 'Concluido'";
    			$result = mysqli_query($con, $sql) or die(mysqli_error($con));
    				$dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
    				
    				if($dados != null){					
    					foreach($dados as $valor){
    						if($id == $valor['id_pessoa1']){
    
    							$sql = "select livro.titulo from livro inner join troca on livro.id_livro = troca.id_livro1 where livro.id_pessoa=$valor[id_pessoa1] and troca.id_troca = $valor[id_troca]";
    							$result = mysqli_query($con, $sql) or die(mysqli_error($con));
    							$d = mysqli_fetch_assoc($result);
    							echo "<div><span>";
    							echo "O seu livro \"$d[titulo]\" ";
    							$sql = "select pessoa.nome,livro.titulo from pessoa inner join livro on pessoa.id_pessoa = livro.id_pessoa where pessoa.id_pessoa=$valor[id_pessoa2] and livro.id_pessoa=$valor[id_pessoa2] and livro.id_livro = $valor[id_livro2]";
    							$result = mysqli_query($con, $sql) or die(mysqli_error($con));
    							$d = mysqli_fetch_assoc($result);
    							echo "em troca de \"$d[titulo]\" do usuario \"$d[nome]\"";
    							echo "</span></div>";
    							
    						}
    						elseif($id == $valor['id_pessoa2']){
    							
    							$sql = "select livro.titulo from livro inner join troca on livro.id_livro = troca.id_livro2 where livro.id_pessoa=$valor[id_pessoa2] and troca.id_troca = $valor[id_troca]";
    							$result = mysqli_query($con, $sql) or die(mysqli_error($con));
    							$d = mysqli_fetch_assoc($result);
    							echo "<div><span>";
    							echo "O seu livro \"$d[titulo]\" ";
    							$sql = "select pessoa.nome,livro.titulo from pessoa inner join livro on pessoa.id_pessoa = livro.id_pessoa where pessoa.id_pessoa=$valor[id_pessoa1] and livro.id_pessoa=$valor[id_pessoa1] and livro.id_livro = $valor[id_livro1]";
    							$result = mysqli_query($con, $sql) or die(mysqli_error($con));
    							$d = mysqli_fetch_assoc($result);
    							echo "em troca de \"$d[titulo]\" do usuario \"$d[nome]\"";
    							echo "</span></div>";
    							
    							
    						}						
    					}
    				}
    				else{
    					echo "<span>Você não possui trocas completadas</span>";
    				}
    		?>
    	</div>
    </div>
</div>
	
<?php	
	require 'footer.php';
?>