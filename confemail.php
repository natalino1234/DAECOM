<?php
include "./functions/conexao.php";
if (isset($_GET['codver']) && isset($_GET['matricula'])) {
    $c = $_GET['codver'];
    if (strlen($c) > 20) {
        $sql = "select alu_verificacao from tb_Aluno where alu_matricula = '" . $_GET["matricula"] . "' and alu_codver = '$c'";
        $r = mysqli_query($con, $sql);
        if ($r) {
            if (mysqli_num_rows($r) > 0) {
                $result = mysqli_fetch_array($r);
                if ($result['alu_verificacao'] == 0) {
                    $sqlup = "update tb_Aluno set alu_verificacao = 1 where alu_matricula = '" . $_GET["matricula"] . "'";
                    $rup = mysqli_query($con, $sqlup);
                    if ($rup) {
                        echo "<script>alert('Bem-Vindo ao Sistema do Diretório Acadêmico de Timóteo!!! O seu E-mail foi validado com sucesso!!!');</script>";
                    } else {
                        echo "<script>alert('Houve um erro ao processar a validação, tente novamente. =(');</script>";
                    }
                } else {
                    echo "<script>alert('Esta conta já foi validada.');</script>";
                }
                session_start();
                if (isset($_SESSION['matricula'])) {
                    $_SESSION['alu_verificacao'] = 1;
                } else {
                    session_destroy();
                }
            }
        } else {
            echo "<script>alert('A matrícula informada não está de acordo com o código, tente novamente. =(');</script>";
        }
    } else {
        echo "<script>alert('O Código de Confirmação é inválido, tente novamente.');</script>";
    }
} else {
    echo "<script>alert('A URL não é válida, tente novamente.');</script>";
}
?>
<meta charset="utf-8">
<meta http-equiv="refresh" content="0;url=/">