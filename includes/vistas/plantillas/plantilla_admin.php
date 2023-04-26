<?php

$params['app']->doInclude('/vistas/helpers/plantilla.php');
$mensajes = mensajesPeticionAnterior();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" type="text/css" href="<?= $params['app']->resuelve('/css/admin.css') ?>" />
</head>

<body>
	<?= $mensajes ?>
	<?php require('includes/vistas/comun/cabecera.php'); ?>
	<main>

		<div class="page-container">
			<div class="contenido">

				<?= $params['contenidoPrincipal'] ?>
				<?php echo \es\ucm\fdi\aw\clases\usuarios\Usuario::mostrarTablaUsuarios(); ?>

			</div>
		</div>
	</main>

	<?php require('includes\vistas\comun\pie.php'); ?>

</body>

</html>