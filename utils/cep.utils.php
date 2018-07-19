<?php
$con = mysqli_connect('localhost', 'root', '', 'daecom') or die;
$sql = "SELECT DISTINCT cep, logradouro, cidade, uf
		FROM selectlogradouro
		WHERE 1=1";
if(isset($_REQUEST["cep"])){
	$sql.= " and cep like '".$_REQUEST["cep"]."%'";
}
$sql.= " ORDER BY cidade LIMIT 3";
echo $sql;
$res = mysqli_query($con,$sql);
while ( $row = mysqli_fetch_array( $res ) ) {
	echo "<div id=\"opccid\">".utf8_encode($row['cep'])." | ".utf8_encode($row['logradouro'])." | ".utf8_encode($row['cidade'])." | ".utf8_encode($row['uf'])."</div>";
}
?>