<?php
include "./conexao.php";
$redirect = false;
$insertGoTo = "/";
if (isset($_GET['colunaalter']) && isset($_GET['codver']) && isset($_GET['matricula'])) {
    $mat = gzinflate($_GET['matricula']);
    $alt = gzinflate($_GET['colunaalter']);
    $cod = $_GET['codver'];
    $sql = "Select alu_$alt from tb_Aluno where alu_matricula = '$mat'";
    $r = mysqli_query($con, $sql);
    if (!$r) {
        $redirect = true;
        $insertGoTo = "/";
        echo "<script>alert('Não existe registro das informações passadas.');</script>";
    }
} else {
    $redirect = true;
    $insertGoTo = "/";
    echo "<script>alert('A URL é inválida.');</script>";
}
if ($redirect) {
    echo "<meta http-equiv='refresh' content='0;url=$insertGoTo' />";
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Diretório Acadêmico - Engenharia da Computação - CEFET-MG Campus Timóteo</title>
        <link rel="icon" href="img/icon.png" type="image/png"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <style>
            @import url("/css/fonts.css");
            @import url("/css/style.css");
            @import url("/css/stylemenu.css");
            @import url("/css/forms.css");
            @import url("/css/totop.css");
            @import url("/css/alert.css");
            @import url("/css/tooltips.css");
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
                background: #333c4a;
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
        <!--<script src="/js/vcad.js"></script>-->
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
                return tempo;
            }
            function recuperar() {
                $.ajax({type: "POST", url: "/functions/alterar.php", data: {"senha": $("#senha").val() + "", "confsenha": $("#confsenha").val() + "", "matricula": $("#matricula").val() + ""}})
                        .done(function (data) {
                            if (data === "ok") {
                                tempo = alertUser("Sua senha foi alterada com sucesso. <br>Você será redirecionado em " + tempo + " segundo(s)");
                                setTimeout(function () {
                                    location.href = $("#form").attr("action");
                                }, tempo);
                            } else {
                                alertUser("Falha na alteração, verifique sua senha e confirmação.");
                            }
                        });
            }
        </script>
    </head>
    <body>
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
                <form id="search" action="/index.php" method="post">
                    <br>
                    Preencha o formulário para ALTERAR sua senha:
                    <br>
                    <br>
                    <div class="input">
                        <i class="fa fa-graduation-cap"></i>
                        <input id="matricula" type="text" name="matricula" size="30" maxlength="12" placeholder="Matrícula" value="<?php echo $_GET["matricula"] ?>">
                    </div>
                    <br>
                    <br>
                    <div class="input">
                        <i class="fa fa-asterisk"></i>
                        <input id="senha" type="password" name="senha" size="30" placeholder="Senha" required>
                    </div>
                    <br>
                    <br>
                    <div class="input">
                        <i class="fa fa-asterisk"></i>
                        <input id="confsenha" type="password" name="confsenha" size="30" placeholder="Confirme a Senha" required>
                    </div>
                    <br>
                    <br>
                    <div class="input submit"><br>
                        <input align="right" type="button" value="Alterar" onclick="recuperar()">
                    </div>
                    <br>
                    <br>
                </form>
            </div>
        </div>

    </body>
</html>