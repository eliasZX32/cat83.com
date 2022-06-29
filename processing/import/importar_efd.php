<?php

include( "../../banco.php");

    $cliente = "00001";
    $importarmesano = "01/2019";

    $arrmesano = explode( "/", $importarmesano);


    $arqEFD = "../../files/{$cliente}/{$arrmesano[1]}/EFD/{$arrmesano[0]}.txt";
    if( !file_exists( $arqEFD )){
        echo "O Arquivo não existe!";
        //echo "files/{$cliente}/{$arrmesano[1]}/EFD/{$arrmesano[0]}.txt";
        return false;
    } 

    // ---------- Totais por Registros --------------------
    $arqtext = file_get_contents($arqEFD);
    $total_registros_0200 = substr_count( $arqtext , '0200|');
    $total_registros_0150 = substr_count( $arqtext , '0150|');
    // ----------------------------------------------------

    $temptable = "temp_" . rand(0, 99999);

    
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

    //resetar a barra de progresso
    file_put_contents("total_registro_atual.txt", "ok");

