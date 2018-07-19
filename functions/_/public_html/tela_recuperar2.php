<?php
   require_once "./util/conexao.php";
    ?>
<?php
	$btnenviar = "btnenviar";
	function checarEmail($email){

			$sqlcomando = "Select count(*) as qtd  FROM tbusuario where email = '$email'";
			$sqlprocesso =$conexao->query($sqlcomando);
			$qtd = $sqlprocesso->fetch(PDO::FETCH_ASSOC);

			if($qtd["qtd"] > 0){
				echo $qtd;
				return false;
				}else{
				echo $qtd;
				return true;
			}

		}

		if (isset($_REQUEST["btnenviar"])) {
			$email = $_REQUEST["email"];
					if ($_REQUEST["email"]=="") {
					echo '<h5 class="header center red white-text">Campo Vazio</h5>';
				} else {
					$sqlcomando = "Select count(*) as qtd  FROM tbusuario where email = '$email'";
					$sql = $conexao->query($sqlcomando);
					$r = $sql->fetch(PDO::FETCH_ASSOC);
				if ($r["qtd"]>0) {

							$novasenha = substr(md5(time()), 0, 6);
							$cnovasenha = md5($novasenha);
							if (mail($email, "Nova Senha", "Sua nova senha é $novasenha")) {
								$sqlcomando = "UPDATE tbusuario set senha = '$cnovasenha' WHERE email='$email'";
								$sqlprocesso = $conexao->query($sqlcomando);
							}
echo '<meta http-equiv="refresh" content="0;index.php">';
				} else {
				 echo '<h5 class="header center red white-text">Email não Existe</h5>';

						}
					}
				}

 if (!isset($_REQUEST["email"])) $_REQUEST["email"] = "";
echo"
			<form method='post' action='tela_recuperar2.php'>

				<div class = 'container'>
	<div class = 'container'>
	<div class = 'container'>
	<div class = 'container'>
  	 <div class='center input-field  col s6'>
         <input name='email' type='email' class='validate' required='' value='".$_REQUEST["email"]."'>
          <label for='email'>Email</label>
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

			</div>
	</div>
	</div>
	</div>
			<div class = 'center row'> <input type='submit' class= ' waves-effect waves-green btn-flat black white-text' name='$btnenviar' value='Enviar email'>

			</form>";
?>
