<html>
    <head>
        <meta charset="utf-8">
        <title>Diretório Acadêmico - Engenharia da Computação - CEFET-MG Campus Timóteo</title>
        <link rel="icon" href="/icon.png" type="image/png"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <style>
            @import url("/css/fonts.css");
            @import url("/css/style.css");
            @import url("/css/stylemenu.css");
            @import url("/css/forms.css");
            @import url("/css/totop.css");
            @import url("/css/tooltips.css");
            @import url("/css/alert.css");
            #top-ban{
                background-image: url(/img/banner-top-1000.png); 
            }
            #content{
                height: 100%;
                padding-bottom: 0px;
            }
            form#search{
                border-top: 3px solid #79828f;
                position: absolute !important;
                top: 50%;
                transform: translateY(-60%);
                width: 100%;
                background: rgba(51, 60, 74, 0.71);
                color: white;
            }
            #sidebar{
                position: fixed;
                min-height: inherit;
                height: 100%;
            }
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src="/js/jquery.js"></script>
        <script src="/js/scriptl.js"></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/forms.js'></script>
        <script>
            function alertUser(mensagem) {
                tempo = mensagem.split(" ").length * 1000;
                $("#alert .mensagem").html(mensagem);
                $("#alert").fadeIn(500);
                setTimeout(function () {
                    $("#alert").fadeOut(500);
                }, tempo);
            }
            function login() {
                $.ajax({type: "POST", url: "/functions/logar.php", data: {"matricula": $("#matricula").val() + "", "senha": $("#senha").val() + ""}})
                        .done(function (data) {
                            if (data === "ok") {
                                location.href = $("#search").attr("action");
                            } else {
                                alertUser("Falha no login, verifique sua senha e matrícula.");
                            }
                        });
            }
            function esquecisenha() {
                var matricula = $("#matricula").val();
                if (matricula === "") {
                    alertUser("Informe a matrícula no formulário de Login.");
                } else {
                    $.ajax({type: "POST", url: "/functions/alterarcadastro.php", data: {"matricula": $("#matricula").val() + ""}})
                            .done(function (data) {
                                if (data === 1) {
                                    alertUser("Foi enviado um email para você com as instruções.");
                                } else {
                                    alertUser("Falha na recuperação de senha, tente novamente.");
                                }
                            });
                }
            }
            $(document).ready(function () {
                $("#senha, #matricula").keyup(function (e) {
                    if (e.keyCode === 13) {
                        login();
                    }
                });
            });
        </script>
    </head>
    <body>
        <div id='background'><img src="/img/Bloco_B_pan.jpg"></div>
        <div id='topo'></div>
        <a class='ancora tooltips' id='toTop' href='#topo'>
            <span>Voltar ao Topo</span>
            <i class="fa fa-arrow-up"></i>
        </a>
        <div id='alert'>
            <div class='alert-top-bar'><i class="fa fa-warning"></i>Aviso do Sistema<i class="fa fa-times-circle"></i></div>
            <div class='mensagem'><?php if ($exibeAlerta) echo $msg ?></div>
        </div>
        <div id='page-container'>
            <script src='/js/menu.js'></script>
            <div id='sidebar'>
                <img src="/img/menuadmin.png" width="230px" height="230px">
                <ul class='sidebar-nav'>
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
                </ul>
            </div>
            <div id="content">
                <form class="online" id="search" action="<?php echo (isset($_GET["dest"])) ? $_GET["dest"] : "/"; ?>" method="post">
                    <br>
                    <?php
                    if (isset($_GET["login"])) {
                        if ($_GET["login"]) {
                            echo "A sessão criada possui um login inválido, faça login para poder acessar ao Sistema:";
                        } else {
                            echo "Você não está logado, faça login para poder prosseguir:";
                        }
                    } else {
                        echo "O acesso ao Sistema é exclusivo para os alunos:";
                    }
                    ?>
                    <br>
                    <br>
                    <div class="input">
                        <i class="fa fa-graduation-cap"></i>
                        <input id="matricula" type="text" name="matricula" maxlength="12" placeholder="Matrícula" required>
                    </div>
                    <br>
                    <br>
                    <div class="input">
                        <i class="fa fa-asterisk"></i>
                        <input id="senha" type="password" name="senha" placeholder="Senha" required>
                    </div>
                    <br>
                    <br>
                    <div class="input">
                        <a href="/Cadastrar">Criar Conta</a>
                    </div>
                    <br>
                    <div class="input">
                        <a onclick="esquecisenha()">Esqueci minha Senha</a>
                    </div>
                    <br>
                    <div class="input submit"><br>
                        <input align="right" type="button" value="Entrar" onclick="login()">
                    </div>
                    <br>
                    <br>
                </form>
            </div>
    </body>
</html>