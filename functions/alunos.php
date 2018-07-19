<?php

function validaAluno($con) {
    $arr = array();
    $arr[0] = validaAtributoAluno($con, "matricula");
    $arr[1] = validaAtributoAluno($con, "nome");
    $arr[2] = validaAtributoAluno($con, "dataNasc");
    $arr[3] = validaAtributoAluno($con, "senha");
    $arr[4] = validaAtributoAluno($con, "confsenha");
    $arr[5] = validaAtributoAluno($con, "email");
    $arr[6] = validaAtributoAluno($con, "cpf");
    $arr[7] = validaAtributoAluno($con, "telefone");
    $arr[8] = validaAtributoAluno($con, "celular");
    $arr[9] = validaAtributoAluno($con, "cep");
    $arr[10] = validaAtributoAluno($con, "estado");
    $arr[11] = validaAtributoAluno($con, "cidade");
    $arr[12] = validaAtributoAluno($con, "bairro");
    $arr[13] = validaAtributoAluno($con, "logradouro");
    $arr[14] = validaAtributoAluno($con, "complemento");
    $arr[15] = validaAtributoAluno($con, "termos");
    $arr[16] = validaAtributoAluno($con, "cnh");
    $arr[17] = validaAtributoAluno($con, "emcnh");
    return $arr;
}

function enviarEmailConfCadastro($nome, $codver, $destino, $mat) {
    $titulo = "Confirmação de Cadastro";
    $msg = "<html><body><h2>Olá, $nome</h2>"
            . "<h3>Este e-mail tem o objetivo de confirmar sua conta, então clique no link abaixo para podermos validar sua conta, e você poderá usar o nosso Sistema sem problemas:</h3><br>"
            . "<h3>Seu código de confirmação é: " . $codver
            . "<a href='http://da-eng.com/ConfirmarCadastro/$codver/$mat'><h3>Confirmar cadastro no Sistema do Diretório Acadêmico</h3></a><br>"
            . "<h3>Caso este e-mail tenha sido enviado a você por engano, desculpe-nos a inconveniência. Apague ou ignore este e-mail.</h3></body></html>";
    $erro = enviarEmail($titulo, $destino, $msg);
    return $erro;
}

function enviarEmailAlterCadastro($nome, $codver, $destino, $mat, $oq) {
    $titulo = "Alteração de Cadastro";
    $msg = "<html><body><h2>Olá, $nome</h2>"
            . "<h3>Este e-mail tem o objetivo de alterar sua conta, então clique no link abaixo e na página preencha o que for necessário para podermos alterar sua conta:</h3><br>"
            . "<h3>Seu código de alteração é: " . $codver
            . "<a href='http://da-eng.com/AlterarCadastro/$oq/$codver/$mat'><h3>Alterar cadastro no Sistema do Diretório Acadêmico</h3></a><br>"
            . "<h3>Caso este e-mail tenha sido enviado a você por engano, desculpe-nos a inconveniência. Apague ou ignore este e-mail.</h3></body></html>";
    $erro = enviarEmail($titulo, $destino, $msg);
    return $erro;
}

function validaMatricula($matricula) {
    if (strlen($matricula) !== 12) {
        return 0;
    }
    $ano = substr($matricula, 0, 4);
    $semestre = substr($matricula, 4, 1);
    $campus = substr($matricula, 5, 1);
    $curso = substr($matricula, 6, 2);
    if ($ano < "2009" || $semestre !== "1" || $campus !== "7" || $curso !== "06") {
        return 0;
    }
    return 1;
}

function getPeriodo($matricula) {
    if (strlen($matricula) !== 12) {
        return 0;
    }
    $ano = substr($matricula, 0, 4);
    $semestre = substr($matricula, 4, 1);
    $anoatual = Date("Y") - $ano;
    $mesatual = Date("m");
    $semestreatual = 1;
    if ($mesatual > "6") {
        $semestreatual = 2;
    }
    $periodo = $anoatual * 2 + $semestreatual - ($semestre - 1);
    if ($periodo > 10) {
        $periodo.="! ";
    }
    return $periodo;
}

function validarCNH($cnh) {
    $Dv = substr($cnh, 8, 1);
    $Dvr = ((2 * substr($cnh, 0, 1)) + (3 * substr($cnh, 1, 1)) + (4 * substr($cnh, 2, 1)) + (5 * substr($cnh, 3, 1)) + (6 * substr($cnh, 4, 1)) + (7 * substr($cnh, 5, 1)) + (8 * substr($cnh, 6, 1)) + (9 * substr($cnh, 7, 1)));
    if ($Dv === $Dvr) {
        RETURN 1;
    } ELSE {
        RETURN 0;
    }
}

function validaAtributoAluno($con, $nome) {
    if (isset($_POST[$nome])) {
        $especs = array(";", "\"", "\'");
        $texto = $_POST[$nome];
        $arr = array("nome" => $nome, "texto" => $texto, "valido" => 1, "erro" => "");
        $texto2 = str_replace($especs, "", $texto);
        if ($texto != $texto2 || $texto === "") {
            $arr["valido"] = 0;
            $arr["erro"] = "O campo '$nome' não é válido.";
            if ($texto === "" && $nome === "cnh") {
                $arr["valido"] = 1;
            }
        } else {
            if ($nome === "cnh") {
                if (strlen($texto) !== "11" && strlen($texto) > 0) {
                    $arr["valido"] = 0;
                    $arr["erro"] = "O campo '$nome' não é válido.";
                }
            }
            if ($nome === "cpf") {
                if (validaCPF($texto)) {
                    $sql = "Select alu_matricula from tb_Aluno where alu_cpf='$texto'";
                    $r = mysqli_query($con, $sql);
                    if (mysqli_num_rows($r) > 0) {
                        $arr["valido"] = 0;
                        $arr["erro"] = "Este CPF já está sendo utilizado.";
                    }
                } else {
                    $arr["erro"] = "Este CPF não é válido.";
                    $arr["valido"] = 0;
                }
            }
            if ($nome === "email") {
                $regex = "/^[A-Za-z0-9\\._-]+@[A-Za-z]+\\.[A-Za-z]+$/";
                if (!preg_match($regex, $texto)) {
                    $arr["erro"] = "Este $nome não é válido.";
                    $arr["valido"] = 0;
                } else {
                    $sql = "Select alu_matricula from tb_Aluno where alu_email='$texto'";
                    $r = mysqli_query($con, $sql);
                    if (mysqli_num_rows($r) > 0) {
                        $arr["erro"] = "Este E-mail já foi cadastrado.";
                        $arr["valido"] = 0;
                    }
                }
            }
            if ($nome === "matricula") {
                if (validaMatricula($texto)) {
                    $sql = "Select alu_matricula from tb_Aluno where alu_matricula='$texto'";
                    $r = mysqli_query($con, $sql);
                    if (mysqli_num_rows($r) > 0) {
                        $arr["erro"] = "Esta matrícula já está cadastrada.";
                        $arr["valido"] = 0;
                    }
                } else {
                    $arr["erro"] = "Esta matrícula não é válida.";
                    $arr["valido"] = 0;
                }
            }
            if ($nome === "telefone" || $nome === "celular") {
                $regex = "/\(\d{2}\)\d{4,5}-\d{4}/";
                if (!preg_match($regex, $texto)) {
                    $arr["erro"] = "Este $nome não é válido.";
                    $arr["valido"] = 0;
                }
            }
            if ($nome === "termos") {
                if ($texto !== "on") {
                    $arr["erro"] = "Você não aceitou os termos de Uso e Serviço.";
                    $arr["valido"] = 0;
                }
            }
        }
        return $arr;
    } else {
        $arr = array("nome" => $nome, "texto" => "", "valido" => 0, "erro" => "Algum erro ocorreu.");
        return $arr;
    }
}

function validaCPF($cpf) {
    $especs = array("-", ".");
    $cpf = str_replace($especs, "", $cpf);
    $status = 0;
    if (!is_numeric($cpf)) {
        $status = 0;
    } else {
        if (($cpf === "11111111111") || ($cpf === "22222222222") || ($cpf === "33333333333") || ($cpf === "44444444444") || ($cpf === "55555555555") ||
                ($cpf === "66666666666") || ($cpf === "77777777777") || ($cpf === "88888888888") || ($cpf === "99999999999") || ($cpf === "00000000000")) {
            $status = 0;
        } else {
            $dv_informado = substr($cpf, 9, 2);
            for ($i = 0; $i <= 8; $i++) {
                $digito[$i] = substr($cpf, $i, 1);
            }
            $posicao = 10;
            $soma = 0;
            for ($i = 0; $i <= 8; $i++) {
                $soma = $soma + $digito[$i] * $posicao;
                $posicao = $posicao - 1;
            }
            $digito[9] = $soma % 11;
            if ($digito[9] < 2) {
                $digito[9] = 0;
            } else {
                $digito[9] = 11 - $digito[9];
            }
            $posicao = 11;
            $soma = 0;
            for ($i = 0; $i <= 9; $i++) {
                $soma = $soma + $digito[$i] * $posicao;
                $posicao = $posicao - 1;
            }
            $digito[10] = $soma % 11;
            if ($digito[10] < 2) {
                $digito[10] = 0;
            } else {
                $digito[10] = 11 - $digito[10];
            }
            $dv = $digito[9] * 10 + $digito[10];
            if ($dv != $dv_informado) {
                $status = 0;
            } else {
                $status = 1;
            }
        }
    }
    return $status;
}

function inserirCNH($con, $cnh, $matricula) {
//    if (validarCNH($cnh)) {
        $sql = "update tb_Aluno set alu_cnh = '$cnh' where alu_matricula = '$matricula'";
        $r = mysqli_query($con, $sql);
        if ($r) {
            return array("msg" => "", "houveErro" => 0);
        } else {
            return array("msg" => "Houve um erro ao cadastrar sua CNH, tente novamente", "houveErro" => 1);
//        }
    }
}

function alterarAluno($con, $coluna, $value, $id) {
    $sql = "update tb_Aluno set alu_$coluna = '$value' Where alu_matricula = $id";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("msg" => "", "houveErro" => 0);
    } else {
        return array("msg" => "Houve um erro ao alterar a Aluno, tente novamente", "houveErro" => 1);
    }
}

function getAluno($con, $id) {
    $sql = "select * from tb_Aluno Where alu_matricula= '$id'";
    $r = mysqli_query($con, $sql);
    if ($r) {
        return array("erro" => "", "houveErro" => 0, "return" => $r);
    } else {
        return array("erro" => "Houve um erro ao remover a Palestra, tente novamente", "houveErro" => 1);
    }
}

function criarAluno($con, $matricula, $nome, 
                    $dataNasc, $senha, $email, 
                    $cpf, $telefone, $celular, 
                    $cep, $uf, $cidade, $bairro, 
                    $logradouro, $complemento, $cnh, 
                    $emcnh) {
    $date = Date('Y-m-d H:i');
    $codver = md5($matricula . "" . $date);
    $sql = "insert into tb_Aluno(alu_matricula, alu_nome, alu_dataNasc, alu_senha, alu_email, alu_cpf, alu_telefone, alu_celular, alu_cep, alu_uf, alu_cidade, alu_bairro, alu_logradouro, alu_complemento, alu_verificacao, alu_codver, alu_verdata, alu_cnh, alu_emcnh)"
            . "values('$matricula', '$nome','$dataNasc','" . md5($senha) . "','$email','$cpf','$telefone','$celular','$cep','$uf','$cidade','$bairro','$logradouro','$complemento','0','$codver', '$date', '$cnh', '$emcnh');";
    $r = mysqli_query($con, $sql);
    if ($r) {
        $ntemerro = enviarEmailConfCadastro($nome, $codver, $email, $matricula);
        if ($ntemerro) {
            return array("erro" => "Enviamos um E-mail de Confirmação, confira a sua Caixa de SPAM caso não o encontre na Caixa de Entrada.", "houveErro" => 0);
        } else {
            return array("erro" => "Houve um erro ao enviar o seu E-mail de Confirmação, você será redirecionado para uma página de envio.", "houveErro" => 2);
        }
    } else {
        return array("erro" => "Houve um erro ao realizar o registro em nosso Banco de Dados, tente novamente.", "houveErro" => 1);
    }
}

?>