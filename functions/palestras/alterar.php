<?php
session_start();
include "../palestras.php";
include "../conexao.php";
$funct = $_GET["funct"];
if ($funct == "allow") {
    $r = autorizarPalestra($con, $_POST["id"],$_SESSION["matricula"]);
    echo json_encode($r);
} else if ($funct == "deny") {
    $r = recusarPalestra($con, $_POST["id"],$_SESSION["matricula"]);
    echo json_encode($r);
} else if ($funct == "signup") {
    $r = participarPalestra($con, $_POST["id"], $_SESSION["matricula"]);
    echo json_encode($r);
} else if ($funct == "leave") {
    $r = sairPalestra($con, $_POST["id"], $_SESSION["matricula"]);
    echo json_encode($r);
}