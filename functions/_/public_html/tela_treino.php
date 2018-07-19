<script src="./js/treino.js"></script>

<?php 
require_once 'util/conexao.php';
if(!isset($_REQUEST["btvar"])) $_REQUEST["btvar"] = "btnSalvar";
if(!isset($_REQUEST["btlabel"])) $_REQUEST["btlabel"] = "Salvar";
if(!isset($_REQUEST["cdTreino"])) $_REQUEST["cdTreino"] = "";
if(!isset($_REQUEST["cdCliente"])) $_REQUEST["cdCliente"] = "";
if(!isset($_REQUEST["cdObjetivo"])) $_REQUEST["cdObjetivo"] = "";
if(!isset($_REQUEST["dtInicio"])) $_REQUEST["dtInicio"] = "";
if(!isset($_REQUEST["dtFim"])) $_REQUEST["dtFim"] = "";
if(!isset($_REQUEST["dataTreino"])) $_REQUEST["dataTreino"] = "";

if(!(isset($_REQUEST["acao"]) || isset($_REQUEST["btnSalvar"]) || isset($_REQUEST["btnAlterar"]) || isset($_REQUEST["btnExc"]))){
  if(isset($_SESSION["treino"])) unset($_SESSION["treino"]);
}
   if(isset($_REQUEST["acao"])){
        $id = $_REQUEST["id"];
        if($_REQUEST["acao"] == "alt"){
            $_REQUEST["btvar"] = "btnAlterar";
            $_REQUEST["btlabel"] = "Alterar";    
        }else{
            $_REQUEST["btvar"] = "btnExc";
            $_REQUEST["btlabel"] = "Excluir";
        }
        $sql = "SELECT * FROM tbtipotreino where cdTreino = :cdTreino";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
                "cdTreino" => $id
        ]);
        $recordSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $_REQUEST["cdTreino"] = $id;
        $_REQUEST["cdObjetivo"] = $recordSet["cdObjetivo"];
        $_REQUEST["cdCliente"] = $recordSet["cdCliente"];
        $_REQUEST["dtFim"] = date('d/m/Y', strtotime($recordSet["dataFim"]));
        $_REQUEST["dtInicio"] = date('d/m/Y', strtotime($recordSet["dataInicio"]));

        $sql = "SELECT * FROM tbexerciciotreino where cdTreino = :cdTreino";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
          "cdTreino" => $id
        ]);
        $recordSet = $stmt->fetchAll();
        if(session_status() == PHP_SESSION_NONE) session_start();

        if(isset($_SESSION["treino"])) unset($_SESSION["treino"]);
        $_SESSION["treino"] = array();

        foreach($recordSet as $row){
          array_push($_SESSION["treino"],[
                    "exercicio" => $row["cdExercicio"],
                    "qtd" => $row["qtd"],
                    "repeticoes" => $row["repeticao"]
                ]);
        }
    }

if(isset($_REQUEST["btnSalvar"])){  
    if (!(empty($_REQUEST["cdCliente"]) || empty($_REQUEST["cdObjetivo"])
                || empty($_REQUEST["dtInicio"]) || empty($_REQUEST["dtFim"])
                || empty($_REQUEST["dataTreino"]))){ 
    $cdCliente = $_REQUEST["cdCliente"];
    $cdObjetivo = $_REQUEST["cdObjetivo"];
    $dtInicio = date('Y-m-d', strtotime(str_replace('/','-',$_REQUEST["dtInicio"])));
    $dtFim =  date('Y-m-d', strtotime(str_replace('/','-',$_REQUEST["dtFim"])));
    $exercicioArr = json_decode($_REQUEST["dataTreino"]);
    if(sizeof($exercicioArr) > 0){
      $sql = "INSERT INTO `tbtipotreino` (`cdCliente`, `dataInicio`, `dataFim`, `cdObjetivo`) VALUES (:cdCliente, :dtInicio, :dtFim, :cdObjetivo);";
      $stmt = $conexao->prepare($sql);
      $stmt->execute([
        "cdCliente" => $cdCliente,
        "cdObjetivo" => $cdObjetivo,
        "dtInicio" => $dtInicio,
        "dtFim" => $dtFim
      ]);
      
      $sql = "SELECT MAX(cdTreino) as cdTreino from tbtipotreino;";
      $stmt= $conexao->prepare($sql);
      $stmt->execute();
      $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
      $cdTreino = $resultSet["cdTreino"];

      $sql = "INSERT INTO `tbexerciciotreino` (`cdTreino`, `cdExercicio`, `qtd`, `repeticao`) VALUES (:cdTreino, :cdExercicio, :qtd, :repeticoes);";
      $stmt = $conexao->prepare($sql);
      for($i =0 ;$i<sizeof($exercicioArr);$i++){
        $cdExercicio =  $exercicioArr[$i]->exercicio;
        $qtd = $exercicioArr[$i]->qtd;
        $repeticoes = $exercicioArr[$i]->repeticoes;
        $stmt->execute([
          "cdTreino" => $cdTreino,
          "cdExercicio" => $cdExercicio,
          "qtd" => $qtd,
          "repeticoes" => $repeticoes
        ]);
      }

      unset($_SESSION["treino"]);
      $_REQUEST["cdCliente"] = "";
      $_REQUEST["cdObjetivo"] = "";
      $_REQUEST["dtInicio"] = "";
      $_REQUEST["dtFim"] = "";
      	echo '<script>Materialize.toast("Inserido com sucesso!", 4000)</script>';
    }
  }else{
    echo "<script>Materialize.toast('Insira todos os dados antes de continuar', 4000)</script>";
  }
}
if(isset($_REQUEST["btnAlterar"])){  
    if (!(empty($_REQUEST["cdCliente"]) || empty($_REQUEST["cdObjetivo"])
                || empty($_REQUEST["dtInicio"]) || empty($_REQUEST["dtFim"])
                || empty($_REQUEST["dataTreino"]))){
    $cdTreino = $_REQUEST["cdTreino"];
    $cdCliente = $_REQUEST["cdCliente"];
    $cdObjetivo = $_REQUEST["cdObjetivo"];
    $dtInicio = date('Y-m-d', strtotime(str_replace('/','-',$_REQUEST["dtInicio"])));
    $dtFim =  date('Y-m-d', strtotime(str_replace('/','-',$_REQUEST["dtFim"])));
    $exercicioArr = json_decode($_REQUEST["dataTreino"]);
    if(sizeof($exercicioArr) > 0){
      $sql = "update  `tbtipotreino` SET `cdCliente` = :cdCliente, `dataInicio` = :dtInicio, `dataFim` =:dtFim, `cdObjetivo`=:cdObjetivo where cdTreino = :cdTreino;";
      $stmt = $conexao->prepare($sql);
      $stmt->execute([
        "cdCliente" => $cdCliente,
        "cdObjetivo" => $cdObjetivo,
        "dtInicio" => $dtInicio,
        "dtFim" => $dtFim,
        "cdTreino" => $cdTreino
      ]);
      
      $sql = "DELETE FROM `tbexerciciotreino` WHERE cdTreino = :cdtreino;";
      $stmt = $conexao->prepare($sql);
      $stmt->execute(["cdtreino"=>$cdTreino]);

      $sql = "INSERT INTO `tbexerciciotreino` (`cdTreino`, `cdExercicio`, `qtd`, `repeticao`) VALUES (:cdTreino, :cdExercicio, :qtd, :repeticoes);";
      $stmt = $conexao->prepare($sql);
      for($i =0 ;$i<sizeof($exercicioArr);$i++){
        $cdExercicio =  $exercicioArr[$i]->exercicio;
        $qtd = $exercicioArr[$i]->qtd;
        $repeticoes = $exercicioArr[$i]->repeticoes;
        $stmt->execute([
          "cdTreino" => $cdTreino,
          "cdExercicio" => $cdExercicio,
          "qtd" => $qtd,
          "repeticoes" => $repeticoes
        ]);
      }
	 echo '<script>Materialize.toast("Alterado com sucesso!", 4000)</script>';
      $_REQUEST["btvar"] = "btnSalvar";
      $_REQUEST["btlabel"] = "Salvar";
      unset($_SESSION["treino"]);
      $_REQUEST["cdCliente"] = "";
      $_REQUEST["cdObjetivo"] = "";
      $_REQUEST["dtInicio"] = "";
      $_REQUEST["dtFim"] = "";
    }
  }else{
    echo "<script>Materialize.toast('Insira todos os dados antes de continuar', 4000)</script>";
  }
}
if(isset($_REQUEST["btnExc"])){  
  $id = $_REQUEST["cdTreino"];
  $sql = "DELETE FROM tbtipotreino where cdTreino = :cdTreino";
  $stmt = $conexao->prepare($sql);
  $stmt->execute(["cdTreino"=>$id]);
  $sql = "DELETE FROM tbexerciciotreino where cdTreino =:cdTreino";
  $stmt = $conexao->prepare($sql);
  $stmt->execute(["cdTreino"=>$id]);
  
  unset($_SESSION["treino"]);
  $_REQUEST["cdCliente"] = "";
  $_REQUEST["cdObjetivo"] = "";
  $_REQUEST["dtInicio"] = "";
  $_REQUEST["dtFim"] = "";
  	echo '<script>Materialize.toast("Excluido com sucesso!", 4000)</script>';
}
?>

<div class="section no-pad-bot" id="index-banner">
    <div class="container screen-content">
      <h1 class="header center pink-text">Treino</h1>
	  
	  <div class="row-top right">
      </div>

<div class="row">
    <form class="col s12" id="formCrud" action="index.php?tela=treino" method="POST">
      <input type="text" hidden value="<?php echo $_REQUEST["cdTreino"];?>" name="cdTreino"/>
      <div class="row">
        <div class="input-field col s12">
          <select id="selCliente" name="cdCliente">
          
          <option value=""></option> 
            <?php
$sql = "SELECT cdCliente, nmCliente FROM tbcliente where tipo = 2";
$dataSet = $conexao->prepare($sql);
$dataSet->execute();
$resultSet = $dataSet->fetchAll();

if(!empty(resultSet)){
	foreach($resultSet as $row){
    if($_REQUEST["cdCliente"] == $row["cdCliente"]){
		  echo "<option selected value='".$row["cdCliente"]."'>".$row["nmCliente"]."</option>";	
    }else{
		  echo "<option value='".$row["cdCliente"]."'>".$row["nmCliente"]."</option>";	
    }
	}
}
?>
          </select>
          <label>Cliente</label>
        </div>
        </div>
        <div class="row">
        <div class="input-field col s12">
          <select id="selObjetivo" name="cdObjetivo">
          <option value=""></option> 
          <?php
$sql = "SELECT cdObjetivo, nmObjetivo FROM tbobjetivo";
$dataSet = $conexao->prepare($sql);
$dataSet->execute();
$resultSet = $dataSet->fetchAll();

if(!empty(resultSet)){
	foreach($resultSet as $row){
    if($row["cdObjetivo"] == $_REQUEST["cdObjetivo"]){
      echo "<option selected value='".$row["cdObjetivo"]."'>".$row["nmObjetivo"]."</option>";	
    }else{
      echo "<option value='".$row["cdObjetivo"]."'>".$row["nmObjetivo"]."</option>";	  
    }
	}
}
?>
          </select>
          <label>Objetivo</label>
        </div>
        </div>
      <div class="row">
        <div class="input-field col s6">
          <input type="text" class="datepicker" name="dtInicio" value="<?php echo $_REQUEST["dtInicio"]; ?>" id="dataInicio"/>
          <label for="dataPicker">Data de Inicio</label>
        </div>
        <div class="input-field col s6">
          <input type="text" class="datepicker" name="dtFim" value="<?php echo $_REQUEST["dtFim"]; ?>" id="dataFim"/>
          <label for="dataPicker">Data de Termino</label>
        </div>
      </div>     
      <div class="sub-crud">
       <h3 class="header center pink-text">Exercícios</h1>
	  
      <div class="row">
        <div class="input-field col s12">
          <select name="exercicio" id="exercicio">
            <?php
$sql = "SELECT cdExercicio, nmExercicio FROM tbexercicio";
$dataSet = $conexao->prepare($sql);
$dataSet->execute();
$resultSet = $dataSet->fetchAll();
if(!empty(resultSet)){
	foreach($resultSet as $row){
		echo "<option value='".$row["cdExercicio"]."'>".$row["nmExercicio"]."</option>";
	}
}
?>
          </select>
          <label >Exercício</label>
        </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <p class="range-field">
      Quantidade<input type="range" id="quantidade" min="1" max="100" />
    </p>
          </div>
        </div>
        
        <div class="row">
          <div class="input-field col s12">
         Repetição  <p class="range-field">
      <input type="range" id="repeticoes" min="1" max="10" />
    </p>
          </div>
        </div>
    <div class='center-align'>
      <button id="btAdd" class="btn waves-effect waves-light" value="">Adicionar</button>
    </div>
    <div id="subData">
      <?php include_once "./auxtreino.php"; ?>
     </div>
        </div>
    <div class='center-align'>
         <?php 
        if(isset($_REQUEST["acao"])){
            echo "<a class='btn waves-effect waves-light btn-submit-treino' href='index.php?tela=treino'>Cancelar</a>";
        }
    ?>
    <input type='text' hidden name="<?php echo $_REQUEST["btvar"]; ?>"  value="<?php echo $_REQUEST["btlabel"]; ?>">
    <button class="btn waves-effect waves-light btn-submit-treino" name="<?php echo $_REQUEST["btvar"]; ?>" id="<?php echo $_REQUEST["btvar"]; ?>" value=""><?php echo $_REQUEST["btlabel"]; ?></button>
    </div>  
      </div>
   </form>        
   

<table id="tableResult" class="highlight bordered responsive-table">
       <h2 class="header center pink-text">Treinos Cadastrados </h2>
            <thead>
                <th>Código</th>
                <th>Nome Cliente</th>
                <th>Objetivo</th>
                <th>Data Inicio</th>
                <th>Data Fim</th>
                <th></th>
                <th></th>
            </thead>
            <tbody>
                <?php
                        $sql = "SELECT t.cdTreino, c.nmCliente, o.nmObjetivo, DATE_FORMAT(t.dataInicio, '%d/%m/%Y') as 'dtInicio',  DATE_FORMAT(t.dataFim, '%d/%m/%Y') as 'dtFim'  FROM `tbtipotreino` t inner join tbcliente c on c.cdCliente = t.cdCliente inner join tbobjetivo o on o.cdObjetivo = t.cdObjetivo";
                        $data = "";
                        $stmt = $conexao->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                        $stmt->execute();

                    $recordSet =$stmt->fetchAll();
                    if(!empty($recordSet)){
                        foreach($recordSet as $row){
                            echo "<tr>  
                                    <td>{$row["cdTreino"]}</td>
                                    <td>{$row["nmCliente"]}</td>
                                    <td>{$row["nmObjetivo"]}</td>
                                    <td>{$row["dtInicio"]}</td>  
                                    <td>{$row["dtFim"]}</td>
                                    <td><a href='index.php?tela=treino&acao=alt&id={$row["cdTreino"]}'>Editar</a></td>
                                    <td><a href='index.php?tela=treino&acao=exc&id={$row["cdTreino"]}'>Excluir</a></td>
                                  </tr> 
                            ";
                        }
                    }
                ?>
            </tbody>
        </table> </div>  </div>


 