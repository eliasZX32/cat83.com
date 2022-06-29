
function importar_dados() {

	$('.barra_progresso').val(0);
	$( '#percentual' ).text(0)

	document.getElementById("error").textContent = "";
	document.getElementById("error").classList.remove("alert-danger");

	ligar_barra();


	$.ajax({
		url: 'processing/import/importar_efd.php',
		async: true,
		type: 'post',
		data: { mes_ano: document.getElementById("importarmesano").value }
	}).done(function (data) {
		if (data) {
			//document.getElementById("error").textContent = data;
		}
		//desligar_barra();

	}).fail(function (xhr, textStatus, errorThrown) {
		document.getElementById("error").textContent = "Ocorreu um erro -> " + xhr.status + " - " + xhr.statusText;
		document.getElementById("error").classList.add("alert-danger");
	});

}

function ligar_barra() {
 	$('#title_barra').show();	
 	$('.barra_progresso').show();

	$('#title_barra').text("");
	$('.barra_progresso').val( 0 );
	timer = window.setInterval(refreshProgress, 1000);
}	


function refreshProgress() {
	 $('#percentual').load("processing/import/total_registro_atual.txt");
	 if($( '#percentual' ).text()=="ok"){
	 	clearInterval( timer );
	 	$('#title_barra').text( "" );	
	 	$('.barra_progresso').val( 0 );

	 	$('#title_barra').hide();	
	 	$('.barra_progresso').hide();

	 }else{
	 	title_barra = "";
	 	barra_progresso = 0;
	 	if( $( '#percentual' ).text() != "0"){
		 	asplit = $( '#percentual' ).text().split("|");
		 	title_barra = asplit[0];
		 	barra_progresso = asplit[1];
		} 	
		$('#title_barra').text( title_barra );	
	 	$('.barra_progresso').val( barra_progresso );	
	 }
		
	 
}
