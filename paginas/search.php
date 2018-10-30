<!doctype html>

<?php
	require('header.php');
?>
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form method="get" action="search.php">
        		<div class="form-group">
        			<label for="titulo">Titulo</label>
        			<input type="text" class="form-control" id="titulo" name="titulo" ></input>
        		</div>
        		<div class="form-group">
        			<label for="autor">Autor</label>
        			<input type="text" class="form-control" id="autor" name="autor" ></input>
        		</div>
        		<div class="form-group">
        			<label for="isbn">ISBN</label>
        			<input type="text" class="form-control" id="isbn" name="isbn"></input>
        		</div>
        		<div>
        		    <?php
        		        if(isset($_GET['option']) && $_GET['option'] == 'troca'){
        		            ?>
        		           <label class="radio-inline"><input type="radio" name="option" value="add" >Adicionar livros</label>
        			        <label class="radio-inline float-right"><input type="radio"  name="option" value="troca" checked>Procurar para troca</label> 
        			        <?php
        		        }
        		        else{
        		    ?>
        			<label class="radio-inline"><input type="radio" name="option" value="add" checked>Adicionar livros</label>
        			<label class="radio-inline float-right"><input type="radio"  name="option" value="troca">Procurar para troca</label>
        			<?php } ?>
        		</div> 
        		<div class="float-right">
        			<button type="submit" class="btn btn-primary" name="search" id="search">Procurar</button>
        		</div>
	       </form>
	    </div>
        <div class="col-md-4"></div>
    </div>
</div>

<?php 

function search(){
		
	    require 'conexao.php';
	    
		$titulo = $_GET['titulo'];	
		$autor = $_GET['autor'];
		$isbn = $_GET['isbn'];	
		$query = '';
		
	if($_GET['option'] == 'add'){
			
			if($titulo !== ''){		
				$query .= 'intitle:'.$titulo;
			}
			if($autor !== ''){		
				$query .= ' inauthor:'.$autor;
			}	
			if($isbn !== ''){		
				$query .= ' isbn:'.$isbn;
			}
			$query = preg_replace('/\s+/', '+', $query);
			
		@$json = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=".$query."&maxResults=40");
		$data = json_decode($json, true);	
		if($data['totalItems'] == 0){
		    $_SESSION['avisos'] = 'Livros não encontrados';
		    return;
		}
?>
        <!-- Mobile -->
        <div class="d-md-none">
            <div class="container-fluid">
                <?php foreach ($data['items'] as $val){ ?>
                    <div class="row">
        				<div class="col-sm-12 border"> 
        					<?php 
            					echo "<span> Titulo: ";
            					echo $val['volumeInfo']['title'];
            					echo"</span><br>";
        					?> 
        					<?php 
            					echo "<span> Autor(es): ";
            					echo @implode(", ", $val['volumeInfo']['authors']);
            					echo "</span><br>";
        					?> 
        					<?php
        					    echo "<span> ISBN: ";
        					    if (array_key_exists('industryIdentifiers',$val['volumeInfo'])){
        					        echo $val['volumeInfo']['industryIdentifiers'][0]['identifier'] ;
        					    } 
        					    echo "</span><br>";
        					?> 
        					<?php 
        					echo "<span> Editora: ";
        					if (array_key_exists('publisher',$val['volumeInfo'])){
        					    
        						echo  $val['volumeInfo']['publisher'];
        						
        					} 
        					echo "</span><br>";
        					?> 
        				    <?php echo '<div class="float-right"> <a type="button" class="btn btn-primary" href="adiciona.php?id=' . $val['id'] . '" role="button">Adicionar</a> </div>' ?>
        				</div>
        			</div>
        		<?php } ?>	
            </div>    
        </div>
        <!-- Desktop -->
        <div class="d-none d-md-block container-fluid">
            <div class="container-fluid mt-5">
    		<hr>	
    				<div class="row"> 
    					<div class="col-sm-3 border"><p class="font-weight-bold">Titulo</p></div>
    					<div class="col-sm-3 border"><p class="font-weight-bold">Autor(es)</p></div>
    					<div class="col-sm-2 border"><p class="font-weight-bold">ISBN</p></div>
    					<div class="col-sm-3 border"><p class="font-weight-bold">Editora</p></div>
    					<div class="col-sm-1 border"></div>
    				</div>
    		<?php foreach ($data['items'] as $val){ ?>
    			<div class="row">
    				<div class="col-sm-3 border"> 
    					<?php echo $val['volumeInfo']['title']; ?> 
    				</div>
    				<div class="col-sm-3 border"> 
    					<?php echo @implode(", ", $val['volumeInfo']['authors']); ?> 
    				</div>
    				<div class="col-sm-2 border"> 
    					<?php echo $val['volumeInfo']['industryIdentifiers'][0]['identifier']; ?> 
    				</div>
    				<div class="col-sm-3 border"> 
    					<?php 
    					if (array_key_exists('publisher',$val['volumeInfo'])){
    						echo $val['volumeInfo']['publisher'];
    					} 
    					?> 
    				</div>
    				<?php echo '<div class="col-sm-1 border"> <a type="button" class="btn btn-primary" href="adiciona.php?id=' . $val['id'] . '" role="button">Adicionar</a> </div>' ?>
    			</div>
    			
    		<?php
    		}
    		
    		
    		?>
    		
    		</div>
        </div>

		
		
		<?php
	
    }		  
	else{
		if($_GET['option'] == 'troca'){
			$titulo = $_GET['titulo'];	
			$titulo = preg_replace("/'/", '', $titulo);
			$autor = $_GET['autor'];
			$isbn = $_GET['isbn'];				
			
			$sql = "select id_pessoa from pessoa where email = '$_SESSION[user_logado]'";
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));
			$dados = mysqli_fetch_assoc($result);
			$id = $dados['id_pessoa'];			
			$sql = "select * from livro where status = 'troca' and id_livro not in (select id_livro1 from troca) and
					id_livro not in (select id_livro2 from troca) and id_pessoa != $id";
			if($titulo !== ''){
				$titulo = strtolower($titulo);
				$sql .= " and LOWER(titulo) LIKE '%$titulo%'";
			}
			if($autor !== ''){
				$autor = strtolower($autor);
				$sql .= " and LOWER(autor) LIKE '%$autor%'";
			}	
			if($isbn !== ''){
				$sql.= " and LOWER(isbn) LIKE '%$isbn%'";
			}
			$result = mysqli_query($con, $sql) or die(mysqli_error($con));
			$dados = mysqli_fetch_all($result, MYSQLI_ASSOC);
			if($dados == NULL){
				$sql = "select count(id_livro) from livro where status = 'desejado' and id_pessoa = $id";
				$result = mysqli_query($con, $sql) or die(mysqli_error($con));
				$dados = mysqli_fetch_assoc($result);
				if($dados['count(id_livro)'] >= 3){
					echo "<div> Livros não encontrados, você já possui o máximo de três livros desejados, remova algum deles para adicionar mais</div>";
				}
				else{
					echo "<div> Livros não encontrados, deseja <a href='searchdesejado.php?titulo=$titulo&autor=$autor&isbn=$isbn&search='>adicionar</a> na lista de desejados? </div>";
				}				
				return;
			}
	?>
	    <!-- Mobile -->
        <div class="d-md-none">
		    <div class ="container-fluid">
		       <?php foreach ($dados as $val){ ?>			
				<div class="row">
					<div class="col-sm-12 border"> 
						<?php echo "<span> Titulo: $val[titulo] </span><br>";?> 
						<?php echo "<span> Autor: $val[autor] </span><br>"; ?> 
					    <?php echo "<span> ISBN: $val[isbn] </span><br>"; ?> 
						<?php echo "<span> Editora: $val[editora] </span><br>"; ?> 
						<?php echo "<div class='float-right'> <a type='button' class='btn btn-primary' href='troca.php?id=$val[id_livro]' role='button'>Trocar</a> </div>" ?>
					</div>
				</div>			
		       
			 <?php } ?>
		    </div>
        </div>
        <!-- Desktop -->
        <div class="d-none d-md-block container-fluid">
            <div class="container-fluid mt-5">
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
						<?php echo "<div class='col-sm-1 border'> <a type='button' class='btn btn-primary' href='troca.php?id=$val[id_livro]' role='button'>Trocar</a> </div>" ?>
					</div>
				
				<?php
				}
				?>
			</div>
        </div>
	
			
<?php
		}
	}
}
	
if(isset($_GET['search'])){
	search();
}	

require 'footer.php';

?>