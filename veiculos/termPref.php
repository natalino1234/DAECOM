<?php

if (isset($_GET["placa"])) {
    session_start();
    include "../functions/conexao.php";
    include "../functions/alunos.php";
    include "../functions/functions.php";
    $sql = ""
            . "select ad.alu_nome as da_coordenador, t.alu_cpf ,p.vei_placa, p.vei_modelo, p.vei_marca, p.vei_tipo, "
            . "p.vei_inscrito, p.vei_cor, p.vei_adesivo, p.alu_matricula, t.alu_nome, t.alu_logradouro, t.alu_complemento, "
            . "t.alu_cidade, t.alu_cep, t.alu_uf, t.alu_telefone, t.alu_celular, p.vei_proprio "
            . "from tb_Veiculo as p inner join tb_Aluno as t on (p.alu_matricula = t.alu_matricula), "
            . "tb_da as da left join tb_Aluno as ad on (da.da_coordenador = ad.alu_matricula)"
            . "where p.alu_matricula = '" . $_SESSION["matricula"] . "' AND p.vei_placa='" . $_GET["placa"] . "' "
            . "group by p.vei_placa "
            . "order by p.vei_placa";
    $matricula = $_SESSION['matricula'];
    $curso = substr($matricula, 6, 2);
    if($curso === "06"){
        $curso = "Engenharia de Computação";
    }
    $periodo = getPeriodo($matricula);
    $r = mysqli_query($con, $sql);
    if ($r) {
        if (mysqli_num_rows($r) > 0) {
            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $res = mysqli_fetch_array($r);
            if($res["vei_proprio"] === "1"){
                $proprio = "Não";
            }else{
                $proprio = "Sim";
            }
            $html = "
                    <table border='1' cellspacing='0'>
                    <tr>
                        <td colspan='6'>
                            <img src='/veiculos/img/logocefet.png' width='100'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='6'>
                            <p>Termo de Uso do Estacionamento</p>
                        </td>
                    </tr>
                    <tr>
                        <td id='col1'>
                            Nome
                        </td>
                        <td colspan='5'>
                            ".$res["alu_nome"]."
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Endereco
                        </td>
                        <td colspan='5'>
                            ".$res["alu_logradouro"].", ".$res["alu_complemento"]."
                        </td>
                    </tr>
                    <tr>
                        <td id='col1'>
                            Cidade
                        </td>
                        <td id='col2'>
                            ".$res["alu_cidade"]."
                        </td>
                        <td id='col3'>
                            CEP
                        </td>
                        <td id='col4'>
                            ".$res["alu_cep"]."
                        </td>
                        <td id='col5'>
                            UF
                        </td>
                        <td id='col6'>
                            ".$res["alu_uf"]."
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Telefone
                        </td>
                        <td colspan='2'>
                            ".$res["alu_telefone"]."
                            
                        </td>
                        <td>
                            Celular
                        </td>
                        <td colspan='2'>
                            ".$res["alu_celular"]."
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Curso
                        </td>
                        <td>
                            $curso
                        </td>
                        <td>
                            Matrícula
                        </td>
                        <td>
                            $matricula
                        </td>
                        <td>
                            Período
                        </td>
                        <td>
                            $periodo
                        </td>
                    </tr>
                    <tr>
                        <td>
                            CNH
                        </td>
                        <td colspan='2'>
                            ".$res["alu_cnh"]."
                        </td>
                        <td>
                            Estado 
                        </td>
                        <td colspan='2'>
                            ".$res["alu_emcnh"]."
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan='6'>
                            <p>Dados do Veículo</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Placa
                        </td>
                        <td colspan='2'>
                            ".$res["vei_placa"]."
                        </td>
                        <td>
                            Marca 
                        </td>
                        <td colspan='2'>
                            ".$res["vei_marca"]."
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Modelo
                        </td>
                        <td colspan='2'>
                            ".$res["vei_modelo"]."
                            
                        </td>
                        <td>
                            Cor
                        </td>
                        <td colspan='2'>
                            ".$res["vei_cor"]."
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Veículo Próprio?
                        </td>
                        <td colspan='5'>
                            $proprio
                        </td>
                    </tr>
                    <tr>
                        <td colspan='6'>
                            <p>Termo de Responsabilidade</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='6' align='justify'>
                            Assumo a responsabilidade de, ao vender ou trocar o veículo, retirar o selo e devolvê-lo 
                            ao setor de Portaria, manter atualizado este cadastro e obedecer a sinalização e os locais 
                            permitidos para estacionamento. Declaro estar ciente que o CEFET-MG não se responsabilizará 
                            por quaisquer danos e extravio de pertences no veículo.
                        </td>
                    </tr>
                    <tr>
                        <td colspan='6'>
                                Timóteo, ____ de ______________ de ______
                        </td>
                    </tr>
                    <tr>                    
                        <td colspan='6'>
                            <br>
                            ________________________________________________________
                            <br>
                                " . $res["alu_nome"] . "
                            <br>
                                Aluno responsável pelo veículo
                        </td>
                    </tr>
                </table>";
            include('../functions/mpdf/mpdf.php');
            $mpdf = new mPDF('utf-8', "A5-L", 0, '', 15,15, 13,2);
            $css = file_get_contents("./css/estilo.css");
            $mpdf->WriteHTML($css, 1);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            exit();
        } else {
            echo '<meta http-equiv="refresh" content="0;url=/404">';
        }
    } else {
        echo '<meta http-equiv="refresh" content="0;url=/404">';
    }
}
