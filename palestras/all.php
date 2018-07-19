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
if ($_SESSION["tipo_usuario"] === "1") {
    $sql = ""
            . "select Distinct p.pal_id, p.pal_autorizacao, p.alu_organizacao as pal_organizacao, p.pal_nome, t.pap_cod, "
            . "p.pal_palestrante, a.alu_nome, p.pal_data, t.pap_presenca, count(p.pal_id) as qtdregs "
            . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
            . "Left Join tb_Aluno as a on (a.alu_matricula = p.pal_palestrante) "
            . "group by p.pal_id "
            . "order by p.pal_id DESC LIMIT $idpag,$quantidadelinhas";
    echo $sql;
    $sqlcountregs = "Select count(q.pal_id) as qtdregs "
            . "from (Select distinct p.pal_id "
            . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
            . "group by p.pal_id) as q";
} else if ($_SESSION["tipo_usuario"] === "0") {
    $sql = ""
            . "select Distinct p.pal_id, p.pal_autorizacao, p.alu_organizacao as pal_organizacao, p.pal_nome, t.pap_cod, "
            . "p.pal_palestrante, a.alu_nome p.pal_data, t.pap_presenca "
            . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
            . "Left Join tb_Aluno as a on (a.alu_matricula = p.pal_palestrante) "
            . "where alu_organizacao = '" . $_SESSION["matricula"] . "' "
            . "group by p.pal_id "
            . "order by p.pal_id DESC LIMIT $idpag,$quantidadelinhas";
    $sqlcountregs = "Select count(q.pal_id) as qtdregs "
            . "from (select distinct p.pal_id "
            . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
            . "where alu_organizacao = '" . $_SESSION["matricula"] . "' "
            . "group by p.pal_id) as q";
} else
if (isset($_REQUEST["btn_buscar"])) {
    if ($_REQUEST["btn_buscar"]) {
        if ($_REQUEST["campo_busca"] === "") {
            $exibeAlerta = true;
            $msg = "O campo de Busca está vazio.";
        } else {
            $search = "?campo_busca=" . $_REQUEST["campo_busca"] . "&busca=" . $_REQUEST["busca"] . "&btn_buscar=" . $_REQUEST["btn_buscar"];
            $sql = "select p.pal_id, p.pal_autorizacao, p.alu_organizacao, p.pal_nome, "
                    . "p.pal_palestrante, a.alu_nome, p.pal_data as pal_data, t.pap_presenca as pap_presenca "
                    . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
                    . "Left Join tb_Aluno as a on (a.alu_matricula = p.pal_palestrante)  "
                    . "where pal_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%' "
                    . "order by p.pal_id DESC LIMIT $idpag,$quantidadelinhas";
            $sqlcountregs = "Select count(q.pal_id) as qtdregs "
                    . "from (select p.pal_id "
                    . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
                    . "where pal_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%' "
                    . "order by p.pal_id) as q";
            if ($_SESSION["tipo_usuario"] === "0") {
                $sql = "select p.pal_id, p.pal_autorizacao, p.alu_organizacao, p.pal_nome, "
                        . "p.pal_palestrante, a.alu_nome, p.pal_data, t.pap_presenca "
                        . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
                        . "Left Join tb_Aluno as a on (a.alu_matricula = p.pal_palestrante)  "
                        . "where pal_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%' and alu_organizacao = '" . $_SESSION["matricula"] . "' "
                        . "order by p.pal_id DESC LIMIT $idpag,$quantidadelinhas";
                $sqlcountregs = "Select count(q.pal_id) as qtdregs "
                        . "from (select p.pal_id "
                        . "from tb_Palestra as p left join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) "
                        . "where pal_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%' and alu_organizacao = '" . $_SESSION["matricula"] . "' "
                        . "order by p.pal_id) as q";
            }
        }
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
        <script>
            $(document).ready(function () {
                $(document).on("click", ".colAcao a", function () {
                    var acao = $(this).attr("act");
                    var pal = $(this).attr("pal");
                    if (acao === "allow") {
                        executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"id": pal}, "Palestra Aceita.", "", undefined, function (element) {
                            element.reload();
                        });
                    } else if (acao === "deny") {
                        executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"id": pal}, "Palestra Rejeitada.", "", undefined, function (element) {
                            element.reload();
                        });
                    } else if (acao === "signup") {
                        executarAJAX(location, "/functions/palestras/alterar.php?funct=" + acao, {"id": pal}, "Inscrição Realizada.", "", undefined, function (element) {
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
                <div id="title">Todas as Palestras</div>
                <div id="properties">
                    <?php
                    $sqlproperties = "select s.submetidas, d.disponiveis, r.recusadas, a.aguardando "
                            . "from "
                            . "(Select count(pal_id) as submetidas from tb_Palestra) as s, "
                            . "(Select count(pal_id) as disponiveis from tb_Palestra where pal_autorizacao = 1) as d, "
                            . "(Select count(pal_id) as recusadas from tb_Palestra where pal_autorizacao = 2) as r, "
                            . "(Select count(pal_id) as aguardando from tb_Palestra where pal_autorizacao = 0) as a ";
                    if ($_SESSION["tipo_usuario"] === "0") {
                        $sqlproperties = "select s.submetidas, d.disponiveis, r.recusadas, a.aguardando "
                                . "from "
                                . "(Select count(pal_id) as submetidas from tb_Palestra "
                                . "where alu_organizacao = '" . $_SESSION["matricula"] . "') as s, "
                                . "(Select count(pal_id) as disponiveis from tb_Palestra "
                                . "where pal_autorizacao = 1 and alu_organizacao = '" . $_SESSION["matricula"] . "') as d, "
                                . "(Select count(pal_id) as recusadas from tb_Palestra "
                                . "where pal_autorizacao = 2 and alu_organizacao = '" . $_SESSION["matricula"] . "') as r, "
                                . "(Select count(pal_id) as aguardando from tb_Palestra "
                                . "where pal_autorizacao = 0 and alu_organizacao = '" . $_SESSION["matricula"] . "') as a ";
                    }
                    $qry1 = mysqli_query($con, $sqlproperties);
                    if ($qry1) {
                        $result = mysqli_fetch_array($qry1);
                        ?>

                        <div class="propertie" id="properties-1">
                            <p>Palestras Submetidas</p> 
                            <p class="number"><?php echo $result["submetidas"] ?></p> 
                            <i class="fa fa-send"></i>
                        </div>
                        <div class="propertie" id="properties-2">
                            <p>Palestras Disponíveis</p> 
                            <p class="number"><?php echo $result["disponiveis"] ?></p> 
                            <i class="fa fa-slideshare"></i>
                        </div>
                        <div class="propertie" id="properties-3">
                            <p>Palestras Recusadas</p> 
                            <p class="number"><?php echo $result["recusadas"] ?></p> 
                            <i class="fa fa-ban"></i>
                        </div>
                        <div class="propertie" id="properties-4">
                            <p>Palestras Aguardando</p> 
                            <p class="number"><?php echo $result["aguardando"] ?></p> 
                            <i class="fa fa-spinner"></i>

                        </div>
                    <?php } ?>
                </div>
                <div id="Submetidas"></div>
                <form id='search' class="one-line" method='get' action="/Admin/Palestras">
                    <div class="input">
                        <i class="fa fa-filter"></i>
                        <input id="campo_busca" type='text' name='campo_busca' value='' placeholder="Buscar">
                    </div>
                    <div class="input checks-group">
                        <input id="bnome" type='radio' name='busca' value='nome' <?php
                        if (isset($_POST["busca"])) {
                            if ($_POST["busca"] === "nome") {
                                echo "checked";
                            }
                        } else {
                            echo "checked";
                        }
                        ?> />
                        <label for="busca"><span><span></span></span>Nome</label>
                        <input id="bmatricula" type='radio' name='busca' value='palestrante' <?php
                        if (isset($_POST["busca"])) {
                            if ($_POST["busca"] === "palestrante") {
                                echo "checked";
                            }
                        }
                        ?>/>
                        <label for="busca"><span><span></span></span>Palestrante</label>
                    </div>
                    <div class="input">
                        <i class="fa fa-filter"></i>
                        <select id="pagregs" type='text' name='pagregs' value='' placeholder="">
                            <option value="10" <?php
                            if ($quantidadelinhas === "10") {
                                echo "selected";
                            }
                            ?>>10 registros por página</option>
                            <option value="20" <?php
                            if ($quantidadelinhas === "20") {
                                echo "selected";
                            }
                            ?>>20 registros por página</option>
                            <option value="30" <?php
                            if ($quantidadelinhas === "30") {
                                echo "selected";
                            }
                            ?>>30 registros por página</option>
                            <option value="50" <?php
                            if ($quantidadelinhas === "50") {
                                echo "selected";
                            }
                            ?>>50 registros por página</option>
                        </select>
                    </div>
                    <div class="input submit">
                        <input id="btn_buscar" type='submit' name='btn_buscar' value='Busca'>
                    </div>
                </form>
                <table class="list">
                    <tr>
                        <th class="colStatus">Status
                        <th>Nome
                        <th>Palestrante
                        <th>Dia da Palestra
                        <th style="width: 150px;">Ação
                            <?php
                            $qry = mysqli_query($con, $sql);
                            if ($qry) {
                                while ($res = mysqli_fetch_array($qry)) {
                                    $tr = $res["qtdregs"];
                                    $status = "";
                                    if ($res["pap_presenca"] === '0') {
                                        $status = "<i class='fa fa-pencil'></i>";
                                    } else if ($res["pap_presenca"] === '1') {
                                        $status = "<i class=\"fa fa-certificate\"></i>";
                                    } else if ($res["pal_autorizacao"] === '0') {
                                        $status = "<i class='fa fa-spinner'></i>";
                                    } else if ($res["pal_autorizacao"] === '1') {
                                        $status = "<i class='fa fa-thumbs-o-up'></i>";
                                    } else if ($res["pal_autorizacao"] === '2') {
                                        $status = "<i class='fa fa-thumbs-o-down'></i>";
                                    }
                                    ?>
                            <tr>
                                <td class='colStatus'><?php echo $status ?>
                                <td><?php echo $res["pal_nome"] ?>
                                <td>
                                    <?php if (!validaMatricula($res["pal_palestrante"])) { ?>
                                        <?php echo $res["pal_palestrante"] ?>
                                        <?php
                                    } else {
                                        if ($_SESSION["tipo_usuario"] === "1") {
                                            ?>
                                            <a href="/Aluno/View/<?php echo $res["pal_palestrante"] ?>" target="_blank">
                                                <?php echo $res["alu_nome"] ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php echo $res["alu_nome"] ?>
                                            <?php
                                        }
                                    }
                                    ?>
                                <td><?php echo Date("d/m/Y", strtotime($res["pal_data"])) ?>
                                <td class='colAcao'>
                                    <?php if ($res["pal_autorizacao"] === '0') { ?>
                                        <a act="allow" pal="<?php echo $res["pal_id"] ?>">
                                            <div class="acao tooltips-u">
                                                <i class="fa fa-thumbs-o-up"></i>
                                                <span>Aceitar Pedido</span>
                                            </div>
                                        </a>
                                        <a act="deny" pal="<?php echo $res["pal_id"] ?>">
                                            <div class="acao tooltips-u">
                                                <i class="fa fa-thumbs-o-down"></i>
                                                <span>Negar Pedido</span>
                                            </div>
                                        </a>
                                    <?php } else if ($res["pal_autorizacao"] === '1') { ?>
                                        <?php if (strtotime($res["pal_data"]) < strtotime(Date('Y-m-d'))) { ?>
                                            <?php if ($res["pal_organizacao"] === $_SESSION["matricula"]) { ?>
                                                <a href="/Palestra/Presenca/<?php echo $res["pal_id"] ?>" target="_blank">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-check-square-o"></i>
                                                        <span>Dar Presença</span>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                            <?php if ($res["pap_presenca"] === "1" || $res["pal_palestrante"] === $_SESSION["matricula"]) { ?>
                                                <a href="/Palestra/Certificado/<?php echo $res["pap_cod"] ?>" target="_blank">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-certificate"></i>
                                                        <span>Gerar Certificado</span>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                            <?php
                                        } else {
                                            if ($res["pal_organizacao"] !== $_SESSION["matricula"] &&
                                                    $res["pal_palestrante"] !== $_SESSION["matricula"] &&
                                                    $res["pap_presenca"] !== "0" && $res["pap_presenca"] !== "1") {
                                                ?>
                                                <a act="signup" pal="<?php echo $res["pal_id"] ?>">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                        <span>Fazer Inscrição</span>
                                                    </div>
                                                </a>
                                                <?php
                                            } else
                                            if ($res["pal_organizacao"] === $_SESSION["matricula"]) {
                                                ?>
                                                <a href="/Palestra/ListaDePresenca/<?php echo $res["pal_id"] ?>" target="_blank">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-print"></i>
                                                        <span>Lista de Presença</span>
                                                    </div>
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    <?php } else if ($res["pal_autorizacao"] === '2') { ?>
                                        <a act="remove" pal="<?php echo $res["pal_id"] ?>">
                                            <div class="acao tooltips-u">
                                                <i class="fa fa-remove"></i>
                                                <span>Remover Palestra</span>
                                            </div>
                                        </a>
                                    <?php } ?>
                                    <a href="/Palestra/View/<?php echo $res["pal_id"] ?>" target="_blank">
                                        <div class="acao tooltips-u">
                                            <i class="fa fa-wpforms"></i>
                                            <span>Visualizar Palestra</span>
                                        </div>
                                    </a>
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
                                O Sistema ainda não tem Palestras.
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
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