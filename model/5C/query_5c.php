<?php include("../../../banco.php");

$html1="";

$stmt=$pdo->prepare('SELECT cd_uf, ds_uf, uf_ibge, icms, st, cfop  FROM tb_uf');
$stmt->execute();

// retorno do numero do item e hora inicial

while($row=$stmt->fetch(PDO::FETCH_OBJ)) {
	$html1.="                <tr onclick='selRegister_uf(this, false);'><td>".$row->cd_uf."</td>";
	$html1.="                <td>".$row->ds_uf."</td>";
	$html1.="                <td>".$row->uf_ibge."</td>";
	$html1.="                <td>".$row->icms."</td>";
	$html1.="                <td>".$row->st."</td>";
	$html1.="                <td>".$row->cfop."</td></tr>";
}

echo $html1;
