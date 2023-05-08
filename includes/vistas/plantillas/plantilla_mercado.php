<?php
include_once './includes/src/mostrar_mercado.php'
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/mercado.css" />
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
				else if ($id == 'compra') { muestra_mercado();} 
				else if ($id == 'venta') { muestra_mercado();}
				else if ($id == 'mis_ventas') {muestra_mercado();}
			?>
		</div>
	</main>
	<?php require('includes/vistas/comun/pie.php'); ?>

</body>
</html>