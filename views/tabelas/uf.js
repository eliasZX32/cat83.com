function importar_dados_5C() {
	$.ajax({
		url: 'model/tabelas/uf/query_uf.php',
		async: false,
		type: 'post',
		data: {}
	}).done(function (data) {
		if (data) {
			document.getElementById("table_body").innerHTML = data;
		}
	}).fail(function (xhr, textStatus, errorThrown) {
		document.getElementById("error").textContent = "Ocorreu um erro -> " + xhr.status + " - " + xhr.statusText;
		document.getElementById("error").classList.add("alert-danger");
	});
}

