<?php

$params['app']->doInclude('/vistas/helpers/plantilla.php');
$mensajes = mensajesPeticionAnterior();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title><?= $params['tituloPagina'] ?></title>
  <link rel="stylesheet" type="text/css" href="<?= $params['app']->resuelve('/css/faq.css') ?>" />
</head>

<body>
  <?= $mensajes ?>
  <main>
    <div class="logo">
        <a href="index.php"><img src="./css/img/Logo.PNG" alt="Logo" height="100" /></a>
      </div>
    <div class="contenido">
      <?= $params['contenidoPrincipal'] ?>
    </div>
  </main>
  <?php require('includes/vistas/comun/pie.php'); ?>
</body>

</html>