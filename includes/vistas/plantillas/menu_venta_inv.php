<?php

use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
?>

<head>
	<link rel="stylesheet" type="text/css" href="./css/menu_inv.css">

</head>

<div class="nav-wrapper">
	<div class="nav-menu">

		<div class="elem-caja">
			<div class="elem-imagen">
				<img src="./css/img/inventario_dorado.png" alt="inventario" class="icon-small" onclick="window.location.href='inventario.php'">
			</div>
			<div class="elem-details">
				<a aria-current="page" href="./inventario.php" class="a-inventory">Inventario</a>
			</div>
		</div>

		<div class="elem-caja">
			<div class="elem-imagen">
				<img src="./css/img/mercado_mini.png" alt="mercado" class="icon-small" onclick="window.location.href='mercado.php'">
			</div>
			<div class="elem-details">
				<details>
					<summary>Mercado</summary>
					<a href="./mercado.php" class="a-inventory">Inicio</a>
					<a href="./mercado.php" class="a-inventory">Iniciar Venta</a>
					<a href="./mercado.php" class="a-inventory">Mis Ventas</a>
				</details>
			</div>
		</div>

		<div class="elem-caja">
			<div class="elem-imagen">
				<img src="./css/img/subasta_dorado.png" alt="subastas" class="icon-small" onclick="window.location.href='subastas.php'">
			</div>
			<div class="elem-details">
				<details>
					<summary>Subastas</summary>
					<a href="./subastas.php" class="a-inventory">Inicio</a>
					<a href="./subastas.php" class="a-inventory">Subastar</a>
					<a href="./subastas.php" class="a-inventory">Mis subastas</a>
					<a href="./subastas.php" class="a-inventory">Pujar</a>
					<a href="./subastas.php" class="a-inventory">Mis pujas</a>
				</details>
			</div>
		</div>

		<div class="elem-caja">
			<div class="elem-imagen">
				<img src="./css/img/comunidad_mini.png" alt="comunidad" class="icon-small" onclick="window.location.href='comunidad.php'">
			</div>
			<div class="elem-details">
				<details>
					<summary>Comunidad</summary>
					<a href="./comunidad.php" class="a-inventory">Inicio</a>
				</details>
			</div>
		</div>
	</div>
</div>
<script src="./js/sidebarizq.js"></script>