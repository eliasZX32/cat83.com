<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="Mosaddek">
	<meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	<link rel="shortcut icon" href="img/favicon.ico">
	<title>CAT83 - Crédito Acumulado</title>
	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-reset.css" rel="stylesheet">
	<!--external css-->
	<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css"
		media="screen" />
	<link rel="stylesheet" href="css/owl.carousel.css" type="text/css">
	<!--right slidebar-->
	<link href="css/slidebars.css" rel="stylesheet">
	<!-- Custom styles for this template -->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet" />
	<!-- Table scroll -->
	<link href="css/table_scroll.css" rel="stylesheet" />
	<link href="css/table-responsive.css" rel="stylesheet">
</head>
<style>
	.barra_progresso {  		
  		width: 350px;
  		height: 60px;
  	}

</style>
<body class="light-sidebar-nav">
	<!-- -------------------------------------------------------- -->
	<section id="container">
		<header class="header white-bg">
			<div class="navbar-header">
				<a href="index.php" class="logo"><image src="logo.png" style="margin-top: -10px;" width="70"></image></span></a>
				<nav class="horizontal-menu navbar navbar-expand-md navbar-light ">
					<button class="navbar-toggler" type="button" data-toggle="collapse"
						data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
						aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">

						<!-- Menu Ficha -->
						<div class="navbar-nav mr-auto">
							<li class="nav-item dropdown">
								<a data-toggle="dropdown" id="navbarDropdown" class="nav-link dropdown-toggle" href="#">Fichas</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="#" onclick="menuSelection('processing/import/importar_tela')">Importação</a>
									<a style="display:none" class="dropdown-item" href="#" onclick="menuSelection('views/tabelas/uf')">1A</a>
									<a style="display:none" class="dropdown-item" href="#" onclick="menuSelection('views/tabelas/cfop')">1B</a>
									<a style="display:none" class="dropdown-item" href="#" onclick="menuSelection('views/tabelas/contas')">3B</a>
								</div>
						</div>

						<!-- Menu Mostrar ficha segundarias -->
						<div style="display:none" class="navbar-nav mr-auto">
							<li class="nav-item dropdown">
								<a data-toggle="dropdown" id="navbarDropdown" class="nav-link dropdown-toggle" href="#">Fichas Secundarias</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="#"onclick="menuSelection('views/compras/cotacao.php')">2A</a>
									<a class="dropdown-item" href="#"onclick="menuSelection('views/compras/negociacao.php')">2B</a>
								</div>
						</div>


						<!-- Menu Gerar arquivo TXT -->
						<div class="navbar-nav mr-auto">
							<li class="nav-item dropdown">
								<a data-toggle="dropdown" id="navbarDropdown"
									class="nav-link dropdown-toggle" href="#">Gerar TXT</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a style="display:none" class="dropdown-item"
										href="#">1A</a>
									<a class="dropdown-item" href="#"
										onclick="menuSelection('views/vendas/pedido')">2A</a>
									<a style="display:none" class="dropdown-item"
										href="#">3B</a>
									<a class="dropdown-item" href="#"
										onclick="menuSelection('views/vendas/pedido')">5A</a>

								</div>
						</div>



					</div>
			</div>
		</header>

		<!-- Retorna Tela Solicitada -->
		<section class="wrapper" id="viewCurrent">

		</section>

		<!-- -------------------------------------------------------- -->

		<!-- js placed at the end of the document so the pages load faster -->
		<script src="js/jquery.js"></script>

		<script src="https://code.jquery.com/jquery-3.6.0.js"
			integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

		<script src="js/bootstrap.bundle.min.js"></script>
		<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
		<script src="js/jquery.scrollTo.min.js"></script>
		<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
		<script src="js/jquery.sparkline.js" type="text/javascript"></script>
		<script src="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
		<script src="js/owl.carousel.js"></script>
		<script src="js/jquery.customSelect.min.js"></script>
		<script src="js/respond.min.js"></script>

		<!--right slidebar-->
		<script src="js/slidebars.min.js"></script>

		<!--common script for all pages-->
		<script src="js/common-scripts.js"></script>

		<!--script for this page-->
		<script src="js/sparkline-chart.js"></script>
		<script src="js/easy-pie-chart.js"></script>
		<script src="js/count.js"></script>

		<!-- -------------------------------------------------------- -->
		<!-- Nossos javascripts -->
		<script src="processing/import/importar.js"></script>
		

		<script>
			//owl carousel
			$(document).ready(function () {
				$("#owl-demo").owlCarousel({
					navigation: true,
					slideSpeed: 300,
					paginationSpeed: 400,
					singleItem: true,
					autoPlay: true

				});
			});

			//custom select box
			$(function () {
				$('select.styled').customSelect();
			});

			function menuSelection(e) {
				$.ajax({
					url: e + '.php',
					async: false,
				}).done(function (data) {
					$('#viewCurrent').html(data);
				});
			}
		</script>

</body>