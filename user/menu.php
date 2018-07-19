

<style>
	*{
		position: relative;
		z-index:0;
		margin: 0px;
		padding: 0px;
		font-family: Arial, sans-serif;
	}
	body{
		position: relative;
		margin: 0px;
		padding: 0px;
		background: #E8E8E8;
		margin-left: auto;
		margin-right: auto;
		z-index: -1;
	}
	a:link {
		text-decoration:none;
		color: #B5684D;
	}
	/* visited link */
	a:visited {
		color: #B5684D;
	}
	/* mouse over link */
	a:hover {
		color: #B5684D;
	}
	/* selected link */
	a:active {
		color: #B5684D;
	}
	.barra{
		position: fixed;
		top: 0px;
		left: 0px;
		height: 40px;
		width: 100%;
		padding-left: 10px;
		padding-right: 10px;
		background: #3E4C54;
		font-family: Arial, sans-serif;
		float: left;
		margin-left: auto;
		margin-right: auto;
	}
	.barra *{
		float: left;
		display: block;
		z-index: 9999;
		white-space: nowrap;
	}
	.barra ul{
		margin: 0px;
		padding: 0px;
	}
	.barra ul li{
		min-width: 40px;
		min-height: 30px;
		line-height: 40px;
		padding-left: 10px;
		padding-right: 10px;
		text-align: center;
		transition: background 0.5s;
		border: 1px;
	}
	.barra ul li:hover{
		background: #202629;
		width:190px;
	}
	.barra ul li ul{
		position: absolute;
		display: none;
		transition: display 1s;
		min-width: 100px;
		background: #3E4C54;
		top: 100%;
		left: -0px;
	}
	.barra ul li ul li{
		width: 190px;
		top: inherit;
	}
	.barra ul li ul li img{
		-webkit-filter: grayscale(100%);
		filter: grayscale(100%);
		transition: filter 1s;
		transition: -webkit-filter 1s;
	}
	.barra ul li ul li:hover img{
		-webkit-filter: grayscale(0%);
		filter: grayscale(0%);
	}
	.content{
		width: 950px;
		margin-left: auto;
		margin-right: auto;
	}
</style>
<script>
$(document).ready(function(){
	$("li").mouseenter(function(){
		var ul = $(this).children("ul");
		if(ul !== undefined){
			ul.animate({ height: 'show' });
			var t = ul.children("li").css("width");
			$(this).animate({"min-width": t},5000);
		}
	});
	$("li").mouseleave(function(){
		var ul = $(this).children("ul");
		if(ul !== undefined){
			ul.animate({ height: 'hide' }); 
			$(this).animate({"min-width": "40px"});
		}
	});
});
</script>
<div class="barra">
	<ul>
		<a href="/">
			<li style="padding: 0px;">
				<img src="/img/home-icon.png" height="40px" title="Home">
				<ul>
					<a href="https://www.facebook.com/Diretório-Acadêmico-da-Engenharia-de-Computação-CEFETTimóteo-132287833605034/">
						<li title="Página do Facebook"><img src="/img/logo-face2.png" width="40px">Página no Facebook</li>
					</a>
					<a href="https://www.facebook.com/groups/351359788261529/">
						<li title="Grupo do Facebook"><img src="/img/group-face2.png" width="40px">Grupo no Facebook</li>
					</a>
				</ul>
			</li>
		</a>
		<a href="/Logout">
			<li style="padding: 0px;">
				<img src="/img/config-icon.png" height="40px" title="Configurações">
				<ul>
					<a href="/Minha-Conta"><li style="width: 150px" title="Minha Conta"><img src="/img/ident-192px.png" width="40px">Minha Conta</li></a>
					<a href="/Logout"><li style="width: 150px" title="Sair"><img src="/img/sair-icon.png" width="40px">Sair</li></a>
				</ul>
			</li>
		</a>
		<a href="/Palestras">
			<li>
				Palestras
				<ul>
					<a href="/Palestras/Disponiveis"><li>Palestras Disponíveis</li></a>
					<a href="/Minha-Conta/Palestras"><li>Minhas Palestras</li></a>
					<a href="/Palestras/Confirmar"><li>Confirmar Presença</li></a>
				</ul>
			</li>
		</a>
		<a href="/Mini-Cursos">
			<li>
				Mini-Cursos
				<ul>
					<a href="/Mini-Cursos/Disponiveis"><li>Mini-Cursos Disponíveis</li></a>
					<a href="/Minha-Conta/Mini-Cursos"><li>Meus Mini-Cursos</li></a>
					<a href="/Mini-Cursos/Confirmar"><li>Confirmar Matrícula</li></a>
				</ul>
			</li>
		</a>
		<a href="/Mini-Cursos">
			<li>
				Veículos
				<ul>
					<a href="/Meus-Veiculos"><li>Meus Veículos</li></a>
					<a href="/Meus-Veiculos/Cadastrar"><li>Cadastrar Veículo</li></a>
				</ul>
			</li>
		</a>
	</ul>
</div>