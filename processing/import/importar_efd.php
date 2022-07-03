<?php

include( "../../banco.php");

session_start();
    $cliente = $_SESSION["cod_cliente"];
    $importarmesano = $_POST["mes_ano"];


    $arrmesano = explode( "/", $importarmesano);


    $arqEFD = "../../files/{$cliente}/{$arrmesano[1]}/EFD/{$arrmesano[0]}.txt";

    if( !file_exists( $arqEFD )){
        echo "O Arquivo não existe!";
        //echo "files/{$cliente}/{$arrmesano[1]}/EFD/{$arrmesano[0]}.txt";
        return false;
    }

    // Abre o arquivo para conferencia
    //|0000|013|1|01012019|31012019|ZUIKO INDUSTRIA DE MAQUINAS LTDA|15500776000148||SP|528085663116|3538006|24271||A|0|
    $file_handle = fopen( $arqEFD, "r");
    $line = fgets($file_handle);
    $dados = explode("|", $line);
    if( $dados[1] != "0000" ){ 
        echo "Arquivo invalido!";
        return false;
    }elseif( substr($dados[4],2,6) != $arrmesano[0].$arrmesano[1] ){ 
        echo "Data inicial do arquivo invalido! O arquivo tem que ser do mês a ser importado.";
        return false;
    }elseif( substr($dados[5],2,6) != $arrmesano[0].$arrmesano[1] ){ 
        echo "Data final do arquivo invalido! O arquivo para importação tem que ter somente um mês.";
        return false;
    }
    fclose( $file_handle );

    // Verifica se já foi importado o arquivo com esse mes/ano 
    $stmt = $pdo->prepare("SELECT REG FROM `C170` WHERE ANO_MES_IMPORT = {$arrmesano[1]}{$arrmesano[0]} LIMIT 1" );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    if( $row->REG ){
        echo "O arquivo  do mês/ano {$arrmesano[0]}/{$arrmesano[1]} já importado!";
        return false;        
    }

    // ---------- Totais por Registros --------------------
    $arqtext = file_get_contents($arqEFD);
    $total_registros_0150 = substr_count( $arqtext , '0150|');
    $total_registros_0200 = substr_count( $arqtext , '0200|');
    $total_registros_C100 = substr_count( $arqtext , 'C100|');
    $total_registros_C170 = substr_count( $arqtext , 'C170|');
    // ----------------------------------------------------

    $temptable  = "temp_" . rand(0, 99999);

    
    /* ================================================================= */
    /* ========================= Produtos - C700 ======================= */


    // Abre o arquivo
    $file_handle = fopen( $arqEFD, "r");


    file_put_contents("total_registro_atual.txt", "Processando dados do Documentos|0");

    $stmt = $pdo->prepare( "CREATE TABLE " . $temptable . " AS SELECT * FROM `C170` LIMIT 0" );
    $stmt->execute();    


    //$temptable='temp_73876';
    // Incluir todos os itens de entrada em uma base temporaria
    $SQL = "INSERT INTO " . $temptable . " ( REG, NUM_ITEM, COD_ITEM, DESCR_COMPL, QTD, UNID, VL_ITEM, VL_DESC, IND_MOV, CST_ICMS, CFOP, COD_NAT, VL_BC_ICMS, ALIQ_ICMS, VL_ICMS, VL_BC_ICMS_ST, ALIQ_ST, VL_ICMS_ST, IND_APUR, CST_IPI, COD_ENQ, VL_BC_IPI, ALIQ_IPI, VL_IPI, CST_PIS, VL_BC_PIS, ALIQ_PIS_PERC, QUANT_BC_PIS, ALIQ_PIS_VLR, VL_PIS, CST_COFINS, VL_BC_COFINS, ALIQ_COFINS_PERC, QUANT_BC_COFINS, ALIQ_COFINS_VLR, VL_COFINS, COD_CTA, VL_ABAT_NT, CHV_NFE, ANO_MES_IMPORT ) VALUES ";


    $i=0; // Controla quatidade de registros para incluir na base de dados


    $regcount = 0;                             // acrescenta um a cada registro processado
    $regcontrole = 0;                          // acrescenta um a cada registro processado até atigir a quantidade de 1 porcento do total, depois zera e recomeça a contagem
    $regperc= ($total_registros_C100 / 100)+1; // Quantidade de registro que corresponde 1% 



    while ( !feof($file_handle) ) {

        $line = fgets($file_handle);

        if( substr($line, 1, 4) == "C100" ){ 
            $dados = explode("|", $line);
            $CHV_NFE = $dados[9]; 
        }


        if( substr($line, 1, 4) == "C170" ){ 


            $dados = explode("|", $line);

            $REG = $dados[1]; 
            $NUM_ITEM = $dados[2]; 
            $COD_ITEM = $dados[3]; 
            $DESCR_COMPL = $dados[4]; 
            $QTD = str_replace( ",", ".", $dados[5]); 
            $UNID = $dados[6]; 
            $VL_ITEM = str_replace( ",", ".", $dados[7]); 
            $VL_DESC = str_replace( ",", ".", $dados[8]); 
            $IND_MOV = str_replace( ",", ".", $dados[9]); 
            $CST_ICMS = $dados[10]; 
            $CFOP = $dados[11]; 
            $COD_NAT = $dados[12]; 
            $VL_BC_ICMS = str_replace( ",", ".", $dados[13]); 
            $ALIQ_ICMS = str_replace( ",", ".", $dados[14]); 
            $VL_ICMS = str_replace( ",", ".", $dados[15]); 
            $VL_BC_ICMS_ST = str_replace( ",", ".", $dados[16]);
            $ALIQ_ST = str_replace( ",", ".", $dados[17]); 
            $VL_ICMS_ST = str_replace( ",", ".", $dados[18]); 
            $IND_APUR = $dados[19]; 
            $CST_IPI = $dados[20]; 
            $COD_ENQ = $dados[21]; 
            $VL_BC_IPI = str_replace( ",", ".", $dados[22]); 
            $ALIQ_IPI = str_replace( ",", ".", $dados[23]); 
            $VL_IPI = str_replace( ",", ".", $dados[24]); 
            $CST_PIS = $dados[25]; 
            $VL_BC_PIS = str_replace( ",", ".", $dados[26]); 
            $ALIQ_PIS_PERC = str_replace( ",", ".", $dados[27]); 
            $QUANT_BC_PIS = str_replace( ",", ".", $dados[28]); 
            $ALIQ_PIS_VLR = str_replace( ",", ".", $dados[29]);
            $VL_PIS = str_replace( ",", ".", $dados[30]); 
            $CST_COFINS = $dados[31]; 
            $VL_BC_COFINS = str_replace( ",", ".", $dados[32]); 
            $ALIQ_COFINS_PERC = str_replace( ",", ".", $dados[33]); 
            $QUANT_BC_COFINS = str_replace( ",", ".", $dados[34]); 
            $ALIQ_COFINS_VLR = str_replace( ",", ".", $dados[35]); 
            $VL_COFINS = str_replace( ",", ".", $dados[36]); 
            $COD_CTA = $dados[37]; 
            $VL_ABAT_NT = str_replace( ",", ".", $dados[38]); 

            $ANO_MES_IMPORT = $arrmesano[1].$arrmesano[0];

            $REGISTRO  = "('{$REG}', '{$NUM_ITEM}', '{$COD_ITEM}', '{$DESCR_COMPL}', '{$QTD}', '{$UNID}', '{$VL_ITEM}',"; 
            $REGISTRO .= "'{$VL_DESC}', '{$IND_MOV}', '{$CST_ICMS}', '{$CFOP}', '{$COD_NAT}', '{$VL_BC_ICMS}',";
            $REGISTRO .= "'{$ALIQ_ICMS}', '{$VL_ICMS}', '{$VL_BC_ICMS_ST}','{$ALIQ_ST}', '{$VL_ICMS_ST}',"; 
            $REGISTRO .= "'{$IND_APUR}', '{$CST_IPI}', '{$COD_ENQ}', '{$VL_BC_IPI}', '{$ALIQ_IPI}', '{$VL_IPI}',"; 
            $REGISTRO .= "'{$CST_PIS}', '{$VL_BC_PIS}', '{$ALIQ_PIS_PERC}', '{$QUANT_BC_PIS}', '{$ALIQ_PIS_VLR}',";
            $REGISTRO .= "'{$VL_PIS}', '{$CST_COFINS}', '{$VL_BC_COFINS}', '{$ALIQ_COFINS_PERC}', '{$QUANT_BC_COFINS}',";
            $REGISTRO .= "'{$ALIQ_COFINS_VLR}', '{$VL_COFINS}', '{$COD_CTA}', '{$VL_ABAT_NT}', '{$CHV_NFE}', '{$ANO_MES_IMPORT}'),";


            $SQL .= "{$REGISTRO}";

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

            $SQL = "INSERT INTO " . $temptable . " ( REG, NUM_ITEM, COD_ITEM, DESCR_COMPL, QTD, UNID, VL_ITEM, VL_DESC, IND_MOV, CST_ICMS, CFOP, COD_NAT, VL_BC_ICMS, ALIQ_ICMS, VL_ICMS, VL_BC_ICMS_ST, ALIQ_ST, VL_ICMS_ST, IND_APUR, CST_IPI, COD_ENQ, VL_BC_IPI, ALIQ_IPI, VL_IPI, CST_PIS, VL_BC_PIS, ALIQ_PIS_PERC, QUANT_BC_PIS, ALIQ_PIS_VLR, VL_PIS, CST_COFINS, VL_BC_COFINS, ALIQ_COFINS_PERC, QUANT_BC_COFINS, ALIQ_COFINS_VLR, VL_COFINS, COD_CTA, VL_ABAT_NT, CHV_NFE, ANO_MES_IMPORT  ) VALUES ";

            $i = 0;        
        }   

   
    }

    fclose( $file_handle );

    if($i>0){ 
        $SQL = substr( $SQL, 0, strlen($SQL)-1);        
        $stmt = $pdo->prepare( $SQL );
        $stmt->execute();         
    }    
    

    $SQL  = "INSERT INTO `C170` ( reg, num_item, cod_item, descr_compl, qtd, unid, vl_item, vl_desc, ind_mov, cst_icms, cfop, cod_nat, vl_bc_icms, aliq_icms, vl_icms, vl_bc_icms_st, aliq_st, vl_icms_st, ind_apur, cst_ipi, cod_enq, vl_bc_ipi, aliq_ipi, vl_ipi, cst_pis, vl_bc_pis, aliq_pis_perc, quant_bc_pis, aliq_pis_vlr, vl_pis, cst_cofins, vl_bc_cofins, aliq_cofins_perc, quant_bc_cofins, aliq_cofins_vlr, vl_cofins, cod_cta, vl_abat_nt, chv_nfe, ano_mes_import ) ";
    $SQL .=               "SELECT reg, num_item, cod_item, descr_compl, qtd, unid, vl_item, vl_desc, ind_mov, cst_icms, cfop, cod_nat, vl_bc_icms, aliq_icms, vl_icms, vl_bc_icms_st, aliq_st, vl_icms_st, ind_apur, cst_ipi, cod_enq, vl_bc_ipi, aliq_ipi, vl_ipi, cst_pis, vl_bc_pis, aliq_pis_perc, quant_bc_pis, aliq_pis_vlr, vl_pis, cst_cofins, vl_bc_cofins, aliq_cofins_perc, quant_bc_cofins, aliq_cofins_vlr, vl_cofins, cod_cta, vl_abat_nt, chv_nfe, ano_mes_import FROM " . $temptable;
    $stmt = $pdo->prepare( $SQL );
    $stmt->execute();    

    $SQL  = " UPDATE `C170` a JOIN `CFOP` b ON a.cfop = b.cfop SET";
    $SQL .= "  a.ficha1 = b.ficha1, a.ficha2 = b.ficha2, a.codigo_lancamento = b.codigo_lancamento, a.ordem = b.ordem;";
    $stmt = $pdo->prepare( $SQL );
    $stmt->execute();    

    $stmt = $pdo->prepare( "DROP TABLE " . $temptable );
    $stmt->execute();    

    //resetar a barra de progresso
    file_put_contents("total_registro_atual.txt", "ok");


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
