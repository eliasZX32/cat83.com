<?php
include( "../../banco.php");

try{
    $arrmesano = explode( "/", $_POST['mes_ano']);
    $MES_ANO = $arrmesano[1].$arrmesano[0];

    $erro="Erro ao excluir dados da tabela C170";
    $stmt = $pdo->prepare( "DELETE FROM `C170` WHERE ANO_MES_IMPORT = {$MES_ANO}" );
    $stmt->execute();

    // Retorna filtro de mes e ano para atualizar a tela
    $stmt = $pdo->prepare( "SELECT MAX(ANO_MES_IMPORT) AS ANO_MES FROM `C170` LIMIT 1" );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    if( $row->ANO_MES ){
        // Se o mes for de dezembro, soma 1 no ano e muda para janeiro
        if(substr($row->ANO_MES, 4, 2) == 12 ){
            $ANO_MES = "01/".substr($row->ANO_MES, 0, 4) + 1;
        }else{
            $ANO_MES = str_pad(substr($row->ANO_MES, 4, 2) +1 , 2, "0", STR_PAD_LEFT) ."/".substr($row->ANO_MES, 0, 4);
        }
        $importarmesano = $ANO_MES;
        $excluirmesano =  substr($row->ANO_MES, 4, 2) . "/" . substr($row->ANO_MES, 0, 4);
        echo $importarmesano."|".$excluirmesano;
    }


} catch(PDOException $e){
   echo $erro;
}



