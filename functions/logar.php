<?php

session_start();
include 'conexao.php';
if (isset($_POST["matricula"]) && isset($_POST["senha"])) {
    $senha = md5($_POST["senha"]);
    $matricula = $_POST["matricula"];
    $sql = "Select alu_matricula, alu_verificacao, alu_type, alu_nome From tb_Aluno where alu_matricula='$matricula' and alu_senha='$senha';";
    $sqlda = "Select * from tb_da order by da_posse desc limit 1";
    $dar = mysqli_query($con, $sqlda);
    $r = mysqli_query($con, $sql);
    if ($r) {
        if (mysqli_num_rows($r) > 0) {
            $arr = mysqli_fetch_array($r);
            $_SESSION["matricula"] = $arr["alu_matricula"];
            $_SESSION["alu_verificacao"] = $arr["alu_verificacao"];
            $_SESSION["nome"] = $arr["alu_nome"];
            $_SESSION["tipo_usuario"] = $arr["alu_type"];
            $mda = false;
            if ($dar) {
                $dar = mysqli_fetch_array($dar);
                $keys = array_keys($dar);
                for ($index = 0; $index < count($keys); $index++) {
                    if ($dar[$keys[$index]] === $arr["alu_matricula"]) {
                        $mda = true;
                        break;
                    }
                }
            }
            if ($mda) {
                $_SESSION["tipo_usuario"] = "1";
                $_SESSION["nome_tipo_usuario"] = "Membro do DA";
            } else {
                if ($_SESSION["tipo_usuario"] === "1") {
                    $_SESSION["nome_tipo_usuario"] = "Administrador";
                } else {
                    $_SESSION["nome_tipo_usuario"] = "Aluno";
                }
            }
            echo 'ok';
        } else {
            session_destroy();
            echo "no";
        }
    } else {
        session_destroy();
        echo "no";
    }
} else {
    session_destroy();
    echo "no";
}
?>