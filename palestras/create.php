<?php
session_start();
include "../functions/conexao.php";
if (isset($_SESSION['matricula'])) {
    
} else {
    header("location: /Login?dest=" . $_SERVER['PHP_SELF']);
}
include "../functions/palestras.php";
include "../functions/functions.php";
$erro = false;
$errov = 0;
if (isset($_POST["submit"])) {
    $arr = validarPalestra();
    $invalidar = "";
    for ($i = 0; $i < 8; $i++) {
        $value = $arr[$i];
        if ($value["valido"] === 0) {
            $invalidar .= "$('#" . $value["nome"] . "').parent('.input').find('i').css({'color':'red'});\n";
            $erro = 1;
        }
    }
    if ($erro != 1) {
        $verif = createPalestra($con, $arr[0]["texto"], $arr[1]["texto"], $arr[2]["texto"], $arr[3]["texto"], $arr[4]["texto"], $arr[5]["texto"], $arr[6]["texto"], $arr[7]["texto"], $_SESSION["matricula"]);
        if($verif["houveErro"] === "1"){
            $errov = 1;
        }
    }
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
            @import url("/css/totop.css");
            @import url("/css/tooltips.css");
            @import url("/css/alert.css");
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src='/js/jquery.js'></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/forms.js'></script>
        <script src='/js/alert.js'></script>
    </head>
    <body>
        <div id='topo'></div>
        <a class='ancora tooltips' id='toTop' href='#topo'>
            <span>Voltar ao Topo</span>
            <i class="fa fa-arrow-up"></i>
        </a>
        <div id='alert'>
            <div class='alert-top-bar'><i class="fa fa-warning"></i>Aviso do Sistema<i class="fa fa-times-circle"></i></div>
            <div class='mensagem'>
                <?php
                if ($erro) {
                    for ($i = 0; $i < 7; $i++) {
                        $value = $arr[$i];
                        if ($value["valido"] === 0) {
                            echo $value["erro"] . "<br>";
                        }
                    }
                }
                ?>
            </div>
        </div>
        <div id='page-container'>
            <div class='image-preview'>
                <div>Imagem para Upload</div>
                <img id="image">
            </div>
            <?php include "../menu.php"; ?>
            <div id='content'>
                <div id="title">Criar Palestras</div>
                <div id="Submetidas"></div>
                <h2>Dados da Palestra</h2>
                <form id='normal' method="post">
                    <table>
                        <tr>
                            <td colspan="2">
                                <label for='data'>Nome da Palestra*:</label>
                                <div class='input'>
                                    <i class='fa fa-slideshare'></i>
                                    <input type='text' name='nome' id="nome" size="50" value="<?php
                                    if ($erro) {
                                        echo $arr[0]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label for='palestrante'>Nome do Palestrante*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='palestrante' id="palestrante" size="50" value="<?php
                                    if ($erro) {
                                        echo $arr[2]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label for="descricao">Descrição da Palestra*:</label>
                                <div class="input">
                                    <textarea id="descricao" name="descricao" style="width: 430px; height: 100px; resize: none; margin-left: 0px;"><?php
                                        if ($erro) {
                                            echo $arr[1]["texto"];
                                        }
                                        ?></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='data'>Dia da Palestra*:</label>
                                <div class='input'>
                                    <i class='fa fa-calendar'></i>
                                    <input type='date' name='data' id="data" value="<?php
                                    if ($erro) {
                                        echo $arr[3]["texto"];
                                    }else{
                                        echo Date("Y-m-d",strtotime("+1 day"));
                                    }
                                    ?>" min="<?php echo Date("Y-m-d",strtotime("+1 day")) ?>">
                                </div>
                            </td>
                            <td>
                                <label for='data'>Hora da Palestra*:</label>
                                <div class='input'>
                                    <i class='fa fa-clock-o'></i>
                                    <input type='time' name='hora' min="00:00" id="hora" value="<?php
                                    if ($erro) {
                                        echo $arr[4]["texto"];
                                    }else{
                                        echo Date("H:i");
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="vagas">Quantidade de Vagas*:</label>
                                <div class='input'>
                                    <i class='fa fa-braille'></i>
                                    <input type='number' name='vagas' min="1" id="vagas" value="<?php
                                    if ($erro) {
                                        echo $arr[5]["texto"];
                                    }else{
                                        echo "1";
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td>
                                <label for='cargahoraria'>Carga Horária*:</label>
                                <div class='input tooltips-r'>
                                    <i class='fa fa-hourglass-end'></i>
                                    <input type='number' name='cargahoraria' min="1" id="cargahoraria" value="<?php
                                    if ($erro) {
                                        echo $arr[6]["texto"];
                                    }else{
                                        echo "1";
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label>Banner da Palestra*:</label>
                                <div class='input'>
                                    <i class='fa fa-image'></i>
                                    <input type="file" name="file" id="file" class="inputfile" accept="image/*" value="<?php
                                    if ($erro) {
                                        echo $arr[7]["texto"];
                                    }
                                    ?>">
                                    <label for="file">Clique aqui para fazer Upload</label>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class='input submit'>
                        <i class='fa fa-send'></i>
                        <input type='submit' name='submit' id="enviar" value="Criar Palestra">
                    </div>

                </form>
            </div>
        </div>
        <?php
        if ($erro) {
            echo "<script>$invalidar alertUser('Os campos em vermelho não são válidos.');";
            echo "</script>";
        }
        if ($errov) {
            echo "<script>alertUser('".$verif["erro"]."');</script>";
        }
        ?>
    </body>
</html>