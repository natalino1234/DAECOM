<?php

include './conexao.php';
include './functions.php';

$keys = array_keys($_POST);

//var_dump($_POST);
if (count($keys) === 5) {
    $c = 0;
    $index = 0;
    for ($index = 0; $index < count($_POST) - 1; $index++) {
        $arr = validaPOST($keys[$index]);
        if ($arr["valido"] === 0) {
            $c++;
        }
        if ($c === 1) {
            break;
        }
    }
    if($_POST[$keys[count($_POST) - 1]]===""){
        $c++;
    }
    if ($c >= 1) {
        echo $index+1;
    } else if ($c === 0) {
        if (!isset($_POST[$keys[count($keys) - 1]])) {
            echo "5";
        } else {
            $tcoordenador = $_POST["tc"];
            $coordenador = $_POST["c"];
            $tsubcoordenador = $_POST["ts"];
            $subcoordenador = $_POST["s"];
            $posse = $_POST["p"];

            $sql = "insert into tb_Coordenacao(coo_coordenador, coo_subcoordenador, coo_coord_titulo, "
                    . "coo_subcoord_titulo, coo_posse) values ('$coordenador', '$subcoordenador', "
                    . "'$tcoordenador', '$tsubcoordenador', '$posse')";
            $t = mysqli_query($con, $sql);
            if ($t) {
                echo "0";
            } else {
                echo "-2";
            }
        }
    }
}  else {
    echo "-2";
}