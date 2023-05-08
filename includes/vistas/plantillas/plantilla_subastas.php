<?php

require_once './includes/src/mostrar_subastas.php';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/subastas.css" />
	<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
</head>

<body>

	<?php require('includes/vistas/comun/cabecera.php'); ?>
	<?php require('includes/vistas/comun/sidebarIzq.php'); ?>
	<main>
		<div class="contenido">
			<?= $params['contenidoPrincipal'] ?>
			<?php 
				$id = $params['id'];
				if ($id == 'inicio') {muestra_inicio();}
				else if ($id == 'subastar') { muestra_inventario();} 
				else if ($id == 'mis_subastas') { muestra_mis_subastas();}
				else if ($id == 'pujar') {muestra_pujas();}
				else {muestra_mis_pujas();}
			 ?>
		</div>
	</main>
	<?php require('includes/vistas/comun/pie.php'); ?>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	<script src="./js/subastas.js"></script>

</body>
</html>