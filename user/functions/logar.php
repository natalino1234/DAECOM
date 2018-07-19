<?php
include 'conexao.php';
if(isset($_POST["matricula"]) && isset($_POST["senha"])){
	$senha = md5($_POST["senha"]);
	$matricula = $_POST["matricula"];
	$sql = "Select alu_matricula, alu_verificacao From tb_Aluno where alu_matricula='$matricula' and alu_senha='$senha';";
	$r = mysqli_query($con, $sql);
	if($r){
		$arr = mysqli_fetch_array($r);
		session_start();
		$_SESSION["matricula"] = $arr["alu_matricula"];
		$_SESSION["alu_verificacao"] = $arr["alu_verificacao"];
		echo 'ok';
	}else{
		session_destroy();
		echo "no";
	}
}else{
	session_destroy();
	echo "no";
}
?>