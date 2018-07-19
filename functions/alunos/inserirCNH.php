<?php

if (isset($_SESSION['matricula'])) {
    $cnh = validaAtributoAluno($con, "cnh");
    if ($cnh["texto"] !== "" && strlen($cnh["texto"]) === 11) {
        $r = inserirCNH($con, $cnh["texto"], $_SESSION["matricula"]);
    } else {
        if ($cnh["texto"] !== "") {
            $msg = "O CNH inserido é inválido.";
        }
    }
}