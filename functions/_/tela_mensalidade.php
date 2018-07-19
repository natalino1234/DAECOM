<script>
	$(document).ready(function(e){
    	$('.money').mask("#####.##0,00", { reverse: true });
	});
	</script>
<?php 

	$btnnome='pagar';

	$btncanc = 'btncanc';

?>

<div class="section no-pad-bot" id="index-banner">

    <div class="container">

		<h1 class="header center pink-text">Mensalidade</h1>

		

		<div class="row-top right">

		</div>

	</div> 

	

	

	<br><br>

	<nav>

		<div class="nav-wrapper">

			<form action = "index.php?tela=mensalidade" method = "post">

				<div class="input-field">

					<input id="search" type="search" name = "pesquisa"  placeholder = "Nome ou Código">

					<label class="label-icon" for="Nome"><i class="material-icons">search</i></label>

					

					<i class="material-icons">close</i>

				</div>

			</form>

		</div>

	</nav>

	

	<?php

		$sqlcomando = "select * from tbcliente where tipo = 2 "; 

		if (isset($_REQUEST["pesquisa"])&& ($_REQUEST["pesquisa"]!=null))

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

				<th></th>

				

			</tr>

		</thead>

		

        <tbody>

			<?php 

				while ($sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {

					echo "<tr>

					<td>".$sqlretorno["cdCliente"]."

					<td>".$sqlretorno["nmCliente"]."

					<td>".$sqlretorno["CPF"]."

					<td>".$sqlretorno["RG"]."

					<td>".$sqlretorno["email"].

					"<td><a href='index.php?tela=mensalidade&sel=".$sqlretorno["cdCliente"]."'>Realizar Pagamento</a> ";

				}

			?>

		</tbody>

	</table>

	

	<?php 

		if (isset($_REQUEST["sel"])) {

			$id=$_REQUEST["sel"];

			$sql = "select * from tbcliente  where cdCliente='".$id."'";

			$sql = $conexao->query($sql);

			$r = $sql->fetch(PDO::FETCH_ASSOC);

			$_REQUEST["cdCliente"] = $r["cdCliente"];

			$_REQUEST["nmCliente"] = $r["nmCliente"];

			$_REQUEST["CPF"] = $r["CPF"];
			$_REQUEST["Valor"]= $r["valorMensalidade"] * 100;

		?>

		<form method='post' action='index.php?tela=mensalidade'>

			

			<br>

			

			<h4 class='header center pink-text'>Realizar Pagamento</h4>

			

			<div class='row'>

				<div class='center input-field col s6'>

					<?php	echo "	<input name='nmCliente' type='text' class='validate' placeholder=' ' value='".$_REQUEST["nmCliente"]."' disabled>

					<input type='hidden' name='cdCliente' value='".$_REQUEST["cdCliente"]."'>"; ?>

					<label class='active' for='nmCliente'>Nome do cliente</label>

					

				</div>

				

				<div class='input-field col s6'>

					<?php	echo "	<input name='CPF' type='text' data-length='11' id= '' placeholder=' ' value='".$_REQUEST["CPF"]."' disabled> "; ?>

					<label class='active' for='CPF'>CPF</label>

				</div>

				

				

				<div class='input-field col s6'>

					<input id= 'datapag' name="datapag" type='date' placeholder='' value='<?php echo date('Y-m-d');?>'>

					<label class='active' for='datapag'>Data de Pagamento</label>

				</div>

				<div class='input-field col s6'>

					<input name='Valor' type='text' class='money' id= '' step=any placeholder=' ' value='<?php echo $_REQUEST["Valor"]; ?>'>

					<label class='active' for='Valor'>Valor</label>

				</div>

			</div>

			

			<?php echo "<div> <input type='submit' class= 'waves-effect waves-green btn-flat black white-text' value='Realizar Pagamento' name='$btnnome'><input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btncanc' value='Cancelar'></div>"; ?>

			

		</form>

		<?php } 

		

		if (isset($_REQUEST["pagar"])) {

			$cdCliente = $_REQUEST["cdCliente"];

			$dtMensalidade = $_REQUEST["datapag"]; 

			$vlMensalidade = $_REQUEST["Valor"];

			

			

			$sqlcomando = "insert into tbmensalidade (TbCliente_cdCliente, dtMensalidade, vlMensalidade) values ('$cdCliente', '$dtMensalidade', '$vlMensalidade' ) ";

			$sqlprocesso = $conexao->query($sqlcomando);

			

		}



	?>	

	

	<div class="container">

		<h3 class="header center pink-text">Pagamentos</h3>

		

		<div class="row-top right">

		</div>

	</div> 

	<?php 

	if (isset($_REQUEST["exc"])) {

			$cd = $_REQUEST["exc"];

			$sqlcomando = "delete from tbmensalidade where cdMensalidade = $cd";

			$sqlprocesso = $conexao->query($sqlcomando);

			echo '<h5 class="header center green white-text">Excluido com Sucesso</h5>';

		}

		if (isset($_REQUEST["alterar"])) {

			$cdMensalidade = $_REQUEST ["cdMensalidade"];

			$cdCliente = $_REQUEST["cdCliente"];

			$dtMensalidade = $_REQUEST["datapag"]; 

			$vlMensalidade = $_REQUEST["Valor"];

			

			

			$sqlcomando = "update tbmensalidade set TbCliente_cdCliente = '$cdCliente', dtMensalidade = '$dtMensalidade', vlMensalidade = '$vlMensalidade' where cdMensalidade = $cdMensalidade";

			$sqlprocesso = $conexao->query($sqlcomando);

			echo '<h5 class="header center green white-text">Alterado com Sucesso</h5>';

		}	

	?>

	

	<br><br>

	<nav>

		<div class="nav-wrapper">

			<form action = "index.php?tela=mensalidade" method = "post">

				<div class="input-field">

					<input id="search2" type="search" name = "pesquisa2"  placeholder = "Nome ou Código">

					<label class="label-icon" for="Nome"><i class="material-icons">search</i></label>

					

					<i class="material-icons">close</i>

				</div>

			</form>

		</div>

	</nav>

	

	<?php

		$sqlcomando3 = "select * from tbmensalidade "; 

		$sqlcomando3 .= "order by dtMensalidade";

		$sqlprocesso3 = $conexao->query($sqlcomando3);

	?>

	

	<table class="bordered highlight">

        <thead>

			<tr>

				<th>Código</th>

				<th>Nome</th>

				<th>CPF</th>

				<th>Data</th>

				<th>Valor</th>

				<th></th>

				<th></th>

				

			</tr>

		</thead>

		

        <tbody>

			<?php 

				

				while ($sqlretorno3 = $sqlprocesso3->fetch(PDO::FETCH_ASSOC)) {

					$sqlcomando2 = "select * from tbcliente where cdCliente = ".$sqlretorno3["TbCliente_cdCliente"].""; 

					if (isset($_REQUEST["pesquisa2"])&& ($_REQUEST["pesquisa2"]!=null))

					$sqlcomando2 .= " and nmCliente like '%".$_REQUEST["pesquisa2"]."%' or cdCliente like '%".$_REQUEST["pesquisa2"]."%' ";

					$sqlprocesso2 = $conexao->query($sqlcomando2);

					while ($sqlretorno2 = $sqlprocesso2->fetch(PDO::FETCH_ASSOC)) {

						echo "<tr>

						<td>".$sqlretorno2["cdCliente"]."

						<td>".$sqlretorno2["nmCliente"]."

						<td>".$sqlretorno2["CPF"]."

						<td>".date("d/m/Y",strtotime($sqlretorno3["dtMensalidade"]))."

						<td> R$ ". number_format($sqlretorno3["vlMensalidade"],	2,'.',',').

						"<td><a href='index.php?tela=mensalidade&alt=".$sqlretorno3["cdMensalidade"]."'>Alterar</a> </td>

						<td><a href='index.php?tela=mensalidade&exc=".$sqlretorno3["cdMensalidade"]."'>Excluir</a> </td></tr> ";

					}

				}

			?>

		</tbody>

	</table>

	<br>

	<?php 

		if (isset($_REQUEST["alt"])) {

			$id=$_REQUEST["alt"];

			$sql = "select * from tbmensalidade where cdmensalidade='".$id."'";

			$sql = $conexao->query($sql);

			$r = $sql->fetch(PDO::FETCH_ASSOC);

			$cdCliente = $r["TbCliente_cdCliente"];

			$sql2 = "select * from tbcliente where cdCliente = ".$cdCliente."";

			$sql2 = $conexao->query($sql2);

			$r2 = $sql2->fetch(PDO::FETCH_ASSOC);

			$nmCliente = $r2["nmCliente"];

			$CPF = $r2["CPF"];

			$Valor = $r ["vlMensalidade"]*100;

			$Data = $r["dtMensalidade"];

			

		?>

		

		<form method='post' action='index.php?tela=mensalidade'>

			

			<br>

			

			<h4 class='header center pink-text'>Alterar Pagamento</h4>

			

			<div class='row'>

				<div class='center input-field col s6'>

					<?php	echo "	<input name='nmCliente' type='text' class='validate' placeholder=' ' value='".$nmCliente."' disabled>

						<input type='hidden' name='cdCliente' value='".$cdCliente."'>

					<input type='hidden' name='cdMensalidade' value='".$id."'>"; ?>

					<label class='active' for='nmCliente'>Nome do cliente</label>

					

				</div>

				

				<div class='input-field col s6'>

					<?php	echo "	<input name='CPF' type='text' data-length='11' id= '' placeholder=' ' value='".$CPF."' disabled> "; ?>

					<label class='active' for='CPF'>CPF</label>

				</div>

				

				

				<div class='input-field col s6'>

					<input id= 'datapag' name="datapag" type='date' placeholder=' ' value='<?php echo $Data; ?>'>

					<label class='active' for='datapag'>Data de Pagamento</label>

				</div>

				<div class='input-field col s6'>

					<input name='Valor' type='text' class='money' id= '' placeholder=' ' step=any value='<?php echo $Valor; ?>'>

					<label class='active' for='Valor'>Valor</label>

				</div>

			</div>

			

			<?php echo "<div> <input type='submit' class= 'waves-effect waves-green btn-flat black white-text'  value='Alterar Pagamento' name='alterar'><input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btncanc' value='Cancelar'></div>"; ?>

			

		</form>

		<?php } 

		

	?>	<br>

