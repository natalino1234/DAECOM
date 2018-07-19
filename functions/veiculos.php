<?php

function validaAtributoVeiculo($con, $nome) {
    if (isset($_POST[$nome])) {
        $especs = array(";", "\"", "\'");
        $texto = $_POST[$nome];
        $arr = array("nome" => $nome, "texto" => $texto, "valido" => 1);
        $texto2 = str_replace($especs, "", $texto);
        if ($texto === "") {
            $arr["valido"] = 0;
            $arr["erro"] = "O campo " . ucfirst($nome) . " está vazio.";
        } else
        if ($texto != $texto2) {
            $arr["valido"] = 0;
            $arr["erro"] = "O campo " . ucfirst($nome) . " é inválido.";
        } else
        if ($nome === "placa") {
            $regex = "/([A-Za-z]{3})-([0-9]{4})/";
            if (!preg_match($regex, $texto)) {
                $arr["valido"] = 0;
                $arr["erro"] = "A Placa informada não segue o padrão AAA-9999.";
            } else {
                $sql = "Select vei_placa from tb_Veiculo where vei_placa='$texto'";
                $r = mysqli_query($con, $sql);
                if (mysqli_num_rows($r) > 0) {
                    $arr["valido"] = 0;
                    $arr["erro"] = "Este Veículo já está cadastrado.";
                }
            }
        } else if ($texto === "Selecione uma Marca") {
            $arr["valido"] = 0;
            $arr["erro"] = "Selecione uma Marca.";
        } else if ($texto === "Selecione um Modelo") {
            $arr["valido"] = 0;
            $arr["erro"] = "Selecione um Modelo.";
        }
        return $arr;
    } else {
        $arr = array("nome" => $nome, "texto" => "", "valido" => 0, "erro" => "O campo " . ucfirst($nome) . " não possui um valor válido.");
        return $arr;
    }
}

function validarVeiculo($con) {
    $arr = array();
    $arr[0] = validaAtributoVeiculo($con, "modelo");
    $arr[1] = validaAtributoVeiculo($con, "marca");
    $arr[2] = validaAtributoVeiculo($con, "placa");
    $arr[3] = validaAtributoVeiculo($con, "cor");
    $arr[4] = validaAtributoVeiculo($con, "tipo");
    $arr[5] = validaAtributoVeiculo($con, "proprio");
    return $arr;
}

function createVeiculo($con, $modelo, $marca, $placa, $tipo, $cor, $proprio, $matricula) {
    $sql = "select vei_placa from tb_Veiculo where alu_matricula = '$matricula';";
    $r = mysqli_query($con, $sql);
    if ($r) {
        if (mysqli_num_rows($r) < 3) {
            $sql = "insert into tb_Veiculo( vei_modelo, vei_marca, vei_placa, vei_tipo, vei_cor,  alu_matricula, vei_proprio ) "
                    . "values ( '$modelo' , '".ucwords($marca)."' , '$placa' , '$cor' , '$tipo' , '$matricula', '$proprio' );";
            if($_SESSION["tipo_usuario"] === "1"){
            $sql = "insert into tb_Veiculo( vei_modelo, vei_marca, vei_placa, vei_tipo, vei_cor,  alu_matricula, vei_proprio, vei_adesivo) "
                    . "values ( '$modelo' , '".ucwords($marca)."' , '$placa' , '$cor' , '$tipo' , '$matricula', '$proprio', 1);";
            }
            $r = mysqli_query($con, $sql);
            if ($r) {
                return array("nome" => "Cadastro", "erro" => "", "houveErro" => 0);
            } else {
                return array("nome" => "Cadastro", "erro" => "Houve um erro ao cadastrar o Veículo, tente novamente", "houveErro" => 1);
            }
        } else {
            return array("nome" => "Cadastro", "erro" => "Você já tem o limite de 3 veículos cadastrados.", "houveErro" => 1);
        }
    } else {
        return array("nome" => "Cadastro", "erro" => "Houve um erro ao cadastrar o Veículo, tente novamente", "houveErro" => 1);
    }
}

function removeVeiculo($con, $placa) {
    $sql = "delete from tb_Veiculo Where vei_placa= '$placa'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao remover o Veiculo, tente novamente", "houveErro" => 1);
    }
}

function renovarVeiculo($con, $id) {
    $ano = Date("Y")+1;
    $sql = "update tb_Veiculo set vei_inscrito = '$ano-03-30' Where vei_placa = '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao alterar o Veiculo, tente novamente", "houveErro" => 1);
    }
}

function alterarVeiculo($con, $coluna, $value, $id) {
    $sql = "update tb_Veiculo set $coluna = '$value' Where vei_placa = '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao alterar o Veiculo, tente novamente", "houveErro" => 1);
    }
}
