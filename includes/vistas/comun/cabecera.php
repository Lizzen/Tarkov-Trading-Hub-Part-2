<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\usuarios\FormularioLogout;

function mostrarSaludo()
{
  $html = '';
  $app = Aplicacion::getInstance();
  if ($app->usuarioLogueado()) {
    $nombreUsuario = $app->nombreUsuario();

    $formLogout = new FormularioLogout();
    $htmlLogout = $formLogout->gestiona();
    $html = "Bienvenido, ${nombreUsuario}. $htmlLogout";
  } else {
    $loginUrl = $app->resuelve('/login.php');
    $registroUrl = $app->resuelve('/registro.php');
    $html = <<<EOS
        Usuario desconocido. <a href="{$loginUrl}">Login</a> <a href="{$registroUrl}">Registro</a>
      EOS;
  }

  return $html;
}
?>

<head>
  <link rel="stylesheet" type="text/css" href="./css/cabecera.css">
</head>

<header>
  <div class="logo">
    <a href="index.php"><img src="./css/img/logo_sin_fondo.PNG" alt="Logo" height="100" /></a>
  </div>
  <div class="saludo">
    <?= mostrarSaludo(); ?>
  </div>
</header>