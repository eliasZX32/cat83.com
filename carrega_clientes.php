<?php

include( "banco.php");

session_start();
	
    $stmt = $pdo->prepare('SELECT cod_cliente, razao FROM CLIENTES');
    $stmt->execute();    

    $html = "<select onclick=\"selecione_cliente()\" style='float:right; margin-top: 15px;' id='cod_cliente'>";
    $html.= "<option value=''>Selecione o cliente</option>";
    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    	if($_SESSION["cod_cliente"]==$row->cod_cliente){
    		$html.= "<option value='{$row->cod_cliente}' selected>{$row->razao}</option>";	
    	}else{
    		$html.= "<option value='{$row->cod_cliente}'>{$row->razao}</option>";	
    	}
        
    }
    $html.="</select>";


    echo $html;
