<?php

include( "../../banco.php");


    $importarmesano = "";
    $excluirmesano = "";


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
    }

// -- Tela de Importação de EFD -- 
    $html  = "";
    
    $html .= "<section class='wrapper'>";
    $html .= "    <div class='row'>";
    $html .= "    <div class='col-md-12'>";
    $html .= "<section class='card'>";
    $html .= "<header class='card-header'>";
    $html .= "    Importação de Arquivo EFD";
    $html .= "</header>";

    $html .= "<div class='row justify-content-center ml-1 mb-1 mt-4'>";

    $html.="    <div class='col-5' >";
    $html.="        <form id='frm_uf'>";
    $html.="            <div class='form-row align-items-center'>";
    $html.="                <div class='ml-4'>";
    $html.="                    <label for=''>Ano/Mês para Importar:</label>";
    if($importarmesano){
        $html.="                    <input id='importarmesano' type='text' class='form-control col-sm-5' value='{$importarmesano}' disabled>";
    }else{
        $html.="                    <input id='importarmesano' type='text' class='form-control col-sm-5' value='{$importarmesano}'>";    
    }    
    $html.="                </div>";
    $html.="                <div class='form-row align-items-center mt-3'>";
    $html.="                    <button id='importar' type='button' class='btn btn-primary ml-2 mr-2' onclick='importar_dados();'><i class='fa fa-file'></i> Importar dados da EFD</button>";
    $html.="                </div>";
    $html.="            </div><br>";
    
    if($importarmesano){    
        $html.="            <div class='form-row align-items-center' id='inputexcluirmesano'>";
    }else{
        $html.="            <div class='form-row align-items-center' id='inputexcluirmesano' hidden>";
    }    
    $html.="                <div class='ml-4'>";
    $html.="                    <label for=''>Ultimo Ano/Mês:</label>";
    $html.="                    <input id='excluirmesano' type='text' class='form-control col-sm-5' value='{$excluirmesano}' disabled>";
    $html.="                </div>";
    $html.="                <div class='form-row align-items-center mt-3'>";
    $html.="                    <button id='excluir' type='button' class='btn btn-danger ml-2 mr-2' onclick='excluir_dados();'><i class='fa fa-eraser'></i> Excluir dados Importados</button>";
    $html.="                </div>";
    $html.="            </div><br>";


    $html.="            <div class='form-row align-items-center'>";
    $html.="                <div class='form-row align-items-center col-12'>";
    $html.="                    <label id='title_barra' ></label>";
    $html.="                </div><br>";
    $html.="                <div class='form-row align-items-center'>";
    $html.="                    <progress class='barra_progresso' value='0' max='100' hidden></progress>";
    $html.="                    <input id='percentual' hidden>";
    $html.="                </div>";
    $html.="            </div><br>";

    $html.="            <div class='form-row align-items-center'>";

    $html.="                <div class='form-row align-items-center'>";
    $html.="                    <label id='error' class='alert' ></label>";
    $html.="                </div>";
    $html.="            </div>";

    $html.="        </form>";
    $html.="    </div>";
    $html.="</div>"; 


echo $html;

?>