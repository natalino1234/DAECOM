<script src="js/cliente.js"></script>
<?php
    if (!isset($_REQUEST["btvar"])) $_REQUEST["btvar"] = "btnSalvar";
    if (!isset($_REQUEST["btlabel"])) $_REQUEST["btlabel"] = "Salvar";
    if(!isset($_REQUEST["cdCliente"])) $_REQUEST["cdCliente"] = "";
    if(!isset($_REQUEST["nmCliente"])) $_REQUEST["nmCliente"] = "";
    if(!isset($_REQUEST["cpfCliente"])) $_REQUEST["cpfCliente"] = "";
    if(!isset($_REQUEST["rgCliente"])) $_REQUEST["rgCliente"] = "";
    if(!isset($_REQUEST["mensCliente"])) $_REQUEST["mensCliente"] = "";
    if(!isset($_REQUEST["emailCliente"])) $_REQUEST["emailCliente"] = "";
    if(!isset($_REQUEST["senhaCliente"])) $_REQUEST["senhaCliente"] = "";
    if(!isset($_REQUEST["tipoCliente"])) $_REQUEST["tipoCliente"] = "";
    

    if(isset($_REQUEST["acao"])){
        $id = $_REQUEST["id"];
        if($_REQUEST["acao"] == "alt"){
            $_REQUEST["btvar"] = "btnAlterar";
            $_REQUEST["btlabel"] = "Alterar";    
        }else{
            $_REQUEST["btvar"] = "btnExc";
            $_REQUEST["btlabel"] = "Excluir";
        }
        $sql = "SELECT * FROM tbcliente where cdCliente = :cdcliente";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
                "cdcliente" => $id
        ]);
        $recordSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $_REQUEST["cdCliente"] = $id;
        $_REQUEST["nmCliente"] = $recordSet["nmCliente"];
        $_REQUEST["cpfCliente"] = $recordSet["CPF"];
        $_REQUEST["rgCliente"] = $recordSet["RG"];
        $_REQUEST["mensCliente"] = $recordSet["valorMensalidade"] * 100.0;
        $_REQUEST["emailCliente"] = $recordSet["email"];
        $_REQUEST["senhaCliente"] = $recordSet["senha"];
        $_REQUEST["tipoCliente"]= $recordSet["tipo"];

    }

    if(isset($_REQUEST["btnAlterar"])){
         if (!(empty($_REQUEST["nmCliente"]) || empty($_REQUEST["cpfCliente"])
                || empty($_REQUEST["rgCliente"]) || empty($_REQUEST["emailCliente"])
                || empty($_REQUEST["senhaCliente"]) || empty($_REQUEST["mensCliente"])
                || empty($_REQUEST["tipoCliente"]))
            ) {
                if(validaCPF($_POST["cpfCliente"])){
            $id = $_POST["cdCliente"];         
            $nome = $_POST["nmCliente"];
            $cpf = $_POST["cpfCliente"];
            $rg = $_POST["rgCliente"];
            $email = $_POST["emailCliente"];
            $senha = $_POST["senhaCliente"];
            if(!(preg_match('/^[a-f0-9]{32}$/', $senha))){
                $senha = md5($senha);
            }
            $mens =  str_replace(",",".",str_replace(".","", $_POST["mensCliente"])) *1.0 ;
            $tipo = $_POST["tipoCliente"];
            try{
                $sql= "UPDATE `tbcliente`  SET `nmCliente` =:nmCliente, `CPF` = :cpf, `RG`= :rg, `senha`= :rg, `email`= :email,
                 `tipo` = :tipo, `valorMensalidade` =:valorMensalidade WHERE cdCliente = :cdCliente";
                $stmt = $conexao->prepare($sql);
                $stmt->execute([
                    "nmCliente" => $nome,
                    "cpf" => $cpf,
                    "rg" => $rg,
                    "email" => $email,
                    "senha" => $senha,
                    "tipo" => $tipo,
                    "valorMensalidade" => $mens,
                    "cdCliente" => $id
                ]);
                $_REQUEST["btnlabel"] = "Salvar";
                $_REQUEST["btnvar"] = "btnSalvar";
                $_REQUEST["cdCliente"] = "";
                $_REQUEST["nmCliente"] = "";
                $_REQUEST["cpfCliente"] = "";
                $_REQUEST["rgCliente"] = "";
                $_REQUEST["mensCliente"] = "";
                $_REQUEST["emailCliente"] = "";
                $_REQUEST["senhaCliente"] = "";
                $_REQUEST["tipoCliente"]= "";
                
                echo "<script>Materialize.toast('Alterado com sucesso!', 4000)</script>";
            }catch(PDOException $exception){
                echo "<script>Materialize.toast('Erro ao inserir ". $exception->getMessage()."', 4000)</script>";
            }
            }else{
                
            echo "<script>Materialize.toast('CPF Inválido', 4000)</script>";
            }
        }else{
            echo "<script>Materialize.toast('Insira todos os dados antes de continuar', 4000)</script>";
        }
    }
    if(isset($_REQUEST["btnExc"])){
        $id = $_REQUEST["cdCliente"];
        $sql = "DELETE FROM tbusuario where email = (select email from tbcliente where cdCliente = :id limit 1);";
        $sql = $sql . "DELETE FROM tbcliente where cdCliente = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            "id" => $id
        ]);
        $_REQUEST["btnlabel"] = "Salvar";
        $_REQUEST["btnvar"] = "btnSalvar";
        $_REQUEST["cdCliente"] = "";
        $_REQUEST["nmCliente"] = "";
        $_REQUEST["cpfCliente"] = "";
        $_REQUEST["rgCliente"] = "";
        $_REQUEST["mensCliente"] = "";
        $_REQUEST["emailCliente"] = "";
        $_REQUEST["senhaCliente"] = "";
        $_REQUEST["tipoCliente"]= "";
        echo "<script>Materialize.toast('Excluído com sucesso!', 4000)</script>";
    }
    if (isset($_REQUEST["btnSalvar"])) {
            if (!(empty($_REQUEST["nmCliente"]) || empty($_REQUEST["cpfCliente"])
                || empty($_REQUEST["rgCliente"]) || empty($_REQUEST["emailCliente"])
                || empty($_REQUEST["senhaCliente"]) || empty($_REQUEST["mensCliente"])
                || empty($_REQUEST["tipoCliente"]))
            ) {
                if(validaCPF($_POST["cpfCliente"])){
            $nome = $_POST["nmCliente"];
            $cpf = $_POST["cpfCliente"];
            $rg = $_POST["rgCliente"];
            $email = $_POST["emailCliente"];
            $senha = md5($_POST["senhaCliente"]);
            $mens =  str_replace(",",".",str_replace(".","", $_POST["mensCliente"])) *1.0 ;
            $tipo = $_POST["tipoCliente"];
            try{
                $sql= "INSERT INTO `tbcliente` (`nmCliente`, `CPF`, `RG`, `senha`, `email`, `tipo`, `valorMensalidade`)";
                $sql = $sql . "VALUES (:nmCliente, :cpf, :rg, :senha, :email,:tipo, :valorMensalidade)";
                $stmt = $conexao->prepare($sql);
                $stmt->execute([
                    "nmCliente" => $nome,
                    "cpf" => $cpf,
                    "rg" => $rg,
                    "email" => $email,
                    "senha" => $senha,
                    "tipo" => $tipo,
                    "valorMensalidade" => $mens
                ]);
                $_REQUEST["btnlabel"] = "Salvar";
                $_REQUEST["btnvar"] = "btnSalvar";
                $_REQUEST["cdCliente"] = "";
                $_REQUEST["nmCliente"] = "";
                $_REQUEST["cpfCliente"] = "";
                $_REQUEST["rgCliente"] = "";
                $_REQUEST["mensCliente"] = "";
                $_REQUEST["emailCliente"] = "";
                $_REQUEST["senhaCliente"] = "";
                $_REQUEST["tipoCliente"]= "";
            }catch(PDOException $exception){
                if($exception->getCode() == 23000){
                    echo "<script>Materialize.toast('Email já em uso', 4000)</script>";
                }else{
                    echo "<pre>";
                    print_r($exception->getGetMessage());
                    echo "</pre>";
                }
            }
        }else{
            echo "<script>Materialize.toast('CPF Inválido', 4000)</script>";
        }
        }else{
            echo "<script>Materialize.toast('Insira todos os dados antes de continuar', 4000)</script>";
        }
    }
?>


<div class="section no-pad-bot" id="index-banner">
    <div class="container screen-content">
      <h1 class="header center pink-text">Cliente</h1>
	  
	  <div class="row-top right">
      </div>
      <div class="row">
          <form id="formCrud" action="index.php?tela=cliente" method="POST" class="col s12">
              <div class="row">
                  <div class="input-field col s6"><input type="text" name="nmCliente" value="<?php echo $_REQUEST["nmCliente"] ;?>"><label for="">Nome</label></div>
                  <div class="input-field col s6"><input type="text" name="cpfCliente" class="CPF" value="<?php echo $_REQUEST["cpfCliente"] ;?>"><label for="">CPF</label></div>
              </div>
              <div class="row">
                  <div class="input-field col s6"><input type="text" name="rgCliente" value="<?php echo $_REQUEST["rgCliente"] ;?>"><label for="">RG</label></div>
                  <div class="input-field col s6"><input type="text" name="mensCliente" class="money" value="<?php echo $_REQUEST["mensCliente"] ;?>"><label for="">Valor da Mensalidade</label></div>
              </div>
              <div class="row">
                  <div class="input-field col s6"><input type="email" name="emailCliente" value="<?php echo $_REQUEST["emailCliente"] ;?>"><label for="">Email</label></div>
                  <div class="input-field col s6"><input type="password" name="senhaCliente" value="<?php echo $_REQUEST["senhaCliente"] ;?>"><label for="">Senha</label></div>
              </div>
              <div class="row">
                  <label for=""><h6>Tipo</h6></label>   
                  <p><input id="clienteTipo" type="radio" value ="2" <?php echo ($_REQUEST["tipoCliente"] == "" || $_REQUEST["tipoCliente"] == "2")?"checked":"";  ?> name="tipoCliente"><label for="clienteTipo">Cliente</label></p>
                  <p><input id="adminTipo" type="radio" value ="1" <?php echo ($_REQUEST["tipoCliente"] == "1")?"checked":"";  ?> name="tipoCliente"><label for="adminTipo">Administrador</label></p>
              </div>
               <div class='center-align'>
    <input type='text' hidden name="<?php echo $_REQUEST["btvar"]; ?>"  value="<?php echo $_REQUEST["btvar"]; ?>">
    <?php 
        if(isset($_REQUEST["acao"])){
            echo "<a class='btn waves-effect waves-light btn-submit-treino' href='index.php?tela=cliente&c=1'>Cancelar</a>";
        }
    ?>
    <button class="btn waves-effect waves-light btn-submit-treino" name="<?php echo $_REQUEST["btvar"]; ?>" id="<?php echo $_REQUEST["btvar"]; ?>" value=""><?php echo $_REQUEST["btlabel"]; ?></button>
    </div>   <input type="text" hidden name="cdCliente" value="<?php echo $_REQUEST["cdCliente"]; ?>">

          </form>
    </div>
     <div class="row">
      <form action = "index.php?tela=cliente" method = "post">
        <div class="input-field input-custom">
          <input id="search" type="search" name = "pesquisa"  placeholder = "Nome ou Código">
        </div>
      </form>
    </div>

      <div>
        <table class="highlight bordered responsive-table">
            <thead>
                <th>Código</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Tipo</th>
                <th></th>
                <th></th>
            </thead>
            <tbody>
                <?php
                        $sql = "SELECT * FROM tbcliente";
                        $data = "";
                        $stmt = $conexao->prepare($sql);
                        if(isset($_REQUEST["pesquisa"])){
                            $data = $_REQUEST["pesquisa"];
                            $data = "%".$data."%";
                            $sql = $sql . " where nmCliente like :data1 OR cdCliente like :data1";
                        }
                        $stmt = $conexao->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                        $stmt->execute([
                            "data1"=> $data
                        ]);

                    $recordSet =$stmt->fetchAll();
                    if(!empty($recordSet)){
                        foreach($recordSet as $row){
                            echo "<tr>  
                                    <td>{$row["cdCliente"]}</td>
                                    <td>{$row["nmCliente"]}</td>
                                    <td>{$row["CPF"]}</td>
                                    <td>{$row["email"]}</td>  
                                    <td>{$row["tipo"]}</td>
                                    <td><a href='index.php?tela=cliente&acao=alt&id={$row["cdCliente"]}'>Editar</a></td>
                                    <td><a href='index.php?tela=cliente&acao=exc&id={$row["cdCliente"]}'>Excluir</a></td>
                                  </tr> 
                            ";
                        }
                    }
                ?>
            </tbody>
        </table>
      </div>
    </div>
</div>