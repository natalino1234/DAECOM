<?php
include "./conexao.php";
include "./functions.php";
$senha = valida($con,"senha");
$csenha = valida($con,"confsenha");
$mat = valida($con,"matricula");
if($senha['valido'] === 1 && $confsenha['valido'] === 1 && ($mat['erro'] === "" || strpos($mat['erro'],"cadastrada") !== false)){
	if(strlen($senha['texto'])>8 && $senha['texto']===$confsenha['texto']){
		$sql = "update tb_Aluno set alu_senha = ".md5($senha['texto'])."where alu_matricula='".$mat['texto']."'";
		$r = mysqli_query($con, $sql);
		if($r){
			echo '1';
		}else{
			echo "0";
		}
	}else{
		echo '0';
	}
}else{
	echo '0';
}
?>