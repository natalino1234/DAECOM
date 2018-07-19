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
include "../functions/palestras.php";
$r = podeLancarPresencaPalestra($con, $_GET["id"], $_SESSION["matricula"]);
if ($r["houveErro"] === "1") {
    echo "<script>alert('" . $r["msg"] . "')</script>";
    echo '<meta http-equiv="refresh" content="0;url=/404">';
}
$sql = ""
        . "select a.alu_matricula, a.alu_nome, t.pap_presenca "
        . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
        . "inner join tb_Aluno as a on (a.alu_matricula = t.alu_matricula) "
        . "where p.pal_autorizacao = 1 AND p.pal_data <= NOW()  AND p.alu_organizacao = '" . $_SESSION["matricula"] . "' "
        . "AND p.pal_id = " . $_GET["id"] . " "
        . "order by a.alu_nome ASC";

$erro = false;
$qry1 = mysqli_query($con, $sql);
if (isset($_POST["btnc"]) && isset($_POST["presente"])) {
    $arr = array();
    $i = 0;
    while ($res1 = mysqli_fetch_array($qry1)) {
        $arr[$i] = array("mat" => $res1["alu_matricula"], "pres" => 0);
        $i++;
    }
    for ($i = 0; $i < count($_POST["presente"]); $i++) {
        for ($j = 0; $j < count($arr); $j++) {
            if ($arr[$j]["mat"] === $_POST["presente"][$i]) {
                $arr[$j]["pres"] = 1;
            }
        }
    }
    $presente = "";
    $ausente = "";
    for ($j = 0; $j < count($arr); $j++) {
        if ($arr[$j]["pres"] == 0) {
            $ausente .= "'" . $arr[$j]["mat"] . "',";
        } else {
            $presente .= "'" . $arr[$j]["mat"] . "',";
        }
    }
    $ausente = "(" . substr($ausente, 0, strlen($ausente) - 1) . ")";
    $presente = "(" . substr($presente, 0, strlen($presente) - 1) . ")";
    $sql1 = "update tb_Pal_Alu_Participar set pap_presenca = 1 where pal_id = " . $_GET["id"] . " AND alu_matricula in " . $presente;
    $r1 = mysqli_query($con, $sql1);
    $sql1 = "update tb_Pal_Alu_Participar set pap_presenca = 0 where pal_id = " . $_GET["id"] . " AND alu_matricula in " . $ausente;
    $r2 = mysqli_query($con, $sql1);
    $msg = "Lista de Presença salva com sucesso.";
    $erro = true;
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
            <div id='content'>
                <div id="title">Lançar Presenças</div>
                <?php
                $id = $_GET["id"];
                $r = getPalestra($con, $id);
                $palestra = null;
                if ($r["houveErro"] !== "1") {
                    $palestra = mysqli_fetch_array($r["return"]);
                }
                ?>
                <h2>
                    <?php echo $palestra["pal_nome"] ?>
                </h2>
                <p>
                    <?php echo Date("d/m/Y", strtotime($palestra["pal_data"])) ?>
                </p>
                <form id="normal"  action="/Palestra/Presenca/<?php echo $_GET["id"] ?>" method="POST">
                    <table class="list">
                        <tr>
                            <th style="width: 170px;">Matrícula
                            <th>Nome
                            <th style="width: 130px;">Presença
                                <?php
                                $qry = mysqli_query($con, $sql);
                                if ($qry) {
                                    while ($res = mysqli_fetch_array($qry)) {
                                        ?>
                                <tr>
                                    <td><?php if ($_SESSION["tipo_usuario"] === "1") {
                                            ?>
                                            <a href="/Aluno/View/<?php echo $res["pal_palestrante"] ?>" target="_blank">
                                                <?php echo $res["alu_matricula"] ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php echo $res["alu_matricula"] ?>
                                            <?php
                                        }
                                        ?>
                                    <td><?php echo $res["alu_nome"]
                                        ?>
                                    <td><div class="input" style="margin: 0;"><input id="bmatricula" type='checkbox' name='presente[]' value='<?php echo $res["alu_matricula"] ?>' <?php
                                            if (isset($res["pap_presenca"])) {
                                                if ($res["pap_presenca"] === "1") {
                                                    echo "checked";
                                                }
                                            }
                                            ?>/>
                                            <label for="busca"><span><span></span></span>Presente</label></div>
                                        <?php
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