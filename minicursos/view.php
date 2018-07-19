<?php
session_start();
if (isset($_SESSION['matricula'])) {
    if ($_SESSION["matricula"] === "") {
        header("location: /Logout");
    }
} else {
    header("location: /Login?dest=" . $_SERVER['PHP_SELF']);
}
include "../functions/minicursos.php";
include "../functions/alunos.php";
include "../functions/conexao.php";
$deuRuim = false;
$nome = "";
$aluno = "";
$minicursonte = "";
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
    $arr = getMinicurso($con, $_GET["id"]);
    if ($arr["houveErro"] === 1) {
        $deuRuim = true;
    } else {
        $result = $arr["return"];
        if (mysqli_num_rows($result) > 0) {
            $minicurso = mysqli_fetch_array($result);
            if (validaMatricula($minicurso["min_professor"])) {
                $arr = getAluno($con, $minicurso["min_professor"]);
                if ($arr["houveErro"] === 1) {
                    $deuRuim = true;
                    echo $arr["erro"];
                } else {
                    $result1 = $arr["return"];
                    $aluno = mysqli_fetch_array($result1);
                    $minicursonte = $aluno['alu_nome'];
                }
            } else {
                $minicursonte = $minicurso['min_professor'];
            }
            $autorizacao = $minicurso["min_autorizacao"];
            $nome = $minicurso["min_nome"];
            $cargahoraria = $minicurso["min_cargahoraria"];
            $ttvagas = $minicurso["min_vagas"];
            $banner = $minicurso["min_banner"];
            $descricao = $minicurso["min_descricao"];
            if (validaMatricula($minicurso["alu_organizacao"])) {
                $arr = getAluno($con, $minicurso["alu_organizacao"]);
                if ($arr["houveErro"] === 1) {
                    $deuRuim = true;
                    echo $arr["erro"];
                } else {
                    $result1 = $arr["return"];
                    $aluno = mysqli_fetch_array($result1);
                    $organizacao = $aluno['alu_nome'];
                }
            } else {
                $deuRuim = true;
            }
            $arr = quantidadeDeInscritos($con, $minicurso["min_id"]);
            if ($arr["houveErro"] === 1) {
                $deuRuim = true;
                echo $arr["erro"];
            } else {
                $result1 = $arr["lista"];
                $qnt = mysqli_fetch_array($result1);
                $vagasocupadas = $qnt['qntd'];
            }
            $arr = estouInscritoNesseMinicurso($con, $minicurso["min_id"], $_SESSION["matricula"]);
            if ($arr["houveErro"] === 1) {
                $deuRuim = true;
                echo $arr["erro"];
            } else {
                $r = $arr["lista"];
                if (mysqli_num_rows($r) > 0) {
                    $inscrito++;
                }
            }
            $sqlh = "select * from tb_Min_Horario where min_id = " . $_GET["id"];
            $r = mysqli_query($con, $sqlh);
            if (!$r) {
                $deuRuim = true;
            }
        } else {
            $deuRuim = true;
        }
    }
} else {
    $deuRuim = true;
}
if ($deuRuim) {
    if (isset($_SESSION["tipo_usuario"])) {
//        echo "<meta http-equiv='refresh' content='0;url=/Admin/Minicursos' />";
    } else {
//        echo "<meta http-equiv='refresh' content='0;url=/Minicursos/Disponiveis' />";
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="icon" href="/icon.png" type="image/png"/>
        <title>Minicursos - DA-ECOM - CEFET-MG Campus Timóteo</title>
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
                                executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"pal": pal}, "Minicurso Aceita.", "", undefined, function (element) {
                                    element.reload();
                                });
                            } else if (acao === "deny") {
                                executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"id": mat}, "Minicurso Rejeitada.", "", undefined, function (element) {
                                    element.reload();
                                });
                            }
        <?php
    }
} else {
    if ($inscrito === 0) {
        ?>
                            if (acao === "signup") {
                                executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"pal": pal}, "Minicurso Aceita.", "", undefined, function (element) {
                                    element.reload();
                                });
                            }
        <?php
    } else {
        ?>
                            if (acao === "leave") {
                                executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"pal": pal}, "Minicurso Aceita.", "", undefined, function (element) {
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
                <h3><?php echo $minicursonte ?></h3>
                <table style="margin-left: 50px;">
                    <tr>
                        <td>

                            <?php
                            $sqlMinicurso = ""
                                    . "select Distinct p.min_id, p.min_autorizacao, p.alu_organizacao as min_organizacao, p.min_nome, t.map_cod, "
                                    . "p.min_professor, p.min_dataprimaula, MIN(h.mih_dia) as min_dia, MAX(h.mih_dia) as max_dia, count(p.min_id) as qtdregs, "
                                    . " t.alu_matricula, count(h.mih_dia) as tt_aulas , count(pres.alu_matricula) as tt_presencas "
                                    . "from tb_Minicurso as p left join tb_Min_Alu_Participar as t on (p.min_id = t.min_id) inner join tb_Min_Horario "
                                    . "as h on (h.min_id = p.min_id) left join tb_Min_Alu_Presenca as pres on (pres.min_id = p.min_id AND pres.alu_matricula = " . $_SESSION["matricula"] . ")"
                                    . "where p.min_id = ".$_GET["id"]." group by p.min_id "
                                    . "order by p.min_id DESC";
                            $res = mysqli_query($con, $sqlMinicurso);
                            $res = mysqli_fetch_array($res);

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
                                if (strtotime($res["min_dia"]) > strtotime(Date('Y-m-d'))) {
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
                            }
                            ?>
                    </tr>
                    <tr>
                        <td colspan="2">
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
                        <td colspan="2"><br>Descrição da Minicurso
                    <tr>
                        <td colspan="2" style="max-width: 500px; border-top: 1px #000 solid; padding-top: 20px;" align='justify'>
                            <?php echo $descricao ?>
                    <tr>
                        <td colspan="2">Organizado por <?php echo $organizacao ?>
                    <tr>
                        <td colspan="2">
                            <table class="function"  style="text-align: center; margin-left: 13px; padding: 10px; display: block; width: 550px;">
                                <tr>
                                    <th colspan='4' style="font-size: 16pt;border-bottom: 1px solid #777; margin-bottom: 10px;">
                                        Dias que ocorrerão as aulas:
                                <tr>
                                    <th style="height: 40px; width: 150px;">
                                        Dia
                                    <th style="height: 40px; width: 150px;">
                                        Dia da Semana
                                    <th style="height: 40px; width: 150px;">
                                        Hora de Início
                                    <th style="height: 40px; width: 150px;">
                                        Hora de Término
                                        <?php
                                        if ($r) {
                                            while ($res = mysqli_fetch_array($r)) {
                                                $dia = $res["mih_dia"];
                                                $diasemana = Date("w", strtotime($res["mih_dia"]));
                                                if ($diasemana === "0") {
                                                    $diasemana = "Domingo";
                                                } else if ($diasemana === "1") {
                                                    $diasemana = "Segunda-Feira";
                                                } else if ($diasemana === "2") {
                                                    $diasemana = "Terça-Feira";
                                                } else if ($diasemana === "3") {
                                                    $diasemana = "Quarta-Feira";
                                                } else if ($diasemana === "4") {
                                                    $diasemana = "Quinta-Feira";
                                                } else if ($diasemana === "5") {
                                                    $diasemana = "Sexta-Feira";
                                                } else if ($diasemana === "6") {
                                                    $diasemana = "Sábado";
                                                }
                                                ?>
                                        <tr>
                                            <td><?php echo $dia ?>
                                            <td><?php echo $diasemana ?>
                                            <td><?php echo $res["mih_horai"] ?>
                                            <td><?php echo $res["mih_horaf"] ?>
                                                <?php
                                            }
                                        }
                                        ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>