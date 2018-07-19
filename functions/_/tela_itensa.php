 <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <h1 class="header center pink-text">Item Avaliação Física</h1>
	  
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
					$sql = "select * from tbitensa where cdItensa='".$id."'";
					$sql = $conexao->query($sql);
					$r = $sql->fetch(PDO::FETCH_ASSOC);
					$_REQUEST["cdItensa"] = $r["cdItensa"];
					$_REQUEST["nmItensa"] = $r["nmItensa"];
					}
					
					
				if (isset($_REQUEST["btnalt"])) {
					if ($_REQUEST["nmItensa"]=="") {
					echo '<h5 class="header center red white-text">Campo Vazio</h5>';
				} else {
					$sql = "select count(*) as qtd from tbitensa 
					where nmItensa='".$_REQUEST["nmItensa"]."'
					and cdItensa <> '".$_REQUEST["cdItensa"]."'";
					$sql = $conexao->query($sql);
					$r = $sql->fetch(PDO::FETCH_ASSOC);	
				if ($r["qtd"]>0) {
					echo '<h5 class="header center red white-text">Item Existente</h5>';
				} else {
				 $sqlcomando = "update tbitensa 
					set 
					nmItensa='{$_REQUEST["nmItensa"]}'
					where cdItensa = '{$_REQUEST["cdItensa"]}'";
					$sqlprocesso = $conexao->query($sqlcomando);
					echo '<h5 class="header center green white-text">Alterado com Sucesso</h5>';
					$_REQUEST["nmItensa"]="";	
						}			 
					}
				}	
	
					if (isset($_REQUEST["btnexc"])) {
						$sqlcomando = "delete from tbitensa 
						where cdItensa = '{$_REQUEST["cdItensa"]}'";
						$sqlprocesso = $conexao->query($sqlcomando);
						echo '<h5 class="header center green white-text">Excluido com Sucesso</h5>';	
                        $_REQUEST["nmItensa"]="";							
					}		
	  	?>
		
		<?php
				if (isset($_REQUEST["btncad"])){
						if(empty($_REQUEST['nmItensa'])){
							 echo '<h5 class="header center red white-text">Campo Vazio</h5>';
						}else{
								
						     $sqlcomando = "select cdItensa from tbitensa where nmItensa = '{$_REQUEST['nmItensa']}'";
							 $sqlprocesso = $conexao->query($sqlcomando);
							 $sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
							 if($sqlretorno["cdItensa"]>0){
								 echo '<h5 class="header center red white-text">Item Existente</h5>';
								 $_REQUEST["nmItensa"]="";		
							    }else{
									echo '<h5 class="header center green white-text">Salvo com Sucesso</h5>';										
								$cn = $_REQUEST['nmItensa'];
								$_REQUEST['nmItensa']="";
								$sqlcomando = "insert into tbitensa (nmItensa) values ('$cn')";
								$sqlprocesso = $conexao->query($sqlcomando);
								// estava aqui							
							}
						}
					}
					if (isset($_REQUEST["btncanc"])){
						$_REQUEST['nmItensa']="";
					}
 ?>

<?php
			
			if (!isset($_REQUEST["cdItensa"])) $_REQUEST["cdItensa"]= "";
	        if (!isset($_REQUEST["nmItensa"])) $_REQUEST["nmItensa"] = "";
		
			echo"
			<form method='post' action='index.php?tela=itensa'>
				<br>
				<div class='row'>
				<div class='center input-field col s6'>
					<input name='nmItensa' type='text' class='validate' placeholder=' ' value='".$_REQUEST["nmItensa"]."'>
					<label class='active' for='nmItensa'>Nome do itensa</label>
				</div>
			</div>
			
			<div> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btnnome' value='$btnvalor'> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btncanc' value='Cancelar'></div> 
			<input type='hidden' name='cdItensa' value='".$_REQUEST["cdItensa"]."'>
			</form>";
			?>
			

 
 <?php
		
 ?>
 
<br><br>
 <nav>
    <div class="nav-wrapper">
      <form action = "index.php?tela=itensa" method = "post">
        <div class="input-field">
          <input id="search" type="search" name = "pesquisa"  placeholder = "Nome ou Código">
          <label class="label-icon" for="Nome"><i class="material-icons">search</i></label>
		 
          <i class="material-icons">close</i>
        </div>
      </form>
    </div>
  </nav>
  
<?php
	$sqlcomando = "select * from tbitensa where 1 = 1 "; 
	if (isset($_REQUEST["pesquisa"])) 
	$sqlcomando .= " and nmItensa like '%".$_REQUEST["pesquisa"]."%' or cdItensa like '%".$_REQUEST["pesquisa"]."%' ";
	$sqlcomando .= "order by nmItensa";
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
		 if (!isset($_REQUEST["nmItensa"]))$_REQUEST["nmItensa"]="";
		 while ($sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {
								echo "<tr>
								<td>".$sqlretorno["cdItensa"]."
								<td>".$sqlretorno["nmItensa"]."
								<td><a href='index.php?tela=itensa&alt=".$sqlretorno["cdItensa"]."'>Editar</a> 
								<td><a href='index.php?tela=itensa&exc=".$sqlretorno["cdItensa"]."'>Excluir</a>";
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