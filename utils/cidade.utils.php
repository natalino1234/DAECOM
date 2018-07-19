<?php
$con = mysqli_connect('localhost', 'root', '', 'daecom') or die;
$sql = "SELECT DISTINCT uf, cidade
		FROM selectlogradouro
		WHERE 1=1";
if(isset($_REQUEST["uf"])){
	$sql.= " and estado like '%".$_REQUEST["uf"]."%'";
}
if(isset($_REQUEST["cidade"])){
	$sql.= " and cidade like '".$_REQUEST["cidade"]."%'";
}
$sql.= " ORDER BY cidade LIMIT 3";
$res = mysqli_query($con,$sql);

while($row = mysqli_fetch_array( $res )){
	echo "<div id=\"opccid\">".utf8_encode($row['uf'])." | ".utf8_encode($row['cidade'])."</div>";
}

?>