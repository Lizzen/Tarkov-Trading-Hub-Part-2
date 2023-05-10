<?php

require_once './includes/src/mostrar_inventario.php';

use es\ucm\fdi\aw\clases\usuarios\Usuario;

$user = Usuario::buscaPorId($_SESSION['idUsuario']);
$params['app']->doInclude('/vistas/helpers/plantilla.php');
$mensajes = mensajesPeticionAnterior();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/inventario.css" />
	<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
</head>

<body >

	<?php require('includes/vistas/comun/cabecera.php'); ?>
	<?php require('includes/vistas/comun/sidebarIzq.php'); ?>

	<?= $mensajes ?>
	<main>
		<div class="contenido">
			<?= $params['contenidoPrincipal'] ?>
			<div class="titulo-inventario">
				<h1>Inventario</h1>
				<h3>Tamaño inventario: <?= $user->getCapacidad_inventario() / 10 ?> x 25</h3>
				<h3>Dinero: <?= $user->getDinero() ?>$</h3>
			</div>

			<?= mostrarInventario(($_SESSION['idUsuario'])) ?>
		</div>
	</main>
	<?php require('includes/vistas/comun/pie.php'); ?>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	<script src="./js/inventario.js"></script>
</body>

</html>