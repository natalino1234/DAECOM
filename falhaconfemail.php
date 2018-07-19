<?php
include "./functions/conexao.php";
include "./functions/functions.php";
include "./functions/alunos.php";
session_start();
$redirect = false;
$insertGoTo = "";
if (isset($_GET['matricula'])) {
    if ($_GET["matricula"] === "") {
        echo "<script>alert('A URL é inválida.');</script>";
        $insertGoTo = "/";
        $redirect = true;
    }
    $m = array("nome" => "matricula", "texto" => $_SESSION['matricula'], "valido" => 1);
    if ($m["valido"]) {
        $sql = "Select alu_nome, alu_email, alu_codver from tb_Aluno where alu_matricula='" . $m["texto"] . "'";
        $r = mysqli_query($con, $sql);
        if ($r) {
            $r = mysqli_fetch_array($r);
            $nome = $r["alu_nome"];
            $destino = $r["alu_email"];
            $codver = $r["alu_codver"];
            $nerro = enviarEmailConfCadastro($nome, $codver, $destino, $m["texto"]);
            if ($nerro) {
                echo "<script>alert('Verifique se o email foi enviado (confira a Caixa de SPAM's), caso contrário tente novamente.');</script>";
            } else {
                echo "<script>alert('O sistema não conseguiu enviar o E-mail de Confirmação, tente novamente.');</script>";
            }
        }
    }
} else {
    echo "<script>alert('A URL é inválida.');</script>";
    $insertGoTo = "/";
    $redirect = true;
}
if ($redirect) {
    echo "<meta http-equiv='refresh' content='0;url=$insertGoTo' />";
}
?>

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
            #content{
                height: 100%;
                padding-bottom: 0px; 
            }
            form{
                position: relative;
                top:50%;
                transform: translateY(-50%);
            }
            form#normal{
                text-align: center;
            }
            #top-ban{
                background-image: url(/img/banner-top-1000.png); 
            }
            #sidebar{
                position: fixed;
                min-height: inherit;
                height: 100%;
            }
            h2{
                margin-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src="/js/jquery.js"></script>
        <script src="/js/scriptl.js"></script>
        <script src="/js/vcad.js"></script>
        <script src='/admin/js/backtop.js'></script>
        <script src='/admin/js/forms.js'></script>
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
            <script src='/admin/js/menu.js'></script>
            <div id='sidebar'>
                <img src="/admin/img/menuadmin.png" width="230px" height="230px">
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
                <form id="normal" action="falhaconfemail.php?matricula=<?php
                if (isset($_GET["matricula"])) {
                    echo $_GET["matricula"];
                }
                ?>" method="post">
                    <h2>Erro ao enviar E-mail de Confirmação</h2>
                    Houve uma falha no envio do seu E-mail de Confirmação, esta página é para você tentar enviar este e-mail.<br>
                    Clique no botão abaixo para Enviar seu E-mail de Confirmação:
                    <div class="input submit">
                        <input align="center" type="submit" value="Enviar Email">
                    </div>
                    <h2></h2>
                </form>
            </div>
        </div>
    </body>
</html>