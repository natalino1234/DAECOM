<?php
session_start();
include "./functions/conexao.php";
if (isset($_SESSION['matricula'])) {
    $ver = $_SESSION["alu_verificacao"];
    if (!$ver) {
        header("location: falhaconfemail.php?matricula=" . $_SESSION['matricula']);
    }
} else {
    header("location: login.php?login=0&dest=" . $_SERVER['PHP_SELF']);
}
$sql = "Select * from tb_Aluno where alu_matricula='" . $_SESSION['matricula'] . "'";
$r = mysqli_query($con, $sql);
if ($r) {
    $r = mysqli_fetch_array($r);
} else {
    echo "<script>alert('Esta conta a qual a sessão atual está vinculada, não existe.');</script>";
    echo '<meta http-equiv="refresh" content="0;url=/Logout">';
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
            @import url("/css/alert.css");
            .cancel{
                display: none;
            }
            table{
                margin: 10px;
                min-width: 800px;
            }
            table *{
                text-align: left;
            }
            table td{
                min-width: 100px;
                padding: 5px;
                padding-right: 10px;
            }
            table .esq{
                text-align: right;
                width: 200px;
                padding-right: 10px;
            }
            table .edittd{
                text-align: center;
                width: 200px;
                padding-right: 10px;
            }
            #help{
                position: relative !important;
                top: 0px !important;
                left: 10px !important;
                width: 700px !important;
            }
            form input{
                border: none;
                background: none;
            }
        </style>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <script src='/js/jquery.js'></script>
        <script>
            function alertUser(mensagem) {
                tempo = mensagem.split(" ").length * 1000;
                $("#alert .mensagem").html(mensagem);
                $("#alert").fadeIn(500);
                setTimeout(function () {
                    $("#alert").fadeOut(500);
                }, tempo);
            }
        </script>
        <script src='/js/backtop.js'></script>
        <script src='/js/alert.js'></script>
        <script src='/js/scriptmc.js'></script>
        <script src='/js/scriptl.js'></script>
        <script src='/js/vcad.js'></script>
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
            <div class='image-preview'>
                <div>Imagem para Upload</div>
                <img id="image">
            </div>
            <?php include "../menu.php"; ?>
            <div id='content'>
                <form>
                    <div id="title">Minha Conta</div>
                    <table cellspacing="0">
                        <tr>
                            <th colspan="3">
                                <h2>Informações de Login</h2>
                            </th>
                        </tr>
                        <tr>
                            <td class="esq">
                                Matrícula
                            </td>
                            <td colspan="2">
                                <?php echo $_SESSION['matricula'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Senha
                            </td>
                            <td>
                            </td>
                            <td class="edittd">
                                <a class="edits">Solicitar Alteração</a>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <h2>Informações Pessoais</h2>
                            </th>
                        </tr>
                        <tr>
                            <td class="esq">
                                Nome
                            </td>
                            <td>
                                <input id="nome" type="text" value="<?php echo $r['alu_nome'] ?>" size="<?php echo strlen($r['alu_nome']) ?>" disabled>
                                <input id="nomeb" type="text" value="<?php echo $r['alu_nome'] ?>" size="<?php echo strlen($r['alu_nome']) ?>" disabled hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Data de Nascimento
                            </td>
                            <td>
                                <input id="dataNasc" type="text" value="<?php echo $r['alu_dataNasc'] ?>" size="<? echo strlen($r['alu_dataNasc'])?>"  disabled>

                                <input id="dataNascb" type="text" value="<?php echo $r['alu_dataNasc'] ?>" size="<? echo strlen($r['alu_dataNasc'])?>"  hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                E-mail
                            </td>
                            <td>
                                <input id="email" type="email" disabled value="<?php echo $r['alu_email'] ?>" size="<?php echo strlen($r['alu_email']) ?>" >
                                <input id="emailb" type="email" disabled value="<?php echo $r['alu_email'] ?>" size="<?php echo strlen($r['alu_email']) ?>" hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                CPF
                            </td>
                            <td>
                                <input id="cpf" type="text" disabled value="<?php echo $r['alu_cpf'] ?>" size="<?php echo strlen($r['alu_cpf']) ?>" >
                                <input id="cpfb" type="text" disabled value="<?php echo $r['alu_cpf'] ?>" size="<?php echo strlen($r['alu_cpf']) ?>" hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                CNH
                            </td>
                            <td>
                                <input id="cnh" type="text" disabled value="<?php echo $r['alu_cnh'] ?>" size="<?php echo strlen($r['alu_cnh']) ?>" >
                                <input id="cnhb" type="text" disabled value="<?php echo $r['alu_cnh'] ?>" size="<?php echo strlen($r['alu_cnh']) ?>" hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Emissor da CNH
                            </td>
                            <td>
                                <input id="emcnh" type="text" disabled value="<?php echo $r['alu_emcnh'] ?>" size="<?php echo strlen($r['alu_emcnh']) ?>" >
                                <input id="cemnhb" type="text" disabled value="<?php echo $r['alu_emcnh'] ?>" size="<?php echo strlen($r['alu_emcnh']) ?>" hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Telefone
                            </td>
                            <td>
                                <input id="telefone" type="text" disabled value="<?php echo $r['alu_telefone'] ?>" size="<?php echo strlen($r['alu_telefone']) ?>" maxlength="14">
                                <input id="telefoneb" type="text" disabled value="<?php echo $r['alu_telefone'] ?>" size="<?php echo strlen($r['alu_telefone']) ?>" hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Celular
                            </td>
                            <td>
                                <input id="celular" type="text" disabled value="<?php echo $r['alu_celular'] ?>" size="<?php echo strlen($r['alu_celular']) ?>" maxlength="14">
                                <input id="celularb" type="text" disabled value="<?php echo $r['alu_celular'] ?>" size="<?php echo strlen($r['alu_celular']) ?>" hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <h2>Informações de Localidade</h2>
                            </th>
                        </tr>
                        <tr>
                            <td class="esq">
                                CEP
                            </td>
                            <td>
                                <input id="cep" type="text" disabled value="<?php echo $r['alu_cep'] ?>" size="<?php echo strlen($r['alu_cep']) ?>" maxlength="8">
                                <input id="cepb" type="text" disabled value="<?php echo $r['alu_cep'] ?>" size="<?php echo strlen($r['alu_cep']) ?>" hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Estado (Sigla)
                            </td>
                            <td>
                                <input id="uf" type="text" disabled value="<?php echo $r['alu_uf'] ?>" size="<?php echo strlen($r['alu_uf']) ?>" maxlength="2">
                                <input id="ufb" type="text" disabled value="<?php echo $r['alu_uf'] ?>" size="<?php echo strlen($r['alu_uf']) ?>" hidden>
                            </td>
                            <td class="edittd" rowspan="4">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Cidade
                            </td>
                            <td>
                                <input id="cidade" type="text" disabled value="<?php echo $r['alu_cidade'] ?>" size="<?php echo strlen($r['alu_cidade']) ?>" >
                                <input id="cidadeb" type="text" disabled value="<?php echo $r['alu_cidade'] ?>" size="<?php echo strlen($r['alu_cidade']) ?>" hidden>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Bairro
                            </td>
                            <td>
                                <input id="bairro" type="text" disabled value="<?php echo $r['alu_bairro'] ?>" size="<?php echo strlen($r['alu_bairro']) ?>" >
                                <input id="bairrob" type="text" disabled value="<?php echo $r['alu_bairro'] ?>" size="<?php echo strlen($r['alu_bairro']) ?>" hidden>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Logradouro
                            </td>
                            <td>
                                <input id="logradouro" type="text" disabled value="<?php echo $r['alu_logradouro'] ?>" size="<?php echo strlen($r['alu_logradouro']) ?>" >
                                <input id="logradourob" type="text" disabled value="<?php echo $r['alu_logradouro'] ?>" size="<?php echo strlen($r['alu_logradouro']) ?>" hidden>
                            </td>
                        </tr>
                        <tr>
                            <td class="esq">
                                Número e Complemento
                            </td>
                            <td>
                                <input id="complemento" type="text" disabled value="<?php echo $r['alu_complemento'] ?>" size="<?php echo strlen($r['alu_complemento']) ?>" >
                                <input id="complementob" type="text" disabled value="<?php echo $r['alu_complemento'] ?>" size="<?php echo strlen($r['alu_complemento']) ?>" hidden>
                            </td>
                            <td class="edittd">
                                <a class="edit">Editar</a>
                                <a class="cancel">Cancelar</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="help" id="help">Ao alterar qualquer um dos itens desbloqueados, será feito uma busca automática pelo CEP.</div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>