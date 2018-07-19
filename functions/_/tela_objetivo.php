 <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <h1 class="header center pink-text">Objetivo</h1>
	  
	  <div class="row-top right">
	  </div>
	  </div>
	  
	  
	  <?php
	  		$btnnome='btncad';
	        $btnvalor='Cadastrar';
			$btncanc = 'btncanc';
		if (isset($_REQUEST["alt"]) || isset($_REQUEST["exc"])) {
		              if (isset($_REQUEST["alt"])) {
						$id=$_REQUEST["alt"];
						$btnnome="btnalt";
						$btnvalor="Alterar";			
					} else {
						$id=$_REQUEST["exc"];
						$btnnome="btnexc";
						$btnvalor="Excluir";						
					}
					$sql = "select * from tbobjetivo where cdObjetivo='".$id."'";
					$sql = $conexao->query($sql);
					$r = $sql->fetch(PDO::FETCH_ASSOC);
					$_REQUEST["cdObjetivo"] = $r["cdObjetivo"];
					$_REQUEST["nmObjetivo"] = $r["nmObjetivo"];
					}
					
					
				if (isset($_REQUEST["btnalt"])) {
					if ($_REQUEST["nmObjetivo"]=="") {
					echo '<h5 class="header center red white-text">Campo Vazio</h5>';
				} else {
					$sql = "select count(*) as qtd from tbobjetivo 
					where nmObjetivo='".$_REQUEST["nmObjetivo"]."'
					and cdObjetivo <> '".$_REQUEST["cdObjetivo"]."'";
					$sql = $conexao->query($sql);
					$r = $sql->fetch(PDO::FETCH_ASSOC);	
				if ($r["qtd"]>0) {
					echo '<h5 class="header center red white-text">Objetivo Existente</h5>';
				} else {
				 $sqlcomando = "update tbobjetivo 
					set 
					nmObjetivo='{$_REQUEST["nmObjetivo"]}'
					where cdObjetivo = '{$_REQUEST["cdObjetivo"]}'";
					$sqlprocesso = $conexao->query($sqlcomando);
					echo '<h5 class="header center green white-text">Alterado com Sucesso</h5>';
					$_REQUEST["nmObjetivo"]="";	
						}			 
					}
				}	
	
					if (isset($_REQUEST["btnexc"])) {
						$sqlcomando = "delete from tbobjetivo 
						where cdObjetivo = '{$_REQUEST["cdObjetivo"]}'";
						$sqlprocesso = $conexao->query($sqlcomando);
						echo '<h5 class="header center green white-text">Excluido com Sucesso</h5>';	
                        $_REQUEST["nmObjetivo"]="";							
					}		
					if (isset($_REQUEST["btncanc"])){
						$_REQUEST['nmObjetivo']="";
					}
	  	?>
		
		<?php
				if (isset($_REQUEST["btncad"])){
						if(empty($_REQUEST['nmObjetivo'])){
							 echo '<h5 class="header center red white-text">Campo Vazio</h5>';
						}else{
								
						     $sqlcomando = "select cdObjetivo from tbobjetivo where nmObjetivo = '{$_REQUEST['nmObjetivo']}'";
							 $sqlprocesso = $conexao->query($sqlcomando);
							 $sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
							 if($sqlretorno["cdObjetivo"]>0){
								 echo '<h5 class="header center red white-text">Objetivo Existente</h5>';
								 $_REQUEST["nmObjetivo"]="";		
							    }else{
									echo '<h5 class="header center green white-text">Salvo com Sucesso</h5>';										
								$cn = $_REQUEST['nmObjetivo'];
								$_REQUEST['nmObjetivo']="";
								$sqlcomando = "insert into tbobjetivo (nmObjetivo) values ('$cn')";
								$sqlprocesso = $conexao->query($sqlcomando);
								// estava aqui							
							}
						}
					}
 ?>

<?php
			
			if (!isset($_REQUEST["cdObjetivo"])) $_REQUEST["cdObjetivo"]= "";
	        if (!isset($_REQUEST["nmObjetivo"])) $_REQUEST["nmObjetivo"] = "";
		
			echo"
			<form method='post' action='index.php?tela=objetivo'>
				<br>
				<div class='row'>
				<div class='center input-field col s6'>
					<input name='nmObjetivo' type='text' class='validate' placeholder=' ' value='".$_REQUEST["nmObjetivo"]."'>
					<label class='active' for='nmObjetivo'>Nome do Objetivo</label>
				</div>
			</div>
			
			<div> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btnnome' value='$btnvalor'> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btncanc' value='Cancelar'> </div>
			<input type='hidden' name='cdObjetivo' value='".$_REQUEST["cdObjetivo"]."'>
			</form>";
			?>
			

 
 <?php
		
 ?>
 
<br><br>
 <nav>
    <div class="nav-wrapper">
      <form action = "index.php?tela=objetivo" method = "post">
        <div class="input-field">
          <input id="search" type="search" name = "pesquisa"  placeholder = "Nome ou Código">
          <label class="label-icon" for="Nome"><i class="material-icons">search</i></label>
		 
          <i class="material-icons">close</i>
        </div>
      </form>
    </div>
  </nav>
  
<?php
	$sqlcomando = "select * from tbobjetivo where 1 = 1 "; 
	if (isset($_REQUEST["pesquisa"])) 
	$sqlcomando .= " and nmObjetivo like '%".$_REQUEST["pesquisa"]."%' or cdObjetivo like '%".$_REQUEST["pesquisa"]."%' ";
	$sqlcomando .= "order by nmObjetivo";
	$sqlprocesso = $conexao->query($sqlcomando);
?>
 <table class="bordered highlight">
        <thead>
          <tr>
              <th>Código</th>
              <th>Nome</th>
          </tr>
        </thead>

        <tbody>
		<?php 
		 if (!isset($_REQUEST["nmObjetivo"]))$_REQUEST["nmObjetivo"]="";
		 while ($sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {
								echo "<tr>
								<td>".$sqlretorno["cdObjetivo"]."
								<td>".$sqlretorno["nmObjetivo"]."
								<td><a href='index.php?tela=objetivo&alt=".$sqlretorno["cdObjetivo"]."'>Editar</a> 
								<td><a href='index.php?tela=objetivo&exc=".$sqlretorno["cdObjetivo"]."'>Excluir</a>";
							}
		 ?>
        </tbody>
   </table>
            

 <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>  
      
	  <br><br>
      

    </div>
  </div>