<script src='/js/menu.js'></script>
<div id='sidebar'>
    <img src="/img/menuadmin.png" width="230px" height="230px">
    <?php if (isset($_SESSION["matricula"])) { ?>
        <div id='mode'>

            <?php echo $_SESSION["nome_tipo_usuario"];
            ?>
        </div>
    <?php } ?>
    <ul class='sidebar-nav'>
        <li>
            <a href='/'>
                <i class="fa fa-home"></i>
                <span>Início</span>
            </a>
        </li>
        <li>
            <a href='https://www.facebook.com/Diretório-Acadêmico-da-Engenharia-de-Computação-CEFETTimóteo-132287833605034/'>
                <i class="fa fa-facebook-official"></i>
                <span>Página no Facebook</span>
            </a>
        </li>
        <li>
            <a href='https://www.facebook.com/groups/351359788261529/'>
                <i class="fa fa-group"></i>
                <span>Grupo no Facebook</span>
            </a>
        </li>
        <?php if (isset($_SESSION["matricula"])) { ?>
            <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                <li class="admin">
                    <a href='/Admin/Alunos'>
                        <i class="fa fa-graduation-cap"></i>
                        <span>Alunos</span>
                    </a>
                </li>
                <li class="admin">
                    <a href='/Admin/Configuracoes'>
                        <i class="fa fa-cog"></i>
                        <span>Configurações</span>
                    </a>
                </li>
            <?php } ?>
            <li>
                <a>
                    <i class="fa fa-slideshare"></i>
                    <i class="fa fa-angle-left"></i>
                    <span>Palestras</span>
                </a>
                <ul>
                    <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                        <li class="admin">
                            <a href='/Admin/Palestras'>
                                <i class="fa fa-list"></i>
                                <span>Ver Todas</span>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href='/Minha-Conta/Palestras'>
                            <i class="fa fa-archive"></i>
                            <span>Minhas Palestras</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Palestras/Criar'>
                            <i class="fa fa-send"></i>
                            <span>Criar</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Palestras/Disponiveis'>
                            <i class="fa fa-pencil-square"></i>
                            <span>Disponíveis</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Palestras/Negadas'>
                            <i class="fa fa-thumbs-o-down"></i>
                            <span>Negadas</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Palestras/Finalizadas'>
                            <i class="fa fa-certificate"></i>
                            <span>Finalizadas</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a>
                    <i class="fa fa-book"></i>
                    <i class="fa fa-angle-left"></i>
                    <span>Minicursos</span>
                </a>
                <ul>
                    <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                        <li class="admin">
                            <a href='/Admin/Minicursos'>
                                <i class="fa fa-list"></i>
                                <span>Ver Todos</span>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href='/Minha-Conta/Minicursos'>
                            <i class="fa fa-archive"></i>
                            <span>Meus Minicursos</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Minicursos/Criar'>
                            <i class="fa fa-send"></i>
                            <span>Criar</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Minicursos/Disponiveis'>
                            <i class="fa fa-pencil-square"></i>
                            <span>Disponíveis</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Minicursos/Negados'>
                            <i class="fa fa-thumbs-o-down"></i>
                            <span>Negados</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Minicursos/Finalizados'>
                            <i class="fa fa-certificate"></i>
                            <span>Finalizados</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a>
                    <i class="fa fa-car"></i>
                    <i class="fa fa-angle-left"></i>
                    <span>Veículos</span>
                </a>
                <ul>
                    <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                        <li>
                            <a class="admin" href='/Admin/Veiculos'>
                                <i class="fa fa-list"></i>
                                <span>Ver Todos</span>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href='/Veiculos/Criar'>
                            <i class="fa fa-send"></i>
                            <span>Cadastrar</span>
                        </a>
                    </li>
                    <li>
                        <a href='/Minha-Conta/Veiculos'>
                            <i class="fa fa-archive"></i>
                            <span>Meus Veículos</span>
                        </a>
                    </li>
                    <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                        <li>
                            <a target="_blank" class="admin" href='/Admin/Veiculos/ListaPortaria'>
                                <i class="fa fa-file-text-o"></i>
                                <span>Lista para Portaria</span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <li>
                <a href='/Minha-Conta'>
                    <i class="fa fa-universal-access"></i>
                    <span>Sua Conta</span>
                </a>
            </li>
            <li>
                <a href='/Logout'>
                    <i class="fa fa-sign-out"></i>
                    <span>Sair</span>
                </a>
            </li>
        <?php } else { ?>
            <li>
                <a href="/Login">
                    <i class="fa fa-sign-in"></i>
                    <span>Log In</span>
                </a>
            </li>
            <li>
                <a href="/Cadastrar">
                    <i class="fa fa-list-alt"></i>
                    <span>Cadastra-se</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>