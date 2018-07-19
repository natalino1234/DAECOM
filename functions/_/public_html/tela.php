<nav class=" grey darken-4" role="navigation">
			<div class="nav-wrapper container"><a id="logo-container" href="index.php?tela=inicio" class="brand-logo black-text">  <img class="responsive-img" src= "img/logo.jpg"  style= "padding-top: 5px;"  = width = "185"></a>
				<?php
					if(ISSET($_SESSION["cdUsuario"])){
					$tipo = $_SESSION["tipo"];
					?>
					<ul class="right hide-on-med-and-down">
						<?php if($tipo==1){ echo '<li><a href="index.php?tela=cliente">Cliente</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a  href="index.php?tela=exercicio">Exercício</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a href="index.php?tela=objetivo">Objetivo</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a href="index.php?tela=itensa">Itens Avaliação</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a  href="index.php?tela=treino">Treino</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a href="index.php?tela=mensalidade">Mensalidade</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a href="index.php?tela=conta">Conta</a></li>';}?>

						<li><a href="index.php?tela=sair">Sair</a></li>
					</ul>
					<ul id="nav-mobile" class="side-nav">
						<?php if($tipo==1) { echo '<li><a href="index.php?tela=cliente">Cliente</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a  href="index.php?tela=exercicio">Exercício</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a  href="index.php?tela=objetivo">Objetivo</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a href="index.php?tela=itensa">Itens Avaliação</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a href="index.php?tela=sair">Sair</a></li>';}?>
						<?php if($tipo==1){ echo ' <li><a  href="index.php?tela=treino">Treino</a></li>';}?>
						<?php if($tipo==1){ echo '<li><a href="index.php?tela=mensalidade">Mensalidade</a></li>';}?>
						<li><a href="index.php?tela=conta">Conta</a></li>
					</ul>
					<?php
					}
				?>



				<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">Menu</i></a>
			</div>
		</nav>


		<?php
			if(!ISSET($_SESSION["cdUsuario"]))
			{
				if(ISSET($_REQUEST["tela"]) &&  ($_REQUEST["tela"]=="recuperar2")){

					include "tela_recuperar2.php";
					}else{
					// aqui
					include "tela_inicio.php";
				}

				}else{
				if(ISSET($_REQUEST["tela"])){
					if($_REQUEST["tela"] == "cliente") {
						if ($_SESSION["tipo"] == 1) {
							include "tela_".$_REQUEST["tela"].".php";
						} else {
							echo '<br><br><br><br><h2 class="header center red white-text">Página Indisponivel</h2><br><br><br><br><br>';
						}
					} else {
						include "tela_".$_REQUEST["tela"].".php";
					}
				}else{
						// aqui
						include "tela_inicio.php";
					}
				}


			function validaCPF($cpf = null) {
				// Verifica se um número foi informado
				if(empty($cpf)) {
					return false;
				}
				$cpf = str_replace(".","",$cpf);
				$cpf = str_replace("-","",$cpf);
				// Verifica se o numero de digitos informados é igual a 11
				if (strlen($cpf) != 11) {
					return false;
				}
				// Verifica se nenhuma das sequências invalidas abaixo
				// foi digitada. Caso afirmativo, retorna falso
				else if ($cpf == '00000000000' ||
				$cpf == '11111111111' ||
				$cpf == '22222222222' ||
				$cpf == '33333333333' ||
				$cpf == '44444444444' ||
				$cpf == '55555555555' ||
				$cpf == '66666666666' ||
				$cpf == '77777777777' ||
				$cpf == '88888888888' ||
				$cpf == '99999999999') {
					return false;
					// Calcula os digitos verificadores para verificar se o
					// CPF é válido
					} else {

					for ($t = 9; $t < 11; $t++) {

						for ($d = 0, $c = 0; $c < $t; $c++) {
							$d += $cpf{$c} * (($t + 1) - $c);
						}
						$d = ((10 * $d) % 11) % 10;
						if ($cpf{$c} != $d) {
							return false;
						}
					}

					return true;
				}
			}
		?>

		 <!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Deseja continuar?</h4>
    </div>
    <div class="modal-footer">
      <a href="javascript:void(0)" id="btnSim" class="modal-action modal-close waves-effect waves-green btn-flat">Sim</a>
      <a href="javascript:void(0)" id="btnNao" class="modal-action modal-close waves-effect waves-green btn-flat">Não</a>
    </div>
  </div>
		<footer class="page-footer grey darken-4">
			<div class="container">
				<div class="row">
					<div class="col l6 s12">
						<h5 class="white-text">Descrição</h5>
						<p class="grey-text text-lighten-4">Gerencie sua academia em qualquer lugar</p>


					</div>
					<div class="col l3 s12">
						<h5 class="white-text">Lojas Parceiras</h5>
						<ul>
							<li><a class="white-text" href="http://www.netshoes.com.br/artigos-esportivos/loja-de-suplemento?campaign=gglepqcmp&cm_mmc=lp__ggle-_-__tcb_pqcmp__-_-txt-_-_var_links_Loja_de_Suplemento&gclid=CPXjoq-i5NQCFRAGkQodSnMGMQ">Netshoes</a></li>
							<li><a class="white-text" href="http://www.centralfit.com.br/">CentralFit</a></li>
							<li><a class="white-text" href="http://www.adidas.com.br/">Adidas</a></li>

						</ul>
					</div>
					<div class="col l3 s12">
						<h5 class="white-text">Contatos</h5>
						<ul>
							<li><a class="white-text" href="#!">Lucas - (38) 99132-1221 </a></li>
							<li><a class="white-text" href="#!">Matheus  - (33) 98412-0246 </a></li>
							<li><a class="white-text" href="#!">Pedro  - (31) 988720-0393</a></li>

						</ul>
					</div>
				</div>
			</div>
			<div class="footer-copyright">
				<div class="container">
					Desenvolvido por <a class=" orange-text text-lighten-3" href=  "https://www.facebook.com/lucassamuelfernandesoliveira">Lucas Samuel, </a>
					<a class="orange-text text-lighten-3" href=  "https://www.facebook.com/matheus.augusto.359126">Matheus Augusto, </a>
					<a class="orange-text text-lighten-3" href=  "https://www.facebook.com/pedro.bruno.10">Pedro Bruno</a>
				</div>
			</div>
			<div class="footer-copyright">
				<div class="container">
					Todos os direitos Reservados©
				</div>
			</div>
		</footer>
