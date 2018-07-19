<div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <h1 class="header center pink-text">Cliente</h1>
	  
	  <div class="row-top right">
	  </div>
	  </div>
	  
	  
	  <?php
	  		$btnnome='btncad';
	        $btnvalor='Cadastrar';
			$btncanc = 'btncanc';
			$desabilitado = "";
		if (isset($_REQUEST["alt"]) || isset($_REQUEST["exc"])) {
			$desabilitado = 'readonly';
		              if (isset($_REQUEST["alt"])) {
						$id=$_REQUEST["alt"];
						$btnnome="btnalt";
						$btnvalor="Alterar";			
					} else {
						$id=$_REQUEST["exc"];
						$btnnome="btnexc";
						$btnvalor="Excluir";						
					}
					$sql = "select * from tbcliente where cdCliente='".$id."'";
					$sql = $conexao->query($sql);
					$r = $sql->fetch(PDO::FETCH_ASSOC);
					$_REQUEST["cdCliente"] = $r["cdCliente"];
					$_REQUEST["nmCliente"] = $r["nmCliente"];
					$_REQUEST["CPF"] = $r["CPF"];
					$_REQUEST["RG"] = $r["RG"];
					$_REQUEST["email"] = $r["email"];
					$_REQUEST["senha"] = $r["senha"];
					$_REQUEST["valorMensalidade"] = $r["valorMensalidade"];
					}
					
					
					
				if (isset($_REQUEST["btnalt"])) {
					if ($_REQUEST["nmCliente"]==""||$_REQUEST["CPF"]==""||$_REQUEST["RG"]==""||$_REQUEST["email"]==""||$_REQUEST["senha"]=="") {
					echo '<h5 class="header center red white-text">Campo Vazio</h5>';
			}else{
				$cs = $_REQUEST['senha'];
				$csc = md5($cs);
				  $sqlcomando = "update tbcliente 
					set 
						nmCliente='{$_REQUEST["nmCliente"]}',
						CPF='{$_REQUEST["CPF"]}',
						RG='{$_REQUEST["RG"]}',
						email='{$_REQUEST["email"]}',
						senha='{$csc}'
						valorMensalidade='{$_REQUEST["valorMensalidade"]}'
					where cdCliente = '{$_REQUEST["cdCliente"]}'";
					$sqlprocesso = $conexao->query($sqlcomando);
					
					$sqlcomando = "update tbusuario 
					set  email='{$_REQUEST["email"]}',
					senha='{$csc}'
					where email = '{$_REQUEST["email"]}'";
					$sqlprocesso = $conexao->query($sqlcomando);
					
					echo '<h5 class="header center green white-text">Alterado com Sucesso</h5>';
					$_REQUEST["nmCliente"]="";	
					$_REQUEST["CPF"]="";
					$_REQUEST["RG"]="";
					$_REQUEST["email"]="";
					$_REQUEST["senha"]="";
					$_REQUEST["tipo"]="";	
					$_REQUEST["valorMensalidade"]="";
						}
			
				}	
					
					if (isset($_REQUEST["btnexc"])) {
						$sqlcomando = "delete from tbcliente 
						where cdCliente = '{$_REQUEST["cdCliente"]}'";
						$sqlprocesso = $conexao->query($sqlcomando);
						
						$sqlcomando2 = "delete from tbusuario 
						where email = '{$_REQUEST["email"]}'";
						$sqlprocesso = $conexao->query($sqlcomando2);
						
						echo '<h5 class="header center green white-text">Excluido com Sucesso</h5>';	
                        $_REQUEST["nmCliente"]="";	
						$_REQUEST["CPF"]="";
						$_REQUEST["RG"]="";
						$_REQUEST["email"]="";
						$_REQUEST["senha"]="";		
						$_REQUEST["tipo"]="";	
						$_REQUEST["valorMensalidade"]="";
					}		
	  	?>
		
		<?php
				if (isset($_REQUEST["btncad"])){
						if(empty($_REQUEST['nmCliente'])||empty($_REQUEST['valorMensalidade'])||empty($_REQUEST['CPF'])||empty($_REQUEST['RG'])||empty($_REQUEST['email'])||empty($_REQUEST['senha'])){
							
							 echo '<h5 class="header center red white-text">Campo Vazio</h5>';
						}elseif(validaCPF ($_REQUEST['CPF'])  == false){
							echo '<h5 class="header center red white-text">CPF Inválido</h5>';
						}else{
								
						     $sqlcomando = "select cdCliente from tbcliente where nmCliente = '{$_REQUEST['nmCliente']}' and CPF = '{$_REQUEST['CPF']}' and RG = '{$_REQUEST['RG']}' and email = '{$_REQUEST['email']}' and senha = '{$_REQUEST['senha']}'";
							 $sqlprocesso = $conexao->query($sqlcomando);
							 $sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
							 $sqlcomando2 = "select cdCliente from tbcliente where  CPF = '{$_REQUEST['CPF']}' ";
							 $sqlprocesso2 = $conexao->query($sqlcomando2);
							 $sqlretorno2 = $sqlprocesso2->fetch(PDO::FETCH_ASSOC);
							 $sqlcomando3 = "select cdCliente from tbcliente where  email = '{$_REQUEST['email']}'";
							 $sqlprocesso3 = $conexao->query($sqlcomando3);
							 $sqlretorno3 = $sqlprocesso3->fetch(PDO::FETCH_ASSOC);
							 if(($sqlretorno["cdCliente"]>0) || ($sqlretorno2["cdCliente"]>0) || ($sqlretorno3["cdCliente"]>0)) {
								 echo '<h5 class="header center red white-text">Cliente Existente</h5>';
								   $_REQUEST["nmCliente"]="";	
									$_REQUEST["CPF"]="";
									$_REQUEST["RG"]="";
									$_REQUEST["email"]="";
									$_REQUEST["senha"]="";	
									$_REQUEST["tipo"]="";
									$_REQUEST["valorMensalidade"]="";
							    }else{
									echo '<h5 class="header center green white-text">Salvo com Sucesso</h5>';										
								$cn = $_REQUEST['nmCliente'];
								$cc = $_REQUEST['CPF'];
								$cr = $_REQUEST['RG'];
								$ce = $_REQUEST['email'];
								$cs = $_REQUEST['senha'];
								$ct = $_REQUEST['tipo'];
								$mensalidade = $_REQUEST["valorMensalidade"];
								$csc = md5($cs);
								$_REQUEST["nmCliente"]="";	
								$_REQUEST["CPF"]="";
								$_REQUEST["RG"]="";
								$_REQUEST["email"]="";
								$_REQUEST["senha"]="";	
								$_REQUEST["tipo"]="";	
								$_REQUEST["valorMensalidade"] = "";
								$sqlcomando = "insert into tbcliente (nmCliente, CPF, RG, email, senha,tipo,valorMensalidade) values ('$cn', '$cc', '$cr', '$ce', '$csc','$ct','$mensalidade' ) ";
								$sqlprocesso = $conexao->query($sqlcomando);
								$sql2 = "insert into tbusuario (email,senha) values ('$ce', '$csc')";
								$sqlprocesso2 = $conexao->query($sql2);							
							}
						}
					}
						if (isset($_REQUEST["btncanc"])){
						$_REQUEST["nmCliente"]="";	
						$_REQUEST["CPF"]="";
						$_REQUEST["RG"]="";
						$_REQUEST["email"]="";
						$_REQUEST["senha"]="";	
						$_REQUEST["tipo"]="";
					}
					
 ?>

<?php
			
			if (!isset($_REQUEST["cdCliente"])) $_REQUEST["cdCliente"]= "";
	        if (!isset($_REQUEST["nmCliente"])) $_REQUEST["nmCliente"] = "";
			if (!isset($_REQUEST["CPF"])) $_REQUEST["CPF"] = "";
			if (!isset($_REQUEST["RG"])) $_REQUEST["RG"] = "";
			if (!isset($_REQUEST["email"])) $_REQUEST["email"] = "";
			if (!isset($_REQUEST["senha"])) $_REQUEST["senha"] = "";
			if (!isset($_REQUEST["tipo"])) $_REQUEST["tipo"] = "";
			if(!isset($_REQUEST["valorMensalidade"])) $_REQUEST["valorMensalidade"] = "";
		
			echo"
			<form method='post' action='index.php?tela=cliente'>
			
				<br>
				
			<div class='row'>
				<div class='center input-field col s6'>
					<input name='nmCliente' type='text' class='validate' placeholder=' ' value='".$_REQUEST["nmCliente"]."'>
					<label class='active' for='nmCliente'>Nome do cliente</label>
					
				</div>
			
					<div class='input-field col s6'>
						<input $desabilitado name='CPF' type='text' data-length='11' id= '$desabilitado' placeholder=' ' value='".$_REQUEST["CPF"]."'>
						<label class='active' for='CPF'>CPF</label>
					</div>
				</div>


				<div class='row'>
					<div class='input-field col s6'>
						<input $desabilitado name='RG' type='text' data-length='10' placeholder=' ' id= '$desabilitado' value='".$_REQUEST["RG"]."'>
						<label class='active' for='RG'>RG</label>
					</div>
		

					<div class='row'>
						<div class='input-field col s6'>
							<input $desabilitado name='email' type='email' class='validate' id= '$desabilitado' placeholder=' ' value='".$_REQUEST["email"]."'>
							<label for='email' class='active' data-error='Insira um e-mail com @' >Email</label>
						</div>
					</div>

			</div>
			

				<div class='row'>
					<div class='input-field col s6'>
						<input name='senha' type='text' data-length='20'placeholder=' 'value='".$_REQUEST["senha"]."'>
						<label for='senha'class='active'>Senha</label>
					</div>
					<div class='input-field col s6'>
						<input name='valorMensalidade' type='text' data-length='20'placeholder=' 'value='".$_REQUEST["valorMensalidade"]."'>
						<label for='valorMensalidade'class='active'>Valor da mensalidade</label>
					</div>
				</div>
				
					<div class='row'>
					<div class='input-field col s6'>
						<label for='tipo'class='active'>Tipo</label>
					</div>
				</div>
				<div class='input-field col s6'>

					<p>
						<input name='tipo' type='radio' id='test1' value = '1' />
						<label for='test1'>Administrador</label>
					</p>
					<p>
						<input checked name='tipo' type='radio' id='test2' value = '2'/>
						<label for='test2'>Cliente</label>
					</p>
			
			</div> <br>
			<div> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btnnome' value='$btnvalor'> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btncanc' value='Cancelar'></div>
			<input type='hidden' name='cdCliente' value='".$_REQUEST["cdCliente"]."'>
			</form>";
			?>
			


<br><br>
 <nav>
    <div class="nav-wrapper">
      <form action = "index.php?tela=cliente" method = "post">
        <div class="input-field">
          <input id="search" type="search" name = "pesquisa"  placeholder = "Nome ou Código">
          <label class="label-icon" for="Nome"><i class="material-icons">search</i></label>
		 
          <i class="material-icons">close</i>
        </div>
      </form>
    </div>
  </nav>
  
<?php
	$sqlcomando = "select * from tbcliente where 1 = 1 "; 
	if (isset($_REQUEST["pesquisa"])) 
	$sqlcomando .= " and nmCliente like '%".$_REQUEST["pesquisa"]."%' or cdCliente like '%".$_REQUEST["pesquisa"]."%' ";
	$sqlcomando .= "order by nmCliente";
	$sqlprocesso = $conexao->query($sqlcomando);
?>

 <table class="bordered highlight">
        <thead>
          <tr>
              <th>Código</th>
              <th>Nome</th>
			  <th>CPF</th>
			  <th>RG</th>
			  <th>Email</th>
			  <th>Tipo*</th>

          </tr>
        </thead>

        <tbody>
		<?php 
		 if (!isset($_REQUEST["nmCliente"]))$_REQUEST["nmCliente"]="";
		  if (!isset($_REQUEST["CPF"]))$_REQUEST["CPF"]="";
		   if (!isset($_REQUEST["RG"]))$_REQUEST["RG"]="";
		    if (!isset($_REQUEST["email"]))$_REQUEST["email"]="";
			 if (!isset($_REQUEST["senha"]))$_REQUEST["senha"]="";
			 if (!isset($_REQUEST["tipo"]))$_REQUEST["tipo"]="";
		 while ($sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {
								echo "<tr>
								<td>".$sqlretorno["cdCliente"]."
								<td>".$sqlretorno["nmCliente"]."
								<td>".$sqlretorno["CPF"]."
								<td>".$sqlretorno["RG"]."
								<td>".$sqlretorno["email"]."
								<td>".$sqlretorno["tipo"]."
								<td><a href='index.php?tela=cliente&alt=".$sqlretorno["cdCliente"]."'>Editar</a> 
								<td><a href='index.php?tela=cliente&exc=".$sqlretorno["cdCliente"]."'>Excluir</a>";
							}
		 ?>
        </tbody>
		
   </table><p>* Tipo 1 = Administrador<br>Tipo 2 = Cliente</p>
            

 <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>  
      
	  <br><br>
      

    </div>
  </div>