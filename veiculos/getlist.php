<?php

session_start();
if (isset($_SESSION['matricula'])) {
    if ($_SESSION["matricula"] === "") {
        header("location: /Logout");
    }
} else {
    header("location: /Login?dest=" . $_SERVER['PHP_SELF']);
}

if($_SESSION["tipo_usuario"] !== "1"){
    header("HTTP/1.1 403 Unauthorized");
}
include "../functions/conexao.php";
include "../functions/functions.php";
$sql = ""
        . "Select p.vei_tipo, p.vei_modelo, p.vei_placa, p.vei_marca, p.vei_cor, a.alu_nome "
        . "from tb_Veiculo as p left join "
        . "tb_Aluno as a on (p.alu_matricula = a.alu_matricula) where p.vei_adesivo=1;";
$r = mysqli_query($con, $sql);
if ($r) {
    if (mysqli_num_rows($r) > 0) {
        $html = ""
                . "<table id='table' width='100%' border='1'>"
                    . "<tr>"
                    . "     <th colspan='5'><p>Uso do estacionamento interno do campus Timóteo</p></th>"
                    . "</tr>"
                    . "<tr>"
                    . "     <th>Nome</th>"
                    . "     <th>Modelo</th>"
                    . "     <th>Placa</th>"
                    . "     <th>Cor</th>"
                    . "     <th>Tipo</th>"
                    . "</tr>";
        while ($res = mysqli_fetch_array($r)) {
            $html .= "<tr>"
                    . "<td>" . $res["alu_nome"] . "</td>"
                    . "<td>" . $res["vei_marca"] . " " . $res["vei_modelo"] . "</td>"
                    . "<td>" . $res["vei_placa"] . "</td>"
                    . "<td>" . $res["vei_cor"] . "</td>"
                    . "<td>" . $res["vei_tipo"] . "</td>"
                    . "</tr>";
        }
        $html .= "</table>";

        include('../functions/mpdf/mpdf.php');
        $mpdf = new mPDF('utf-8', 'A4-L');
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter("<span style='font-size:11pt;'>Página {PAGENO}</span>");
        $css = file_get_contents("./css/estilo.css");
        $mpdf->WriteHTML($css, 1);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        exit();
    }
} else {
    echo '<meta http-equiv="refresh" content="0;url=/404">';
}
