<?php
session_start();
include "../functions/conexao.php";
$temcnh = true;
if (!isset($_SESSION['matricula'])) {
    header("location: /Login?dest=" . $_SERVER['PHP_SELF']);
}
$msg = "";
include "../functions/functions.php";
include "../functions/veiculos.php";
include "../functions/alunos.php";
include "../functions/alunos/inserirCNH.php";
$sql = "Select alu_cnh from tb_Aluno where alu_matricula='" . $_SESSION["matricula"] . "'";
$r = mysqli_query($con, $sql);
if ($r) {
    $r = mysqli_fetch_array($r);
    if ($r["alu_cnh"] === "" || $r["alu_cnh"] === null) {
        $temcnh = false;
    }
}
$erro = false;
$errov = 0;
$invalidar = "";
$msg = "";
if (isset($_POST["submit"])) {
    $arr = validarVeiculo($con);
    for ($i = 0; $i < 5; $i++) {
        $value = $arr[$i];
        if ($value["valido"] === 0) {
            $invalidar .= "$('#" . $value["nome"] . "').parent().css({'color':'red'});\n";
            $msg .= $value["erro"] . "<br>";
            $erro = 1;
        }
    }
    if ($erro != 1) {
        $verif = createVeiculo($con, $arr[0]["texto"], $arr[1]["texto"], $arr[2]["texto"], $arr[3]["texto"], $arr[4]["texto"], $arr[5]["texto"], $_SESSION["matricula"]);
        if ($verif["houveErro"] === 1) {
            $msg = $verif["erro"];
            $erro = 1;
        }
    }
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
            @import url("/css/totop.css");
            @import url("/css/tooltips.css");
            @import url("/css/alert.css");
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src='/js/jquery.js'></script>
        <script src='/js/jquery.mask.js'></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/forms.js'></script>
        <script src='/js/alert.js'></script>
        <script>
            $(document).ready(function () {
                $('#placa').mask('AAA-9999');
                $("input[name=tipo]").click(function () {
                    atualizarMarcas($(this).val());
                });
                $("select[name=marca]").change(function () {
                    atualizarModelos($("input[name=tipo]:checked").val(), $("select[name='marca'] option:selected").attr("id"));
                });
                atualizarMarcas("carro");
            });
            function toTitleCase(str)
            {
                return str.replace(/\w\S*/g, function (txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                });
            }
            function atualizarMarcas(tipo) {
                $.getJSON("http://fipeapi.appspot.com/api/1/" + tipo + "s/marcas.json", function (dados) {

                    if (!("erro" in dados)) {
                        var txt = "<option selected>Selecione uma Marca</option>";
                        var size = 0;
                        while (size < dados.length) {
                            var sel = dados[size];
                            var name = toTitleCase((sel.name + "").toLowerCase());
                            txt += "<option id='" + sel.id + "' value=\"" + sel.name + "\">" + name + "</option>";
                            size++;
                        }
                        $("#marca").html(txt);
                        $("#marca").removeAttr("disabled");
                    } else {
                        $("#marca").attr("disabled");
                        alertUser("Não foi possível atualizar a lista de marcas.");
                    }
                });
            }
            function atualizarModelos(tipo, marca) {
                $.getJSON("http://fipeapi.appspot.com/api/1/" + tipo + "s/veiculos/" + marca + ".json", function (dados) {
                    if (!("erro" in dados)) {
                        var txt = "<option selected>Selecione um Modelo</option>";
                        var size = 0;
                        var veiculos = new Array();
                        var existe;
                        var i = 0;
                        while (size < dados.length) {
                            existe = false;
                            var sel = dados[size];
                            var nmsplit = sel.name.split(" ")[0].split("/");
                            var name = sel.name.split(" ")[0];
                            if (nmsplit[1] === "") {
                                name = nmsplit[0];
                            }
                            name = toTitleCase(name);
                            for (i = 0; i < veiculos.length; i++) {
                                if (veiculos[i] === name) {
                                    existe = true;
                                }
                            }
                            if (!existe) {
                                txt += "<option value=\"" + name + "\">" + name + "</option>";
                                veiculos[i] = name;
                            }
                            size++;
                        }
                        $("#modelo").html(txt);
                        $("#modelo").removeAttr("disabled");
                    } else {
                        $("#modelo").attr("disabled");
                        alertUser("Não foi possível atualizar a lista de marcas.");
                    }
                }).fail(function () {
                    $("#modelo").attr("disabled", true);
                });
            }
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
                <?php if ($temcnh) { ?>
                    <div id="title">Cadastro de Veículo</div>
                    <div id="Submetidas"></div>
                    <h2>Preencha com os dados do seu Veículo:</h2>
                    <form id='normal' method="post">
                        <label for="tipo">Tipo de Veículo*:</label>
                        <div class="input checkbox">
                            <input type="radio" name="tipo" id="moto" value="carro" required checked>
                            <label for="tipo">
                                <span><span></span></span>
                                Carro
                            </label>
                            <input type="radio" name="tipo" id="carro" value="moto" required>
                            <label for="tipo">
                                <span><span></span></span>
                                Moto
                            </label>
                        </div>
                        <label for="proprio">Seu veículo é próprio?*:</label>
                        <div class="input checkbox">
                            <input type="radio" name="proprio" id="nao" value="0" required checked>
                            <label for="tipo">
                                <span><span></span></span>
                                Sim
                            </label>
                            <input type="radio" name="proprio" id="sim" value="1" required>
                            <label for="tipo">
                                <span><span></span></span>
                                Não
                            </label>
                        </div>
                        <table>
                            <tr>
                                <td>
                                    <label for="marca">Marca*:</label>
                                    <div class="input">
                                        <i class="fa fa-car"></i>
                                        <select name="marca" id="marca" disabled required style="width: 200px;">
                                            Selecione uma Marca
                                        </select>
                                    </div>
                                <td>
                                    <label for="modelo">Modelo*:</label>
                                    <div class="input">
                                        <i class="fa fa-car"></i>
                                        <select name="modelo" id="modelo" disabled required style="width: 200px;">
                                            <option>Selecione um Modelo</option>
                                        </select>
                                    </div>
                            <tr>
                                <td>
                                    <label for="cor">Cor*:</label>
                                    <div class="input">
                                        <i class="fa fa-car"></i>
                                        <input id="cor" type="text" name="cor" size="10" style="width: 200px;">
                                    </div>
                                <td>
                                    <label for="placa">Placa*:</label>
                                    <div class="input">
                                        <i class="fa fa-car"></i>
                                        <input id="placa" type="text" name="placa" style="width: 200px;">
                                    </div>

                        </table>
                        <div class='input submit'>
                            <i class='fa fa-send'></i>
                            <input type='submit' name='submit' id="enviar" value="Cadastrar Veículo">
                        </div>
                    </form>
                <?php } else { ?>
                    <div style="width: 100%; height: 100%;">
                        <form method="post" id="search" style="position: absolute; width: 100%; transform: translate3d(0,-50%,0); top: 50%;">
                            <table align="center">
                                <tr>
                                    <th>Você não tem um CNH registrado no nosso sistema é necessário para poder inserir seus veículos.<br>
                                        Preencha abaixo para que você consiga cadastrar veículos.</th>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <label for="cpf">CNH*:</label>
                                        <div class="input">
                                            <i class="fa fa-car"></i>
                                            <input id="cnh" type="text" name="cnh">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <div class="input submit">
                                            <input id="btn_cnh" type='submit' name='btn_cnh' value='Enviar CNH'>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        if ($erro) {
            echo "<script>$invalidar</script>";
        }
        if ($msg !== "") {
            echo "<script>alertUser('$msg');</script>";
        }
        ?>
    </body>
</html>