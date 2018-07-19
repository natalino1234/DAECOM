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
$idpag = 0;
if (!isset($_GET["pagregs"])) {
    $quantidadelinhas = 20;
} else {
    $quantidadelinhas = $_GET["pagregs"];
}
if (isset($_GET["pag"])) {
    $idpag = $_GET["pag"] * $quantidadelinhas;
} else {
    $_GET["pag"] = 0;
}
$search = "";
$sql = ""
        . "select p.vei_placa, p.vei_modelo, p.vei_marca, p.vei_tipo, p.vei_inscrito, p.vei_cor, p.vei_adesivo, p.alu_matricula, t.alu_nome "
        . "from tb_Veiculo as p inner join tb_Aluno as t on (p.alu_matricula = t.alu_matricula) "
        . "where p.alu_matricula = '" . $_SESSION["matricula"] . "' "
        . "group by p.vei_placa "
        . "order by p.vei_placa";
$sqlcountregs = "Select count(q.vei_placa) as qtdregs "
        . "from (select p.vei_placa "
        . "from tb_Veiculo as p inner join tb_Aluno as t on (p.alu_matricula = t.alu_matricula) "
        . "where p.alu_matricula = '" . $_SESSION["matricula"] . "' "
        . "group by p.vei_placa) as q";
if (isset($_REQUEST["btn_buscar"])) {
    if ($_REQUEST["btn_buscar"]) {
        if ($_REQUEST["campo_busca"] === "") {
            $exibeAlerta = true;
            $msg = "O campo de Busca está vazio.";
        }
        $sql = "select p.vei_placa, p.vei_modelo, p.vei_marca, p.vei_tipo, p.vei_inscrito, p.vei_cor, p.vei_adesivo, p.alu_matricula, t.alu_nome "
                . "from tb_Veiculo as p inner join tb_Aluno as t on (p.alu_matricula = t.alu_matricula) "
                . "where vei_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%' and alu_matricula = '" . $_SESSION["matricula"] . "' "
                . "order by p.vei_placa DESC LIMIT $idpag,$quantidadelinhas";
        $sqlcountregs = "Select count(q.vei_placa) as qtdregs "
                . "from (select p.vei_placa "
                . "from tb_Veiculo as p inner join tb_Aluno as t on (p.alu_matricula = t.alu_matricula) "
                . "where vei_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%' and alu_matricula = '" . $_SESSION["matricula"] . "' "
                . "order by p.vei_placa) as q";
    }
}
if ($search === "") {
    $search = "?";
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="icon" href="/icon.png" type="image/png"/>
        <title>Veiculos - DA-ECOM - CEFET-MG Campus Timóteo</title>
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
        <script>
            $(document).ready(function () {
                $(document).on("click", ".colAcao a", function () {
                    var acao = $(this).attr("act");
                    var pal = $(this).attr("placa");
                    if (acao === "adhesive") {
                        executarAJAX(location, "/functions/veiculos/alterar.php?funct=" + acao, {"id": pal}, "Adesivo Entregue.", "", undefined, function (element) {
                            element.reload();
                        });
                    } else if (acao === "remove") {
                        executarAJAX(location, "/functions/veiculos/alterar.php?funct=" + acao, {"id": pal}, "Veículo Removido.", "", undefined, function (element) {
                            element.reload();
                        });
                    } else if (acao === "renew") {
                        executarAJAX(location, "/functions/veiculos/alterar.php?funct=" + acao, {"id": pal}, "Veículo Removido.", "", undefined, function (element) {
                            element.reload();
                        });
                    }
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
        <div id='alert'>
            <div class='alert-top-bar'><i class="fa fa-warning"></i>Aviso do Sistema<i class="fa fa-times-circle"></i></div>
            <div class='mensagem'></div>
        </div>
        <div id='page-container'>
            <?php include "../menu.php"; ?>
            <div id='content'>
                <div id="title">
                    Meus Veículos
                </div>
                <br>
                <h4 style="color: red; text-align: center;">É necessário entregar os dois termos para o DA que o mesmo será responsável para controlar esses documentos.</h4>
                <table class="list">
                    <tr>
                        <th class="colStatus">Status
                        <th>Placa
                        <th>Modelo

                        <th>Validade
                        <th>Tipo
                        <th>Adesivo
                        <th style="width: 150px;">Ação
                            <?php
                            $qry = mysqli_query($con, $sql);
                            if ($qry) {
                                while ($res = mysqli_fetch_array($qry)) {
                                    $status = "";
                                    if ($res["vei_adesivo"] === '0') {
                                        $status = "<i class='fa fa-thumbs-o-down'></i>";
                                    } else if ($res["vei_adesivo"] === '1') {
                                        $status = "<i class='fa fa-thumbs-o-up'></i>";
                                    }
                                    ?>
                            <tr>
                                <td class='colStatus'><?php echo $status ?>
                                <td><?php echo $res["vei_placa"] ?>
                                <td><?php echo $res["vei_modelo"] ?>
                                <td>
                                    <?php if (strtotime($res["vei_inscrito"]) > strtotime(Date("d/m/Y"))) { ?>
                                        Dentro da validade
                                    <?php } else { ?>
                                        Vencida
                                    <?php } ?>
                                <td><?php echo ucfirst($res["vei_tipo"]) ?>
                                <td>
                                    <?php if ($res["vei_adesivo"] === "1") { ?>
                                        Entregue
                                    <?php } else { ?>
                                        Não Entregue
                                    <?php } ?>
                                <td class="colAcao">
                                    <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                                        <?php if ($res["vei_adesivo"] === "0") { ?>
                                            <a act="adhesive" placa="<?php echo $res["vei_placa"] ?>">
                                                <div class="acao tooltips-u">
                                                    <i class="fa fa-thumbs-o-up"></i>
                                                    <span>Adesivo Entregue</span>
                                                </div>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($_SESSION["matricula"] === $res["alu_matricula"]) { ?>
                                        <?php if ($res["vei_adesivo"] === "0") { ?>
                                            <a href="/Veiculo/Termo/<?php echo $res["vei_placa"] ?>" target="_blank">
                                                <div class="acao tooltips-u">
                                                    <i class="fa fa-file-text"></i>
                                                    <span>Termo para o DA</span>
                                                </div>
                                            </a>
                                            <a href="/Veiculo/TermoPrefeitura/<?php echo $res["vei_placa"] ?>" target="_blank">
                                                <div class="acao tooltips-u">
                                                    <i class="fa fa-file-text"></i>
                                                    <span>Termo para o CEFET</span>
                                                </div>
                                            </a>
                                        <?php } else { ?>
                                            <?php if (strtotime(Date("d/m/Y")) > strtotime(Date("d/m/Y", (Date("Y") + 1)) . "-12-31") &&
                                                    (strtotime(Date("d/m/Y")) < strtotime(Date("d/m/Y", (Date("Y") + 1)) . "-03-30"))) {
                                                ?>
                                                <a act="renew" placa="<?php echo $res["vei_placa"] ?>">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-refresh"></i>
                                                        <span>Renovar</span>
                                                    </div>
                                                </a>
                                            <?php } ?>
            <?php } ?>
                                        <a act="remove" placa="<?php echo $res["vei_placa"] ?>">
                                            <div class="acao tooltips-u">
                                                <i class="fa fa-remove"></i>
                                                <span>Remover Veículo</span>
                                            </div>
                                        </a>
                                    <?php } ?>
                                    <?php
                                }
                            }
                            $r = mysqli_query($con, $sqlcountregs);
                            $r = mysqli_fetch_array($r);
                            $tp = ($r["qtdregs"] / $quantidadelinhas) - 1; // verifica o número total de páginas
                            if ($quantidadelinhas <= 0) {
                                ?>
                        <tr>
                            <td colspan="5">
                                Você não tem Veículo registrado.
                            </td>
                        </tr>
                    <?php } ?>
<?php if ($_GET["pag"] > 0 || $_GET["pag"] < $tp) { ?>
                        <tr>
                            <td colspan="5">
                                <?php
                                if ($_GET["pag"] > 0) {
                                    ?>
                                    <a class='antpag' href="/Admin/Palestras<?php echo $search . "pag=" . ($_GET["pag"] - 1) ?>"><i class="fa fa-arrow-left"></i><span>Página Anterior</span></a>
                                <?php } ?>
                                <?php
                                if ($_GET["pag"] < $tp) {
                                    ?>
                                    <a class='proxpag' href="/Admin/Palestras<?php echo $search . "pag=" . ($_GET["pag"] + 1) ?>"><span>Próxima Página</span><i class="fa fa-arrow-right"></i></a>
    <?php } ?>
                            </td>
                        </tr>
<?php } ?>
                </table>
            </div>
        </div>
    </body>
</html>