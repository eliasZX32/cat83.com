<?php    

    /* ================================================================= */
    /* ========================= Produtos - C100 ======================= */


    // Abre o arquivo
    $file_handle = fopen( $arqEFD, "r");

    file_put_contents("total_registro_atual.txt", "Processando dados do Documentos|0");

    $stmt = $pdo->prepare( "CREATE TABLE " . $temptable . " AS SELECT * FROM `C100` LIMIT 0" );
    $stmt->execute();    


    //$temptable='temp_73876';
    // Incluir todos os entradas em uma base temporaria
    $SQL = "INSERT INTO " . $temptable . " ( REG, IND_OPER, IND_EMIT, COD_PART, COD_MOD, COD_SIT, SER, NUM_DOC, CHV_NFE, DT_DOC, DT_E_S, VL_DOC, IND_PGTO, VL_DESC, VL_ABAT_NT, IND_FRT, VL_FRT, VL_SEG, VL_OUT_DA, VL_BC_ICMS, VL_ICMS, VL_BC_ICMS_ST, VL_ICMS_ST, VL_IPI, VL_PIS, VL_COFINS, VL_PIS_ST, VL_COFINS_ST ) VALUES ";
    $i=0; // Controla quatidade de registros para incluir na base de dados


    $regcount = 0;                             // acrescenta um a cada registro processado
    $regcontrole = 0;                          // acrescenta um a cada registro processado até atigir a quantidade de 1 porcento do total, depois zera e recomeça a contagem
    $regperc= ($total_registros_C100 / 100)+1; // Quantidade de registro que corresponde 1% 



    while ( !feof($file_handle) ) {

        $line = fgets($file_handle);

        if( substr($line, 1, 4) == "C100" ){ 


            $dados = explode("|", $line);

            $REG = $dados[1]; 
            $IND_OPER = $dados[2]; 
            $IND_EMIT = $dados[3]; 
            $COD_PART = $dados[4]; 
            $COD_MOD = $dados[5]; 
            $COD_SIT = $dados[6]; 
            $SER = $dados[7]; 
            $NUM_DOC = $dados[8]; 
            $CHV_NFE = $dados[9]; 
            $DT_DOC = $dados[10]; 
            $DT_E_S = $dados[11]; 
            $VL_DOC        = str_replace( ",", ".", $dados[12]); 
            $IND_PGTO      = $dados[13]; 
            $VL_DESC       = str_replace( ",", ".", $dados[14]); 
            $VL_ABAT_NT    = str_replace( ",", ".", $dados[15]); 
            $IND_FRT       = $dados[16]; 
            $VL_FRT        = str_replace( ",", ".", $dados[17]); 
            $VL_SEG        = str_replace( ",", ".", $dados[18]); 
            $VL_OUT_DA     = str_replace( ",", ".", $dados[19]); 
            $VL_BC_ICMS    = str_replace( ",", ".", $dados[20]); 
            $VL_ICMS       = str_replace( ",", ".", $dados[21]); 
            $VL_BC_ICMS_ST = str_replace( ",", ".", $dados[22]); 
            $VL_ICMS_ST    = str_replace( ",", ".", $dados[23]); $VL_IPI = str_replace( ",", ".", $dados[24]); 
            $VL_PIS        = str_replace( ",", ".", $dados[25]); 
            $VL_COFINS     = str_replace( ",", ".", $dados[26]); 
            $VL_PIS_ST     = str_replace( ",", ".", $dados[27]);   
            $VL_COFINS_ST  = str_replace( ",", ".", $dados[28]);

            $REG  = "('{$REG}', '{$IND_OPER}', '{$IND_EMIT}', '{$COD_PART}','{$COD_MOD}', '{$COD_SIT}',";
            $REG .= "'{$SER}', '{$NUM_DOC}','{$CHV_NFE}', '{$DT_DOC}', '{$DT_E_S}', '{$VL_DOC}',";
            $REG .= "'{$IND_PGTO}', '{$VL_DESC}', '{$VL_ABAT_NT}', '{$IND_FRT}', '{$VL_FRT}', '{$VL_SEG}',";
            $REG .= "'{$VL_OUT_DA}', '{$VL_BC_ICMS}', '{$VL_ICMS}', '{$VL_BC_ICMS_ST}', '{$VL_ICMS_ST}', '{$VL_IPI}',";
            $REG .= "'{$VL_PIS}', '{$VL_COFINS}', '{$VL_PIS_ST}', '{$VL_COFINS_ST}'),";

            $SQL .= "{$REG}";

            $i++;

            // Controla a barra de progresso
            $regcount++;
            $regcontrole++;            
            if( $regcontrole >= $regperc){
                $percentual = intval( $regcount / $total_registros_C100 * 100 );
                file_put_contents("total_registro_atual.txt", "Processando dados Produtos|".$percentual);    
                $regcontrole=0;
            }   

        }

        if($i>=250){ 

            $SQL = substr( $SQL, 0, strlen($SQL)-1);
        
            $stmt = $pdo->prepare( $SQL );
            $stmt->execute(); 

            $SQL = "INSERT INTO " . $temptable . " ( REG, IND_OPER, IND_EMIT, COD_PART, COD_MOD, COD_SIT, SER, NUM_DOC, CHV_NFE, DT_DOC, DT_E_S, VL_DOC, IND_PGTO, VL_DESC, VL_ABAT_NT, IND_FRT, VL_FRT, VL_SEG, VL_OUT_DA, VL_BC_ICMS, VL_ICMS, VL_BC_ICMS_ST, VL_ICMS_ST, VL_IPI, VL_PIS, VL_COFINS, VL_PIS_ST, VL_COFINS_ST ) VALUES ";
            $i = 0;
        
        }   

    }
   
    fclose( $file_handle );


    if($i>0){ 
        $SQL = substr( $SQL, 0, strlen($SQL)-1);        
        $stmt = $pdo->prepare( $SQL );
        $stmt->execute();         
    }    
    

    //$SQL   = "INSERT INTO `0200` ( cod_item, descr_item, cod_barra, cod_ant_item, unid_inv, tipo_item, cod_ncm, ex_ipi, cod_gen, cod_lst, aliq_icms, cest ) ";
    //$SQL  .=               "SELECT cod_item, descr_item, cod_barra, cod_ant_item, unid_inv, tipo_item, cod_ncm, ex_ipi, cod_gen, cod_lst, aliq_icms, cest FROM " . $temptable;
    //$stmt = $pdo->prepare( $SQL );
    //$stmt->execute();    

    //$stmt = $pdo->prepare( "DROP TABLE " . $temptable );
    //$stmt->execute();    


    /* ================================================================= */

    // Abre o arquivo
    $file_handle = fopen( $arqEFD, "r");
    
    file_put_contents("total_registro_atual.txt", "Processando dados Participantes|0");

    $temptable = "temp_" . rand(0, 99999);

    $stmt = $pdo->prepare( "CREATE TABLE " . $temptable . " AS SELECT * FROM 5c LIMIT 0" );
    $stmt->execute();    

    $SQL = "INSERT INTO " . $temptable . " (cod_part, nome, cod_pais, cnpj, ie, cod_mun, suframa, ende, num, compl, bairro ) VALUES ";
    $i=0; // Controla quatidade de registros para incluir na base de dados

    $regcount = 0;                              // acrescenta um a cada registro processado
    $regcontrole = 0;                           // acrescenta um a cada registro processado até atigir a quantidade de 1 porcento do total, depois zera e recomeça a contagem
    $regperc = ($total_registros_0150 / 100)+1; // Quantidade de registro que corresponde 1% 
    $percentual = "";

    // Incluir todos os participantes em uma base temporaria
    while ( !feof($file_handle) ) {

        $line = fgets($file_handle);

        if( substr($line, 1, 4) == "0150" ){ 


            $dados = explode("|", $line);

            $COD_PART = $dados[2]; $NOME = $dados[3]; $COD_PAIS = $dados[4]; $CNPJ = $dados[5].$dados[6]; $IE = $dados[7]; 
            $COD_MUN = $dados[8]; $SUFRAMA = $dados[9]; $END = $dados[10]; $NUM = $dados[11]; $COMPL = $dados[12]; $BAIRRO = $dados[13];


            $SQL .= " ( '{$COD_PART}', '{$NOME}', {$COD_PAIS}, '{$CNPJ}', '{$IE}', '{$COD_MUN}', '{$SUFRAMA}', '{$END}', '{$NUM}', '{$COMPL}', '{$BAIRRO}' ),";
            $i++;


            // Controla a barra de progresso
            $regcount++;
            $regcontrole++;            

            if( $regcontrole >= $regperc){
                $percentual .= "" .intval( $regcount / $total_registros_0150 * 100 );
                file_put_contents("total_registro_atual.txt", "Processando dados Participantes|".$percentual);    
                $regcontrole = 0;
            } 
            
        }

        // Se estiver alcansado a quantidade para inserir no banco de dados
        if($i>=250){ 
            // Incluir na tabela temporaria
            $SQL = substr( $SQL, 0, strlen($SQL)-1);
            $stmt = $pdo->prepare( $SQL );
            $stmt->execute(); 

            $SQL = "INSERT INTO " . $temptable . " (cod_part, nome, cod_pais, cnpj, ie, cod_mun, suframa, ende, num, compl, bairro ) VALUES ";
            $i = 0;
        }   


    }
    fclose( $file_handle );

    //Se tiver algum registro sem incluir
    if($i>0){ 
        $SQL = substr( $SQL, 0, strlen($SQL)-1);         
        $stmt = $pdo->prepare( $SQL );
        $stmt->execute();         
    }    

    // Excluir todos os Participantes que já estão cadastrados na tabela 5c
    $SQL  = "DELETE FROM " . $temptable . " WHERE cod_part in (SELECT cod_part FROM 5c)";
    $stmt = $pdo->prepare( $SQL );
    $stmt->execute();    

    // Incluir os que não esto cadastrado
    $SQL   = "INSERT INTO 5c  (cod_part, nome, cod_pais, cnpj, ie, cod_mun, suframa, ende, num, compl, bairro ) ";
    $SQL  .=           "SELECT cod_part, nome, cod_pais, cnpj, ie, cod_mun, suframa, ende, num, compl, bairro FROM " . $temptable;
    $stmt = $pdo->prepare( $SQL );
    $stmt->execute();    

    $stmt = $pdo->prepare( "DROP TABLE " . $temptable );
    $stmt->execute();

sleep(2);

    /* ================================================================= */
    /* ========================= Produtos - 0200 ======================= */


    // Abre o arquivo
    $file_handle = fopen( $arqEFD, "r");

    file_put_contents("total_registro_atual.txt", "Processando dados Produtos|0");

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
            $stmt = $pdo->prepare( $SQL );
            $stmt->execute(); 

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
    
    // Excluir todos os Participantes que já estão cadastrados na tabela 5c
    $SQL  = "DELETE FROM " . $temptable . " WHERE cod_item in (SELECT cod_item FROM `0200`)";
    $stmt = $pdo->prepare( $SQL );
    $stmt->execute();    

    $SQL   = "INSERT INTO `0200` ( cod_item, descr_item, cod_barra, cod_ant_item, unid_inv, tipo_item, cod_ncm, ex_ipi, cod_gen, cod_lst, aliq_icms, cest ) ";
    $SQL  .=               "SELECT cod_item, descr_item, cod_barra, cod_ant_item, unid_inv, tipo_item, cod_ncm, ex_ipi, cod_gen, cod_lst, aliq_icms, cest FROM " . $temptable;
    $stmt = $pdo->prepare( $SQL );
    $stmt->execute();    

    $stmt = $pdo->prepare( "DROP TABLE " . $temptable );
    $stmt->execute();    
sleep(2);
    /* ================================================================= */
