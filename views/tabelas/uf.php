<?php
include( "../../banco.php");

// -- Tela de Cadastro de UF -- 

    $html ="    <div class='col-5' >";
    $html.="        <form id='frm_uf'>";
    $html.="            <div class='form-row align-items-center'>";
    $html.="                <div class='ml-2'>";
    $html.="                    <label for=''>UF:</label>";
    $html.="                    <input id='cd_uf' type='text' class='form-control col-sm-4' disabled>";
    $html.="                </div>";
    $html.="            </div>";
    $html.="            <div class='form-row align-items-center mt-2'>";
    $html.="                <div class='ml-2'>";
    $html.="                    <label for=''>Estado:</label>";
    $html.="                    <input id='ds_uf' type='text' class='form-control'>";
    $html.="                </div>";
    $html.="                <div class='ml-2'>";
    $html.="                    <label for=''>UF IBGE:</label>";
    $html.="                    <input id='uf_ibge' type='number' class='form-control'>";
    $html.="                </div>";
    $html.="            </div>";

    $html.="            <div class='form-row align-items-center mt-3'>";
    $html.="                <div class='ml-2'>";
    $html.="                    <label for=''>ICMS:</label>";
    $html.="                    <input id='icms' type='number' class='form-control'>";
    $html.="                </div>";
    $html.="                <div class='ml-2'>";
    $html.="                    <label for=''>Situação Tributária:</label>";
    $html.="                    <input id='st' type='number' class='form-control'>";
    $html.="                </div>";
    $html.="                <div class='ml-2'>";
    $html.="                    <label for=''>CFOP:</label>";
    $html.="                    <input id='cfop' type='number' class='form-control'>";
    $html.="                </div>";
    $html.="            </div>";
    
    
    $html.="            <div class='form-row align-items-center mt-3'>";
    $html.="                <button id='new' type='button' class='btn btn-success ml-2 mr-2' onclick='clear_form_uf(\"frm_uf\");'><i class='fa fa-file'></i> Novo</button>";
    $html.="                <button id='upd' type='button' class='btn btn-primary mr-2' onclick='upd_register_uf(\"frm_uf\");'><i class='fa fa-save' ></i> Gravar </button>";
    $html.="                <button id='add' type='button' class='btn btn-primary mr-2' onclick='add_register_uf(\"frm_uf\");' hidden><i class='fa fa-save'></i> Incluir </button>";
    $html.="                <button id='del' type='button' class='btn btn-danger' onclick='document.getElementById(\"confirm\").hidden = false;' ><i class='fa fa-times'></i> Excluir</button>";
    $html.="                <button id='cancel' type='button' class='btn btn-danger' onclick='cancel_add_uf(\"frm_uf\");' hidden><i class='fa fa-times'></i> Cancela</button>";
    $html.="            </div>";

    $html.="            <div id='confirm' class='form-row align-items-center mt-2' hidden>";
    $html.="                <label id='question' class='alert' >Deseja excluir o registro?</label>";
    $html.="                <button id='no'  type='button' class='btn btn-primary mr-2' onclick='document.getElementById(\"confirm\").hidden = true;' ><i class='fa fa-times'></i> Não </button>";
    $html.="                <button id='yes' type='button' class='btn btn-danger mr-2'  onclick='del_register_uf(\"frm_uf\");' ><i class='fa fa-eraser' aria-hidden='true'></i> Sim </button>";
    $html.="            </div>";

    $html.="            <div class='form-row align-items-center mt-3'>";
    $html.="                <label id='error' class='alert' ></label>";
    $html.="            </div>";

    $html.="        </form>";
    $html.="    </div>";
    $html.="</div>"; 

    $html ="";
    $html.="<section class='wrapper'>";
    $html.="    <div class='row'>";
    $html.="    <div class='col-md-12'>";
    $html.="<section class='card'>";
    $html.="<header class='card-header'>";
    $html.="    Cadastro de UF";
    $html.="</header>";
    $html.="<div class='row justify-content-center ml-1 mb-1 mt-4'>";
    $html.="    <div class='col-7 table-responsive table-wrapper-scroll-y my-custom-scrollbar-3'>";
    $html.="        <table id='table_uf' class='table table-bordered table-hover '>";
    $html.="            <thead class='table-dark'>";
    $html.="                <tr><th>UF</th>";
    $html.="                    <th>Descrição</th>";
    $html.="                    <th>UF IBGE</th>";
    $html.="                    <th>ICMS</th>";
    $html.="                    <th>ST</th>";
    $html.="                    <th>CFOP</th></tr>";
    $html.="            </thead>";
    $html.="            <tbody id='table_body'>";

    $stmt = $pdo->prepare('SELECT cd_uf, ds_uf, uf_ibge, icms, st, cfop  FROM tb_uf');
    $stmt->execute();    
    // retorno do numero do item e hora inicial
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $html.="                <tr onclick='selRegister_uf(this, false);' disabled='disabled'><td>".$row->cd_uf."</td>";
        $html.=                     "<td>".$row->ds_uf."</td>";
        $html.=                     "<td>".$row->uf_ibge."</td>";
        $html.=                     "<td>".$row->icms."</td>";
        $html.=                     "<td>".$row->st."</td>";
        $html.="                <td>".$row->cfop."</td></tr>";
    }

    $html.="            </tbody>";
    $html.="        </table>";
    $html.="    </div>";

    $html.="</section>";
    $html.="</div>";
    $html.="</div>";
    $html.="</section>";

echo $html;

?>