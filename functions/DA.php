<?php

include './conexao.php';
include './alunos.php';

$keys = array_keys($_POST);

//var_dump($_POST);
if (count($keys) === 8) {
    $c = 0;
    $index = 0;
    for ($index = 0; $index < count($_POST) - 1; $index++) {
        if (validaMatricula($_POST[$keys[$index]]) === 0) {
            $c++;
        }
        if ($c === 1) {
            break;
        }
    }
    if ($_POST[$keys[count($_POST) - 1]] === "") {
        $c++;
    }
    if ($c >= 1) {
        echo $index + 1;
    } else if ($c === 0) {
        if (!isset($_POST[$keys[count($keys) - 1]])) {
            echo "8";
        } else {
            $coordenador = $_POST["c"];
            $subcoordenador = $_POST["s"];
            $primeirosec = $_POST["s1"];
            $segundosec = $_POST["s2"];
            $coordenadorpedagogico = $_POST["cp"];
            $promotordeeventos = $_POST["pe"];
            $tesoureiro = $_POST["t"];
            $posse = $_POST["p"];

            $sql = "insert into tb_da(da_coordenador, da_subcoordenador, da_prim_secretario, da_seg_secretario, da_tesoureiro, "
                    . "da_promot_eventos, da_coord_pedagogico, da_posse) values ('$coordenador', '$subcoordenador', '$primeirosec', "
                    . "'$segundosec', '$coordenadorpedagogico', '$promotordeeventos', '$tesoureiro', '$posse')";
            $t = mysqli_query($con, $sql);
            if ($t) {
                echo "0";
            } else {
                echo "-2";
            }
        }
    }
} else {
    echo "-2";
}