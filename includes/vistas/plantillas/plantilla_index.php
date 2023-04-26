<?php

$params['app']->doInclude('/vistas/helpers/plantilla.php');
$mensajes = mensajesPeticionAnterior();
$mostrarBotones = (isset($_SESSION['nombre_usuario']) && !empty($_SESSION['nombre_usuario']));
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/index.css" />
</head>

<body>
	<?= $mensajes ?>
	<main>
		<div id="contenedor">

			<?php if ($mostrarBotones) : ?>
				<div class=botones>
					<img src="./css/img/Login.png" alt="Login" onclick="window.location.href='login.php'" />
					<form method="POST" action="./logout.php" id="formLogout">
						<input type="hidden" name="tipoFormulario" value="formLogout">
						<input type="image" src="./css/img/Logout.png" alt="Cerrar Sesión" class="logout-icon">
					</form>
				</div>
			<?php else : ?>
				<div class=log>
					<div>
						<button class="btnlog" onclick="window.location.href='login.php'">Iniciar sesión</button>
					</div>
					<div>
						<button class="btnlog" onclick="window.location.href='registro.php'">Registrarse</button>
					</div>
				</div>
			<?php endif; ?>

			<?= $params['contenidoPrincipal'] ?>

		</div>
	</main>

	<?php require('includes/vistas/comun/pie.php'); ?>

</body>

</html>