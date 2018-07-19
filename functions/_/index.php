<?php
	session_start();   
	require_once "./util/conexao.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
		<title>Academia BioFitness</title>
		<!-- CSS  -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script src="./js/jquery.mask.js"></script>
		<script src="./js/jquery.validate.js"></script>
		<script src="./js/materialize.js"></script>
		<script src="./js/treino.js"></script>
	</head>
	<!--Gerenciar-->
	<body>
		<?php include_once "./tela.php"; ?>

	</body>
</html>
