<?php
session_start();
include "../veiculos.php";
include "../conexao.php";
$funct = $_GET["funct"];
if ($funct == "adhesive") {
    $r = alterarVeiculo($con, "vei_adesivo", "1", $_POST["id"]);
    echo json_encode($r);
} else if ($funct == "remove") {
    $r = removeVeiculo($con, $_POST["id"],$_SESSION["matricula"]);
    echo json_encode($r);
} else if ($funct == "renew") {
    $r = renovarVeiculo($con, $_POST["id"],$_SESSION["matricula"]);
    echo json_encode($r);
}else{
    echo json_encode(array("erro" => "Houve um erro ao realizar esta ação, tente novamente", "houveErro" => 1));
}