<?php

function uploadBanner($input) {
    // Prepara a variável do arquivo
    $arquivo = isset($_FILES["$input"]) ? $_FILES["$input"] : FALSE;
    // Formulário postado... executa as ações
    if ($arquivo) {
        if ($arquivo["name"] == "") {
            return array("nome" => $input, "texto" => "", "valido" => "0");
        } else {
            // Pega extensão do arquivo
            $ext = explode(".", $arquivo["name"]);
            $qtd = count($ext);
            // Gera um nome único para a imagem
            $qtd--;
            $nomearquivo = md5(uniqid(time())) . "." . $ext[$qtd];
            $servidor = 'da-eng.com';
            // Caminho de onde a imagem ficará
            $caminho_absoluto = '/banner/';
            $arquivo = $_FILES['arquivo'];

            $con_id = ftp_connect($servidor) or die('Não conectou em: ' . $servidor);
            ftp_login($con_id, 'u629824613.masterdaecom', 'daecom20');

            // Faz o upload da imagem
            ftp_put($con_id, $caminho_absoluto . $arquivo['name'], $nomearquivo, FTP_BINARY);
            return array("nome" => $input, "texto" => $caminho_absoluto . "" . $nomearquivo, "valido" => "1");
        }
    } else {
        return array("nome" => $input, "texto" => "", "valido" => "0");
    }
}

function validarPalestra() {
    $arr = array();
    $arr[0] = validaPOST("nome");
    $arr[1] = validaPOST("descricao");
    $arr[2] = validaPOST("palestrante");
    $arr[3] = validaPOST("data");
    if(strtotime(Date("Y-m-d"))>strtotime($arr[3]["texto"])){
        $arr[3]["valido"] = 0;
    }
    $arr[4] = validaPOST("hora");
    $arr[3] = validaPOST("data");
    if(strtotime(Date("H:i"))>strtotime($arr[3]["texto"])){
        $arr[3]["valido"] = 0;
    }
    $arr[5] = validaPOST("vagas");
    if($arr[5]["texto"]<="0"){
        $arr[5]["valido"] = 0;
    }
    $arr[6] = validaPOST("cargahoraria");
    if($arr[6]["texto"]<="0"){
        $arr[6]["valido"] = 0;
    }
    $arr[7] = uploadBanner("file");
    return $arr;
}

function createPalestra($con, $nomepalestra, $descricao, $palestrante, $data, $hora, $vagas, $cargahoraria, $banner, $matricula) {
    $sql = "insert into tb_Palestra(pal_nome, pal_palestrante, pal_descricao, pal_data, pal_hora, pal_cargahoraria, pal_banner, pal_vagas, alu_organizacao, pal_autorizacao)"
            . "values ('$nomepalestra', '$palestrante', '$descricao', '$data','$hora', '$cargahoraria', '$banner', " . ($vagas) . ", '$matricula', 0)";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao cadastrar a Palestra, verifique os dados e tente novamente", "houveErro" => 1);
    }
}

function getPalestra($con, $id) {
    $sql = "select * from tb_Palestra Where pal_id= '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "return" => $r);
    } else {
        return array("erro" => "Houve um erro ao remover a Palestra, tente novamente", "houveErro" => 1);
    }
}

function removePalestra($id) {
    $sql = "delete * from tb_Palestra Where pal_id= '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao remover a Palestra, tente novamente", "houveErro" => 1);
    }
}

function alterarPalestra($con, $coluna, $value, $id) {
    $sql = "update tb_Palestra set $coluna = '$value' Where pal_id = $id";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao alterar a Palestra, tente novamente", "houveErro" => 1);
    }
}

function autorizarPalestra($con, $id, $matricula) {
    $sql = "update tb_Palestra set pal_autorizacao = 1, alu_autorizacao = '$matricula', alu_datetime_autorizacao = NOW() Where pal_id = $id";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao remover a Palestra, tente novamente", "houveErro" => 1);
    }
}

function recusarPalestra($con, $id, $matricula) {
    $sql = "update tb_Palestra set pal_autorizacao = 0, alu_autorizacao = '$matricula', alu_datetime_autorizacao = NOW() Where pal_id = $id";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao remover a Palestra, tente novamente", "houveErro" => 1);
    }
}

function podeLancarPresencaPalestra($con, $id, $matricula) {
    $sql = ""
            . "Select pal_autorizacao, pal_data, pal_lancou_presenca"
            . "from tb_Palestra"
            . "where pal_autorizacao = 1 AND alu_organizacao = " . $matricula . " AND pal_id = " . $id;
    $qry1 = mysqli_query($con, $sql);
    if ($qry1) {
        if (mysqli_num_rows($qry1) <= 0) {
            return array("erro" => "Você não tem autorização para lançar presença nesta Palestra.", "houveErro" => 1);
        } else {
            $r = mysqli_fetch_array($qry1);
            if ($r["pal_autorizacao"] === "0") {
                return array("erro" => "A palestra não tem autorizacao.", "houveErro" => 1);
            } else if (strtotime($r["pal_data"]) > strtotime(Date("Y-m-d"))) {
                return array("erro" => "A palestra ainda não ocorreu.", "houveErro" => 1);
            } else if ($r["pal_lancou_presenca"] === "1") {
                return array("erro" => "Já foi lançada presença nesta Palestra.", "houveErro" => 1);
            } else {
                return array("erro" => "", "houveErro" => 0);
            }
        }
    } else {
        return array("erro" => "Houve um erro ao verificar o estado da palestra.", "houveErro" => 1);
    }
}

function participarPalestra($con, $palid, $matricula) {
    $sqlvaga = "select (p.pal_vagas-count(t.pal_id)) as vagas, pal_nome from tb_Pal_Alu_Participar as t Inner join tb_Palestra as p on (t.pal_id = p.pal_id) where p.pal_id='$palid'";
    $r = mysqli_query($con, $sqlvaga);
    if ($r) {
        $r = mysqli_fetch_array($r);
        if ($r["vagas"] > 0) {
            $data = Date('Y-m-d H:i');
            $ident = md5($matricula . "" . $data);
            $sql = "insert into tb_Pal_Alu_Participar(pal_id, alu_matricula, pap_cod, pap_confirmacao, pap_presenca) values('$palid','$matricula','$ident',0,0)";
            $r = mysqli_query($con, $sql);
            if ($r) {
                return array("erro" => "", "houveErro" => 0);
            } else {
//                return array("erro" => "Houve um erro ao registrar sua participação na Palestra, tente novamente", "houveErro" => 1);
                return array("erro" => "$sql", "houveErro" => 1);
            }
        } else {
            return array("erro" => "Não há vagas para a Palestra" . $r["pal_nome"] . " , tente novamente", "houveErro" => 1);
        }
    } else {
        return array("erro" => "$sqlvaga", "houveErro" => 1);
    }
}

function sairPalestra($con, $palid, $matricula) {
    $sql = "delete from tb_Pal_Alu_Participar where pal_id='$palid' AND alu_matricula='$matricula'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao remover sua inscrição nesta Palestra, tente novamente", "houveErro" => 1);
    }
}

function isPresente($con, $id, $mat) {
    $sql = "Select presenca from tb_Pal_Alu_Participar where pal_id = '$id' AND mat = '$mat'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar as Palestras que ainda não foram autorizadas, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function quantidadeDeInscritos($con, $id) {
    $sql = "Select count(pal_id) as qntd from tb_Pal_Alu_Participar where pal_id = '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos disponíveis, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function estouInscritoNessaPalestra($con, $id, $matricula) {
    $sql = "Select * from tb_Pal_Alu_Participar where pal_id = '$id' and alu_matricula = '$matricula'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos disponíveis, tente novamente", "houveErro" => 1, "lista" => null);
    }
}
