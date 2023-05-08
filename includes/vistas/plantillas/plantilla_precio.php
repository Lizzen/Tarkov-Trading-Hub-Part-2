<?php
include_once './includes/src/ponerVenta.php'
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
		</div>
	</main>
	<?php require('includes/vistas/comun/pie.php'); ?>

</body>
</html>