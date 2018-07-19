<?php

function uploadBannerMinicurso($input) {
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
        return array("nome" => $input, "texto" => "", "valido" => "1");
    }
}

function validaAtributoMinicurso($nome) {
    if (isset($_POST[$nome])) {
        $especs = array(";", "\"", "\'");
        $texto = $_POST[$nome];
        $arr = array("nome" => $nome, "texto" => $texto, "valido" => 1);
        $texto2 = str_replace($especs, "", $texto);
        if ($texto != $texto2 || $texto === "") {
            $arr["valido"] = 0;
            $arr["erro"] = "O campo $nome é inválido.";
        }
        if ($nome === "vagas") {
            if ($texto <= "0") {
                $arr["valido"] = 0;
                $arr["erro"] = "O campo $nome é inválido.";
            }
        }
        return $arr;
    } else {
        $arr = array("nome" => $nome, "texto" => "", "valido" => 0);
        return $arr;
    }
}

function validarMinicurso() {
    $arr = array();
    $arr[0] = validaAtributoMinicurso("nome");
    $arr[1] = validaAtributoMinicurso("professor");
    $arr[2] = validaAtributoMinicurso("descricao");
    $arr[3] = validaAtributoMinicurso("vagas");
    $arr[4] = validaPOST("vagas");
    if ($arr[4]["texto"] <= "0") {
        $arr[4]["valido"] = 0;
    }
    $arr[5] = validaAtributoMinicurso("cargahoraria");
    if ($arr[5]["texto"] <= "0") {
        $arr[5]["valido"] = 0;
    }
    $arr[6] = uploadBannerMinicurso("file");
    $horarios = array();
    $valido = 1;
    if (isset($_POST["dia"]) && isset($_POST["hi"]) && isset($_POST["hf"])) {
        for ($i = 0; $i < count($_POST["dia"]); $i++) {
            if (isset($_POST["dia"][$i]) && isset($_POST["hi"][$i]) && isset($_POST["hf"][$i])) {
                if (strtotime($_POST["dia"][$i]) > strtotime(Date("Y-m-d"))) {
                    $horarios[$i] = array("dia" => $_POST["dia"][$i], "hi" => $_POST["hi"][$i], "hf" => $_POST["hf"][$i]);
                } else {
                    $erro = "Tem alguma data do horário marcado para antes do dia atual.";
                    $valido = 0;
                }
            } else {
                $erro = "Tem horário com campo em branco.";
                $valido = 0;
            }
        }
        $arr[7] = array("nome" => "horario", "texto" => $horarios, "valido" => $valido, "erro" => $erro);
    } else {
        $arr[7] = array("nome" => "horario", "texto" => "", "valido" => 0);
    }
    return $arr;
}

function createMinicurso($con, $nomeminicurso, $descricao, $professor, $vagas, $cargahoraria, $banner, $horario, $matricula) {
    $a = Date("Y-m-d H:s");
    $sql = "insert into tb_Minicurso(min_nome, min_professor, min_descricao, min_cargahoraria, min_banner, min_vagas, alu_organizacao, min_autorizacao, min_regdatatime)"
            . "values ('$nomeminicurso', '$professor', '$descricao', '$cargahoraria', '$banner', " . ($vagas) . ", '$matricula', 0, '$a');";
    $r = mysqli_query($con, $sql);
    if ($r) {
        $sqls = "Select min_id from tb_Minicurso where min_regdatatime='$a' order by min_regdatatime desc limit 1";
        $r1 = mysqli_query($con, $sqls);
        $id = "";
        if ($r1) {
            $id = mysqli_fetch_array($r1);
            $id = $id["min_id"];
            $sqld = "insert into tb_Min_Horario(min_id, mih_dia, mih_horai, mih_horaf) values ";
            for ($i = 0; $i < count($horario); $i++) {
                $sqld .= "($id,'" . $horario[$i]["dia"] . "','" . $horario[$i]["hi"] . "','" . $horario[$i]["hf"] . "'),";
            }
            $sqld = substr($sqld, 0, strlen($sqld) - 1);
            $r2 = mysqli_query($con, $sqld);
            if ($r2) {
                return array("erro" => "", "houveErro" => 0);
            } else {
                return array("erro" => "Houve um erro ao registrar o horário do Minicurso, tente novamente", "houveErro" => 1);
            }
        } else {
            return array("erro" => "Houve um erro ao registrar o horário do Minicurso, tente novamente", "houveErro" => 1);
        }
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao cadastrar a Minicurso, tente novamente", "houveErro" => 1);
    }
}

function removeMinicurso($id) {
    $sql = "delete * from tb_Minicurso Where min_id= '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao remover a Minicurso, tente novamente", "houveErro" => 1);
    }
}

function alterarMinicurso($coluna, $value, $id) {
    $sql = "update tb_Minicurso set $coluna = '$value' Where min_id = $id";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao alterar a Minicurso, tente novamente", "houveErro" => 1);
    }
}

function autorizarMinicurso($con, $id, $autorizacao) {
    $sql = "update tb_Minicurso set min_autorizacao = 1, alu_autorizacao = '$autorizacao', alu_datetime_autorizacao = '" . Date("Y-m-d H:s") . "' Where min_id = $id";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao autorizar o Minicurso, tente novamente", "houveErro" => 1);
    }
}

function recusarMinicurso($con, $id, $autorizacao) {
    $sql = "update tb_Minicurso set min_autorizacao = 2, alu_autorizacao = '$autorizacao', alu_datetime_autorizacao = '" . Date("Y-m-d H:s") . "' Where min_id = $id";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao autorizar o Minicurso, tente novamente", "houveErro" => 1);
    }
}

function participarMinicurso($con, $minid, $matricula) {
    $sqlvaga = "select (p.vagas-count(t.min_id)) as vagas, min_nome from tb_Mcu_Alu_Participar as t Inner join tb_Minicurso as p on "
            . "(t.min_id = p.min_id) where min_id='$minid'";
    $r = mysqli_query($con, $sqlvaga);
    if ($r) {
        $r = mysqli_fetch_array($r);
        if ($r["vagas"] > 0) {
            $data = Date('Y-m-d H:i');
            $ident = md5($matricula . "" . $data);
            $sql = "insert into tb_Min_Alu_Participar(min_id, alu_matricula, map_confirmacao, map_presenca, map_cod) values('$minid','$matricula', 0, 0, '$ident')";
            $r = mysqli_query($con, $sql);
            if ($r) {
                return array("erro" => "", "houveErro" => 0);
            } else {
                return array("erro" => "Houve um erro ao registrar sua participação no Minicurso, tente novamente", "houveErro" => 1);
            }
        } else {
            return array("erro" => "Não há vagas para a Minicurso " . $r["min_nome"] . ", tente novamente", "houveErro" => 1);
        }
    } else {
        return array("erro" => "Houve um erro ao verificar as vagas no Minicurso, tente novamente", "houveErro" => 1);
    }
}

function confirmarMinicurso($minid, $matricula) {
    $sql = "update tb_Mcu_Alu_Participar set confirmacao=1 where min_id='$minid' AND alu_matricula='$matricula'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao registrar sua participação na Minicurso, tente novamente", "houveErro" => 1);
    }
}

function darPresenca($minid, $matricula, $data, $presente) {
    $sql = "insert into tb_Mcu_Alu_Aula(min_id, alu_matricula, maa_data, maa_presenca) "
            . "values ('$minid', '$matricula', '$data','$presente')";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0);
    } else {
        return array("erro" => "Houve um erro ao registrar a presença deste aluno em $data neste Minicurso, tente novamente", "houveErro" => 1);
    }
}

function listarMinicursosDisponiveis() {
    $sql = "Select * from tb_Minicurso where min_autorizacao = 1";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos disponíveis, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function listarMinicursosParaAutorizar() {
    $sql = "Select * from tb_Minicurso where min_autorizacao = 0";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos que não foram autorizados, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function listarMinicursosQueMinistro($matricula) {
    $sql = "Select * from tb_Minicurso where alu_matricula = '$matricula'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos que você ministrou, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function listarMinicursosParticipando($matricula) {
    $sql = "Select min_nome from tb_Minicurso as m inner join tb_Mcu_Alu_Participar as a on (m.min_id = a.min_id) where a.alu_matricula = '$matricula'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos que você está participando, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function listarMinicursosQueParticipou($matricula) {
    $sql = "Select m.min_nome, (count(a.min_id)*50) as horasaula from tb_Minicurso as m inner join tb_Mcu_Alu_Participar as p on (m.min_id = p.min_id) "
            . "inner join tb_Mcu_Alu_Aula as a on (m.min_id = a.min_id) where a.alu_matricula = '$matricula' "
            . "Group By m.min_nome having horasaula >= m.horas";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos que você participou, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function detalhaMinicurso($minid, $matricula) {
    $sql = "Select * as horasaula from tb_Minicurso as m inner join tb_Mcu_Alu_Participar as p on (m.min_id = p.min_id) "
            . "inner join tb_Mcu_Alu_Aula as a on (m.min_id = a.min_id) where a.alu_matricula = '$matricula' AND "
            . "p.alu_matricula = '$matricula' AND m.min_id = '$minid'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos que você participou, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function quantidadeDeInscritos($con, $id) {
    $sql = "Select count(min_id) as qntd from tb_Min_Alu_Participar where min_id = '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos disponíveis, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function estouInscritoNesseMinicurso($con, $id, $matricula) {
    $sql = "Select * from tb_Min_Alu_Participar where min_id = '$id' and alu_matricula = '$matricula'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "lista" => $r);
    } else {
        return array("erro" => "Houve um erro ao listar os Minicursos disponíveis, tente novamente", "houveErro" => 1, "lista" => null);
    }
}

function getMinicurso($con, $id) {
    $sql = "select * from tb_Minicurso Where min_id= '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "return" => $r);
    } else {
        return array("erro" => "Houve um erro ao remover a Palestra, tente novamente", "houveErro" => 1);
    }
}

function podeLancarPresencaMinicurso($con, $id, $matricula) {
    $sql = ""
            . "Select m.min_autorizacao, h.mih_lancou_presenca"
            . "from tb_Minicurso as m inner join tb_Min_Horario as h on (m.min_id = h.min_id) "
            . "where NOW() >= h.mih_dia AND m.min_autorizacao = 1 AND m.alu_organizacao = " . $matricula . " AND m.min_id = " . $id." "
            . "order by h.mih_dia Asc LIMIT 1";
    $qry1 = mysqli_query($con, $sql);
    if ($qry1) {
        if (mysqli_num_rows($qry1) <= 0) {
            return array("erro" => "Você não tem autorização ou o minicurso ainda não começou para poder lançar presença nesta Palestra.", "houveErro" => 1);
        } else {
            $r = mysqli_fetch_array($qry1);
            if ($r["min_autorizacao"] === "0") {
                return array("erro" => "O minicurso não tem autorizacao.", "houveErro" => 1);
            } else {
                return array("erro" => "", "houveErro" => 0);
            }
        }
    } else {
        return array("erro" => "Houve um erro ao verificar o status do minicurso.", "houveErro" => 1);
    }
}
