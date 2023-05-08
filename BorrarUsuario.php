<?php

require_once './includes/config.php';
require_once './includes/vistas/helpers/autorizacion.php';
use es\ucm\fdi\aw\clases\Utils;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

verificaLogado(Utils::buildUrl('./login.php'));

$idUsuario = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$idUsuario) {
    Utils::redirige(Utils::buildUrl('/usuarios.php'));
}

$usuario = Usuario::buscaPorId($idUsuario);
if (idUsuarioLogado() != $usuario->getId() && ! esAdmin()) {
    Utils::paginaError(403, 'Borrar Usuario', 'No tienes permisos para borrar este usuario');
}

Usuario::borraPorId($idUsuario);

Utils::redirige(Utils::buildUrl('./admin.php'));
exit();
