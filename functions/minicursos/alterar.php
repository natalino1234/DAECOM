<?php
session_start();
include "../minicursos.php";
include "../conexao.php";
$funct = $_GET["funct"];
if ($funct == "allow") {
    $r = autorizarMinicurso($con, $_POST["id"],$_SESSION["matricula"]);
    echo json_encode($r);
} else if ($funct == "deny") {
    $r = recusarMinicurso($con, $_POST["id"],$_SESSION["matricula"]);
    echo json_encode($r);
} else if ($funct == "signup") {
    $r = participarMinicurso($con, $_POST["id"], $_SESSION["matricula"]);
    echo json_encode($r);
} else if ($funct == "leave") {
    $r = sairMinicurso($con, $_POST["id"], $_SESSION["matricula"]);
    echo json_encode($r);
}