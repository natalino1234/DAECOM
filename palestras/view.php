<?php
session_start();
if (isset($_SESSION['matricula'])) {
    if ($_SESSION["matricula"] === "") {
        header("location: /Logout");
    }
} else {
    header("location: /Login?dest=" . $_SERVER['PHP_SELF']);
}
include "../functions/palestras.php";
include "../functions/alunos.php";
include "../functions/conexao.php";
$deuRuim = false;
$nome = "";
$aluno = "";
$palestrante = "";
$data = "";
$hora = "";
$ttvagas = "";
$vagasocupadas = "";
$cargahoraria = "";
$banner = "";
$descricao = "";
$organizacao = "";
$autorizacao = 0;
$inscrito = 0;
$presente = 0;
if (isset($_GET["id"]) && $_GET["id"] !== "") {
    $arr = getPalestra($con, $_GET["id"]);
    if ($arr["houveErro"] === 1) {
        $deuRuim = true;
    } else {
        $result = $arr["return"];
        if (mysqli_num_rows($result) > 0) {
            $palestra = mysqli_fetch_array($result);
            if (validaMatricula($palestra["pal_palestrante"])) {
                $arr = getAluno($con, $palestra["pal_palestrante"]);
                if ($arr["houveErro"] === 1) {
                    $deuRuim = true;
                } else {
                    $result1 = $arr["return"];
                    $aluno = mysqli_fetch_array($result1);
                    $palestrante = $aluno['alu_nome'];
                }
            } else {
                $palestrante = $palestra['pal_palestrante'];
            }
            $autorizacao = $palestra["pal_autorizacao"];
            $nome = $palestra["pal_nome"];
            $data = $palestra["pal_data"];
            $dia = date('d/m/Y', strtotime($palestra["pal_data"]));
            $hora = $palestra["pal_hora"];
            $cargahoraria = $palestra["pal_cargahoraria"];
            $ttvagas = $palestra["pal_vagas"];
            $banner = $palestra["pal_banner"];
            $descricao = $palestra["pal_descricao"];
            if (validaMatricula($palestra["alu_organizacao"])) {
                $arr = getAluno($con, $palestra["alu_organizacao"]);
                if ($arr["houveErro"] === 1) {
                    $deuRuim = true;
                } else {
                    $result1 = $arr["return"];
                    $aluno = mysqli_fetch_array($result1);
                    $organizacao = $aluno['alu_nome'];
                }
            } else {
                $deuRuim = true;
            }
            $arr = quantidadeDeInscritos($con, $palestra["pal_id"]);
            if ($arr["houveErro"] === 1) {
                $deuRuim = true;
            } else {
                $result1 = $arr["lista"];
                $qnt = mysqli_fetch_array($result1);
                $vagasocupadas = $qnt['qntd'];
            }
            $arr = estouInscritoNessaPalestra($con, $palestra["pal_id"], $_SESSION["matricula"]);
            if ($arr["houveErro"] === 1) {
                $deuRuim = true;
            } else {
                $r = $arr["lista"];
                if (mysqli_num_rows($r) > 0) {
                    $inscrito++;
                }
            }
        } else {
            $deuRuim = true;
        }
    }
} else {
    $deuRuim = true;
}
if ($deuRuim) {
    echo "<meta http-equiv='refresh' content='0;url=/Admin/Palestras' />";
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="icon" href="/icon.png" type="image/png"/>
        <title>Palestras - DA-ECOM - CEFET-MG Campus Timóteo</title>
        <style>
            @import url("/css/fonts.css");
            @import url("/css/style.css");
            @import url("/css/stylemenu.css");
            @import url("/css/properties.css");
            @import url("/css/forms.css");
            @import url("/css/tables.css");
            @import url("/css/totop.css");
            @import url("/css/tooltips.css");
            td{
                padding: 5px;
            }
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src='/js/jquery.js'></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/forms.js'></script>
        <script>
            $(document).ready(function () {
                $(document).on("click", ".colAcao a", function () {
                    var acao = $(this).attr("act");
                    var pal = $(this).attr("pal");
<?php
if ($autorizacao === 0) {
    if ($_SESSION["tipo_usuario"] === "Admin") {
        ?>
                            if (acao === "allow") {
                                executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"pal": pal}, "Palestra Aceita.", "", undefined, function (element) {
                                    element.reload();
                                });
                            } else if (acao === "deny") {
                                executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"id": mat}, "Palestra Rejeitada.", "", undefined, function (element) {
                                    element.reload();
                                });
                            }
        <?php
    }
} else {
    if ($inscrito === 0) {
        ?>
                            if (acao === "signup") {
                                executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"pal": pal}, "Palestra Aceita.", "", undefined, function (element) {
                                    element.reload();
                                });
                            }
        <?php
    } else {
        ?>
                            if (acao === "leave") {
                                executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"pal": pal}, "Palestra Aceita.", "", undefined, function (element) {
                                    element.reload();
                                });
                            }
        <?php
    }
}
?>
                });
            });
        </script>
    </head>
    <body>
        <div id='topo'></div>
        <a class='ancora tooltips' id='toTop' href='#topo'>
            <span>Voltar ao Topo</span>
            <i class="fa fa-arrow-up"></i>
        </a>
        <div id='page-container'>
            <div class='image-preview'>
                <div>Imagem para Upload</div>
                <img id="image">
            </div>
            <?php include "../menu.php"; ?>
            <div id='content' style="padding-top: 20px;">
                <h2><?php echo $nome ?></h2>
                <h3><?php echo $palestrante ?></h3>
                <table style="margin-left: 50px;">
                    <tr>
                        <td>

                            <?php
                            if ($autorizacao === "0") {
                                if ($_SESSION["tipo_usuario"] === "1") {
                                    ?>
                                    <a class="btview" ac="allow" pal="<?php echo $_GET["id"] ?>">
                                        <div class="acao tooltips-u">
                                            <i class="fa fa-thumbs-o-up"></i>
                                            Aceitar Pedido
                                        </div>
                                    </a>
                                <td>
                                    <a class="btview" ac="deny" pal="<?php echo $_GET["id"] ?>">
                                        <div class="acao tooltips-u">
                                            <i class="fa fa-thumbs-o-down"></i>
                                            Negar Pedido
                                        </div>
                                    </a>
                                    <?php
                                }
                            } else {
                                if ($inscrito === 0) {
                                    ?>
                                    <a class="btview" ac="signup" pal="<?php echo $_GET["id"] ?>">
                                        <div class="acao tooltips-u">
                                            <i class="fa fa-pencil-square"></i>
                                            Fazer Inscricao
                                        </div>
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <a class="btview" ac="leave" pal="<?php echo $_GET["id"] ?>">
                                        <div class="acao tooltips-u">
                                            <i class="fa fa-share-square-o"></i>
                                            Desfazer inscrição
                                        </div>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                    </tr>
                    <tr>
                        <td colspan="2">Marcado para <?php echo $data ?> às <?php echo $hora ?>
                            <?php if ($banner !== "") { ?>
                            <td rowspan="3" valign='top'>
                                <a href="../img/banner-top-1200.png" target="_blank">
                                    <img src="../img/banner-top-1200.png" width="300px" align="right">
                                </a>
                            <?php } ?>
                    <tr>
                        <td>Total de Vagas: <?php echo $ttvagas ?>
                        <td>Ocupadas: <?php echo $vagasocupadas ?>
                    <tr>
                        <td colspan="2">Carga Horária: <?php echo $cargahoraria ?>hs
                    <tr>
                        <td colspan="2"><br>Descrição da Palestra
                    <tr>
                        <td colspan="2" style="max-width: 500px; border-top: 1px #000 solid; padding-top: 20px;" align='justify'>
                            <?php echo $descricao ?>
                    <tr>
                        <td colspan="2">Organizado por <?php echo $organizacao ?>
                </table>
            </div>
        </div>
    </body>
</html>