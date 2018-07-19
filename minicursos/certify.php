<?php
if (isset($_GET["cod"])) {
    include "../functions/conexao.php";
    include "../functions/functions.php";
    $sqldatasi = "Select mih_dia from tb_Min_Horario where min_id = '" . $_GET["cod"] . "' Limit 1";
    $sqldatasf = "Select mih_dia from tb_Min_Horario where min_id = '" . $_GET["cod"] . "' order by mih_dia desc Limit 1";
    $sql = ""
            . "Select a.alu_matricula, a.alu_nome, p.min_nome, c.coo_posse, p.min_professor, p.min_cargahoraria, di.mih_dia as prim_dia, df.mih_dia as ultimo_dia "
            . ", c.coo_coordenador, c.coo_coord_titulo, c.coo_subcoordenador, c.coo_subcoord_titulo, ad.alu_nome as da_coordenador_nome, da.da_posse, c.coo_posse "
            . "from tb_Minicurso as p inner join tb_Min_Alu_Participar as t on (p.min_id = t.min_id) "
            . ", tb_Coordenacao as c, tb_da as da left join tb_Aluno as ad on (da.da_coordenador = ad.alu_matricula), ($sqldatasi) as di, ($sqldatasf) as df "
            . "where p.min_id = '" . $_GET["cod"] . "' AND t.alu_matricula = '" . $_SESSION["matricula"] . "' OR p.alu_organizacao = '" . $_SESSION["matricula"] . "' AND c.coo_posse < df.mih_dia AND da.da_posse < df.mih_dia "
            . "order by c.coo_posse desc LIMIT 1";
    echo $sql;
    $r = mysqli_query($con, $sql);
    if ($r) {
        if (mysqli_num_rows($r) > 0) {
            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $res = mysqli_fetch_array($r);
            $cargahoraria = $res["min_cargahoraria"];
            $participante = "participou";
            $AnoPosseDA = Date("Y", strtotime($res["da_posse"]));
            $AnoMinicurso = Date("Y", strtotime($res["min_data"]));
            $diferenca = $AnoMinicurso - $AnoPosseDA;
            $subcoordenador = 0;
            if ($diferenca > 1) {
                $subcoordenador = 1;
            }
            if ($res["alu_matricula"] === $res["min_professor"]) {
                $cargahoraria = 2 * $res["min_cargahoraria"];
                $participante = "foi professor";
            }
            $html = "
                    <fieldset>
                           <img id='logocefet' src='/palestras/img/logocefet.png' width='150px'>
                           <h4>Centro Federal de Educação Tecnológica de Minas Gerais <br> Campus Timóteo</h4>
                           <h1>Certificado</h1>
                           <h2 class='center sub-titulo'>";
            if($res['prim_dia'] === $res['prim_dia']){
            $html .= "                       O Diretório Acadêmico de Engenharia de Computação certifica que " . strtoupper($res["alu_nome"]) . " 
                                   $participante participou do Minicurso \"" . strtoupper($res["min_nome"]) . "\", no dia " . strftime('%d de %B de %Y', strtotime($res["min_data"])) . ", com
                                   carga horária de " . $cargahoraria . " (" . valorPorExtenso($cargahoraria, false, false) . ") horas.";
            }else{
            $html .= "                       O Diretório Acadêmico de Engenharia de Computação certifica que " . strtoupper($res["alu_nome"]) . " 
                                   $participante participou do Minicurso " . strtoupper($res["min_nome"]) . ", do dia " . strftime('%d', strtotime($res["prim_dia"])) . " ao dia " . strftime('%d de %B de %Y', strtotime($res["min_data"])) . ", com
                                   carga horária de " . $cargahoraria . " (" . valorPorExtenso($cargahoraria, false, false) . ") horas.";
            }
            $html .= "
                           </h2>
                           <br>
                           <p>Timóteo, " . strftime('%d de %B de %Y', strtotime($res["min_data"])) . "</p>
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
//            echo '<meta http-equiv="refresh" content="0;url=/404">';
        }
    } else {
//        echo '<meta http-equiv="refresh" content="0;url=/404">';
    }
}
