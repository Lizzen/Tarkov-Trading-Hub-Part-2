<?php
require_once __DIR__.'/includes/config.php';

if (!isset($_SESSION['nombre_usuario'])) { 
    header('Location: login.php');
    exit();
}

$formVenta = new \es\ucm\fdi\aw\clases\usuarios\FormularioVenta($_GET['item']);
$formVenta = $formVenta->gestiona();

$tituloPagina = 'Venta Mercado';
$contenidoPrincipal=<<<EOS
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mercado</title>
  </head>
  <body>
      <h1>Venta Mercado</h1>
      $formVenta
  </body>
</html>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_precio.php', $params);