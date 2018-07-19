<?php
session_start();
if (isset($_SESSION['matricula'])) {
    if ($_SESSION["matricula"] === "") {
        header("location: /Logout");
    }
} else {
    header("location: /Login?dest=" . $_SERVER['PHP_SELF']);
}
include "../functions/conexao.php";
include "../functions/alunos.php";
include "../functions/minicursos.php";
$r = podeLancarPresencaMinicurso($con, $_GET["id"], $_SESSION["matricula"]);
$id = $_GET["id"];
if ($r["houveErro"] === "1") {
    echo "<script>alert('" . $r["msg"] . "')</script>";
    echo '<meta http-equiv="refresh" content="0;url=/404">';
}
$sqlCurso = "Select * from tb_Minicurso where min_id = " . $id;
$resCurso = mysqli_query($con, $sqlCurso);
$resCurso = mysqli_fetch_array($resCurso);
/* Selecionar os alunos do minicurso. */
$sql = ""
        . "select a.alu_matricula, a.alu_nome "
        . "from tb_Minicurso as p inner join tb_Min_Alu_Participar as t on (p.min_id = t.min_id) "
        . "inner join tb_Aluno as a on (a.alu_matricula = t.alu_matricula) "
        . "where p.min_autorizacao = 1 AND p.alu_organizacao = '" . $_SESSION["matricula"] . "' "
        . "AND p.min_id = " . $id . " "
        . "order by a.alu_nome ASC";
$qry1 = mysqli_query($con, $sql);
/* Selecionar os dias do minicurso. */
$sqldias = ""
        . "select t.mih_dia, t.mih_dia, t.mih_horai as mih_hora, t.mih_id "
        . "from tb_Minicurso as p inner join tb_Min_Horario as t on (p.min_id = t.min_id) "
        . "where p.min_autorizacao = 1 AND p.alu_organizacao = '" . $_SESSION["matricula"] . "' "
        . "AND p.min_id = " . $_GET["id"] . " AND mih_dia<NOW() "
        . "order by t.mih_dia ASC";
$rdias = mysqli_query($con, $sqldias);
$alunos = array();
$countA = 0;
while ($l = mysqli_fetch_array($qry1)) {
    $alunos[$countA] = $l;
    $countA++;
}
$horarios = array();
$countH = 0;
while ($l = mysqli_fetch_array($rdias)) {
    $horarios[$countH] = $l;
    $countH++;
}
if ($resCurso["min_lancou_presenca"] == 0) {
    $inserts = "insert into tb_Min_Alu_Presenca (min_id, alu_matricula, mapr_horario, mapr_presenca) Values ";
    for ($y = 0; $y < $countH; $y++) {
        for ($j = 0; $j < $countA; $j++) {
            $inserts .= "('$id','" . $alunos[$j]["alu_matricula"] . "'," . $horarios[$y]["mih_id"] . ", '0')";
            if ($j < $countA - 1) {
                $inserts.=", ";
            }
        }
        if ($y < $countH - 1) {
            $inserts.=", ";
        }
    }
    $resultInsert = mysqli_query($con, $inserts);
    $edit = "Update tb_Minicurso set min_lancou_presenca = 1 where min_id = " . $id;
    mysqli_query($con, $edit);
}
for ($y = 0; $y < $countH; $y++) {
    for ($j = 0; $j < $countA; $j++) {
        $alunos[$alunos[$j]["alu_matricula"]][$horarios[$y]["mih_id"]] = 0;
    }
}
/* Selecionar os dias que os alunos estiveram presentes ou não. */
$sqlpresencas = ""
        . "select pres.mapr_horario, pres.alu_matricula, pres.mapr_presenca "
        . "from tb_Min_Alu_Presenca as pres inner join tb_Min_Horario as h on (pres.mapr_horario = h.mih_id)"
        . "where pres.min_id = " . $id . " "
        . "AND h.mih_dia < NOW() group by pres.mapr_horario "
        . "order by pres.alu_matricula ASC";
$listadepresencas = mysqli_query($con, $sqlpresencas);
while ($presenca = mysqli_fetch_array($listadepresencas)) {
    for ($j = 0; $j < $countA; $j++) {
        $alunos[$presenca["alu_matricula"]][$presenca["mapr_horario"]] = $presenca["mapr_presenca"];
    }
}
$erro = false;
if (isset($_POST["btnc"])) {

    //Zerando lista de presença para ser salva
    for ($y = 0; $y < $countH; $y++) {
        for ($j = 0; $j < $countA; $j++) {
            $alunos[$alunos[$j]["alu_matricula"]][$horarios[$y]["mih_id"]] = 0;
        }
    }
    $keys = array_keys($_POST);
    for ($y = 0; $y < $countH; $y++) {
        foreach ($keys as $key) {
            if (str_replace("presente", "", $key) === $horarios[$y]["mih_id"]) {
                if ($key !== "btnc") {
                    $presentes = $_POST[$key];
                    foreach ($presentes as $presente) {
                        $alunos[$presente][$horarios[$y]["mih_id"]] = 1;
                    }
                }
            }
        }
    }
    //Criando Updates para gravar no banco de dados
    $updates = "";
    for ($y = 0; $y < $countH; $y++) {
        for ($j = 0; $j < $countA; $j++) {
            $updates.="Update tb_Min_Alu_Presenca set mapr_presenca=" . $alunos[$alunos[$j]["alu_matricula"]][$horarios[$y]["mih_id"]] .
                    " where min_id=" . $id . " And alu_matricula = '" . $alunos[$j]["alu_matricula"] . "' And mapr_horario=" . $horarios[$y]["mih_id"] . "; ";
        }
    }
    $res = mysqli_query($con, $updates);
    $msg = "Lista de Presença salva com sucesso.";
    $erro = true;
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
            @import url("/css/alert.css");
            @import url("/css/tooltips.css");
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src='/js/jquery.js'></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/tooltips.js'></script>
        <script src='/js/alert.js'></script>
        <?php if ($erro) { ?>
            <script>
                function alertUser(mensagem) {
                    tempo = mensagem.split(" ").length * 1000;
                    $("#alert .mensagem").html(mensagem);
                    $("#alert").fadeIn(500);
                    setTimeout(function () {
                        $("#alert").fadeOut(500);
                    }, tempo);
                }
                $(document).ready(function () {
                    alertUser("<?php echo $msg ?>");
                });
            </script>
        <?php } ?>
    </head>
    <body>
        <div id='topo'></div>
        <a class='ancora tooltips' id='toTop' href='#topo'>
            <span>Voltar ao Topo</span>
            <i class="fa fa-arrow-up"></i>
        </a>
        <div id='alert'>
            <div class='alert-top-bar'><i class="fa fa-warning"></i>Aviso do Sistema<i class="fa fa-times-circle"></i></div>
            <div class='mensagem'></div>
        </div>
        <div id='page-container'>
            <?php include "../menu.php"; ?>
            <div id='content' style="overflow-x: auto;">
                <div id="title">Lançar Presenças</div>
                <?php
                $r = getMinicurso($con, $id);
                $minicurso = null;
                if ($r["houveErro"] !== "1") {
                    $minicurso = mysqli_fetch_array($r["return"]);
                }
                ?>
                <h2>
                    <?php echo $minicurso["min_nome"] ?>
                </h2>
                <form id="normal"  action="/Minicurso/Presenca/<?php echo $_GET["id"] ?>" method="POST">
                    <table class="list">
                        <tr>
                            <th style="width: 170px;" rowspan="2">Matrícula
                            <th style="width: 290px;"rowspan="2">Nome
                            <th colspan="<?php echo $countH ?>">Presenças
                        <tr>
                            <?php for ($y = 0; $y < $countH; $y++) { ?>
                                <th style="width: 130px;"><?php echo $horarios[$y]["mih_dia"] . " " . $horarios[$y]["mih_hora"] ?>
                                    <?php
                                }
                                if ($resCurso["min_lancou_presenca"] == 1) {
                                    for ($i = 0; $i < $countA; $i++) {
                                        ?>
                                <tr>
                                    <td><a href="/Aluno/View/<?php echo $alunos[$i]["alu_matricula"] ?>" target="_blank"><?php echo $alunos[$i]["alu_matricula"] ?></a>
                                    <td><?php echo $alunos[$i]["alu_nome"] ?>
                                        <?php for ($y = 0; $y < $countH; $y++) { ?>
                                        <td><div class="input" style="margin: 0;"><input id="bmatricula" type='checkbox' name='presente<?php echo $horarios[$y]["mih_id"] ?>[]' value='<?php echo $alunos[$i]["alu_matricula"] ?>' <?php
                                                if ($alunos[$alunos[$i]["alu_matricula"]][$horarios[$y]["mih_id"]] == 1) {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for="presente<?php echo $horarios[$y]["mih_id"] ?>[]"><span><span></span></span>Presente</label></div>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                    </table>
                    <span align="right" style="text-align: right;">
                        <span style="display: inline-block; position: absolute; right: 50px;">
                            <div class="input submit">
                                <i class="fa fa-send"></i>
                                <input id="submit" type="submit" name="btnc" value="Salvar Lista de Presenças">
                            </div>
                        </span>
                    </span>
                </form>
            </div>
        </div>
    </body>
</html>