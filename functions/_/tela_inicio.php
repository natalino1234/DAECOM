<?php
   require_once "./util/conexao.php";
?>
<div class="section no-pad-bot" id="index-banner">
    <div class="container">
		<br><br>

		<h1 class="header center pink-text">Academia BioFitness</h1>
		<div class="row center">
			<h5 class="header col s12 light">Gerencie sua academia em qualquer lugar!!!</h5>
		</div>

		<br><br>

	</div>
</div>

<?php
	$btnlogin = 'btnlogin';

	if (isset($_REQUEST["btnlogin"])) {
		$cs = $_REQUEST['senha'];
		$csc = md5($cs);
		$sqlcomando = "select * from tbusuario where email = '{$_REQUEST["email"]}' and senha = '{$csc}' ";
		$sqlprocesso = $conexao->query($sqlcomando);
		$sqlretorno = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
		if($sqlretorno["cdUsuario"]>0){
			$_SESSION["cdUsuario"]= $sqlretorno["cdUsuario"];
			$_SESSION["email"]= $sqlretorno["email"];
			$sqlcomando2 = "select * from tbcliente where email = '{$_REQUEST["email"]}' and senha = '{$csc}' ";
			$sqlprocesso2 = $conexao->query($sqlcomando2);
			$sqlretorno2 = $sqlprocesso2->fetch(PDO::FETCH_ASSOC);
			$_SESSION["tipo"]=$sqlretorno2["tipo"];
			if($_SESSION["tipo"]== 1){
				echo '<h5 class="header center green white-text">Logado como Administrador</h5>';
				echo '<meta http-equiv="refresh" content="2;index.php?tela=cliente">';
				}else{
				echo '<h5 class="header center green white-text">Logado como Cliente</h5>';
				echo '<meta http-equiv="refresh" content="2;index.php?tela=LoginCliente">';
			}
			}else{
			echo '<h5 class="header center red white-text">Login ou senha Inválidos</h5>';
		}
	}

?>


<?php
	if (!isset($_REQUEST["email"])) $_REQUEST["email"] = "";
	if (!isset($_REQUEST["senha"])) $_REQUEST["senha"] = "";

	echo"
		<form method='post' action='index.php'>

			<div class = 'container'>
				<div class = 'container'>
					<div class = 'container'>
						<div class = 'container'>
							<div class='input-field  col s6'>
								<input name='email' type='email' class='validate'>
								<label for='email'>Email</label>
							</div>

							<div class='input-field col s12 m4 l8'>
								<input name='senha' type='password' class='validate'>
								<label for='password'>Senha</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class = 'container'>
				<div class = 'container'>
					<div class = 'container'>
						<div class = 'container'>
						</div>
						<div class = 'center row'>
							<a class= '  red-text' href=  'index.php?tela=recuperar2'>Esqueci a Senha </a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class = 'center row'> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btnlogin' value='Logar'>

		</form>";
?>





<div class="carousel">
    <a class="carousel-item" href="#one!"><img src="img/fitness.jpg"></a>
    <a class="carousel-item" href="#two!"><img src="img/fitness_stephanie_rao_body_buttocks_back-1680x1050.jpg"></a>
    <a class="carousel-item" href="#three!"><img src="img/mulher-maromba1.jpg"></a>
</div>
