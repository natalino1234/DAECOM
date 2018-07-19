<?php

session_start();
if (isset($_SESSION['matricula'])) {
    if ($_SESSION["matricula"] === "") {
        header("location: /Logout");
    }
} else {
    header("location: /Login?dest=" . $_SERVER['PHP_SELF']);
}
if (isset($_GET["id"])) {
    include "../functions/conexao.php";
    include "../functions/functions.php";
    $sql = ""
            . "Select p.pal_nome, p.pal_data, p.pal_palestrante, a.alu_nome "
            . "from tb_Palestra as p left join "
            . "tb_Aluno as a on (p.pal_palestrante = a.alu_matricula) "
            . "where p.pal_id = '" . $_GET["id"] . "';";
    echo $sql . "<br>";
    $r = mysqli_query($con, $sql);
    if ($r) {
        if (mysqli_num_rows($r) > 0) {
            $res = mysqli_fetch_array($r);
            $palestrante = $res["pal_palestrante"];
            if ($res["alu_nome"] !== null) {
                $palestrante = $res["alu_nome"];
            }
            $header = "<div class='topo'>"
                    . "     <img style='float: left;' src='/palestras/img/simbolo.png' width='70px'>"
                    . "     <h2 class='titulo' style='margin-left: 10px; float: right; font-size: 13pt; font-family: arial; text-align: center;'>"
                    . "         Diretório Acadêmico - Engenharia de Computação"
                    . "         <br>"
                    . "         Centro Federal de Educação Tecnológica de Minhas Gerais"
                    . "         <br>"
                    . "         Campus Timóteo"
                    . "     </h2>"
                    . "</div>"
                    . "Palestra: " . $res["pal_nome"] . "<br>"
                    . "Palestrante: " . $palestrante . "<br>"
                    . "Data: " . Date("d/m/Y", strtotime($res["pal_data"])) . "<br>"
                    . "<h3>Lista de Presença</h3>";
            $sql = ""
                    . "Select a.alu_nome, a.alu_matricula "
                    . "from tb_Pal_Alu_Participar as p inner join "
                    . "tb_Aluno as a on (p.alu_matricula = a.alu_matricula) "
                    . "where p.pal_id = '" . $_GET["id"] . "';";
            echo $sql . "<br>";
            $r = mysqli_query($con, $sql);
            if ($r) {
                if (mysqli_num_rows($r) > 0) {
                    $html = ""
                            . "<table style='width: 100%;' border='1' cellspacing='0'>"
                            . "<tr>"
                            . "     <td style='width: 100px;'>Presença</td>"
                            . "     <td style='width: 150px;'>Matrícula</td>"
                            . "     <td>Nome do Aluno</td>"
                            . "</tr>";
                    while ($res = mysqli_fetch_array($r)) {
                        $html .= "<tr>"
                                . "<td></td>"
                                . "<td>" . $res["alu_matricula"] . "</td>"
                                . "<td>" . $res["alu_nome"] . "</td>"
                                . "</tr>";
                    }
                    $html .= "</table>";

                    include('../functions/mpdf/mpdf.php');
                    $mpdf = new mPDF('utf-8', 'A4', '', '', 15, 15, 57, 15);
                    $mpdf->SetHTMLHeader($header);
                    $mpdf->SetHTMLFooter("<span style='font-size:11pt;'>Página {PAGENO}</span>");
                    $css = file_get_contents("./css/estilo1.css");
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
        } else {
            echo '<meta http-equiv="refresh" content="0;url=/404">';
        }
    } else {
        echo '<meta http-equiv="refresh" content="0;url=/404">';
    }
} else {
    echo '<meta http-equiv="refresh" content="0;url=/404">';
}
