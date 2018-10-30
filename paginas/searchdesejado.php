<!doctype html>

<?php
	require('header.php');	
?>


<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        	<form method="get" action="searchdesejado.php">
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
    
	$titulo = $_GET['titulo'];	
	$autor = $_GET['autor'];
	$isbn = $_GET['isbn'];	
	$query = '';
	
		
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
		
		$json = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=".$query."&maxResults=40");	
		$data = json_decode($json, true);	
		
		if($data['totalItems'] == 0){
		    $_SESSION['avisos'] = 'Livros não encontrados';
		    return;
		}
		
?>
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
				<?php echo '<div class="col-sm-1 border"> <a type="button" class="btn btn-primary" href="adicionadesejado.php?id=' . $val['id'] . '" role="button">Adicionar</a> </div>' ?>
			</div>
			
		<?php
		}
		
		
		?>
		
		</div>
		
		<?php
		
		echo "<div>
				<a href='search.php'><button>Proxima Pagina</button></a>
			  </div>";
}
	
if(isset($_GET['search'])){
	search();
}	

require 'footer.php';

?>