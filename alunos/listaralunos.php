<?php
session_start();
include "../functions/conexao.php";
if (isset($_SESSION['matricula'])) {
    if($_SESSION['tipo_usuario']==="0"){
        http_response_code(404);
    }
} else {
    header("./login.php?dest=".$_SERVER['PHP_SELF']);
}
$idpag = 0;
$search = "?";
if (isset($_GET["pag"])) {
    $idpag = $_GET["pag"] * 3;
} else {
    $_GET["pag"] = 0;
}
$sql = "select alu_matricula, alu_nome, alu_status, alu_email from tb_Aluno order by alu_nome LIMIT $idpag,3";
if (isset($_REQUEST["btn_buscar"])) {
    if ($_REQUEST["btn_buscar"]) {
        if ($_REQUEST["campo_busca"] === "") {
            $exibeAlerta = true;
            $msg = "O campo de Busca está vazio.";
        } else {
            $search = "campo_busca=" . $_REQUEST["campo_busca"] . "&busca=" . $_REQUEST["busca"] . "&btn_buscar=" . $_REQUEST["btn_buscar"];
            $sql = "select alu_matricula, alu_nome, alu_status, alu_email from tb_Aluno where alu_" . $_REQUEST["busca"] . " like '%" . $_REQUEST["campo_busca"] . "%'";
        }
    }
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
            @import url("/css/properties.css");
            @import url("/css/tables.css");
            @import url("/css/forms.css");
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
                    var mat = $(this).attr("mat");
                    if (acao === "block") {
                        executarAJAX($(this), "/functions/alunos/alterardados.php?funct=" + acao, {"matricula": mat}, "Aluno Bloqueado.", "", undefined, function (element) {
                            var tr = element.parent().parent();
                            tr.children(".colStatus").html("<i class='fa fa-lock'></i>");
                            var colacao = tr.children(".colAcao");
                            colacao.children("a").attr("act","unblock");
                            colacao.children("a").children("div").html("<i class='fa fa-unlock-alt'></i><span>Desbloquear</span>");
                        });
                    } else if (acao === "unblock") {
                        executarAJAX($(this), "/functions/alunos/alterardados.php?funct=" + acao, {"matricula": mat}, "Aluno Bloqueado.", "", undefined, function (element) {
                            var tr = element.parent().parent();
                            tr.children(".colStatus").html("<i class='fa fa-unlock-alt'></i>");
                            var colacao = tr.children(".colAcao");
                            colacao.children("a").attr("act","block");
                            colacao.children("a").children("div").html("<i class='fa fa-lock'></i><span>Bloquear</span>");
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
            <div class='mensagem'><?php if ($exibeAlerta) echo $msg ?></div>
        </div>
        <div id='page-container'>
            <?php include "../menu.php" ?>
            <div id='content'>
                <div id="title">Alunos</div>
                <form id='search' class="one-line" method='get' action="/Admin/Alunos">
                    <div class="input">
                        <i class="fa fa-filter"></i>
                        <input id="campo_busca" type='text' name='campo_busca' value='<?php
                        if (isset($_REQUEST["campo_busca"])) {
                            echo $_REQUEST["campo_busca"];
                        }
                        ?>' placeholder="Buscar">
                    </div>
                    <div class="input checks-group">
                        <input id="bnome" type='radio' name='busca' value='nome' <?php
                        if (isset($_REQUEST["busca"])) {
                            if ($_REQUEST["busca"] === "nome") {
                                echo "checked";
                            }
                        } else {
                            echo "checked";
                        }
                        ?> />
                        <label for="busca"><span><span></span></span>Nome</label>
                        <input id="bmatricula" type='radio' name='busca' value='matricula' <?php
                        if (isset($_REQUEST["busca"])) {
                            if ($_REQUEST["busca"] === "matricula") {
                                echo "checked";
                            }
                        }
                        ?>/>
                        <label for="busca"><span><span></span></span>Matrícula</label>
                        <input id="bemail" type='radio' name='busca' value='email' <?php
                        if (isset($_REQUEST["busca"])) {
                            if ($_REQUEST["busca"] === "email") {
                                echo "checked";
                            }
                        }
                        ?>/>
                        <label for="busca"><span><span></span></span>E-mail</label>
                    </div>
                    <div class="input submit">
                        <input id="btn_buscar" type='submit' name="btn_buscar" value='Buscar'>
                    </div>
                </form>
                <table cellspacing="0" class="list">
                    <tr>
                        <th>Status
                        <th>Matrícula
                        <th>Nome
                        <th>E-mail
                        <th class='colAcao'>Ação
                            <?php
                            $qry = mysqli_query($con, $sql);
                            $tp = 0;
                            if ($qry) {
                                $tr = mysqli_num_rows($qry); // verifica o número total de registros
                                $tp = $tr / $tr; // verifica o número total de páginas
                                while ($res = mysqli_fetch_array($qry)) {
                                    if ($res["alu_status"] == '0') {
                                        $status = "<i class='fa fa-lock'></i>";
                                        $fazer = "<i class='fa fa-unlock-alt'></i>";
                                        $acao = "unblock";
                                        $tt = "Desbloquear";
                                    } else {
                                        $fazer = "<i class='fa fa-lock'></i>";
                                        $status = "<i class='fa fa-unlock-alt'></i>";
                                        $acao = "block";
                                        $tt = "Bloquear";
                                    }
                                    ?>
                            <tr>
                                <td class='colStatus'><?php echo $status ?>
                                <td><?php echo $res["alu_matricula"] ?>
                                <td><?php echo $res["alu_nome"] ?>
                                <td><?php echo $res["alu_email"] ?>
                                <td class='colAcao'>
                                    <a act='<?php echo $acao ?>' mat="<?php echo $res["alu_matricula"] ?>">
                                        <div class="acao tooltips-l">
                                            <?php echo $fazer ?>
                                            <span><?php echo $tt ?></span>
                                        </div>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                    <tr>
                        <td colspan="5">
                            <?php
                            if ($_GET["pag"] != 0) {
                                ?>
                                <a class='proxpag' href="<?php echo $search . "&pag=" . ($_GET["pag"] - 1) ?>"><i class="fa fa-arrow-left"></i><span>Página Anterior</span></a>
                            <?php } ?>
                            <?php
                            if ($_GET["pag"] < $tp) {
                                ?>
                                <a class='antpag' href="<?php echo $search . "&pag=" . ($_GET["pag"] + 1) ?>"><span>Próxima Página</span><i class="fa fa-arrow-right"></i></a>
                                <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>