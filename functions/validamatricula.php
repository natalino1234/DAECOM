<?php
include "./conexao.php";
include "./alunos.php";
$r = validaAtributoAluno($con,"matricula");
echo $r["valido"];
?>