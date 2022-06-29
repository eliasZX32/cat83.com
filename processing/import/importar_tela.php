<?php

include( "../../banco.php");


    //$stmt = $pdo->prepare('SELECT DATE_FORMAT(max(data),'%d/%m/%Y') as maxdata FROM 1a');
    //$stmt->execute();    

    $importarmesano = "01/2019";
    //while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
     //   $mesano.= $row->maxdata;
    //}

    $excluirmesano = "00/0000";
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
    $html.="                    <input id='importarmesano' type='text' class='form-control col-sm-5' value='{$importarmesano}' disabled>";
    $html.="                </div>";
    $html.="                <div class='form-row align-items-center mt-3'>";
    $html.="                    <button id='importar' type='button' class='btn btn-primary ml-2 mr-2' onclick='importar_dados(\"frm_uf\");'><i class='fa fa-file'></i> Importar</button>";
    $html.="                </div>";
    $html.="            </div><br>";
    
    
    $html.="            <div class='form-row align-items-center'>";
    $html.="                <div class='ml-4'>";
    $html.="                    <label for=''>Ultimo Ano/Mês:</label>";
    $html.="                    <input id='excluirmesano' type='text' class='form-control col-sm-5' value='{$excluirmesano}' disabled>";
    $html.="                </div>";
    $html.="                <div class='form-row align-items-center mt-3'>";
    $html.="                    <button id='excluir' type='button' class='btn btn-danger ml-2 mr-2' onclick='clear_form_uf(\"frm_uf\");'><i class='fa fa-eraser'></i> Excluir Importar</button>";
    $html.="                </div>";
    $html.="            </div><br>";

    $html.="            <div class='form-row align-items-center'>";
    $html.="                <div class='form-row align-items-center col-12'>";
    $html.="                    <label id='title_barra' ></label>";
    $html.="                </div><br>";
    $html.="                <div class='form-row align-items-center'>";
    $html.="                    <progress class='barra_progresso' value='0' max='100'></progress>";
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