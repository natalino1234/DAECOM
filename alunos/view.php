<?php
session_start();
include "../functions/alunos.php";
include "../functions/conexao.php";
$deuruim = false;
$aluno = null;
if (isset($_GET["id"]) && $_GET["id"] !== "") {
    $r = getAluno($con, $_GET["id"]);
    if ($r["houveErro"] !== 1) {
        if (mysqli_num_rows($r["return"]) > 0) {
            $aluno = mysqli_fetch_array($r["return"]);
        }
    } else {
        $deuRuim = true;
    }
} else {
    $deuRuim = true;
}
if ($deuruim) {
    echo "<meta http-equiv='refresh' content='0;url=/Admin' />";
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
            @import url("/css/tables.css");
            @import url("/css/totop.css");
            @import url("/css/tooltips.css");
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src='/js/jquery.js'></script>
        <script src='/js/backtop.js'></script>
        <script src='/js/forms.js'></script>
    </head>
    <body>
        <div id='topo'></div>
        <a class='ancora tooltips' id='toTop' href='#topo'>
            <span>Voltar ao Topo</span>
            <i class="fa fa-arrow-up"></i>
        </a>
        <div id='page-container'>
            <?php include "../menu.php"; ?>
            <div id='content' style="padding-top: 20px;">
                <h2><?php echo $aluno["alu_nome"] ?></h2>
                <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                    <h3><?php echo ($aluno["alu_status"] === "1") ? "Desbloqueado" : "Bloqueado" ?></h3>
                <?php } ?>
                <table style="margin-left: 50px;">
                    <tr>
                        <td colspan="2"><h2>Informações Básicas</h2>
                    <tr>
                        <td>Período: <?php echo getPeriodo($_GET["id"]) . "º" ?>
                    <tr>
                        <td>Matrícula: <?php echo $aluno["alu_matricula"] ?>
                    <tr>
                        <td>Data de Nascimento: <?php echo Date("d/m/Y", strtotime($aluno["alu_dataNasc"])) ?>
                    <tr>
                        <td colspan="2"><h2>Contato</h2>
                    <tr>
                        <td>E-mail: <?php echo $aluno["alu_email"] ?>
                    <tr>
                        <td>Telefone Residencial: <?php echo $aluno["alu_telefone"] ?>
                    <tr>
                        <td>Telefone Celular: <?php echo $aluno["alu_celular"] ?>
                            <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                        <tr>
                            <td colspan="2"><h2>Informações de Localidade</h2>
                        <tr>
                            <td>CEP: <?php echo $aluno["alu_cep"] ?>
                        <tr>
                            <td>Cidade: <?php echo utf8_encode($aluno["alu_cidade"]) ?>
                        <tr>
                            <td>Estado: <?php echo $aluno["alu_uf"] ?>
                        <tr>
                            <td>Bairro: <?php echo $aluno["alu_bairro"] ?>
                        <tr>
                            <td>Endereço: <?php echo utf8_encode($aluno["alu_logradouro"] . "," . $aluno["alu_complemento"]) ?>
                            <?php } ?>
                </table>
            </div>
        </div>
    </body>
</html>