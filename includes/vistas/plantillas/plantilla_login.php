<?php

$params['app']->doInclude('/vistas/helpers/plantilla.php');
$mensajes = mensajesPeticionAnterior();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/login.css" />
</head>

<body>
	<?= $mensajes ?>
	<div class="container">
		<img src="./css/img/Logo.png" alt="Logo" onclick="window.location.href='index.php'"/>
        <h1>Iniciar sesión</h1>
		<?= $params['contenidoPrincipal'] ?>
	</div>
	<?php require('includes/vistas/comun/pie.php');?>
</body>

</html>