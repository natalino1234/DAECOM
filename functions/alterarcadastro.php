<?php

session_start();
include "./alunos.php";
include "./conexao.php";
if (isset($_POST['colAlt'])) {
    $matricula = "";
    if (isset($_SESSION['matricula'])) {
        $matricula = $_SESSION['matricula'];
    } else {
        $matricula = validaAtributoAluno($con, $matricula);
        if (!($matricula['erro'] === "" || strpos($matricula['erro'], "cadastrada") !== false)) {
            echo "0";
        }
    }
    $value = validaAtributoAluno($con, "valueAlt");
    $col = $_POST["colAlt"];
    $erro = false;
    if ($_POST['colAlt'] === "cpf") {
        if (!validaCPF($value["texto"])) {
            echo 0;
            $erro = true;
        }
    }
//    if ($_POST['colAlt'] === "alu_cnh") {
//        if (!validarCNH($value["texto"])) {
//            echo 0;
//            $erro = true;
//        }
//    }
    if ($_POST["colAlt"] === "matricula") {
        $erro = true;
        echo 0;
    }
    if (!$erro) {
        if ($_POST['colAlt'] === "senha") {
            $sql = "Select alu_email, alu_nome, alu_codver From tb_Aluno where alu_matricula = '" . $matricula . "'";
            $r = mysqli_query($con, $sql);
            if ($r) {
                $r = mysqli_fetch_array($r);
                $e = enviarEmailAlterCadastro($r['alu_nome'], $r['alu_codver'], $r['alu_email'], gzdeflate($_SESSION['matricula']), gzdeflate("alterarasenha"));
                echo $e;
            } else {
                echo "0";
            }
        } else {
            $erro = 0;
            $cols = array("alu_nome", "alu_telefone", "alu_celular", "alu_cpf", "alu_email", "alu_dataNasc");
            $r = str_replace($cols, "", $col);
            if ($value["valido"] && $col !== "") {
                $col = $_POST["colAlt"];
                $sql = "Update tb_Aluno Set alu_$col='" . $value["texto"] . "' where alu_matricula='" . $_SESSION['matricula'] . "'";
                $r = mysqli_query($con, $sql);
                if ($r) {
                    echo "1";
                } else {
                    echo "0";
                }
            } else {
                echo "0";
            }
        }
    }
} else {
    echo "0";
}
?>