<?php
session_start();
if (isset($_GET["cod"])) {
    include "../functions/conexao.php";
    include "../functions/functions.php";
    $sql = ""
            . "Select a.alu_matricula, a.alu_nome, p.pal_nome, p.pal_data, c.coo_posse, p.pal_palestrante, p.pal_cargahoraria "
            . ", c.coo_coordenador, c.coo_coord_titulo, c.coo_subcoordenador, c.coo_subcoord_titulo, ad.alu_nome as da_coordenador_nome, da.da_posse, c.coo_posse "
            . "from tb_Palestra as p inner join tb_Pal_Alu_Participar as t on (p.pal_id = t.pal_id) inner join "
            . "tb_Aluno as a on (t.alu_matricula = a.alu_matricula OR p.pal_palestrante = a.alu_matricula) "
            . ", tb_Coordenacao as c, tb_da as da left join tb_Aluno as ad on (da.da_coordenador = ad.alu_matricula)"
            . "where t.pap_cod = '" . $_GET["cod"] . "' AND t.alu_matricula = '" . $_SESSION["matricula"] . "' OR p.alu_organizacao = '" . $_SESSION["matricula"] . "' AND c.coo_posse < p.pal_data AND da.da_posse < p.pal_data "
            . "order by c.coo_posse desc LIMIT 1";
    echo $sql;
    $r = mysqli_query($con, $sql);
    if ($r) {
        if (mysqli_num_rows($r) > 0) {
            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $res = mysqli_fetch_array($r);
            $cargahoraria = $res["pal_cargahoraria"];
            $participante = "participou";
            $AnoPosseDA = Date("Y", strtotime($res["da_posse"]));
            $AnoPalestra = Date("Y", strtotime($res["pal_data"]));
            $diferenca = $AnoPalestra - $AnoPosseDA;
            $subcoordenador = 0;
            if ($diferenca > 1) {
                $subcoordenador = 1;
            }
            if ($res["alu_matricula"] === $res["pal_palestrante"]) {
                $cargahoraria = 2 * $res["pal_cargahoraria"];
                $participante = "foi palestrante";
            }
            $html = "
                    <fieldset>
                           <img id='logocefet' src='/palestras/img/logocefet.png' width='150px'>
                           <h4>Centro Federal de Educação Tecnológica de Minas Gerais <br> Campus Timóteo</h4>
                           <h1>Certificado</h1>
                           <h2 class='center sub-titulo'>
                                   O Diretório Acadêmico de Engenharia de Computação certifica que " . strtoupper($res["alu_nome"]) . " 
                                   $participante da palestra " . strtoupper($res["pal_nome"]) . ", no dia " . strftime('%d de %B de %Y', strtotime($res["pal_data"])) . ", com
                                   carga horária de " . $cargahoraria . " (" . valorPorExtenso($cargahoraria, false, false) . ") horas.
                           </h2>
                           <br>
                           <p>Timóteo, " . strftime('%d de %B de %Y', strtotime($res["pal_data"])) . "</p>
                           <br>
                           <br>
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
                                           " . $res["coo_coord_titulo"] . " " . $res["coo_coordenador"] . "
                                       <br>
                                           Coord. Curso - Eng. de Computação
                                       </p>
                                   </td>
                                   <td>
                                       <div id='esquerda'>";
            if ($subcoordenador == 1) {
                $html .= "                               <p>
                                               " . $res["coo_subcoord_titulo"] . " " . $res["coo_subcoordenador"] . "
                                           <br>
                                               Sub-Coord. Curso - Eng. de Computação
                                           </p>";
            } else {
                $html .= "                               <p>
                                               " . $res["da_coordenador_nome"] . "
                                           <br>
                                               Coord. Geral do DA - Eng. de Computação
                                           </p>";
            }
            $html .="                  </div>
                                   </td>
                               </tr>
                           </table>
                    </fieldset>";
            include('../functions/mpdf/mpdf.php');
            $mpdf = new mPDF('utf-8', "A4-L");
            $css = file_get_contents("./css/estilo.css");
            $mpdf->WriteHTML($css, 1);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            exit();
        } else {
            //echo '<meta http-equiv="refresh" content="0;url=/404">';
        }
    } else {
    //    echo '<meta http-equiv="refresh" content="0;url=/404">';
    }
}
