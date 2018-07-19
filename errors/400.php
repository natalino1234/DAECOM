<?php session_start() ?>
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
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src="/js/jquery.js"></script>
        <script src="/js/scriptl.js"></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/forms.js'></script>
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
            <?php include "../menu.php" ?>
            <div id="content">
                <div id="title">Erro 400</div>
                <h2 style="width: 700px; border: none; text-align: center; margin-left: auto; margin-right: auto;">Não sei o que você disse...<br>Mas não conseguimos entender. :(</h2>
                <img src="/errors/400.png" style="margin-left: auto; margin-right: auto; opacity: 0.7;" width="500">
            </div>
    </body>
</html>