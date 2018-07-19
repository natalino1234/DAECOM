<?php

if (isset($_GET["placa"])) {
    session_start();
    include "../functions/conexao.php";
    include "../functions/functions.php";
    $sql = ""
            . "select ad.alu_nome as da_coordenador, t.alu_cpf ,p.vei_placa, p.vei_modelo, p.vei_marca, p.vei_tipo, p.vei_inscrito, p.vei_cor, p.vei_adesivo, p.alu_matricula, t.alu_nome "
            . "from tb_Veiculo as p inner join tb_Aluno as t on (p.alu_matricula = t.alu_matricula), "
            . "tb_da as da left join tb_Aluno as ad on (da.da_coordenador = ad.alu_matricula)"
            . "where p.alu_matricula = '" . $_SESSION["matricula"] . "' AND p.vei_placa='" . $_GET["placa"] . "' "
            . "group by p.vei_placa "
            . "order by p.vei_placa";
    echo $sql;
    $r = mysqli_query($con, $sql);
    if ($r) {
        if (mysqli_num_rows($r) > 0) {
            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $res = mysqli_fetch_array($r);
            $html = "
                    <h1>Termo de Uso do Estacionamento</h1>
                    <p>
                       Eu, <u>" . $res['alu_nome'] . "</u>,
                       portador do C.P.F: <u>" . $res['alu_cpf'] . "</u>, declaro para
                       a finalidade de cadastramento, que o veículo de marca <u>" . $res['vei_marca'] . "</u>, modelo <u>" . $res['vei_modelo'] . "</u>
                       e placa <u>" . $res['vei_placa'] . "</u>, é de minha responsabilidade.
                    </p>
                    <p>
                        Eu, segundo dados acima, <strong>declaro estar ciente que</strong>:
                        <ol type='1'>
                            <li>
                                Sou responsável pelo adesivo de identificação do veículo e pela
                                carteira de acesso ao estacionamento, e que em caso de perda ou extravio
                                terei que arcar com os custos da reposição;
                            </li>
                            <li>
                                O adesivo deverá estar sempre visível no veículo, para
                                reconhecimento pelo porteiro ao ingresso na área do estacionamento;
                            </li>
                            <li>
                                O termo de uso do estacionamento será válido até o dia trinta do mês de março do ano
                                seguinte ao ano em que foi assinado este termo;
                            </li>
                            <li>
                                Existem regras a serem seguidas, <strong>e que qualquer <u>tentativa de
                                burlar</u> as regras <u>será retirado o adesivo e o direito</u> de utilizar o
                                estacionamento interno do campus</strong>.
                            </li>
                        </ol>
                        <h2>Regras:</h2>
                            <ol type='1'>
                                <li>
                                    As vagas de estacionamento destinam-se ao estacionamento de veículos de responsabilidade - comprovada - dos
                                    alunos do campus.
                                </li>
                                <li>
                                    A velocidade máxima permitida dentro do estacionamento é de 40 km/h.
                                </li>
                                <li>
                                    Não será permitido a permanência de automóveis no estacionamento após as 20 horas, exceto em casos especiais.
                                </li>
                                <li>
                                    Não é permitido efetuar quaisquer manobras de risco.
                                </li>
                                <li>
                                    Não será permitido som alto dentro da área do Campus.
                                </li>
                                <li>
                                    Deve-se obedecer a todas as placas e estacionar nos lugares demarcados.
                                </li>
                                <li>
                                    O CEFET-MG Campus VII e o DA-ECOM não disporá de seguro e nem será responsável por cobertura de danos,
                                incêndio, roubos, furtos ou qualquer tipo de sinistro que porventura venha a ocorrer com os veículos de transportes
                                automotores ou não, dos alunos, professores ou terceiros, no estacionamento.
                                </li>
                                <li>
                                    O condômino que por ventura causar danos será responsabilizado por seus atos, cabendo ao condômino prejudicado
                                a cobrança ao responsável.
                                </li>
                                <li>
                                    Qualquer outra ação incompatível com o ambiente escolar implicará na perda do acesso ao estacionamento. 
                                </li>
                            </ol>
                        </p>
                        <p align='center'>Timóteo, ___ de ______________ de ______<p>
                        <table>
                            <tr>                    
                                <td>________________________________________________
                                </td>
                                <td>________________________________________________
                                </td>
                            </tr>                    
                            <tr>                    
                                <td>
                                    <p>
                                        " . $res["alu_nome"] . "
                                    <br>
                                        Aluno responsável pelo veículo
                                    </p>
                                </td>
                                <td>
                                    <div id='esquerda'>
                                         <p>
                                             " . $res["da_coordenador"] . "
                                             <br>
                                             Coordenador DA-ECOM
                                         </p>
                                     </div>
                                </td>
                            </tr>
                        </table>";
            include('../functions/mpdf/mpdf.php');
            $mpdf = new mPDF('utf-8', "A4");
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
