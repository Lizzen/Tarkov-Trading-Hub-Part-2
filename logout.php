<?php
require_once __DIR__.'/includes/config.php';

if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    $app->redirige('./index.php');
}

$formLogout = new \es\ucm\fdi\aw\clases\usuarios\FormularioLogout();
$formLogout->gestiona();
$app->redirige('./index.php');