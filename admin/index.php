<?php
session_start();
include "../functions/conexao.php";
if (isset($_SESSION['matricula'])) {
    
} else {
    header("./login.php?dest=".$_SERVER['PHP_SELF']);
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="icon" href="/icon.png" type="image/png"/>
        <title>Admin - DA-ECOM - CEFET-MG Campus Timóteo</title>
        <style>
            @import url("/css/fonts.css");
            @import url("/css/style.css");
            @import url("/css/stylemenu.css");
            @import url("/css/properties.css");
            @import url("/css/tables.css");
            @import url("/css/totop.css");
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
            <?php include "../menu.php"; ?>
            <div id='content'>
                <div id="title">Bem-vindo, <?php echo $_SESSION["nome"] ?></div>
                <div id="top-ban"></div>
                <div id="properties">
                    <a class="ancora" href="/Admin/Alunos">
                        <div class="propertie" id="properties-1">
                            <p>Alunos Inscritos</p> 
                            <p class="number">20</p> 
                            <i class="fa fa-group"></i>
                        </div>
                    </a>
                    <a class="ancora" href="/Admin/Palestras">
                        <div class="propertie" id="properties-2">
                            <p>Palestras Aguardando</p> 
                            <p class="number">300</p> 
                            <i class="fa fa-slideshare"></i>
                        </div>
                    </a>
                    <a class="ancora" href="/Admin/Minicursos">
                        <div class="propertie" id="properties-3">
                            <p>Minicursos Aguardando</p> 
                            <p class="number">1000</p> 
                            <i class="fa fa-book"></i>
                        </div>
                    </a>
                    <a class="ancora" href="/Admin/Veiculos">
                        <div class="propertie" id="properties-4">
                            <p>Veículos Cadastrados</p> 
                            <p class="number">50000</p> 
                            <i class="fa fa-car"></i>

                        </div>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>