<?php    


        // Abre o arquivo
    $file_handle = fopen( $arqEFD, "r");

    file_put_contents("total_registro_atual.txt", "Processando dados Produtos|0");
    sleep(10);

    $stmt = $pdo->prepare( "CREATE TABLE " . $temptable . " AS SELECT * FROM `0200` LIMIT 0" );
    $stmt->execute();    

    // Incluir todos os participantes em uma base temporaria
    $SQL = "INSERT INTO " . $temptable . " ( cod_item, descr_item, cod_barra, cod_ant_item, unid_inv, tipo_item, cod_ncm, ex_ipi, cod_gen, cod_lst, aliq_icms, cest )  VALUES ";
    $i=0; // Controla quatidade de registros para incluir na base de dados

    $regcount = 0;                             // acrescenta um a cada registro processado
    $regcontrole = 0;                          // acrescenta um a cada registro processado até atigir a quantidade de 1 porcento do total, depois zera e recomeça a contagem
    $regperc= ($total_registros_0200 / 100)+1; // Quantidade de registro que corresponde 1% 


    while ( !feof($file_handle) ) {

        $line = fgets($file_handle);

        if( substr($line, 1, 4) == "0200" ){ 

            $dados = explode("|", $line);

            $COD_ITEM = $dados[2]; $DESCR_ITEM = $dados[3]; $COD_BARRA = $dados[4]; $COD_ANT_ITEM = $dados[5]; $UNID_INV = $dados[6]; $TIPO_ITEM = $dados[7]; 
            $COD_NCM = $dados[8]; $EX_IPI = $dados[9]; $COD_GEN = $dados[10]; $COD_LST = $dados[11]; 
            $ALIQ_ICMS = 0;
            if($dados[12]){ $ALIQ_ICMS = $dados[12]; }            
            $CEST = $dados[13];

            $SQL .= "( '{$COD_ITEM}', '{$DESCR_ITEM}', '{$COD_BARRA}', '{$COD_ANT_ITEM}', '{$UNID_INV}', '{$TIPO_ITEM}', '{$COD_NCM}', '{$EX_IPI}', '{$COD_GEN}', '{$COD_LST}', {$ALIQ_ICMS}, '{$CEST}' ),";
            $i++;

            // Controla a barra de progresso
            $regcount++;
            $regcontrole++;            
            if( $regcontrole >= $regperc){
                $percentual = intval( $regcount / $total_registros_0200 * 100 );
                file_put_contents("total_registro_atual.txt", "Processando dados Produtos|".$percentual);    
                $regcontrole=0;
            }   

        }

        if($i>=250){ 

            $SQL = substr( $SQL, 0, strlen($SQL)-1);
            //$stmt = $pdo->prepare( $SQL );
            //$stmt->execute(); 

            $SQL  = "INSERT INTO " . $temptable . " ( cod_item, descr_item, cod_barra, cod_ant_item, unid_inv, tipo_item, cod_ncm, ex_ipi, cod_gen, cod_lst, aliq_icms, cest )  VALUES ";
            $i = 0;
        }   

    }
    fclose( $file_handle );


    if($i>0){ 

        $SQL = substr( $SQL, 0, strlen($SQL)-1);        
        
        $stmt = $pdo->prepare( $SQL );
        $stmt->execute();         
    }    
    
    //echo $SQL    ;    die();

    // Excluir todos os Participantes que já estão cadastrados na tabela 5c
    $SQL  = "DELETE FROM " . $temptable . " WHERE cod_item in (SELECT cod_item FROM `0200`)";
    $stmt = $pdo->prepare( $SQL );
    $stmt->execute();    

    $SQL   = "INSERT INTO `0200` ( cod_item, descr_item, cod_barra, cod_ant_item, unid_inv, tipo_item, cod_ncm, ex_ipi, cod_gen, cod_lst, aliq_icms, cest ) ";
    $SQL  .=           "SELECT cod_item, descr_item, cod_barra, cod_ant_item, unid_inv, tipo_item, cod_ncm, ex_ipi, cod_gen, cod_lst, aliq_icms, cest FROM " . $temptable;
    $stmt = $pdo->prepare( $SQL );
    $stmt->execute();    

    $stmt = $pdo->prepare( "DROP TABLE " . $temptable );
    $stmt->execute();    
