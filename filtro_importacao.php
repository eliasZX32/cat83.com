<?php

include( "banco.php");

    $stmt = $pdo->prepare("SELECT ANO_MES_IMPORT FROM `C170` WHERE ANO_MES_IMPORT = {$arrmesano[1]}{$arrmesano[0]} LIMIT 1" );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    if( $row->ANO_MES_IMPORT ){
        if(substr($row->ANO_MES_IMPORT, 5, 2) == 12 ){
            $ANO_MES = (substr($row->ANO_MES_IMPORT, 5, 2)."/".(substr($row->ANO_MES_IMPORT + 1, 4, 4) + 1)
        }else{
            $ANO_MES = "01/".(substr($row->ANO_MES_IMPORT, 1, 4) + 1);
        }
        echo $ANO_MES."|".$row->ANO_MES_IMPORT;
        
    }else{
        echo "";
    }

