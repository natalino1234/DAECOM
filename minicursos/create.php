<?php
session_start();
if (isset($_SESSION['matricula'])) {
    if ($_SESSION["matricula"] === "") {
        header("location: /Logout");
    }
} else {
    header("location: /Login?dest=" . $_SERVER['REQUEST_URI']);
}
include "../functions/conexao.php";
include "../functions/minicursos.php";
include "../functions/functions.php";
$erro = false;
$errov = 0;
if (isset($_POST["submit"])) {
    $arr = validarMinicurso();
    $invalidar = "";
    for ($i = 0; $i < 8; $i++) {
        $value = $arr[$i];
        if ($value["valido"] === 0) {
            $invalidar .= "$('#" . $value["nome"] . "').parent('.input').css({'color':'red'});\n";
            $erro = 1;
        }
    }
    if ($erro != 1) {
        $verif = createMinicurso($con, $arr[0]["texto"], $arr[1]["texto"], $arr[2]["texto"], $arr[3]["texto"], $arr[4]["texto"], $arr[5]["texto"], $arr[6]["texto"], $arr[7]["texto"], $_SESSION["matricula"]);
        if ($verif["houveErro"] === "1") {
            $errov = 1;
        }
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
            @import url("/css/forms.css");
            @import url("/css/tables.css");
            @import url("/css/totop.css");
            @import url("/css/tooltips.css");
            @import url("/css/alert.css");
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src='/js/jquery.js'></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/forms.js'></script>
        <script src='/js/tables.js'></script>
        <script src='/js/tooltips.js'></script>
        <script src='/js/alert.js'></script>
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
            <div id='content'>
                <div id="title">Criar Minicurso</div>
                <div id="Submetidas"></div>
                <h2>Dados do Minicurso</h2>
                <form id='normal' method="post">
                    <table>
                        <tr>
                            <td colspan="2">
                                <label for="nome">Nome de Minicurso*:</label>
                                <div class='input'>
                                    <i class='fa fa-slideshare'></i>
                                    <input type='text' name='nome' id="nome" size="50" value="<?php
                                    if ($erro) {
                                        echo $arr[0]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        <tr>
                            <td colspan="2">
                                <label for="alu_professor">Nome do Professor*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='professor' id="alu_professor" size="50" value="<?php
                                    if ($erro) {
                                        echo $arr[1]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <label for="descricao">Descrição do Minicurso*:</label>
                                <div class="input">
                                    <textarea name="descricao" style="width: 524px; height: 100px; resize: none; margin-left: 0px;"><?php
                                        if ($erro) {
                                            echo $arr[2]["texto"];
                                        }
                                        ?></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="cargahoraria">Carga Horária</label>
                                <div class='input tooltips-r'>
                                    <i class='fa fa-hourglass-end'></i>
                                    <input type='number' name='cargahoraria' id="cargahoraria" value="<?php
                                    if ($erro) {
                                        echo $arr[4]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>                            
                            <td>                            
                                <label for="vagas">Nº de Vagas</label>
                                <div class='input'>
                                    <i class='fa fa-braille'></i>
                                    <input type='number' name='vagas' id="vagas" value="<?php
                                    if ($erro) {
                                        echo $arr[5]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>                            
                            <td>                            
                                <label for="file">Banner do Mini-Curso</label>
                                <div class='input'>
                                    <i class='fa fa-image'></i>
                                    <input type="file" name="file" id="file" class="inputfile" accept="image/*">
                                    <label for="file">Clique aqui para fazer Upload</label>
                                </div>
                            </td>                            
                        </tr>
                    </table>
                    <table class="function" style="margin-left: 13px; padding: 10px; display: block; width: 550px;">
                        <tr>
                            <th colspan="4" style="font-size: 16pt;border-bottom: 1px solid #777; margin-bottom: 10px;">
                                Monte o seu Horário
                        <tr>
                            <th colspan="4" style="color: #ce1a1a;">
                                Ignore os dias que não ocorrerão as aulas
                        <tr>
                            <th style="height: 40px;">
                                Dia da Aula
                            <th>
                                Início da Aula
                            <th>
                                Término da Aula
                        <tr>
                            <td>
                                <div class='input'>
                                    <i class='fa fa-calendar'></i>
                                    <input type='date' name='dia[]' id='domhi' value="<?php
                                    if ($erro) {
                                        echo $arr[7]["texto"][0]["dia"];
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td>
                                <div class='input'>
                                    <i class='fa fa-clock-o'></i>
                                    <input type='time' name='hi[]' id='domhi' value="<?php
                                    if ($erro) {
                                        echo $arr[7]["texto"][0]["hi"];
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td>
                                <div class='input'>
                                    <i class='fa fa-clock-o'></i>
                                    <input type='time' name='hf[]' id='domhf' value="<?php
                                    if ($erro) {
                                        echo $arr[7]["texto"][0]["hf"];
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td>
                                <div class='input submit addLine tooltips-r'>
                                    <i class='fa fa-plus'></i>
                                    <button> </button>
                                    <span>Adicionar Horário</span>
                                </div>
                            </td>
                        </tr>
                        <?php if (isset($_POST["submit"])) { ?>
                            <?php for ($i = 1; $i < count($arr[7]["texto"]); $i++) { ?>
                                <tr>
                                    <td>
                                        <div class='input'>
                                            <i class='fa fa-calendar'></i>
                                            <input type='time' name='dia[]' id='domhi' value="<?php
                                            if ($erro) {
                                                echo $arr[7]["texto"][$i]["dia"];
                                            }
                                            ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class='input'>
                                            <i class='fa fa-clock-o'></i>
                                            <input type='time' name='hi[]' id='domhi' value="<?php
                                            if ($erro) {
                                                echo $arr[7]["texto"][$i]["hi"];
                                            }
                                            ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class='input'>
                                            <i class='fa fa-clock-o'></i>
                                            <input type='time' name='hf[]' id='domhf' value="<?php
                                            if ($erro) {
                                                echo $arr[7]["texto"][$i]["hf"];
                                            }
                                            ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class='input submit remLine tooltips-r'>
                                            <i class='fa fa-plus'></i>
                                            <button> </button>
                                            <span>Remover Horário</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <?php
                        }
                        ?>
                    </table>
                    <div class='input submit'>
                        <i class='fa fa-send'></i>
                        <input type='submit' name='submit' id="enviar" value="Criar Minicurso">
                    </div>
                </form>
            </div>
        </div>
        <?php
        if ($erro) {
            echo "<script>$invalidar</script>";
            echo "<script>alertUser(\"Verifique os campos indicados com vermelho, pois são inválidos.\");</script>";
        }
        ?>
    </body>
</html>