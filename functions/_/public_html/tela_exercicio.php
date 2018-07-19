 <div class="section no-pad-bot" id="index-banner">

    <div class="container">

      <h1 class="header center pink-text">Exercício</h1>

	  

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

					$sql = "select * from tbexercicio where cdExercicio='".$id."'";

					$sql = $conexao->query($sql);

					$r = $sql->fetch(PDO::FETCH_ASSOC);

					$_REQUEST["cdExercicio"] = $r["cdExercicio"];

					$_REQUEST["nmExercicio"] = $r["nmExercicio"];

					}

					

					

				if (isset($_REQUEST["btnalt"])) {

					if ($_REQUEST["nmExercicio"]=="") {

					echo '<h5 class="header center red white-text">Campo Vazio</h5>';	

				} else {

					$sql = "select count(*) as qtd from tbexercicio 

					where nmExercicio='".$_REQUEST["nmExercicio"]."'

					and cdExercicio <> '".$_REQUEST["cdExercicio"]."'";

					$sql = $conexao->query($sql);

					$r = $sql->fetch(PDO::FETCH_ASSOC);	

				if ($r["qtd"]>0) {

					echo '<h5 class="header center red white-text">Exercício Existente</h5>';	

				} else {

				 $sqlcomando = "update tbexercicio 

					set 

					nmExercicio='{$_REQUEST["nmExercicio"]}'

					where cdExercicio = '{$_REQUEST["cdExercicio"]}'";

					$sqlprocesso = $conexao->query($sqlcomando);

					echo '<h5 class="header center green white-text">Alterado com Sucesso</h5>';	

					$_REQUEST['nmExercicio']="";

						}			 

					}

				}	

	

					if (isset($_REQUEST["btnexc"])) {

						$sqlcomando = "delete from tbexercicio 

						where cdExercicio = '{$_REQUEST["cdExercicio"]}'";

						$sqlprocesso = $conexao->query($sqlcomando);

						echo '<h5 class="header center green white-text">Excluido com Sucesso</h5>';

						$_REQUEST['nmExercicio']="";

					}		

	  	?>

		

		<?php

			if (isset($_REQUEST["btncad"])){

						if(empty($_REQUEST['nmExercicio'])){

							 echo '<h5 class="header center red white-text">Campo Vazio</h5>';

						}else{

								

						     $sqlcomando = "select cdExercicio from tbexercicio where nmExercicio = '{$_REQUEST['nmExercicio']}'";

							 $sqlprocesso = $conexao->query($sqlcomando);

							 $sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC);

							 if($sqlretorno["cdExercicio"]>0){

								 echo '<h5 class="header center red white-text">Exercício Existente</h5>';	

								 $_REQUEST['nmExercicio']="";

							    }else{

									echo '<h5 class="header center green white-text">Salvo com Sucesso</h5>';	

								$cn = $_REQUEST['nmExercicio'];

								$_REQUEST['nmExercicio']="";

								$sqlcomando = "insert into tbexercicio (nmExercicio) values ('$cn')";

								$sqlprocesso = $conexao->query($sqlcomando);

								// estava aqui							

							}

						}

					}

					if (isset($_REQUEST["btncanc"])){

						$_REQUEST['nmExercicio']="";

					}

 ?>



<?php

			

			if (!isset($_REQUEST["cdExercicio"])) $_REQUEST["cdExercicio"]= "";

	        if (!isset($_REQUEST["nmExercicio"])) $_REQUEST["nmExercicio"] = "";

		

			echo"

			<form method='post' action='index.php?tela=exercicio'>

				<br>

				<div class='row'>

				<div class='center input-field col s6'>

					<input name='nmExercicio' type='text' class='validate' placeholder=' ' value='".$_REQUEST["nmExercicio"]."'>

					<label class='active' for='nmExercicio'>Nome do Exercício</label>

				</div>

			</div>

			

			<div> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btnnome' value='$btnvalor'> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btncanc' value='Cancelar'> </div>
			
			<input type='hidden' name='cdExercicio' value='".$_REQUEST["cdExercicio"]."'>

			</form>";

			?>

			



 

 <?php

		

 ?>

 

<br><br>

 <nav>

    <div class="nav-wrapper">

      <form action = "index.php?tela=exercicio" method = "post">

        <div class="input-field">

          <input id="search" type="search" name = "pesquisa"  placeholder = "Nome ou Código">

          <label class="label-icon" for="Nome"><i class="material-icons">search</i></label>

		 

          <i class="material-icons">close</i>

        </div>

      </form>

    </div>

  </nav>

  

<?php

	$sqlcomando = "select * from tbexercicio where 1 = 1 "; 

	if (isset($_REQUEST["pesquisa"])) 

	$sqlcomando .= " and nmExercicio like '%".$_REQUEST["pesquisa"]."%' or cdExercicio like '%".$_REQUEST["pesquisa"]."%' ";

	$sqlcomando .= "order by nmExercicio";

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

		 if (!isset($_REQUEST["nmExercicio"]))$_REQUEST["nmExercicio"]="";

		 while ($sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {

								echo "<tr>

								<td>".$sqlretorno["cdExercicio"]."

								<td>".$sqlretorno["nmExercicio"]."

								<td><a href='index.php?tela=exercicio&alt=".$sqlretorno["cdExercicio"]."'>Editar</a> 

								<td><a href='index.php?tela=exercicio&exc=".$sqlretorno["cdExercicio"]."'>Excluir</a>";

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