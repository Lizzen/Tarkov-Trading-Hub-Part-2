<?php

require_once __DIR__.'/includes/config.php';

$formLogin = new \es\ucm\fdi\aw\clases\usuarios\FormularioLogin();
$formLogin = $formLogin->gestiona();


$tituloPagina = 'Login';
$contenidoPrincipal=<<<EOF
    $formLogin
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_login.php', $params);