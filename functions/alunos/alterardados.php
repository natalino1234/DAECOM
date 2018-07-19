<?php
include "../alunos.php";
include "../conexao.php";
$funct = $_GET["funct"];
if ($funct == "block") {
    $r = alterarAluno($con, "status", 0, $_POST["matricula"]);
    echo json_encode($r);
} else if ($funct == "unblock") {
    $r = alterarAluno($con, "status", 1, $_POST["matricula"]);
    echo json_encode($r);
} else if ($funct == "change") {
    
}