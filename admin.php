<?php
require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Admin';
$contenidoPrincipal = '';

if ($app->tieneRol(es\ucm\fdi\aw\clases\usuarios\Usuario::ADMIN_ROLE)) {
  $contenidoPrincipal = <<<EOS
    <h1>Consola de administración</h1>
    <h2>Usuarios Registrados</h2>
  EOS;
} else {
  $contenidoPrincipal = <<<EOS
  <h1>Acceso Denegado!</h1>
  <p>No tienes permisos suficientes para administrar la web.</p>
  EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_admin.php', $params);
