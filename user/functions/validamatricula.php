<?php
include "./conexao.php";
include "./functions.php";
$r = valida($con,"matricula");
echo $r["valido"];
?>