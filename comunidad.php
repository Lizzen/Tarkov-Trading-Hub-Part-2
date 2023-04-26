<?php
require_once __DIR__.'/includes/config.php';

if (!isset($_SESSION['nombre_usuario'])) { 
  header('Location: login.php');
  exit();
}

$tituloPagina = 'Subastas';
$contenidoPrincipal=<<<EOS
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Comunidad</title>
  </head>
  <body>
      <h1>Comunidad</h1>
  </body>
</html>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_comunidad.php', $params);