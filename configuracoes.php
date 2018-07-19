<?php
session_start();
include "./functions/conexao.php";
$mostra = false;
if (isset($_SESSION['matricula'])) {
    $ver = $_SESSION["alu_verificacao"];
    if ($ver === "0") {
        header("location: /Falha-Enviar-Email/" . $_SESSION['matricula']);
    } else {
        $mostra = true;
    }
} else {
    $_GET['dest'] = $_SERVER['PHP_SELF'];
    include "./login.php";
}
if ($mostra) {
    ?>
    <html>
        <head>
            <meta charset="UTF-8">
            <link rel="icon" href="/icon.png" type="image/png"/>
            <title><?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                    Administrador
                <?php } else { ?>
                    Aluno
                <?php } ?> - DA-ECOM - CEFET-MG Campus Timóteo</title>
            <style>
                @import url("/css/fonts.css");
                @import url("/css/style.css");
                @import url("/css/stylemenu.css");
                @import url("/css/properties.css");
                @import url("/css/tables.css");
                @import url("/css/forms.css");
                @import url("/css/totop.css");
                @import url("/css/alert.css");

                #blockfundo{
                    background: rgba(0,0,0,0.5);
                    position: fixed;
                    width: 100%;
                    height: 100%;
                    top: 0px;
                    display: none;
                }

                #addDA{
                    display: none;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate3d(-50%, -50%, 0);
                    width: 500px;
                    height: 90%;
                    max-height: 700px;
                    background: rgb(238,238,238);
                    overflow-y: auto;
                    overflow-x: hidden;
                    box-shadow: 5px 5px 5px rgba(0,0,0,0.5);
                    border: 2px rgb(121,130,143) solid; 
                }
                #addCoord{
                    display: none;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate3d(-50%, -50%, 0);
                    width: 500px;
                    height: 500px;
                    background: rgb(238,238,238);
                    overflow-y: hidden;
                    overflow-x: hidden;
                    box-shadow: 5px 5px 5px rgba(0,0,0,0.5);
                    border: 3px rgb(121,130,143) solid; 
                }
            </style>
            <link rel="stylesheet" href="/css/font-awesome.min.css">
            <script src='/js/jquery.js'></script>
            <script src='/js/backtop.js'></script>
            <script src='/js/alert.js'></script>
            <script>
                function adicionarDA() {
                    $("#blockfundo").fadeIn(400);
                    $("#addDA").fadeIn(400);
                    $("body").css({"overflow-y": "hidden"});
                }
                function adicionarCoord() {
                    $("#blockfundo").fadeIn();
                    $("#addCoord").fadeIn();
                }
                function salvarDA() {
                    $.ajax({type: "POST", url: "/functions/DA.php",
                        data: {"c": $("#coord").val() + "",
                            "s": $("#subc").val() + "",
                            "s1": $("#sec1").val() + "",
                            "s2": $("#sec2").val() + "",
                            "cp": $("#coordp").val() + "",
                            "pe": $("#promot").val() + "",
                            "t": $("#tes").val() + "",
                            "p": $("#posse").val() + ""
                        }
                    })
                            .done(function (data) {
                                if (data > 0) {
                                    var s = "";
                                    switch (data) {
                                        case "1":
                                            s = "Coordenador";
                                            break;
                                        case "2":
                                            s = "Subcoordenador";
                                            break;
                                        case "3":
                                            s = "1º Secretário";
                                            break;
                                        case "4":
                                            s = "2º Secretário";
                                            break;
                                        case "5":
                                            s = "Coordenador Pedagógico";
                                            break;
                                        case "6":
                                            s = "Promotor de Eventos";
                                            break;
                                        case "7":
                                            s = "Tesoureiro";
                                            break;
                                        case "8":
                                            s = "Posse";
                                            break;
                                    }
                                    alertUser("O valor inserido não é válido para " + s + ".");
                                } else if (data === "0") {
                                    location.reload();
                                } else {
                                    alertUser("Falha na comunicação com o servidor.");
                                }
                            });
                }
                function salvarCoord() {
                    $.ajax({type: "POST", url: "/functions/coordenacao.php",
                        data: {"tc": $("#titulocoord").val() + "",
                            "c": $("#ccoord").val() + "",
                            "ts": $("#titulosubc").val() + "",
                            "s": $("#csubc").val() + "",
                            "p": $("#cposse").val() + ""
                        }
                    })
                            .done(function (data) {
                                if (data > 0) {
                                    var s = "";
                                    switch (data) {
                                        case "1":
                                            s = "Título do Coordenador";
                                            break;
                                        case "2":
                                            s = "Coordenador";
                                            break;
                                        case "3":
                                            s = "Título do Subcoordenador";
                                            break;
                                        case "4":
                                            s = "Subcoordenador";
                                            break;
                                        case "5":
                                            s = "Posse";
                                            break;
                                    }
                                    alertUser("O valor inserido não é válido para " + s + ".");
                                } else if (data === "0") {
                                    location.reload();
                                } else {
                                    alertUser("Falha na comunicação com o servidor.");
                                }
                            });
                }
                function cancelarDA() {
                    $("#addDA").fadeOut();
                    $("#blockfundo").fadeOut();
                    $("body").css({"overflow-y": "auto"});
                }
                function cancelarCoord() {
                    $("#addCoord").fadeOut();
                    $("#blockfundo").fadeOut();
                    $("body").css({"overflow-y": "auto"});
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
                </div>
            </div>
            <div id='page-container'>
                <?php include "./menu.php"; ?>
                <div id='content'>
                    <div id="title">Configurações do Sistema</div>
                    <?php
                    $sqlproperties = "select s.palestras, d.minicursos, r.veiculos, a.alunos "
                            . "from "
                            . "(Select count(pal_id) as palestras from tb_Palestra "
                            . "where pal_autorizacao = 1) as s, "
                            . "(Select count(min_id) as minicursos from tb_Minicurso "
                            . "where min_autorizacao = 1) as d, "
                            . "(Select count(alu_matricula) as alunos from tb_Aluno "
                            . "where alu_status = 1) as a, "
                            . "(Select count(vei_placa) as veiculos from tb_Veiculo "
                            . "where vei_adesivo = 1) as r ";
                    $qry1 = mysqli_query($con, $sqlproperties);
                    if ($qry1) {
                        $result = mysqli_fetch_array($qry1);
                        ?>

                        <h2>Informações do Sistema</h2>
                        <a class="ancora" href="/Admin/Alunos">
                            <div class="propertie" id="properties-1">
                                <p>Alunos Inscritos</p> 
                                <p class="number"><?php echo $result["alunos"] ?></p> 
                                <i class="fa fa-group"></i>
                            </div>
                        </a>
                        <a href="/Palestras">
                            <div class="propertie" id="properties-2">
                                <p>Palestras Cadastrados</p> 
                                <p class="number"><?php echo $result["palestras"] ?></p> 
                                <i class="fa fa-user"></i>
                            </div>
                        </a>
                        <a href="/Minicursos">
                            <div class="propertie" id="properties-3">
                                <p>Minicursos Cadastrados</p> 
                                <p class="number"><?php echo $result["minicursos"] ?></p> 
                                <i class="fa fa-book"></i>
                            </div>
                        </a>
                        <a href="/Veiculos">
                            <div class="propertie" id="properties-4">
                                <p>Veículos Cadastrados</p> 
                                <p class="number"><?php echo $result["veiculos"] ?></p> 
                                <i class="fa fa-car"></i>
                            </div>
                        </a>
                    <?php } ?>
                    <h2>Diretórios Acadamêmicos Eleitos</h2>
                    <?php
                    $sqldas = "Select * from tb_da";
                    $qry1 = mysqli_query($con, $sqldas);
                    if ($qry1) {
                        ?>
                        <table cellspacing="0" class="list">
                            <tr>
                                <th>
                                    Posse
                                </th>
                                <th>
                                    Coordenador
                                </th>
                                <th>
                                    Subcoordenador
                                </th>
                                <th>
                                    1º Secretário
                                </th>
                                <th>
                                    2º Secretário
                                </th>
                                <th>
                                    Coord. Pedagógico
                                </th>
                                <th>
                                    Promotor de Eventos
                                </th>
                                <th>
                                    Tesoureiro
                                </th>
                            </tr>
                            <?php
                            while ($result = mysqli_fetch_array($qry1)) {
                                ?>
                                <tr>
                                    <td><?php echo Date("d/m/y", strtotime($result["da_posse"])) ?></td>
                                    <td><a href="/Aluno/View/<?php echo $result["da_coordenador"] ?>" target="_blank"><?php echo $result["da_coordenador"] ?></a></td>
                                    <td><a href="/Aluno/View/<?php echo $result["da_subcoordenador"] ?>" target="_blank"><?php echo $result["da_subcoordenador"] ?></a></td>
                                    <td><a href="/Aluno/View/<?php echo $result["da_prim_secretario"] ?>" target="_blank"><?php echo $result["da_prim_secretario"] ?></a></td>
                                    <td><a href="/Aluno/View/<?php echo $result["da_seg_secretario"] ?>" target="_blank"><?php echo $result["da_seg_secretario"] ?></a></td>
                                    <td><a href="/Aluno/View/<?php echo $result["da_coord_pedagogico"] ?>" target="_blank"><?php echo $result["da_coord_pedagogico"] ?></a></td>
                                    <td><a href="/Aluno/View/<?php echo $result["da_promot_eventos"] ?>" target="_blank"><?php echo $result["da_promot_eventos"] ?></a></td>
                                    <td><a href="/Aluno/View/<?php echo $result["da_tesoureiro"] ?>" target="_blank"><?php echo $result["da_tesoureiro"] ?></a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    <?php } ?>
                    <div class="input submit"><br>
                        <input align="right" type="button" value="Cadastrar Novo Diretório Acadêmico" onclick="adicionarDA();">
                    </div>
                    <h2>Coordenações do Curso de Engenharia de Computação</h2>
                    <?php
                    $sqldas = "Select * from tb_coordenacao";
                    $qry1 = mysqli_query($con, $sqldas);
                    if ($qry1) {
                        ?>
                        <table cellspacing="0" class="list">
                            <tr>
                                <th>
                                    Posse
                                </th>
                                <th>
                                    Coordenador
                                </th>
                                <th>
                                    Subcoordenador
                                </th>
                            </tr>
                            <?php
                            while ($result = mysqli_fetch_array($qry1)) {
                                ?>
                                <tr>
                                    <td><?php echo Date("d/m/y", strtotime($result["coo_posse"])) ?></td>
                                    <td><?php echo $result["coo_coord_titulo"] ?><?php echo $result["coo_coordenador"] ?></td>
                                    <td><?php echo $result["coo_subcoord_titulo"] ?><?php echo $result["coo_subcoordenador"] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    <?php } ?>
                    <div class="input submit"><br>
                        <input align="right" type="button" value="Cadastrar Nova Coordenação" onclick="adicionarCoord()">
                    </div>
                </div>
            </div>
            <div id="blockfundo"></div>
            <div id="addDA">
                <form id='normal' method="post" style="min-width: 500px; padding: 0; margin: 0;">
                    <h2>Novos Eleitos do DA</h2>
                    <p style="color: red;" align="center">São solicitadas apenas matrículas para garantir veracidade das informações, para isso os alunos tem de estar previamente cadastrados.</p>
                    <table style="min-width: 100%;">
                        <tr>
                            <td>
                                <label for='coord'>Coordenador*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='coord' id="coord" size="50" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='subc'>Subcoordenador*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='subc' id="subc" size="50" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='sec1'>1º Secretário*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='sec1' id="sec1" size="50" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='sec2'>2º Secretário*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='sec2' id="sec2" size="50" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='coordp'>Coordenador Pedagógico*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='coordp' id="coordp" size="50" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='promot'>Promotor de Eventos*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='promot' id="promot" size="50" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='tes'>Tesoureiro*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='tes' id="tes" size="50" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='posse'>Posse*:</label>
                                <div class='input'>
                                    <i class='fa fa-calendar'></i>
                                    <input type='date' name='posse' id="posse" size="50" value="">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class='input submit'>
                        <i class='fa fa-send'></i>
                        <input type='button' name='submit' id="enviar" value="Salvar" onclick="salvarDA()">
                    </div>
                    <div class='input submit'>
                        <i class='fa fa-close'></i>
                        <input type='button' name='submit' id="enviar" value="Cancelar" onclick="cancelarDA()">
                    </div>
                </form>
            </div>
            <div id="addCoord">
                <form id='normal' method="post" style="min-width: 500px; padding: 0; margin: 0;">
                    <h2>Nova Coordenação do Curso</h2>
                    <p style="color: red;" align="center">São solicitadas apenas matrículas para garantir veracidade das informações, para isso os alunos tem de estar previamente cadastrados.</p>
                    <table style="min-width: 100%;">
                        <tr>
                            <td>
                                <label for='titulocoord'>Título do Coordenador*:</label>
                                <div class='input'>
                                    <i class='fa fa-list-alt'></i>
                                    <input type='text' name='titulocoord' id="titulocoord" size="15" value="Prof.">
                                </div>
                            </td>
                            <td>
                                <label for='ccoord'>Coordenador*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='ccoord' id="ccoord" size="25" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='tiitulosubc'>Título do Subcoordenador*:</label>
                                <div class='input'>
                                    <i class='fa fa-list-alt'></i>
                                    <input type='text' name='titulosubc' id="titulosubc" size="15" value="Prof.">
                                </div>
                            </td>
                            <td>
                                <label for='csubc'>Subcoordenador*:</label>
                                <div class='input'>
                                    <i class='fa fa-user'></i>
                                    <input type='text' name='csubc' id="csubc" size="25" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for='cposse'>Posse*:</label>
                                <div class='input'>
                                    <i class='fa fa-calendar'></i>
                                    <input type='date' name='cposse' id="cposse" size="50" value="">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class='input submit'>
                        <i class='fa fa-send'></i>
                        <input type='button' name='submit' id="enviar" value="Salvar" onclick="salvarCoord()">
                    </div>
                    <div class='input submit'>
                        <i class='fa fa-close'></i>
                        <input type='button' name='submit' id="enviar" value="Cancelar" onclick="cancelarCoord()">
                    </div>
                </form>
            </div>
        </body>
    </html>
<?php }
?>