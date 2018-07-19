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
            <title><?php if ($_SESSION["tipo_usuario"] >= "1") { ?>
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
                @import url("./css/totop.css");
            </style>
            <link rel="stylesheet" href="/css/font-awesome.min.css">
            <script src='/js/jquery.js'></script>
            <script src='/js/backtop.js'></script>
        </head>
        <body>
            <div id='topo'></div>
            <a class='ancora tooltips' id='toTop' href='#topo'>
                <span>Voltar ao Topo</span>
                <i class="fa fa-arrow-up"></i>
            </a>
            <div id='page-container'>
                <?php include "./menu.php"; ?>
                <div id='content'>
                    <div id="title">Bem-vindo, <?php echo $_SESSION["nome"] ?></div>
                    <div id="top-ban"></div>
                    <div id="properties"><?php
                        $sqlproperties = "select s.palestras, d.minicursos, r.veiculos "
                                . "from "
                                . "(Select count(pal_id) as palestras from tb_Palestra "
                                . "where pal_autorizacao = 1 and alu_organizacao = '" . $_SESSION["matricula"] . "') as s, "
                                . "(Select count(min_id) as minicursos from tb_Minicurso "
                                . "where min_autorizacao = 1 and alu_organizacao = '" . $_SESSION["matricula"] . "') as d, "
                                . "(Select count(vei_placa) as veiculos from tb_Veiculo "
                                . "where alu_matricula = '" . $_SESSION["matricula"] . "') as r ";
                        $qry1 = mysqli_query($con, $sqlproperties);
                        if ($qry1) {
                            $result = mysqli_fetch_array($qry1);
                            ?>
                            <h2>Suas Informações</h2>
                            <div class="propertie" id="properties-1">
                                <p>Palestras Autorizadas</p> 
                                <p class="number"><?php echo $result["palestras"] ?></p> 
                                <i class="fa fa-send"></i>
                            </div>
                            <div class="propertie" id="properties-2">
                                <p>Minicursos Autorizados</p> 
                                <p class="number"><?php echo $result["minicursos"] ?></p> 
                                <i class="fa fa-slideshare"></i>
                            </div>
                            <div class="propertie" id="properties-4">
                                <p>Veículos Cadastrados</p> 
                                <p class="number"><?php echo $result["veiculos"] ?></p> 
                                <i class="fa fa-car"></i>
                            </div>
                            <br>
                            <?php
                            if ($_SESSION["tipo_usuario"] === "1") {
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
                                            <p>Palestras Cadastradas</p> 
                                            <p class="number"><?php echo $result["palestras"] ?></p> 
                                            <i class="fa fa-slideshare"></i>
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
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </body>
    </html>
<?php }
?>