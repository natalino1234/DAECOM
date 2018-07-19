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
$matricula = $_SESSION["matricula"];
$sql = ""
        . "select Distinct p.min_id, p.min_autorizacao, p.alu_organizacao as min_organizacao, p.min_nome, t.map_cod, "
        . "p.min_professor, p.min_dataprimaula, MIN(h.mih_dia) as min_dia, MAX(h.mih_dia) as max_dia, count(p.min_id) as qtdregs, "
        . " t.alu_matricula, count(h.mih_dia) as tt_aulas , count(pres.alu_matricula) as tt_presencas "
        . "from tb_Minicurso as p left join tb_Min_Alu_Participar as t on (p.min_id = t.min_id) inner join tb_Min_Horario "
        . "as h on (h.min_id = p.min_id) left join tb_Min_Alu_Presenca as pres on (pres.min_id = p.min_id AND pres.alu_matricula = ".$_SESSION["matricula"].")"
        . "where p.min_autorizacao = 2 AND p.alu_organizacao like '$matricula' group by p.min_id "
        . "order by p.min_id DESC LIMIT $idpag,$quantidadelinhas";
$sqlcountregs = "Select count(q.min_id) as qtdregs "
        . "from (Select distinct p.min_id "
        . "from tb_Minicurso as p where p.min_autorizacao = 2 AND p.alu_organizacao like '$matricula' "
        . "group by p.min_id) as q";
if (isset($_REQUEST["btn_buscar"])) {
    if ($_REQUEST["btn_buscar"]) {
        if ($_REQUEST["campo_busca"] === "") {
            $exibeAlerta = true;
            $msg = "O campo de Busca está vazio.";
        }
        $search = "?campo_busca=" . $_REQUEST["campo_busca"] . "&busca=" . $_REQUEST["busca"] . "&btn_buscar=" . $_REQUEST["btn_buscar"];
        $sql = "select p.min_id as min_id, p.min_autorizacao as min_autorizacao, p.alu_organizacao as min_organizacao, p.min_nome as min_nome, "
                . "a.alu_nome as min_professor, p.min_dataprimaula as min_dataprimaula, MAX(h.mih_dia) as mih_dia, t.alu_matricula"
                . "from tb_Minicurso as p left join tb_Min_Alu_Participar as t on (p.min_id = t.min_id) inner join "
                . "tb_Aluno as a on (a.alu_matricula = p.min_professor) inner join tb_Min_Horario"
                . "where min_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%' OR "
                . "min_professor like '%" . $_REQUEST["campo_busca"] . "%' AND p.min_autorizacao = 2 AND p.alu_organizacao like '$matricula'  "
                . "order by p.min_id DESC LIMIT $idpag,$quantidadelinhas";
        $sqlcountregs = "Select count(q.min_id) as qtdregs "
                . "from (select p.min_id "
                . "from tb_Minicurso as p left join tb_Min_Alu_Participar as t on (p.min_id = t.min_id) inner join "
                . "tb_Aluno as a on (a.alu_matricula = p.min_professor) "
                . "where min_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%' OR "
                . "alu_nome like '%" . $_REQUEST["campo_busca"] . "%' AND p.min_autorizacao = 2 AND p.alu_organizacao like '$matricula'  "
                . "order by p.min_id) as q";
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
        <script>
            $(document).ready(function () {
                $(document).on("click", ".colAcao a", function () {
                    var acao = $(this).attr("act");
                    var pal = $(this).attr("pal");
                    if (acao === "allow") {
                        executarAJAX(location, "/functions/minicursos/alterar.php?funct=" + acao, {"id": pal}, "Minicurso Aceita.", "", undefined, function (element) {
                            element.reload();
                        });
                    } else if (acao === "deny") {
                        executarAJAX(location, "/functions/minicursos/alterar.php?funct=" + acao, {"id": pal}, "Minicurso Rejeitada.", "", undefined, function (element) {
                            element.reload();
                        });
                    } else if (acao === "signup") {
                        executarAJAX(location, "/functions/minicursos/alterar.php?funct=" + acao, {"id": pal}, "Inscrição Realizada.", "", undefined, function (element) {
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
                <div id="title">Minicursos Negados</div>
                <div id="properties">
                    <?php
                    $sqlproperties = "select s.Submetidos, d.disponiveis, r.recusadas, a.aguardando "
                            . "from "
                            . "(Select count(min_id) as Submetidos from tb_Minicurso) as s, "
                            . "(Select count(min_id) as disponiveis from tb_Minicurso where min_autorizacao = 1) as d, "
                            . "(Select count(min_id) as recusadas from tb_Minicurso where min_autorizacao = 2) as r, "
                            . "(Select count(min_id) as aguardando from tb_Minicurso where min_autorizacao = 0) as a ";
                    if ($_SESSION["tipo_usuario"] === "0") {
                        $sqlproperties = "select s.Submetidos, d.disponiveis, r.recusadas, a.aguardando "
                                . "from "
                                . "(Select count(min_id) as Submetidos from tb_Minicurso "
                                . "where alu_organizacao = '" . $_SESSION["matricula"] . "') as s, "
                                . "(Select count(min_id) as disponiveis from tb_Minicurso "
                                . "where min_autorizacao = 1 and alu_organizacao = '" . $_SESSION["matricula"] . "') as d, "
                                . "(Select count(min_id) as recusadas from tb_Minicurso "
                                . "where min_autorizacao = 2 and alu_organizacao = '" . $_SESSION["matricula"] . "') as r, "
                                . "(Select count(min_id) as aguardando from tb_Minicurso "
                                . "where min_autorizacao = 0 and alu_organizacao = '" . $_SESSION["matricula"] . "') as a ";
                    }
                    $qry1 = mysqli_query($con, $sqlproperties);
                    if ($qry1) {
                        $result = mysqli_fetch_array($qry1);
                        ?>

                        <div class="propertie" id="properties-1">
                            <p>Minicursos Submetidos</p> 
                            <p class="number"><?php echo $result["Submetidos"] ?></p> 
                            <i class="fa fa-send"></i>
                        </div>
                        <div class="propertie" id="properties-2">
                            <p>Minicursos Disponíveis</p> 
                            <p class="number"><?php echo $result["disponiveis"] ?></p> 
                            <i class="fa fa-slideshare"></i>
                        </div>
                        <div class="propertie" id="properties-3">
                            <p>Minicursos Recusadas</p> 
                            <p class="number"><?php echo $result["recusadas"] ?></p> 
                            <i class="fa fa-ban"></i>
                        </div>
                        <div class="propertie" id="properties-4">
                            <p>Minicursos Aguardando</p> 
                            <p class="number"><?php echo $result["aguardando"] ?></p> 
                            <i class="fa fa-spinner"></i>

                        </div>
                    <?php } ?>
                </div>
                <div id="Submetidos"></div>
                <form id='search' class="one-line" method='get' action="/Admin/Minicursos">
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
                        <input id="bmatricula" type='radio' name='busca' value='professor' <?php
                        if (isset($_POST["busca"])) {
                            if ($_POST["busca"] === "professor") {
                                echo "checked";
                            }
                        }
                        ?>/>
                        <label for="busca"><span><span></span></span>Professor</label>
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
                        <th>Professor
                        <th>Horário
                        <th style="width: 150px;">Ação
                            <?php
                            $qry = mysqli_query($con, $sql);
                            if ($qry) {
                                while ($res = mysqli_fetch_array($qry)) {
                                    $tr = $res["qtdregs"];
                                    $status = "";
                                    if ($res["alu_matricula"] === $_SESSION["matricula"]) {
                                        $status = "<i class='fa fa-pencil'></i>";
                                    } else if ($res["min_autorizacao"] === '0') {
                                        $status = "<i class='fa fa-spinner'></i>";
                                    } else if ($res["min_autorizacao"] === '1') {
                                        $status = "<i class='fa fa-thumbs-o-up'></i>";
                                    } else if ($res["min_autorizacao"] === '2') {
                                        $status = "<i class='fa fa-thumbs-o-down'></i>";
                                    }
                                    ?>
                            <tr>
                                <td class='colStatus'><?php echo $status ?>
                                <td><?php echo $res["min_nome"] ?>
                                <td>
                                    <?php if (!validaMatricula($res["min_professor"])) { ?>
                                        <?php echo $res["min_professor"] ?>
                                    <?php } else { ?>
                                        <a href="/Aluno/View/<?php echo $res["min_professor"] ?>" target="_blank">
                                            <?php echo $res["min_professor"] ?>
                                        </a>
                                    <?php } ?>
                                <td><strong>Primeira Aula: <?php echo Date("d/m/Y", strtotime($res["min_dia"])) ?></strong><br>
                                    <?php
                                    $sqlhorario = "Select * from tb_Min_Horario where min_id=" . $res["min_id"] . " order by mih_dia asc";
                                    $rh = mysqli_query($con, $sqlhorario);
                                    if ($rh) {
                                        while ($res1 = mysqli_fetch_array($rh)) {
                                            if ($res1["mih_dia"] === "0") {
                                                echo "Domingo, " . $res1["mih_hora"] . "<br>";
                                            } else if ($res1["mih_dia"] === "1") {
                                                echo "Segunda-Feira, " . $res1["mih_hora"] . "<br>";
                                            } else if ($res1["mih_dia"] === "2") {
                                                echo "Terça-Feira, " . $res1["mih_hora"] . "<br>";
                                            } else if ($res1["mih_dia"] === "3") {
                                                echo "Quarta-Feira, " . $res1["mih_hora"] . "<br>";
                                            } else if ($res1["mih_dia"] === "4") {
                                                echo "Quinta-Feira, " . $res1["mih_hora"] . "<br>";
                                            } else if ($res1["mih_dia"] === "5") {
                                                echo "Sexta-Feira, " . $res1["mih_hora"] . "<br>";
                                            } else if ($res1["mih_dia"] === "6") {
                                                echo "Sábado, " . $res1["mih_hora"] . "<br>";
                                            }
                                        }
                                    }
                                    ?> 
                                <td class='colAcao'>
                                    <?php if ($res["min_autorizacao"] === '0') { ?>
                                        <a act="allow" pal="<?php echo $res["min_id"] ?>">
                                            <div class="acao tooltips-u">
                                                <i class="fa fa-thumbs-o-up"></i>
                                                <span>Aceitar Pedido</span>
                                            </div>
                                        </a>
                                        <a act="deny" pal="<?php echo $res["min_id"] ?>">
                                            <div class="acao tooltips-u">
                                                <i class="fa fa-thumbs-o-down"></i>
                                                <span>Negar Pedido</span>
                                            </div>
                                        </a>
                                    <?php } else if ($res["min_autorizacao"] === '1') {
                                        if (strtotime($res["min_dia"]) < strtotime(Date('Y-m-d'))) { ?>
                                            <?php if ($res["min_organizacao"] === $_SESSION["matricula"]) { ?>
                                                <a href="/Minicurso/Presenca/<?php echo $res["min_id"] ?>" target="_blank">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-check-square-o"></i>
                                                        <span>Dar Presença</span>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                            <?php if (strtotime($res["max_dia"]) < strtotime(Date('Y-m-d')) || $res["min_professor"] === $_SESSION["matricula"]) { ?>
                                                <a href="/Minicurso/Certificado/<?php echo $res["map_cod"] ?>" target="_blank">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-certificate"></i>
                                                        <span>Gerar Certificado</span>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                            <?php
                                        } else {
                                            if ($res["min_organizacao"] !== $_SESSION["matricula"] &&
                                                    $res["min_professor"] !== $_SESSION["matricula"] &&
                                                    $res["tt_aulas"] === $res["tt_presencas"]) {
                                                ?>
                                                <a act="signup" pal="<?php echo $res["min_id"] ?>">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                        <span>Fazer Inscrição</span>
                                                    </div>
                                                </a>
                                                <?php
                                            } else
                                            if ($res["min_organizacao"] === $_SESSION["matricula"]) {
                                                ?>
                                                <a href="/Minicurso/ListaDePresenca/<?php echo $res["min_id"] ?>" target="_blank">
                                                    <div class="acao tooltips-u">
                                                        <i class="fa fa-print"></i>
                                                        <span>Lista de Presença</span>
                                                    </div>
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    <?php } else if ($res["min_autorizacao"] === '2') { ?>
                                        <a act="remove" pal="<?php echo $res["min_id"] ?>">
                                            <div class="acao tooltips-u">
                                                <i class="fa fa-remove"></i>
                                                <span>Remover Minicurso</span>
                                            </div>
                                        </a>
                                    <?php } ?>
                                    <a href="/Minicurso/View/<?php echo $res["min_id"] ?>" target="_blank">
                                        <div class="acao tooltips-u">
                                            <i class="fa fa-wpforms"></i>
                                            <span>Visualizar Minicurso</span>
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
                                O Sistema ainda não tem Minicursos.
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
                                    <a class='antpag' href="/Admin/Minicursos<?php echo $search . "pag=" . ($_GET["pag"] - 1) ?>"><i class="fa fa-arrow-left"></i><span>Página Anterior</span></a>
                                <?php } ?>
                                <?php
                                if ($_GET["pag"] < $tp) {
                                    ?>
                                    <a class='proxpag' href="/Admin/Minicursos<?php echo $search . "pag=" . ($_GET["pag"] + 1) ?>"><span>Próxima Página</span><i class="fa fa-arrow-right"></i></a>
                                    <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </body>
</html>
                    <?php echo mysqli_errno($con)?>