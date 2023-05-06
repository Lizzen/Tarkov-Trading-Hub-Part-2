<?php

require_once './includes/src/mostrar_subastas.php';

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/subastas.css" />
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

</body>
</html>