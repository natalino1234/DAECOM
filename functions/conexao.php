<?php
header('Content-Type: text/html; charset=utf8');
$servidor = "localhost";
$user = "root";
$password = "";
$bd = "daecom";
//$servidor = "mysql.hostinger.com.br";
//$user = "u629824613_admin";
//$password = "daecom20";
//$bd = "u629824613_daeco";
$con = mysqli_connect($servidor, $user, $password, $bd) or die("<script>Houve um erro na criação da conexão com Banco de Dados.</script>");
?>