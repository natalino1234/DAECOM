<?php
session_start();
$redirect = 0;
$insertGoTo = "";
if (isset($_SESSION['matricula'])) {
    $ver = $_SESSION["alu_verificacao"];
    $redirect = 1;
    if (!$ver) {
        $insertToGo = "/falhaconfemail.php?matricula=" . $_SESSION['matricula'];
    } else {
        $insertGoTo = "/index.php";
    }
}
$erro = 0;
if (isset($_POST["btnc"])) {
    include "./functions/conexao.php";
    include "./functions/functions.php";
    include "./functions/alunos.php";
    $arr = validaAluno($con);
    $senha = $arr[3];
    $csenha = $arr[4];
    if ($senha["texto"] !== $csenha["texto"]) {
        $arr[4]["valido"] = 0;
    }
    $invalidar = "";
    $sqlv = "";
    for ($i = 0; $i < 15; $i++) {
        $value = $arr[$i];
        if ($value["valido"] === 0) {
            $invalidar .= "$('#" . $value["nome"] . "').parent('.input').css({'color':'red'});\n";
            $erro = 1;
        }
        if ($value["nome"] !== "confsenha") {
            $sqlv.="'" . $value["texto"] . "'";
            if ($i < 14) {
                $sqlv.=",";
            }
        }
    }
    if (!$erro) {
        $r = criarAluno($con, $arr[0]["texto"], $arr[1]["texto"], $arr[2]["texto"], $arr[3]["texto"], $arr[5]["texto"], 
                $arr[6]["texto"], $arr[7]["texto"], $arr[8]["texto"], $arr[9]["texto"], $arr[10]["texto"], 
                $arr[11]["texto"], $arr[12]["texto"], $arr[13]["texto"], $arr[14]["texto"], $arr[16]["texto"], $arr[17]["texto"]);
        if ($r["houveErro"] === 2) {
            echo "<script>alert('".$r["erro"]."');</script>";
            $insertGoTo = "/Falha-Enviar-Email/" . $matricula;
            $redirect = true;
        } else if ($r["houveErro"] === 0) {
            echo "<script>alert('".$r["erro"]."');</script>";
            $insertGoTo = "/";
            $redirect = true;
        } else if ($r["houveErro"] === 1) {
            $invalidar = $r["erro"];
            $erro = 1;
        }
    } else {
        $erro = 1;
    }
}
if ($redirect) {
    echo "<meta http-equiv='refresh' content='0;url=$insertGoTo' />";
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
            @import url("/css/forms.css");
            @import url("/css/totop.css");
            @import url("/css/tooltips.css");
            @import url("/css/alert.css");
            #top-ban{
                background-image: url(/img/banner-top-1000.png); 
            }
            #sidebar{
                position: fixed;
                min-height: inherit;
                height: 100%;
            }
            #help{
                top: 700px !important;
                left: 300px;
            }
            form#normal{
                background: rgba(51, 60, 74, 0.50);
                border-top: 3px solid #79828f;
                border-bottom: 3px solid #79828f;
                color: white;
            }
            form#normal label{
                color: white;
            }
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src="/js/jquery.js"></script>
        <script src="/js/scriptl.js"></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/vcad.js'></script>
        <script src='/js/forms.js'></script>
        <script src='/js/alert.js'></script>
        <script>
            function alertUser(mensagem) {
                tempo = mensagem.split(" ").length * 1000;
                $("#alert .mensagem").html(mensagem);
                $("#alert").fadeIn(500);
                setTimeout(function () {
                    $("#alert").fadeOut(500);
                }, tempo);
                return tempo;
            }
            $(document).ready(function () {
<?php
if ($erro) {
    echo "alertUser(\"";
    for ($i = 0; $i < 15; $i++) {
        $value = $arr[$i];
        if ($value["valido"] === 0) {
            echo $value["erro"] . "<br>";
        }
    }
    echo "\");";
}
?>
            });
        </script>
    </head>
    <body>
        <div id='background'><img src="/img/Bloco_B_pan.jpg"></div>
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
            <?php include './menu.php'; ?>
            <div id="content">
                <div id="title">Cadastrar</div>
                <br>
                <form id="normal" method="post" action="cadastrar.php">
                    <h2>Informações do Aluno</h2>
                    <table>
                        <tr>
                            <td>
                                <label for="matricula">Matrícula*:</label>
                                <div class="input">
                                    <i class="fa fa-graduation-cap"></i>
                                    <input id="matricula" type="text" name="matricula"  maxlength="12" pattern="[0-9]{12}" required value="<?php
                                    if ($erro) {
                                        echo $arr[0]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td colspan="2">
                                <label for="nome">Nome Completo*:</label>
                                <div class="input">
                                    <i class="fa fa-user"></i>
                                    <input id="nome" type="text" name="nome" size="55" required value="<?php
                                    if ($erro) {
                                        echo $arr[1]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="dataNasc">Data de Nascimento*:</label>
                                <div class="input tooltips-r">
                                    <i class="fa fa-calendar"></i>
                                    <input id="dataNasc" type="date" name="dataNasc" required value="<?php
                                    if ($erro) {
                                        echo $arr[2]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td>
                                <label for="cpf">CPF*:</label>
                                <div class="input">
                                    <i class="fa fa-list-alt"></i>
                                    <input id="cpf" type="text" name="cpf"  required value="<?php
                                    if ($erro) {
                                        echo $arr[6]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="cpf">CNH:</label>
                                <div class="input">
                                    <i class="fa fa-car"></i>
                                    <input id="cnh" type="text" name="cnh" value="<?php
                                    if ($erro) {
                                        echo $arr[16]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td>
                                <label for="cpf">Emissor da CNH (Ex: SSP MG):</label>
                                <div class="input">
                                    <i class="fa fa-car"></i>
                                    <input id="cnh" type="text" name="emcnh" value="<?php
                                    if ($erro) {
                                        echo $arr[17]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="telefone">Telefone*:</label>
                                <div class="input">
                                    <i class="fa fa-phone"></i>
                                    <input id="telefone" type="text" name="telefone" required value="<?php
                                    if ($erro) {
                                        echo $arr[7]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td>
                                <label for="celular">Celular*:</label>
                                <div class="input">
                                    <i class="fa fa-mobile-phone"></i>
                                    <input id="celular" type="text" name="celular" required value="<?php
                                    if ($erro) {
                                        echo $arr[8]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="senha">Senha*:</label>
                                <div class="input">
                                    <i class="fa fa-asterisk"></i>
                                    <input id="senha" type="password" name="senha"  required value="<?php
                                    if ($erro) {
                                        echo $arr[3]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                            <td>
                                <label for="confsenha">Confirme sua Senha*:</label>
                                <div class="input">
                                    <i class="fa fa-asterisk"></i>
                                    <input id="confsenha" type="password" name="confsenha" required value="<?php
                                    if ($erro) {
                                        echo $arr[4]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label for="email">E-mail*:</label>
                                <div class="input">
                                    <i class="fa fa-envelope"></i>
                                    <input id="email" type="email" name="email" size="30" required value="<?php
                                    if ($erro) {
                                        echo $arr[5]["texto"];
                                    }
                                    ?>">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <h2>Informações do Localidade</h2>
                    <p style="width: 100%;">Dica: Digite o CEP ou preencha os campos de Cidade, Estado e Rua para nós ajudarmos você.</p>
                    <label for="cep">CEP*:</label>
                    <div class="input">
                        <i class="fa fa-map-marker"></i>
                        <input id="cep" type="text" name="cep" maxlength="8" required value="<?php
                        if ($erro) {
                            echo $arr[9]["texto"];
                        }
                        ?>">
                    </div>
                    <label for="estado">Sigla do Estado*:</label>
                    <div class="input">
                        <i class="fa fa-map"></i>
                        <input id="uf" type="text" name="estado" maxlength="2" required value="<?php
                        if ($erro) {
                            echo $arr[10]["texto"];
                        }
                        ?>">
                    </div>
                    <label for="cidade">Cidade*:</label>
                    <div class="input">
                        <i class="fa fa-bank"></i>
                        <input id="cidade" type="text" name="cidade" required value="<?php
                        if ($erro) {
                            echo $arr[11]["texto"];
                        }
                        ?>">
                    </div>
                    <label for="logradouro">Logradouro*:</label>
                    <div class="input">
                        <i class="fa fa-map-signs"></i>
                        <input id="logradouro" type="text" name="logradouro" required value="<?php
                        if ($erro) {
                            echo $arr[13]["texto"];
                        }
                        ?>">
                    </div>
                    <label for="bairro">Bairro*:</label>
                    <div class="input">
                        <i class="fa fa-map-pin"></i>
                        <input id="bairro" type="text" name="bairro" required value="<?php
                        if ($erro) {
                            echo $arr[12]["texto"];
                        }
                        ?>">
                    </div>
                    <label for="complemento">Número e Complemento*:</label>
                    <div class="input">
                        <i class="fa fa-fa"></i>
                        <input id="complemento" type="text" name="complemento" required value="<?php
                        if ($erro) {
                            echo $arr[14]["texto"];
                        }
                        ?>">
                    </div>
                    <div class="help" id="help"></div>
                    <h2>Termos de Uso e de Serviço</h2>
                    <br>
                    <div class="input checkbox">
                        <input type="checkbox" name="termos" id="termos" required>
                        <label for="termos">
                            <span><span></span></span>
                            Aceito os <a target="_blank" href="TermosUsoSistemaDA.pdf" style="color: rgb(0, 202, 194);">Termos de Uso e de Serviço do Sistema</a>.
                        </label>
                    </div>
                    <h2></h2>
                    <div class="input submit">
                        <i class="fa fa-send"></i>
                        <input id="submit" type="submit" name="btnc" value="Criar Conta">
                    </div>
                </form>
            </div>
        </div>
        <?php
        if ($erro) {
            echo "<script>$invalidar</script>";
        }
        ?>
    </body>
</html>